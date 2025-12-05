<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

            // Generate IDs
            $transactionCode = Transaction::generateTransactionCode();
            $orderId = Transaction::generateOrderId($programId, $user->id);

            // Create transaction record
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'midtrans_order_id' => $orderId,
                'student_id' => $user->id,
                'program_id' => $programId,
                'amount' => $program->price,
                'payment_method' => 'midtrans',
                'status' => 'pending',
            ]);

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
                        'name' => $program->program,
                    ]
                ],
                'callbacks' => [
                    'finish' => route('client.payment.finish'),
                ]
            ];

            // Get Snap token
            $snapToken = Snap::getSnapToken($params);

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
            } else if ($transactionStatus == 'deny' || $transactionStatus == 'expire' || $transactionStatus == 'cancel') {
                $transaction->status = 'failed';
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

        $status = $transaction->status;
        $message = '';

        switch ($status) {
            case 'paid':
                $message = 'Pembayaran berhasil! Silakan cek dashboard Anda.';
                $type = 'success';
                break;
            case 'pending':
                $message = 'Pembayaran Anda sedang diproses. Mohon tunggu konfirmasi.';
                $type = 'info';
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
}
