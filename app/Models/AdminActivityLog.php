<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'action',
        'admin_id',
        'admin_name',
        'admin_email',
        'notes',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
