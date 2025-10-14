<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get statistics for dashboard
        $totalOrders = Order::completed()->count();
        $totalRevenue = Order::completed()->sum('total_amount');
        $pendingOrders = Order::where('payment_status', 'Pending')->count();
        $todayOrders = Order::completed()->whereDate('created_at', today())->count();
        
        // Get recent orders
        $recentOrders = Order::completed()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get settings for display
        $settings = [
            'robux_price_per_100' => Setting::getValue('robux_price_per_100', '10000'),
            'gamepass_tax_rate' => Setting::getValue('gamepass_tax_rate', '30'),
            'minimal_purchase' => Setting::getValue('minimal_purchase', '5000'),
            'auto_complete_days' => Setting::getValue('auto_complete_days', '5'),
        ];

        return view('admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'todayOrders',
            'recentOrders',
            'settings'
        ));
    }
}
