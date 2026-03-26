<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount_method',
        'discount_value_min',
        'discount_value_max',
        'max_uses',
        'current_uses',
        'is_active',
    ];

    protected $casts = [
        'discount_value_min' => 'decimal:2',
        'discount_value_max' => 'decimal:2',
        'max_uses' => 'integer',
        'current_uses' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Generate a random promo code
     */
    public static function generateCode($length = 8)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $code = '';
        
        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Check if code already exists
        while (self::where('code', $code)->exists()) {
            $code = '';
            for ($i = 0; $i < $length; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        
        return $code;
    }

    /**
     * Validate if promo code can be used
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->current_uses >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Get random discount value within min-max range
     */
    public function getRandomDiscountValue()
    {
        if ($this->discount_method === 'percentage') {
            return rand($this->discount_value_min, $this->discount_value_max);
        } else { // fixed_amount
            // For fixed amount, return random value between min and max
            return rand($this->discount_value_min, $this->discount_value_max);
        }
    }

    /**
     * Apply discount to a price
     */
    public function applyDiscount($price)
    {
        if (!$this->isValid()) {
            return $price;
        }

        $discountValue = $this->getRandomDiscountValue();

        if ($this->discount_method === 'percentage') {
            $discountAmount = $price * ($discountValue / 100);
        } else { // fixed_amount
            $discountAmount = min($discountValue, $price); // Cannot discount more than price
        }

        return max(0, $price - $discountAmount);
    }

    /**
     * Increment usage count
     */
    public function incrementUsage()
    {
        $this->increment('current_uses');
    }

    /**
     * Get all usages of this promo code
     */
    public function usages()
    {
        return $this->hasMany(PromoCodeUsage::class);
    }

    /**
     * Get paid usages count
     */
    public function getPaidUsagesCountAttribute()
    {
        return $this->usages()->where('is_paid', true)->count();
    }

    /**
     * Get unpaid usages count
     */
    public function getUnpaidUsagesCountAttribute()
    {
        return $this->usages()->where('is_paid', false)->count();
    }
}
