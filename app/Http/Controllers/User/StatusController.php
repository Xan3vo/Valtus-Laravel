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
            // Search by order ID - only show orders with valid order_status
            $order = Order::where('order_id', $query)
                ->whereNotNull('order_status')
                ->first();
            
            if (!$order) {
                return view('user.status', [
                    'error' => 'Order dengan ID "' . $query . '" tidak ditemukan atau sudah kadaluarsa'
                ]);
            }
            
            return view('user.status', [
                'order' => $order,
                'searchType' => 'id',
                'searchQuery' => $query
            ]);
            
        } else {
            // Search by username - only show orders with valid order_status
            $orders = Order::where('username', 'like', '%' . $query . '%')
                ->whereNotNull('order_status')
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($orders->isEmpty()) {
                return view('user.status', [
                    'error' => 'Tidak ada order aktif ditemukan untuk username "' . $query . '"'
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
        $order = Order::where('order_id', $orderId)
            ->whereNotNull('order_status')
            ->first();
        
        if (!$order) {
            return redirect()->route('user.status')->with('error', 'Order tidak ditemukan atau sudah kadaluarsa');
        }
        
        return view('user.status', [
            'order' => $order,
            'searchType' => 'id',
            'searchQuery' => $orderId
        ]);
    }
}
