<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // Default: show only waiting_confirmation orders
        $query = Order::where('payment_status', 'waiting_confirmation')
            ->whereNotNull('order_status');

        // If status filter is provided, show all relevant statuses
        if ($request->has('status') && $request->status) {
            if ($request->status === 'all') {
                // Show all statuses
                $query = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])
                    ->whereNotNull('order_status');
            } else {
                // Show specific status - map lowercase to proper case
                $statusMap = [
                    'waiting_confirmation' => 'waiting_confirmation',
                    'completed' => 'Completed',
                    'failed' => 'Failed'
                ];
                $mappedStatus = $statusMap[$request->status] ?? $request->status;
                
                $query = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])
                    ->whereNotNull('order_status')
                    ->where('payment_status', $mappedStatus);
            }
        }

        // Search by username or order ID
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

        // Order by priority: waiting_confirmation first, then by created_at asc
        if ($request->has('status') && $request->status === 'all') {
            $orders = $query->orderByRaw("CASE WHEN payment_status = 'waiting_confirmation' THEN 0 ELSE 1 END")
                ->orderBy('created_at', 'asc')
                ->paginate(20);
        } else {
            $orders = $query->orderBy('created_at', 'asc')
                ->paginate(20);
        }

        // Get all orders for statistics (including completed)
        $allOrders = Order::whereIn('payment_status', ['waiting_confirmation', 'Completed', 'Failed'])->get();

        return view('admin.payments.index', compact('orders', 'allOrders'));
    }

    public function show(Order $order)
    {
        return view('admin.payments.show', compact('order'));
    }

    public function confirmPayment(Request $request, Order $order)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes' => 'nullable|string|max:500'
        ]);

        if ($request->action === 'approve') {
            $order->update([
                'payment_status' => 'Completed',
                'order_status' => 'pending',
                'notes' => json_encode(array_merge(
                    json_decode($order->notes ?? '{}', true),
                    ['admin_notes' => $request->notes ?? 'Payment confirmed by admin'],
                    ['confirmed_at' => now()->toISOString()]
                ))
            ]);
            
            $message = 'Payment confirmed successfully!';
        } else {
            $order->update([
                'payment_status' => 'Failed',
                'order_status' => null,
                'notes' => json_encode(array_merge(
                    json_decode($order->notes ?? '{}', true),
                    ['admin_notes' => $request->notes ?? 'Payment rejected by admin'],
                    ['rejected_at' => now()->toISOString()]
                ))
            ]);
            
            $message = 'Payment rejected successfully!';
        }

        return redirect()->route('admin.payments')->with('success', $message);
    }

    public function downloadProof(Order $order)
    {
        if (!$order->proof_file) {
            abort(404, 'Proof file not found');
        }

        $filePath = public_path('proofs/' . $order->proof_file);
        
        if (!file_exists($filePath)) {
            abort(404, 'Proof file not found on server');
        }

        return response()->download($filePath);
    }
}