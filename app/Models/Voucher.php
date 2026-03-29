<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'name',
        'code',
        'discount_type',
        'discount_value',
        'start_date',
        'end_date',
        'max_usages',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    /**
     * Relationship: A voucher can have many transactions.
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Check if the voucher is valid (active, within dates, hasn't exceeded usage).
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        if ($this->end_date && $now->gt($this->end_date->endOfDay())) {
            return false;
        }

        if ($this->max_usages !== null) {
            $usageCount = $this->transactions()
                ->whereIn('status', ['paid', 'pending'])
                ->count();
            
            if ($usageCount >= $this->max_usages) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given price.
     */
    public function calculateDiscount($originalPrice)
    {
        if (!$this->isValid()) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            $discountAmount = ($originalPrice * $this->discount_value) / 100;
            // Cap discount to original price just in case
            return min($discountAmount, $originalPrice);
        }

        if ($this->discount_type === 'fixed') {
            return min($this->discount_value, $originalPrice);
        }

        return 0;
    }
}
