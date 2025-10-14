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
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
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
}
