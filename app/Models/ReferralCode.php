<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'secret_token',
        'buyer_discount_method',
        'buyer_discount_value',
        'reward_method',
        'reward_value',
        'min_order_amount',
        'max_order_amount',
        'is_active',
    ];

    protected $casts = [
        'buyer_discount_value' => 'decimal:2',
        'reward_value' => 'decimal:2',
        'min_order_amount' => 'decimal:2',
        'max_order_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function clicks()
    {
        return $this->hasMany(ReferralClick::class);
    }

    public function conversions()
    {
        return $this->hasMany(ReferralConversion::class);
    }

    public function isValidForOrderAmount(float $orderAmount): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->min_order_amount !== null && $orderAmount < (float) $this->min_order_amount) {
            return false;
        }

        if ($this->max_order_amount !== null && $orderAmount > (float) $this->max_order_amount) {
            return false;
        }

        return true;
    }
}
