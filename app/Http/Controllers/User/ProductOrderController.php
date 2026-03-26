<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
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

        // Fetch product details to get discount information
        $product = Product::find($request->product_id);
        
        // Calculate discount information
        $originalPrice = $product->total_price;
        $finalPrice = $request->product_price;
        $productDiscountAmount = 0;
        $productHasDiscount = false;
        
        if ($product->discount_active && $product->final_price < $product->total_price) {
            $productHasDiscount = true;
            $productDiscountAmount = $product->total_price - $product->final_price;
            $originalPrice = $product->total_price;
            $finalPrice = $product->final_price;
        }

        // Store product data in session including discount information
        session([
            'selected_product_id' => $request->product_id,
            'selected_product_name' => $request->product_name,
            'selected_product_price' => $finalPrice, // Use final price (after discount)
            'product_original_price' => $originalPrice, // Original price before discount
            'product_discount_amount' => $productDiscountAmount, // Discount amount
            'product_has_discount' => $productHasDiscount, // Whether product has discount
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
            if (Blacklist::isUsernameBlocked($request->username)) {
                return redirect()->back()->with('error', 'Akun anda diblokir. Silakan hubungi admin.');
            }

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

            Log::info('Product order created successfully:', [
                'order_id' => $order->order_id,
                'payment_mode' => $request->payment_mode,
                'selected_method' => $request->selected_method,
            ]);

            // If gateway mode, redirect to Midtrans payment page
            if ($request->payment_mode === 'gateway') {
                return redirect()->route('user.midtrans-payment', ['orderId' => $order->order_id]);
            }
            
            // If manual mode, redirect to payment methods (upload bukti)
            return redirect()->route('user.payment-methods');

        } catch (\Exception $e) {
            Log::error('Error creating product order:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan. Silakan coba lagi.');
        }
    }

    private function generateOrderId()
    {
        // Get payment mode to determine if we need Midtrans-compatible order_id
        $paymentMode = request()->input('payment_mode', 'manual');
        $isGateway = $paymentMode === 'gateway';
        
        if ($isGateway) {
            // For gateway mode (Midtrans), generate unique 8-character order_id
            // Format: {BASE36_TIMESTAMP}{RANDOM} = 8 characters total
            // Base36 encoding makes timestamp shorter while maintaining uniqueness
            // Midtrans requires unique order_id even across different environments
            
            $maxRetries = 20; // More retries for shorter ID
            $retryCount = 0;
            
            do {
                // Convert timestamp to base36 (shorter representation)
                // Use last 6 digits of timestamp mod 36^5 for compactness
                $timestamp = time();
                $timestampMod = $timestamp % (36 * 36 * 36 * 36 * 36); // Mod to fit in 5 base36 digits
                $base36 = strtoupper(base_convert($timestampMod, 10, 36)); // Convert to base36
                $base36 = str_pad($base36, 5, '0', STR_PAD_LEFT); // Pad to 5 chars
                
                // Generate 3 random characters
                $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
                
                // Combine: 5 chars (base36 timestamp) + 3 chars (random) = 8 chars total
                $orderId = $base36 . $random;
                
                // Check if ID already exists in database
                $existsInDb = \App\Models\Order::where('order_id', $orderId)->exists();
                
                if (!$existsInDb) {
                    break;
                }
                
                $retryCount++;
                
                // If collision, use microsecond component for extra randomness
                if ($retryCount > 0) {
                    $micros = substr(str_replace('.', '', microtime(true)), -2); // Last 2 digits
                    $microsBase36 = strtoupper(base_convert(intval($micros), 10, 36));
                    $microsBase36 = str_pad($microsBase36, 2, '0', STR_PAD_LEFT);
                    
                    // Use 4 chars timestamp + 2 chars microsecond + 2 chars random = 8 chars
                    $timestampMod = $timestamp % (36 * 36 * 36 * 36); // Mod for 4 chars
                    $base36 = strtoupper(base_convert($timestampMod, 10, 36));
                    $base36 = str_pad($base36, 4, '0', STR_PAD_LEFT);
                    $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 2));
                    $orderId = $base36 . $microsBase36 . $random;
                    
                    // Check again
                    $existsInDb = \App\Models\Order::where('order_id', $orderId)->exists();
                    if (!$existsInDb) {
                        break;
                    }
                }
                
                // If still collision, wait and use fresh timestamp
                if ($retryCount >= $maxRetries) {
                    usleep(1000); // 1ms delay
                    $timestamp = time();
                    $timestampMod = $timestamp % (36 * 36 * 36 * 36 * 36);
                    $base36 = strtoupper(base_convert($timestampMod, 10, 36));
                    $base36 = str_pad($base36, 5, '0', STR_PAD_LEFT);
                    $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 3));
                    $orderId = $base36 . $random;
                    break;
                }
                
                // Small delay to get different timestamp/microsecond
                usleep(500); // 0.5ms delay
                
            } while (true);
            
            return $orderId;
        } else {
            // For manual mode, use shorter format (backward compatible)
        do {
            $orderId = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (\App\Models\Order::where('order_id', $orderId)->exists());
        
        return $orderId;
        }
    }
}
