<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RobuxDiscountRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_method',
        'min_amount',
        'max_amount',
        'discount_method',
        'discount_value',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'integer',
        'max_amount' => 'integer',
        'discount_value' => 'decimal:2',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Find matching discount rule for given amount and purchase method
     * Rules dengan priority lebih kecil (sort_order) dicek lebih dulu
     * Untuk exact amount, harus exact match
     * Untuk range, amount harus dalam range min-max
     * Untuk minimum only (max null), amount harus >= min
     */
    public static function findMatchingRule(int $amount, string $purchaseMethod): ?self
    {
        return self::where('purchase_method', $purchaseMethod)
            ->where('is_active', true)
            ->where(function($query) use ($amount) {
                $query->where(function($q) use ($amount) {
                    // Exact amount match (both min and max are same and match amount)
                    $q->whereColumn('min_amount', 'max_amount')
                      ->where('min_amount', $amount);
                })->orWhere(function($q) use ($amount) {
                    // Range match (min and max both set, different values)
                    $q->whereColumn('min_amount', '!=', 'max_amount')
                      ->whereNotNull('min_amount')
                      ->whereNotNull('max_amount')
                      ->where('min_amount', '<=', $amount)
                      ->where('max_amount', '>=', $amount);
                })->orWhere(function($q) use ($amount) {
                    // Minimum only (max is null, unlimited)
                    $q->whereNotNull('min_amount')
                      ->whereNull('max_amount')
                      ->where('min_amount', '<=', $amount);
                });
            })
            ->orderBy('sort_order') // Lower sort_order = higher priority (checked first)
            ->orderBy('min_amount', 'desc') // If same priority, higher min_amount first
            ->first();
    }

    /**
     * Calculate discount amount for a given price
     */
    public function calculateDiscount(float $price): float
    {
        if ($this->discount_method === 'percentage') {
            return $price * ($this->discount_value / 100);
        } else { // fixed_amount
            return min($this->discount_value, $price); // Cannot discount more than price
        }
    }

    /**
     * Get display description for the rule
     */
    public function getDescriptionAttribute(): string
    {
        if ($this->min_amount === $this->max_amount && $this->min_amount !== null) {
            // Exact amount
            return "Pembelian {$this->min_amount} Robux";
        } else if ($this->max_amount === null) {
            // Min only (unlimited max)
            return "Pembelian ≥ {$this->min_amount} Robux";
        } else {
            // Range
            return "Pembelian {$this->min_amount}-{$this->max_amount} Robux";
        }
    }
}
