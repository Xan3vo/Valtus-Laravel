<?php

namespace App\Services;

use App\Models\Setting;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class RobuxStockService
{
    /**
     * Get current Robux stock (for gamepass method)
     */
    public static function getCurrentStock(): int
    {
        return (int) Setting::getValue('robux_stock', '100000');
    }

    /**
     * Get current Group Robux stock
     */
    public static function getCurrentGroupStock(): int
    {
        return (int) Setting::getValue('group_robux_stock', '50000');
    }

    /**
     * Get minimum stock alert level (for gamepass method)
     */
    public static function getMinimumStock(): int
    {
        return (int) Setting::getValue('robux_stock_minimum', '10000');
    }

    /**
     * Get minimum Group stock alert level
     */
    public static function getMinimumGroupStock(): int
    {
        return (int) Setting::getValue('group_robux_stock_minimum', '5000');
    }

    /**
     * Check if stock is sufficient for order
     * @param int $amount
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass'
     */
    public static function isStockSufficient(int $amount, ?string $purchaseMethod = null): bool
    {
        // Default to gamepass for backward compatibility
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
        } else {
            $currentStock = self::getCurrentStock();
        }
        return $currentStock >= $amount;
    }

    /**
     * Check if stock is below minimum alert level
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass'
     */
    public static function isStockLow(?string $purchaseMethod = null): bool
    {
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
            $minimumStock = self::getMinimumGroupStock();
        } else {
            $currentStock = self::getCurrentStock();
            $minimumStock = self::getMinimumStock();
        }
        return $currentStock <= $minimumStock;
    }

    /**
     * Reduce stock when order is confirmed
     * @param int $amount
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass' for backward compatibility
     */
    public static function reduceStock(int $amount, ?string $purchaseMethod = null): bool
    {
        // Default to gamepass for backward compatibility
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
            $stockKey = 'group_robux_stock';
            $stockDescription = 'Available Group Robux stock';
        } else {
            $currentStock = self::getCurrentStock();
            $stockKey = 'robux_stock';
            $stockDescription = 'Available Robux stock';
        }
        
        if ($currentStock < $amount) {
            Log::warning('Insufficient Robux stock', [
                'requested' => $amount,
                'available' => $currentStock,
                'method' => $purchaseMethod ?? 'gamepass'
            ]);
            return false;
        }

        $newStock = $currentStock - $amount;
        Setting::setValue($stockKey, $newStock, $stockDescription);
        
        Log::info('Robux stock reduced', [
            'amount_reduced' => $amount,
            'previous_stock' => $currentStock,
            'new_stock' => $newStock,
            'method' => $purchaseMethod ?? 'gamepass'
        ]);

        return true;
    }

    /**
     * Add stock (for admin to replenish)
     * @param int $amount
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass'
     */
    public static function addStock(int $amount, ?string $purchaseMethod = null): bool
    {
        if ($amount <= 0) {
            return false;
        }

        // Default to gamepass for backward compatibility
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
            $stockKey = 'group_robux_stock';
            $stockDescription = 'Available Group Robux stock';
        } else {
            $currentStock = self::getCurrentStock();
            $stockKey = 'robux_stock';
            $stockDescription = 'Available Robux stock';
        }

        $newStock = $currentStock + $amount;
        Setting::setValue($stockKey, $newStock, $stockDescription);
        
        Log::info('Robux stock added', [
            'amount_added' => $amount,
            'previous_stock' => $currentStock,
            'new_stock' => $newStock,
            'method' => $purchaseMethod ?? 'gamepass'
        ]);

        return true;
    }

    /**
     * Get stock status for display
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass'
     */
    public static function getStockStatus(?string $purchaseMethod = null): array
    {
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
            $minimumStock = self::getMinimumGroupStock();
        } else {
            $currentStock = self::getCurrentStock();
            $minimumStock = self::getMinimumStock();
        }
        
        $isLow = self::isStockLow($purchaseMethod);

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
     * @param string|null $purchaseMethod 'gamepass' or 'group', null = all Robux orders
     */
    public static function getPendingStockReduction(?string $purchaseMethod = null): int
    {
        $query = Order::where('order_status', 'pending')
            ->where('game_type', 'Robux');
        
        // Filter by purchase method if specified
        if ($purchaseMethod === 'group') {
            $query->where('purchase_method', 'group');
        } elseif ($purchaseMethod === 'gamepass') {
            $query->where(function($q) {
                $q->where('purchase_method', 'gamepass')
                  ->orWhereNull('purchase_method'); // Backward compatibility
            });
        }
        // If null, return all pending Robux orders
        
        return $query->sum('amount');
    }

    /**
     * Get total stock that will be used (current + pending orders)
     * @param string|null $purchaseMethod 'gamepass' or 'group', null defaults to 'gamepass'
     */
    public static function getTotalStockUsage(?string $purchaseMethod = null): int
    {
        if ($purchaseMethod === 'group') {
            $currentStock = self::getCurrentGroupStock();
        } else {
            $currentStock = self::getCurrentStock();
        }
        
        $pendingReduction = self::getPendingStockReduction($purchaseMethod);

        $available = $currentStock - $pendingReduction;
        return max(0, (int) $available);
    }
}
