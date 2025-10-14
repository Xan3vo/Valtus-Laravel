<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserOrderController extends Controller
{
    private function generateOrderId()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $orderId = '';
        
        // Generate 6-7 character alphanumeric ID
        $length = rand(6, 7);
        for ($i = 0; $i < $length; $i++) {
            $orderId .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // Check if ID already exists, regenerate if needed
        while (Order::where('order_id', $orderId)->exists()) {
            $orderId = '';
            for ($i = 0; $i < $length; $i++) {
                $orderId .= $characters[rand(0, strlen($characters) - 1)];
            }
        }
        
        return $orderId;
    }

    public function createOrder(Request $request)
    {
        try {
            // Log incoming request for debugging
            \Log::info('Create Order Request:', $request->all());
            
            $request->validate([
                'amount' => 'required|integer|min:1',
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'price' => 'required|numeric|min:0',
                'payment_mode' => 'nullable|string|in:manual,gateway',
                'selected_method' => 'nullable|string',
                'gamepass_link' => 'nullable|string'
            ]);

            // Calculate expiry time
            $expiresAt = now()->addMinutes(10);

            // Generate unique order ID
            $orderId = $this->generateOrderId();
            
            // Create order with 10 minutes expiry
            $order = Order::create([
                'order_id' => $orderId, // Use custom order_id
                'username' => $request->username,
                'email' => $request->email,
                'game_type' => 'Robux',
                'amount' => $request->amount,
                'price' => $request->price,
                'tax' => 0,
                'total_amount' => $request->price,
                'payment_status' => 'pending',
                'order_status' => null, // Will be set to 'pending' when payment is completed
                'payment_method' => $request->selected_method,
                'payment_reference' => null,
                'gamepass_link' => $request->gamepass_link ?? null,
                'expires_at' => $expiresAt,
                'notes' => json_encode([
                    'payment_mode' => $request->payment_mode ?? 'manual',
                    'selected_method' => $request->selected_method
                ])
            ]);

            // Store order ID in session and redirect to payment methods
            session(['current_order_id' => $order->order_id]);
            
            \Log::info('Order created successfully:', [
                'order_id' => $order->order_id,
                'session_order_id' => session('current_order_id')
            ]);
            
            return redirect('/user/payment-methods');
            
        } catch (\Exception $e) {
            \Log::error('Error creating order:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            
            return redirect()->back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }

    public function updatePaymentMethod(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'payment_method' => 'required|string|in:qris,bca,gopay,ovo'
        ]);

        $order = Order::where('order_id', $request->order_id)->first();
        
        if (!$order) {
            return redirect()->route('home')->with('error', 'Order tidak ditemukan');
        }

        // Check if order is expired
        if ($order->expires_at && now()->gt($order->expires_at)) {
            $order->update(['order_status' => 'expired']);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan.');
        }

        $order->update([
            'payment_method' => $request->payment_method,
            'payment_status' => 'waiting_payment'
        ]);

        // Redirect to status page
        return redirect()->route('user.status', ['order' => $order->order_id]);
    }

    public function uploadProof(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|string',
                'proof_file' => 'required|file|image|max:10240' // 10MB max
            ]);

        $order = Order::where('order_id', $request->order_id)->first();
        
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order tidak ditemukan'], 404);
        }

        // Check if order is expired
        if ($order->expires_at && now()->gt($order->expires_at)) {
            return response()->json(['success' => false, 'message' => 'Waktu pembayaran telah habis'], 400);
        }

        // Store proof file
        $file = $request->file('proof_file');
        $filename = 'proof_' . $order->order_id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('proofs'), $filename);

        // Update order with proof file
        $notes = json_decode($order->notes, true) ?? [];
        $notes['proof_uploaded_at'] = now()->toISOString();

        $order->update([
            'proof_file' => $filename,
            'notes' => json_encode($notes),
            'payment_status' => 'waiting_confirmation',
            'order_status' => 'pending'
        ]);

        return response()->json([
            'success' => true, 
            'message' => 'Bukti transfer berhasil diupload',
            'proof_file' => $filename
        ]);
        
        } catch (\Exception $e) {
            \Log::error('Upload proof error: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeGamepassLink(Request $request)
    {
        $request->validate([
            'gamepass_link' => 'required|url'
        ]);

        // Store in session
        session(['gamepass_link' => $request->gamepass_link]);

        return response()->json(['success' => true]);
    }

    public function storeAmount(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:0'
        ]);

        // Store in session
        session(['selected_amount' => $request->amount]);

        return response()->json(['success' => true]);
    }

    public function storeUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255'
        ]);

        // Store in session
        session(['selected_username' => $request->username]);

        return response()->json(['success' => true]);
    }
}
