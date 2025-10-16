<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class RobuxStockService
{
    /**
     * Get current Robux stock
     */
    public static function getCurrentStock(): int
    {
        return (int) Setting::getValue('robux_stock', '100000');
    }

    /**
     * Get minimum stock alert level
     */
    public static function getMinimumStock(): int
    {
        return (int) Setting::getValue('robux_stock_minimum', '10000');
    }

    /**
     * Check if stock is sufficient for order
     */
    public static function isStockSufficient(int $amount): bool
    {
        $currentStock = self::getCurrentStock();
        return $currentStock >= $amount;
    }

    /**
     * Check if stock is below minimum alert level
     */
    public static function isStockLow(): bool
    {
        $currentStock = self::getCurrentStock();
        $minimumStock = self::getMinimumStock();
        return $currentStock <= $minimumStock;
    }

    /**
     * Reduce stock when order is confirmed
     */
    public static function reduceStock(int $amount): bool
    {
        $currentStock = self::getCurrentStock();
        
        if ($currentStock < $amount) {
            Log::warning('Insufficient Robux stock', [
                'requested' => $amount,
                'available' => $currentStock
            ]);
            return false;
        }

        $newStock = $currentStock - $amount;
        Setting::setValue('robux_stock', $newStock, 'Available Robux stock');
        
        Log::info('Robux stock reduced', [
            'amount_reduced' => $amount,
            'previous_stock' => $currentStock,
            'new_stock' => $newStock
        ]);

        return true;
    }

    /**
     * Add stock (for admin to replenish)
     */
    public static function addStock(int $amount): bool
    {
        if ($amount <= 0) {
            return false;
        }

        $currentStock = self::getCurrentStock();
        $newStock = $currentStock + $amount;
        Setting::setValue('robux_stock', $newStock, 'Available Robux stock');
        
        Log::info('Robux stock added', [
            'amount_added' => $amount,
            'previous_stock' => $currentStock,
            'new_stock' => $newStock
        ]);

        return true;
    }

    /**
     * Get stock status for display
     */
    public static function getStockStatus(): array
    {
        $currentStock = self::getCurrentStock();
        $minimumStock = self::getMinimumStock();
        $isLow = self::isStockLow();

        return [
            'current' => $currentStock,
            'minimum' => $minimumStock,
            'is_low' => $isLow,
            'status' => $isLow ? 'low' : ($currentStock > $minimumStock * 2 ? 'high' : 'normal'),
            'percentage' => $minimumStock > 0 ? round(($currentStock / $minimumStock) * 100, 1) : 0
        ];
    }

    /**
     * Get pending orders that will reduce stock
     */
    public static function getPendingStockReduction(): int
    {
        return Order::where('order_status', 'pending')
            ->where('game_type', 'Robux')
            ->sum('amount');
    }

    /**
     * Get total stock that will be used (current + pending orders)
     */
    public static function getTotalStockUsage(): int
    {
        $currentStock = self::getCurrentStock();
        $pendingReduction = self::getPendingStockReduction();
        
        return $currentStock - $pendingReduction;
    }
}
