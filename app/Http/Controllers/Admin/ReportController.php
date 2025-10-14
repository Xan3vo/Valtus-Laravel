<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or default to current month
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));
        
        // Parse dates
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Get all completed orders in date range
        $orders = Order::where('payment_status', 'Completed')
            ->whereNotNull('order_status')
            ->whereBetween('created_at', [$start, $end])
            ->get();

        // Calculate statistics
        $stats = [
            'total_orders' => $orders->count(),
            'total_revenue' => $orders->sum('total_amount'),
            'robux_orders' => $orders->where('game_type', 'Robux')->count(),
            'other_orders' => $orders->where('game_type', '!=', 'Robux')->count(),
            'robux_revenue' => $orders->where('game_type', 'Robux')->sum('total_amount'),
            'other_revenue' => $orders->where('game_type', '!=', 'Robux')->sum('total_amount'),
            'pending_orders' => $orders->where('order_status', 'pending')->count(),
            'completed_orders' => $orders->where('order_status', 'completed')->count(),
        ];

        // Calculate daily revenue for chart
        $dailyRevenue = [];
        $current = $start->copy();
        $daysDiff = $start->diffInDays($end);
        
        // If more than 30 days, group by week
        if ($daysDiff > 30) {
            while ($current->lte($end)) {
                $weekEnd = $current->copy()->addDays(6);
                if ($weekEnd->gt($end)) {
                    $weekEnd = $end->copy();
                }
                
                $weekRevenue = $orders->where('created_at', '>=', $current->copy()->startOfDay())
                    ->where('created_at', '<=', $weekEnd->copy()->endOfDay())
                    ->where('order_status', 'completed')
                    ->sum('total_amount');
                
                $dailyRevenue[] = [
                    'date' => $current->format('M d') . ' - ' . $weekEnd->format('M d'),
                    'revenue' => $weekRevenue
                ];
                
                $current->addWeek();
            }
        } else {
            // Daily data for periods <= 30 days
            while ($current->lte($end)) {
                $dayRevenue = $orders->where('created_at', '>=', $current->copy()->startOfDay())
                    ->where('created_at', '<=', $current->copy()->endOfDay())
                    ->where('order_status', 'completed')
                    ->sum('total_amount');
                
                $dailyRevenue[] = [
                    'date' => $current->format('M d'),
                    'revenue' => $dayRevenue
                ];
                
                $current->addDay();
            }
        }

        // Get top customers by revenue
        $topCustomers = $orders->where('order_status', 'completed')
            ->groupBy('username')
            ->map(function ($userOrders) {
                return [
                    'username' => $userOrders->first()->username,
                    'total_orders' => $userOrders->count(),
                    'total_revenue' => $userOrders->sum('total_amount')
                ];
            })
            ->sortByDesc('total_revenue')
            ->take(10)
            ->values();

        return view('admin.reports', compact('stats', 'dailyRevenue', 'topCustomers', 'startDate', 'endDate'));
    }
}