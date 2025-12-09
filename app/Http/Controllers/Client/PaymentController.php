<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Show payment page
     */
    public function showPaymentPage($programSlug)
    {
        $user = Auth::user();

        // Get program by slug
        $program = DB::table('data_programs')
            ->leftJoin('users', 'data_programs.instructor_id', '=', 'users.id')
            ->select(
                'data_programs.*',
                'users.name as instructor_name'
            )
            ->where('data_programs.slug', $programSlug)
            ->where('data_programs.status', 'published')
            ->first();

        if (!$program) {
            abort(404, 'Program tidak ditemukan');
        }

        // Check if user already purchased this program (paid status)
        $hasPurchased = Transaction::where('student_id', $user->id)
            ->where('program_id', $program->id)
            ->where('status', 'paid')
            ->exists();

        if ($hasPurchased) {
            return redirect()->route('client.dashboard.program')
                ->with('info', 'Anda sudah membeli program ini. Silakan akses di dashboard Anda.');
        }

        // Check for valid pending transaction (not expired)
        $pendingTransaction = Transaction::where('student_id', $user->id)
            ->where('program_id', $program->id)
            ->validPending()
            ->first();

        if ($pendingTransaction) {
            return redirect()->route('client.dashboard.transaction')
                ->with('warning', 'Anda memiliki transaksi pending untuk program ini. Silakan selesaikan pembayaran atau tunggu hingga kadaluarsa.');
        }

        // Mark expired pending transactions as expired
        Transaction::where('student_id', $user->id)
            ->where('program_id', $program->id)
            ->expiredPending()
            ->update(['status' => 'expired']);

        // Decode JSON fields
        $program->tools = json_decode($program->tools, true) ?? [];
        $program->learning_materials = json_decode($program->learning_materials, true) ?? [];
        $program->benefits = json_decode($program->benefits, true) ?? [];

        return view('client.program.pembayaran', compact('program'));
    }

    /**
     * Create transaction and get Snap token
     */
    public function createTransaction(Request $request)
    {
        try {
            $user = Auth::user();
            $programId = $request->program_id;

            // Get program details
            $program = DB::table('data_programs')->where('id', $programId)->first();
            
            if (!$program) {
                return response()->json(['error' => 'Program tidak ditemukan'], 404);
            }

            // Check if user already purchased this program
            $hasPurchased = Transaction::where('student_id', $user->id)
                ->where('program_id', $programId)
                ->where('status', 'paid')
                ->exists();

            if ($hasPurchased) {
                return response()->json(['error' => 'Anda sudah membeli program ini'], 400);
            }

            // Check for valid pending transaction
            $pendingTransaction = Transaction::where('student_id', $user->id)
                ->where('program_id', $programId)
                ->validPending()
                ->first();

            if ($pendingTransaction && $pendingTransaction->snap_token) {
                // Return existing snap token if still valid
                return response()->json([
                    'snap_token' => $pendingTransaction->snap_token,
                    'order_id' => $pendingTransaction->midtrans_order_id,
                ]);
            }

            // Mark expired pending transactions as expired
            Transaction::where('student_id', $user->id)
                ->where('program_id', $programId)
                ->expiredPending()
                ->update(['status' => 'expired']);

            // Generate IDs
            $transactionCode = Transaction::generateTransactionCode();
            $orderId = Transaction::generateOrderId($programId, $user->id);

            // Set expiration time (3 hours from now)
            $expiresAt = Carbon::now()->addHours(3);

            // Prepare Snap parameters
            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $program->price,
                ],
                'customer_details' => [
                    'first_name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '',
                ],
                'item_details' => [
                    [
                        'id' => $programId,
                        'price' => (int) $program->price,
                        'quantity' => 1,
                        'name' => substr($program->program, 0, 50), // Max 50 chars for Midtrans
                    ]
                ],
                'callbacks' => [
                    'finish' => route('client.payment.finish'),
                ],
                'expiry' => [
                    'start_time' => Carbon::now()->format('Y-m-d H:i:s O'),
                    'unit' => 'hours',
                    'duration' => 3,
                ]
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

            // Create transaction record
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'midtrans_order_id' => $orderId,
                'snap_token' => $snapToken,
                'student_id' => $user->id,
                'program_id' => $programId,
                'amount' => $program->price,
                'payment_method' => 'midtrans',
                'status' => 'pending',
                'expires_at' => $expiresAt,
            ]);

            return response()->json([
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle Midtrans callback/notification
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status;
            $orderId = $notification->order_id;

            // Find transaction
            $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

            if (!$transaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Update transaction based on status
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $transaction->status = 'paid';
                    $transaction->payment_date = now();
                }
            } else if ($transactionStatus == 'settlement') {
                $transaction->status = 'paid';
                $transaction->payment_date = now();
            } else if ($transactionStatus == 'pending') {
                $transaction->status = 'pending';
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                $transaction->status = 'cancelled';
            } else if ($transactionStatus == 'expire') {
                $transaction->status = 'expired';
            }

            // Update Midtrans data
            $transaction->midtrans_transaction_id = $notification->transaction_id ?? null;
            $transaction->midtrans_status = $transactionStatus;
            $transaction->midtrans_response = json_encode($notification);
            $transaction->payment_method = $notification->payment_type ?? 'midtrans';
            $transaction->save();

            // If paid, create enrollment
            if ($transaction->status == 'paid') {
                $this->createEnrollment($transaction);
            }

            return response()->json(['message' => 'Callback processed']);

        } catch (\Exception $e) {
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle payment finish redirect
     */
    public function finish(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('midtrans_order_id', $orderId)->first();

        if (!$transaction) {
            return redirect()->route('client.home')
                ->with('error', 'Transaksi tidak ditemukan');
        }

        // Check and update status from Midtrans API directly
        // This handles the case where redirect happens before callback
        if ($transaction->status === 'pending') {
            $this->syncTransactionStatus($transaction);
        }

        // Load program relation
        $transaction->load('program');

        $status = $transaction->status;
        $message = '';

        switch ($status) {
            case 'paid':
                $message = 'Pembayaran berhasil! Anda sekarang terdaftar di program ini.';
                $type = 'success';
                break;
            case 'pending':
                $remainingTime = $transaction->getRemainingTimeFormatted();
                $message = "Pembayaran Anda sedang menunggu. Selesaikan dalam {$remainingTime}.";
                $type = 'info';
                break;
            case 'expired':
                $message = 'Transaksi sudah kadaluarsa. Silakan buat transaksi baru.';

                $type = 'error';
                break;
            default:
                $message = 'Pembayaran gagal atau dibatalkan.';
                $type = 'error';
                break;
        }

        return view('client.program.payment-status', [
            'transaction' => $transaction,
            'message' => $message,
            'type' => $type
        ]);
    }

    /**
     * Resume payment for pending transaction
     */
    public function resumePayment($transactionId)
    {
        $user = Auth::user();
        $transaction = Transaction::where('id', $transactionId)
            ->where('student_id', $user->id)
            ->first();

        if (!$transaction) {
            return response()->json(['error' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($transaction->status !== 'pending') {
            return response()->json(['error' => 'Transaksi tidak dapat dilanjutkan'], 400);
        }

        if ($transaction->isExpired()) {
            $transaction->status = 'expired';
            $transaction->save();
            return response()->json(['error' => 'Transaksi sudah kadaluarsa'], 400);
        }

        if (!$transaction->snap_token) {
            return response()->json(['error' => 'Token pembayaran tidak tersedia'], 400);
        }

        return response()->json([
            'snap_token' => $transaction->snap_token,
            'order_id' => $transaction->midtrans_order_id,
        ]);
    }

    /**
     * Create enrollment after successful payment
     */
    private function createEnrollment($transaction)
    {
        try {
            // Check if enrollment already exists
            $exists = DB::table('enrollments')
                ->where('student_id', $transaction->student_id)
                ->where('program_id', $transaction->program_id)
                ->exists();

            if (!$exists) {
                DB::table('enrollments')->insert([
                    'student_id' => $transaction->student_id,
                    'program_id' => $transaction->program_id,
                    'transaction_id' => $transaction->id,
                    'status' => 'active',
                    'enrolled_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Update enrolled_count in program
                DB::table('data_programs')
                    ->where('id', $transaction->program_id)
                    ->increment('enrolled_count');
            }
        } catch (\Exception $e) {
            Log::error('Enrollment Creation Error: ' . $e->getMessage());
        }
    }

    /**
     * Sync transaction status from Midtrans API
     * This handles the case where redirect happens before callback
     */
    private function syncTransactionStatus($transaction)
    {
        try {
            // Call Midtrans Status API
            $serverKey = config('midtrans.server_key');
            $orderId = $transaction->midtrans_order_id;
            
            $url = config('midtrans.is_production') 
                ? "https://api.midtrans.com/v2/{$orderId}/status"
                : "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode !== 200) {
                Log::warning("Midtrans Status API returned {$httpCode} for order {$orderId}");
                return;
            }

            $data = json_decode($response, true);
            
            if (!$data || !isset($data['transaction_status'])) {
                return;
            }

            $transactionStatus = $data['transaction_status'];
            $fraudStatus = $data['fraud_status'] ?? 'accept';

            // Update transaction based on status
            $newStatus = null;
            if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                if ($fraudStatus == 'accept' || $fraudStatus == null) {
                    $newStatus = 'paid';
                    $transaction->payment_date = now();
                }
            } else if ($transactionStatus == 'pending') {
                $newStatus = 'pending';
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'cancel') {
                $newStatus = 'cancelled';
            } else if ($transactionStatus == 'expire') {
                $newStatus = 'expired';
            }

            if ($newStatus && $newStatus !== $transaction->status) {
                $transaction->status = $newStatus;
                $transaction->midtrans_transaction_id = $data['transaction_id'] ?? null;
                $transaction->midtrans_status = $transactionStatus;
                $transaction->midtrans_response = json_encode($data);
                $transaction->payment_method = $data['payment_type'] ?? 'midtrans';
                $transaction->save();

                // If paid, create enrollment
                if ($newStatus === 'paid') {
                    $this->createEnrollment($transaction);
                }

                Log::info("Transaction {$orderId} status synced to {$newStatus}");
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Status Sync Error: ' . $e->getMessage());
        }
    }
}
