<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'game_type',
        'price',
        'tax_rate',
        'is_active',
        'sort_order',
        'image_url',
        'image',
        'discount_active',
        'discount_method',
        'discount_value',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'discount_active' => 'boolean',
        'discount_value' => 'decimal:2',
    ];

    // Scope for active products
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for specific category
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Calculate total price with tax
    public function getTotalPriceAttribute()
    {
        return $this->price + ($this->price * $this->tax_rate / 100);
    }

    // Calculate tax amount
    public function getTaxAmountAttribute()
    {
        return $this->price * $this->tax_rate / 100;
    }

    // Calculate discount amount
    public function getDiscountAmountAttribute()
    {
        if (!$this->discount_active || !$this->discount_method || !$this->discount_value) {
            return 0;
        }

        $basePrice = $this->getTotalPriceAttribute(); // Price after tax

        if ($this->discount_method === 'percentage') {
            return $basePrice * ($this->discount_value / 100);
        } else { // fixed_amount
            return min($this->discount_value, $basePrice); // Cannot discount more than the price
        }
    }

    // Calculate final price with discount
    public function getFinalPriceAttribute()
    {
        $totalPrice = $this->getTotalPriceAttribute();
        $discountAmount = $this->getDiscountAmountAttribute();
        return max(0, $totalPrice - $discountAmount);
    }
}
