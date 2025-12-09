<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'transaction_code',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'snap_token',
        'student_id',
        'program_id',
        'amount',
        'payment_method',
        'payment_proof',
        'status',
        'midtrans_status',
        'midtrans_response',
        'payment_date',
        'expires_at',
        'notes',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'payment_date' => 'datetime',
        'expires_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Relation to User (student)
     */
    public function student()
    {
        return $this->belongsTo(\App\Models\User::class, 'student_id');
    }

    /**
     * Relation to Program
     */
    public function program()
    {
        return $this->belongsTo(\App\Models\Program::class, 'program_id');
    }

    /**
     * Check if transaction is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Check if transaction is still valid (pending and not expired)
     */
    public function isValidPending(): bool
    {
        return $this->status === 'pending' && !$this->isExpired();
    }

    /**
     * Get remaining time in seconds
     */
    public function getRemainingSeconds(): int
    {
        if (!$this->expires_at || $this->isExpired()) {
            return 0;
        }
        return Carbon::now()->diffInSeconds($this->expires_at, false);
    }

    /**
     * Get remaining time formatted (e.g., "2 jam 30 menit 15 detik")
     */
    public function getRemainingTimeFormatted(): string
    {
        $seconds = $this->getRemainingSeconds();
        
        if ($seconds <= 0) {
            return 'Kadaluarsa';
        }

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return "{$hours} jam {$minutes} menit {$secs} detik";
        } elseif ($minutes > 0) {
            return "{$minutes} menit {$secs} detik";
        }
        return "{$secs} detik";
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for valid pending transactions (not expired)
     */
    public function scopeValidPending($query)
    {
        return $query->where('status', 'pending')
                     ->where(function($q) {
                         $q->whereNull('expires_at')
                           ->orWhere('expires_at', '>', Carbon::now());
                     });
    }

    /**
     * Scope for expired pending transactions
     */
    public function scopeExpiredPending($query)
    {
        return $query->where('status', 'pending')
                     ->whereNotNull('expires_at')
                     ->where('expires_at', '<=', Carbon::now());
    }

    /**
     * Scope for paid transactions
     */
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    /**
     * Generate unique transaction code
     */
    public static function generateTransactionCode()
    {
        return 'TRX-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }

    /**
     * Generate Midtrans order ID
     */
    public static function generateOrderId($programId, $userId)
    {
        return 'ORDER-' . $programId . '-' . $userId . '-' . time();
    }

    /**
     * Get status label in Indonesian
     */
    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Berhasil',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColor(): string
    {
        $colors = [
            'pending' => 'yellow',
            'paid' => 'green',
            'failed' => 'red',
            'refunded' => 'blue',
            'cancelled' => 'gray',
            'expired' => 'red',
        ];

        return $colors[$this->status] ?? 'gray';
    }
}
