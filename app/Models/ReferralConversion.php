<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralConversion extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_code_id',
        'order_id',
        'buyer_username',
        'buyer_email',
        'order_amount',
        'buyer_discount_method',
        'buyer_discount_value',
        'buyer_discount_amount',
        'reward_method',
        'reward_value',
        'reward_amount',
        'status',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'buyer_discount_value' => 'decimal:2',
        'buyer_discount_amount' => 'decimal:2',
        'reward_value' => 'decimal:2',
        'reward_amount' => 'decimal:2',
    ];

    public function referralCode()
    {
        return $this->belongsTo(ReferralCode::class);
    }
}
