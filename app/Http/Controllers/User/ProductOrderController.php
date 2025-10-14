<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductOrderController extends Controller
{
    public function show($gameType)
    {
        $products = Product::where('is_active', true)
            ->where('game_type', $gameType)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('user.product-order', compact('products', 'gameType'));
    }

    public function storeProductData(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|integer|min:0',
            'game_type' => 'required|string|max:100',
            'username' => 'required|string|max:100',
            'email' => 'required|email|max:255',
        ]);

        // Store product data in session
        session([
            'selected_product_id' => $request->product_id,
            'selected_product_name' => $request->product_name,
            'selected_product_price' => $request->product_price,
            'selected_game_type' => $request->game_type,
            'selected_username' => $request->username,
            'selected_email' => $request->email,
        ]);

        // Redirect to payment page
        return redirect()->route('user.product-payment');
    }

    public function payment()
    {
        // Check if product data exists in session
        if (!session('selected_product_id')) {
            return redirect()->route('products')->with('error', 'Silakan pilih produk terlebih dahulu');
        }

        return view('user.product-payment');
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_name' => 'required|string|max:255',
            'product_price' => 'required|integer|min:0',
            'game_type' => 'required|string|max:100',
            'username' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'payment_mode' => 'required|in:manual,gateway',
            'selected_method' => 'nullable|string',
        ]);

        try {
            // Generate unique order ID
            $orderId = $this->generateOrderId();
            
            // Create order
            $order = \App\Models\Order::create([
                'order_id' => $orderId,
                'username' => $request->username,
                'email' => $request->email,
                'amount' => 1, // For products, amount is always 1
                'price' => $request->product_price,
                'game_type' => $request->game_type,
                'product_name' => $request->product_name,
                'tax' => 0,
                'total_amount' => $request->product_price,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'payment_method' => $request->selected_method, // Set payment method
                'expires_at' => now()->addMinutes(10),
                'notes' => json_encode([
                    'product_id' => $request->product_id,
                    'payment_mode' => $request->payment_mode,
                    'selected_method' => $request->selected_method,
                ])
            ]);

            // Store order ID in session
            session(['current_order_id' => $order->order_id]);

            Log::info('Product order created successfully:', ['order_id' => $order->order_id]);

            // Redirect to payment methods
            return redirect()->route('user.payment-methods');

        } catch (\Exception $e) {
            Log::error('Error creating product order:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }

    private function generateOrderId()
    {
        do {
            $orderId = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (\App\Models\Order::where('order_id', $orderId)->exists());
        
        return $orderId;
    }
}
