<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        return view('user.status');
    }
    
    public function search(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'id'); // 'id' or 'username'
        
        if (!$query) {
            return view('user.status', [
                'error' => 'Masukkan ID order atau username untuk mencari'
            ]);
        }
        
        if ($type === 'id') {
            // Search by order ID - show orders with payment confirmed OR failed
            $order = Order::where('order_id', $query)
                ->whereIn('payment_status', ['Completed', 'waiting_confirmation', 'Failed'])
                ->where(function($q) {
                    $q->whereNotNull('order_status')
                      ->orWhere('payment_status', 'Failed');
                })
                ->first();
            
            if (!$order) {
                return view('user.status', [
                    'error' => 'Order dengan ID "' . $query . '" tidak ditemukan'
                ]);
            }
            
            return view('user.status', [
                'order' => $order,
                'searchType' => 'id',
                'searchQuery' => $query
            ]);
            
        } else {
            // Search by username - show orders with payment confirmed OR failed
            $orders = Order::where('username', 'like', '%' . $query . '%')
                ->whereIn('payment_status', ['Completed', 'waiting_confirmation', 'Failed'])
                ->where(function($q) {
                    $q->whereNotNull('order_status')
                      ->orWhere('payment_status', 'Failed');
                })
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($orders->isEmpty()) {
                return view('user.status', [
                    'error' => 'Tidak ada order ditemukan untuk username "' . $query . '"'
                ]);
            }
            
            return view('user.status', [
                'orders' => $orders,
                'searchType' => 'username',
                'searchQuery' => $query
            ]);
        }
    }
    
    public function show($orderId)
    {
        // Find order - allow all statuses including pending (for Midtrans return)
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('user.status')->with('error', 'Order tidak ditemukan');
        }
        
        return view('user.status', [
            'order' => $order,
            'searchType' => 'id',
            'searchQuery' => $orderId
        ]);
    }
}
