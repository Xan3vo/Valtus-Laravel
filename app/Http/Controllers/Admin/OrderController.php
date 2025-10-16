<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Activity;
use App\Services\RobuxStockService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // Only show orders with payment_status = 'Completed'
        $query = Order::where('payment_status', 'Completed')
            ->whereNotNull('order_status');

        // Filter by game type
        $gameType = $request->get('type', 'robux'); // Default to robux
        
        if ($gameType === 'robux') {
            $query->where('game_type', 'Robux');
        } else {
            $query->where('game_type', '!=', 'Robux');
        }

        // Search by username or order_id
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                  ->orWhere('order_id', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by order_status - default to pending only
        if ($request->has('status') && $request->status) {
            $query->where('order_status', $request->status);
        } else {
            // Default to pending orders only
            $query->where('order_status', 'pending');
        }

        // Order by created_at ascending (oldest first) for processing order
        $orders = $query->orderBy('created_at', 'asc')->paginate(20);

        // Get statistics for current game type
        $baseQuery = Order::where('payment_status', 'Completed')
            ->whereNotNull('order_status');
            
        if ($gameType === 'robux') {
            $baseQuery->where('game_type', 'Robux');
        } else {
            $baseQuery->where('game_type', '!=', 'Robux');
        }

        $stats = [
            'pending_orders' => (clone $baseQuery)->where('order_status', 'pending')->count(),
            'completed_orders' => (clone $baseQuery)->where('order_status', 'completed')->count(),
            'total_revenue' => (clone $baseQuery)->where('order_status', 'completed')->sum('total_amount'),
            'total_orders' => (clone $baseQuery)->count()
        ];

        return view('admin.orders', compact('orders', 'gameType', 'stats'));
    }

    public function show(Order $order)
    {
        return view('admin.order-detail', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,completed'
        ]);

        $updateData = [
            'order_status' => $request->order_status
        ];

        // If setting status to completed, set completed_at based on game type
        if ($request->order_status === 'completed') {
            // Check if it's a Robux order and reduce stock
            if ($order->game_type === 'Robux' && $order->amount) {
                if (!RobuxStockService::reduceStock((int) $order->amount)) {
                    return redirect()->back()->with('error', 'Insufficient Robux stock! Current stock: ' . number_format(RobuxStockService::getCurrentStock(), 0, ',', '.'));
                }
            }
            
            if ($order->game_type === 'Robux') {
                // For Robux orders, use auto_complete_days setting
                $autoCompleteDays = \App\Models\Setting::getIntValue('auto_complete_days', 5);
                $updateData['completed_at'] = now()->addDays($autoCompleteDays);
            } else {
                // For other products, use 15 hours
                $updateData['completed_at'] = now()->addHours(15);
            }
        }

        $order->update($updateData);

        // Create activity record when order is completed
        if ($request->order_status === 'completed') {
            Activity::createFromOrder($order);
        }

        $message = $request->order_status === 'completed' 
            ? ($order->game_type === 'Robux' 
                ? "Order berhasil diproses! Akan selesai dalam {$autoCompleteDays} hari."
                : "Order berhasil diproses! Akan selesai dalam 15 jam.")
            : 'Order status updated successfully.';

        return redirect()->back()->with('success', $message);
    }
}
