<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'transaction_code',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'student_id',
        'program_id',
        'amount',
        'payment_method',
        'payment_proof',
        'status',
        'midtrans_status',
        'midtrans_response',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'midtrans_response' => 'array',
        'payment_date' => 'datetime',
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
}
