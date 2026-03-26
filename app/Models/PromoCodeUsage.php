<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCodeUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'promo_code_id',
        'order_id',
        'username',
        'email',
        'original_price',
        'discount_amount',
        'final_price',
        'payment_status',
        'is_paid',
    ];

    protected $casts = [
        'original_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_price' => 'decimal:2',
        'is_paid' => 'boolean',
    ];

    /**
     * Get the promo code that owns this usage
     */
    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }

    /**
     * Get the order associated with this usage
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
