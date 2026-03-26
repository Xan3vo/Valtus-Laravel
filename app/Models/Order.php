<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'order_id';

    protected $fillable = [
        'order_id',
        'username',
        'email',
        'game_type',
        'product_name',
        'amount',
        'price',
        'tax',
        'total_amount',
        'payment_status',
        'order_status',
        'payment_method',
        'purchase_method', // 'gamepass' or 'group' - only for Robux orders, nullable for backward compatibility
        'payment_gateway', // 'midtrans' for Midtrans payments, NULL for manual
        'transaction_id',
        'proof_file',
        'gamepass_link',
        'notes',
        'expires_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'price' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'expires_at' => 'datetime',
        'completed_at' => 'datetime',
        'spreadsheet_sent_at' => 'datetime',
        'spreadsheet_last_attempt_at' => 'datetime',
        'spreadsheet_attempts' => 'integer',
        'notes' => 'array',
    ];

    // Scope to get only completed orders
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'Completed');
    }

    // Scope to search by username
    public function scopeByUsername($query, $username)
    {
        return $query->where('username', 'like', '%' . $username . '%');
    }
}
