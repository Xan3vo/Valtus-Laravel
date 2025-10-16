<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'username',
        'avatar_url',
        'game_type',
        'amount',
        'total_amount',
        'activity_type',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    /**
     * Get recent activities for live feed
     */
    public static function getRecentActivities($limit = 3)
    {
        // Always limit to 3 maximum
        $actualLimit = min($limit, 3);
        
        return self::where('status', 'completed')
            ->where('activity_type', 'purchase')
            ->orderBy('processed_at', 'desc')
            ->limit($actualLimit)
            ->get();
    }

    /**
     * Create activity when order is processed
     */
    public static function createFromOrder($order)
    {
        return self::create([
            'order_id' => $order->order_id,
            'username' => $order->username,
            'avatar_url' => null, // Will be fetched dynamically
            'game_type' => $order->game_type,
            'amount' => $order->amount,
            'total_amount' => $order->total_amount,
            'activity_type' => 'purchase',
            'status' => 'completed',
            'processed_at' => now(),
        ]);
    }

    /**
     * Mask username for display (riz*****)
     */
    public function getMaskedUsernameAttribute()
    {
        $username = $this->username;
        
        // Limit username to 14 characters max
        if (strlen($username) > 14) {
            $username = substr($username, 0, 14);
        }
        
        if (strlen($username) <= 3) {
            return str_repeat('*', strlen($username));
        }
        
        $firstThree = substr($username, 0, 3);
        $stars = str_repeat('*', strlen($username) - 3);
        
        return $firstThree . $stars;
    }

    /**
     * Get formatted amount for display
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get product information for display
     */
    public function getProductInfoAttribute()
    {
        if ($this->game_type === 'Robux') {
            return [
                'type' => 'robux',
                'name' => 'Robux',
                'amount' => $this->formatted_amount . ' Robux',
                'icon' => 'robux'
            ];
        } else {
            // For other products, we need to get product_name from the order
            $order = \App\Models\Order::where('order_id', $this->order_id)->first();
            $productName = $order ? $order->product_name : $this->game_type;
            
            return [
                'type' => 'product',
                'name' => $productName ?: $this->game_type,
                'amount' => $this->formatted_amount . ' items',
                'icon' => 'product'
            ];
        }
    }

    /**
     * Get avatar data for display
     */
    public function getAvatarDataAttribute()
    {
        // Always fetch avatar dynamically from Roblox API
        $avatarData = \App\Services\RobloxAvatarService::getAvatarWithFallback($this->username);
        
        return $avatarData;
    }
}