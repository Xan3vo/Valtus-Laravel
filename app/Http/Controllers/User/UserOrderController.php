<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blacklist;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\PromoCodeUsage;
use App\Models\ReferralCode;
use App\Models\ReferralConversion;
use App\Models\RobuxDiscountRule;
use App\Services\RobuxStockService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class UserOrderController extends Controller
{
    private function getActiveReferralCode(Request $request): ?ReferralCode
    {
        $code = $request->cookie('referral_code') ?: session('referral_code');
        if (!$code) {
            return null;
        }

        $code = strtoupper(trim((string) $code));
        if ($code === '') {
            return null;
        }

        return ReferralCode::where('code', $code)
            ->where('is_active', true)
            ->first();
    }

    private function calculateDiscountAmount(string $method, float $value, float $price): float
    {
        if ($price <= 0) {
            return 0;
        }

        if ($method === 'percentage') {
            $discount = $price * ($value / 100);
        } else {
            $discount = $value;
        }

        return (float) min($discount, $price);
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
                $existsInDb = Order::where('order_id', $orderId)->exists();
                
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
                    $existsInDb = Order::where('order_id', $orderId)->exists();
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
    }

    public function createOrder(Request $request)
    {
        try {
            // Log incoming request for debugging
            Log::info('Create Order Request:', $request->all());
            
            $request->validate([
                'amount' => 'required|integer|min:1',
                'username' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'price' => 'required|numeric|min:0',
                'payment_mode' => 'nullable|string|in:manual,gateway',
                'selected_method' => 'nullable|string',
                'purchase_method' => 'nullable|string|in:gamepass,group', // Only for Robux orders
                'gamepass_link' => 'nullable|string',
                'promo_code_id' => 'nullable|integer|exists:promo_codes,id',
                'promo_code' => 'nullable|string|max:50',
                'original_price' => 'nullable|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
            ]);

            if (Blacklist::isUsernameBlocked($request->username)) {
                return redirect()->back()->with('error', 'Akun anda diblokir. Silakan hubungi admin.');
            }

            // CRITICAL: Validate purchase_method - must be provided and valid
            // Don't default to 'gamepass' - this prevents bug where group order becomes gamepass
            $purchaseMethod = $request->purchase_method;
            if (!$purchaseMethod || !in_array($purchaseMethod, ['gamepass', 'group'])) {
                Log::error('Invalid purchase_method in createOrder:', [
                    'purchase_method' => $purchaseMethod,
                    'request_data' => $request->all()
                ]);
                return redirect()->back()->with('error', 'Metode pembelian tidak valid. Silakan buat pesanan baru.');
            }

            // CRITICAL: Validate Robux stock availability (server-side)
            // Prevent creating orders when stock is 0 or requested amount exceeds available stock.
            // Use current stock. Stock is deducted later based on payment flow (manual upload proof / Midtrans webhook success).
            $requestedAmount = (int) $request->amount;
            if ($purchaseMethod === 'group') {
                $availableStock = RobuxStockService::getCurrentGroupStock();
            } else {
                $availableStock = RobuxStockService::getCurrentStock();
            }
            if ($availableStock < $requestedAmount) {
                Log::warning('Insufficient Robux stock for createOrder', [
                    'purchase_method' => $purchaseMethod,
                    'requested_amount' => $requestedAmount,
                    'available_stock' => $availableStock,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);

                $methodLabel = $purchaseMethod === 'group' ? 'Group' : 'Gamepass';
                $errorMessage = 'Stok Robux (' . $methodLabel . ') tidak mencukupi. Sisa stok: ' . number_format($availableStock, 0, ',', '.') . ' Robux.';
                return redirect()->back()->with('error', $errorMessage);
            }
            
            // CRITICAL: Calculate price server-side (do NOT trust client/session price)
            // This prevents price/amount parameter manipulation (e.g., buying 3000 RBX but paying 1k).
            $pricePer100 = $purchaseMethod === 'group'
                ? (float) Setting::getValue('group_robux_price_per_100', '10000')
                : (float) Setting::getValue('robux_price_per_100', '10000');
            $basePrice = $pricePer100 * ($request->amount / 100);

            $rule = RobuxDiscountRule::findMatchingRule((int) $request->amount, $purchaseMethod);
            $ruleDiscountAmount = 0;
            if ($rule) {
                $ruleDiscountAmount = $rule->calculateDiscount($basePrice);
            }
            $priceAfterRule = max(0, $basePrice - $ruleDiscountAmount);

            // Apply referral discount (server-side, anti-inspect)
            // Referral cannot be combined with promo code
            $referralCode = $this->getActiveReferralCode($request);
            $referralDiscountAmount = 0;
            $referralRewardAmount = 0;
            $referralSnapshot = null;
            if ($referralCode) {
                if ($request->promo_code_id || $request->promo_code) {
                    return redirect()->back()->with('error', 'Kode promo tidak bisa digunakan bersamaan dengan referral.');
                }

                if ($referralCode->isValidForOrderAmount((float) $priceAfterRule)) {
                    $referralDiscountAmount = $this->calculateDiscountAmount(
                        (string) $referralCode->buyer_discount_method,
                        (float) $referralCode->buyer_discount_value,
                        (float) $priceAfterRule
                    );

                    $priceAfterRule = max(0, (float) $priceAfterRule - (float) $referralDiscountAmount);

                    $referralRewardAmount = $this->calculateDiscountAmount(
                        (string) $referralCode->reward_method,
                        (float) $referralCode->reward_value,
                        (float) $priceAfterRule
                    );

                    $referralSnapshot = [
                        'code' => $referralCode->code,
                        'buyer_discount_method' => $referralCode->buyer_discount_method,
                        'buyer_discount_value' => (float) $referralCode->buyer_discount_value,
                        'buyer_discount_amount' => (float) round($referralDiscountAmount, 2),
                        'reward_method' => $referralCode->reward_method,
                        'reward_value' => (float) $referralCode->reward_value,
                        'reward_amount' => (float) round($referralRewardAmount, 2),
                        'applied_at' => now()->toISOString(),
                    ];
                }
            }

            $promoDiscountAmount = 0;
            if ($request->promo_code_id) {
                $promoCode = PromoCode::find($request->promo_code_id);
                if ($promoCode && $promoCode->isValid()) {
                    $priceAfterPromo = $promoCode->applyDiscount($priceAfterRule);
                    $promoDiscountAmount = max(0, $priceAfterRule - $priceAfterPromo);
                    $priceAfterRule = $priceAfterPromo;
                }
            }

            $validatedBasePrice = (float) round($basePrice, 2);
            $validatedFinalPrice = (float) round($priceAfterRule, 2);
            $discountAmount = (float) round($ruleDiscountAmount + $referralDiscountAmount + $promoDiscountAmount, 2);

            if ($validatedFinalPrice <= 0) {
                Log::error('Invalid final price in createOrder (server calculated):', [
                    'final_price' => $validatedFinalPrice,
                    'purchase_method' => $purchaseMethod,
                    'amount' => (int) $request->amount,
                ]);
                return redirect()->back()->with('error', 'Harga tidak valid. Silakan buat pesanan baru.');
            }

            Log::info('Price calculated server-side in createOrder:', [
                'purchase_method' => $purchaseMethod,
                'amount' => (int) $request->amount,
                'price_per_100' => $pricePer100,
                'base_price' => $validatedBasePrice,
                'rule_discount' => (float) round($ruleDiscountAmount, 2),
                'promo_discount' => (float) round($promoDiscountAmount, 2),
                'final_price' => $validatedFinalPrice,
            ]);
            
            // Calculate expiry time
            $expiresAt = now()->addMinutes(10);

            // Generate unique order ID
            $orderId = $this->generateOrderId();
            
            // Handle promo code if provided
            // Use validated prices from above (server-calculated)
            $originalPrice = $validatedBasePrice;
            $finalPrice = $validatedFinalPrice; // Use validated final price
            
            $promoCodeId = null;
            $promoCodeData = null;
            if ($request->promo_code_id) {
                $promoCode = PromoCode::find($request->promo_code_id);
                if ($promoCode && $promoCode->isValid()) {
                    $promoCodeId = $promoCode->id;
                    $promoCodeData = [
                        'promo_code_id' => $promoCodeId,
                        'promo_code' => $request->promo_code,
                        'original_price' => $originalPrice,
                        'discount_amount' => $discountAmount,
                        'final_price' => $finalPrice,
                    ];
                    
                    // Increment promo code usage immediately (even if not paid yet)
                    $promoCode->incrementUsage();
                }
            }
            
            // Validate gamepass_link - should only be present for gamepass method
            if ($purchaseMethod === 'group' && $request->gamepass_link) {
                Log::warning('gamepass_link provided for group method, ignoring:', [
                    'purchase_method' => $purchaseMethod,
                    'gamepass_link' => $request->gamepass_link
                ]);
                $request->merge(['gamepass_link' => null]);
            }
            
            // Prepare notes
            $notes = [
                'payment_mode' => $request->payment_mode ?? 'manual',
                'selected_method' => $request->selected_method,
                'purchase_method' => $purchaseMethod // Use validated purchase_method
            ];

            if ($referralSnapshot) {
                $notes['referral'] = $referralSnapshot;
            }
            
            if ($promoCodeData) {
                $notes['promo_code'] = $promoCodeData;
            }
            
            // Create order with 10 minutes expiry
            $order = Order::create([
                'order_id' => $orderId, // Use custom order_id
                'username' => $request->username,
                'email' => $request->email,
                'game_type' => 'Robux',
                'amount' => $request->amount,
                'price' => $finalPrice, // Final price after discount
                'tax' => 0,
                'total_amount' => $finalPrice,
                'payment_status' => 'Pending',
                'order_status' => null, // Will be set to 'pending' when payment is completed
                'payment_method' => $request->selected_method,
                'purchase_method' => $purchaseMethod, // Use validated purchase_method, no default
                'payment_reference' => null,
                'gamepass_link' => ($purchaseMethod === 'gamepass') ? ($request->gamepass_link ?? null) : null,
                'expires_at' => $expiresAt,
                'notes' => json_encode($notes)
            ]);
            
            // Create promo code usage record (even if order not paid yet)
            if ($promoCodeId) {
                PromoCodeUsage::create([
                    'promo_code_id' => $promoCodeId,
                    'order_id' => $orderId,
                    'username' => $request->username,
                    'email' => $request->email,
                    'original_price' => $originalPrice,
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice,
                    'payment_status' => 'Pending',
                    'is_paid' => false,
                ]);
            }

            // Create referral conversion record (pending) if referral applied
            if ($referralSnapshot && $referralCode) {
                ReferralConversion::create([
                    'referral_code_id' => $referralCode->id,
                    'order_id' => $orderId,
                    'buyer_username' => $request->username,
                    'buyer_email' => $request->email,
                    'order_amount' => $finalPrice,
                    'buyer_discount_method' => (string) $referralSnapshot['buyer_discount_method'],
                    'buyer_discount_value' => (float) $referralSnapshot['buyer_discount_value'],
                    'buyer_discount_amount' => (float) $referralSnapshot['buyer_discount_amount'],
                    'reward_method' => (string) $referralSnapshot['reward_method'],
                    'reward_value' => (float) $referralSnapshot['reward_value'],
                    'reward_amount' => (float) $referralSnapshot['reward_amount'],
                    'status' => 'pending',
                ]);
            }

            // Store order ID in session
            session(['current_order_id' => $order->order_id]);
            
            Log::info('Order created successfully:', [
                'order_id' => $order->order_id,
                'payment_mode' => $request->payment_mode,
                'selected_method' => $request->selected_method,
                'session_order_id' => session('current_order_id')
            ]);
            
            // If gateway mode, redirect to Midtrans payment page
            if ($request->payment_mode === 'gateway') {
                return redirect()->route('user.midtrans-payment', ['orderId' => $order->order_id]);
            }
            
            // If manual mode, redirect to payment methods (upload bukti)
            return redirect('/user/payment-methods');
            
        } catch (\Exception $e) {
            Log::error('Error creating order:', [
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
            'payment_status' => 'Pending'
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

        // Re-check and deduct Robux stock on proof upload (manual reserve)
        if ($order->game_type === 'Robux' && $order->amount) {
            $purchaseMethod = $order->purchase_method ?? 'gamepass';

            $notes = [];
            if (is_array($order->notes)) {
                $notes = $order->notes;
            } elseif (is_string($order->notes) && !empty($order->notes)) {
                $decoded = @json_decode((string) $order->notes, true);
                if (is_array($decoded)) {
                    $notes = $decoded;
                }
            }

            // Avoid double-deduct if user retries upload
            if (empty($notes['stock_deducted_at'])) {
                if (!RobuxStockService::reduceStock((int) $order->amount, $purchaseMethod)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok Robux tidak mencukupi untuk melanjutkan. Silakan tunggu pengisian ulang.'
                    ], 400);
                }

                $notes['stock_deducted_at'] = now()->toISOString();
                $notes['stock_deducted_by'] = 'manual_upload_proof';
                $notes['stock_deducted_method'] = $purchaseMethod;
                $order->update(['notes' => json_encode($notes)]);
                $order->refresh();
            }
        }

        // Check if order is expired
        if ($order->expires_at && now()->gt($order->expires_at)) {
            return response()->json(['success' => false, 'message' => 'Waktu pembayaran telah habis'], 400);
        }

        // Store proof file
        $file = $request->file('proof_file');
        $filename = 'proof_' . $order->order_id . '_' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
        Storage::disk('local')->putFileAs('proofs', $file, $filename);

        // Update order with proof file
        $notes = [];
        if (is_array($order->notes)) {
            $notes = $order->notes;
        } elseif (is_string($order->notes) && !empty($order->notes)) {
            $decoded = @json_decode((string) $order->notes, true);
            if (is_array($decoded)) {
                $notes = $decoded;
            }
        }
        $notes['proof_uploaded_at'] = now()->toISOString();

        $order->update([
            'proof_file' => $filename,
            'notes' => json_encode($notes),
            'payment_status' => 'waiting_confirmation',
            'order_status' => 'pending'
        ]);

        // Send email notification
        try {
            // Re-apply email config from settings before sending
            $this->applyEmailConfig();
            
            // Log email attempt
            Log::info('Attempting to send order created email', [
                'order_id' => $order->order_id,
                'email' => $order->email,
                'mailer' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'from' => config('mail.from.address'),
            ]);
            
            Mail::to($order->email)->send(new \App\Mail\OrderCreatedNotification($order));
            
            Log::info('Order created email sent successfully', [
                'order_id' => $order->order_id,
                'email' => $order->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send order created email', [
                'order_id' => $order->order_id,
                'email' => $order->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't fail the upload if email fails
        }

        return response()->json([
            'success' => true, 
            'message' => 'Bukti transfer berhasil diupload',
            'proof_file' => $filename
        ]);
        
        } catch (\Exception $e) {
            Log::error('Upload proof error: ' . $e->getMessage());
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

    public function storePurchaseMethod(Request $request)
    {
        $request->validate([
            'purchase_method' => 'required|string|in:gamepass,group'
        ]);

        // Store in session
        session(['selected_purchase_method' => $request->purchase_method]);

        return response()->json(['success' => true]);
    }

    public function storeDiscountData(Request $request)
    {
        $request->validate([
            'base_price' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'has_discount' => 'nullable|boolean',
        ]);

        // Store discount data in session
        session([
            'robux_base_price' => $request->base_price ?? null,
            'robux_discount_amount' => $request->discount_amount ?? null,
            'robux_final_price' => $request->final_price ?? null,
            'robux_has_discount' => $request->has_discount ?? false,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Store complete order session data atomically
     * This prevents race conditions when multiple users are using the system
     */
    public function storeOrderSession(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required|string|max:255',
                'amount' => 'required|integer|min:1',
                'purchase_method' => 'required|string|in:gamepass,group',
                'base_price' => 'required|numeric|min:0',
                'discount_amount' => 'nullable|numeric|min:0',
                'final_price' => 'required|numeric|min:0',
                'has_discount' => 'nullable|boolean',
                'gamepass_link' => 'nullable|string|url',
            ]);

            // CRITICAL: Validate purchase_method and price consistency
            $purchaseMethod = $request->purchase_method;
            $expectedPricePer100 = $purchaseMethod === 'group' 
                ? (float) Setting::getValue('group_robux_price_per_100', '10000')
                : (float) Setting::getValue('robux_price_per_100', '10000');
            
            $expectedBasePrice = $expectedPricePer100 * ($request->amount / 100);
            $priceDiff = abs($request->base_price - $expectedBasePrice);
            $priceDiffPercent = ($priceDiff / $expectedBasePrice) * 100;
            
            // Reject if price difference is too large (>50%)
            if ($priceDiffPercent > 50) {
                Log::error('Price validation failed in storeOrderSession:', [
                    'purchase_method' => $purchaseMethod,
                    'expected_base_price' => $expectedBasePrice,
                    'provided_base_price' => $request->base_price,
                    'difference_percent' => $priceDiffPercent,
                    'amount' => $request->amount
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Harga tidak sesuai dengan metode pembelian. Silakan buat pesanan baru.'
                ], 400);
            }

            // Validate final price is reasonable
            if ($request->final_price > $request->base_price) {
                Log::error('Final price greater than base price in storeOrderSession:', [
                    'base_price' => $request->base_price,
                    'final_price' => $request->final_price
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Harga final tidak valid. Silakan buat pesanan baru.'
                ], 400);
            }

            // CRITICAL: Store all data atomically in a single session write
            // This prevents race conditions where partial data is saved
            session([
                'selected_username' => $request->username,
                'selected_amount' => $request->amount,
                'selected_purchase_method' => $purchaseMethod,
                'robux_base_price' => $request->base_price,
                'robux_discount_amount' => $request->discount_amount ?? 0,
                'robux_final_price' => $request->final_price,
                'robux_has_discount' => $request->has_discount ?? false,
            ]);

            // Only store gamepass_link if method is gamepass
            if ($purchaseMethod === 'gamepass' && $request->gamepass_link) {
                session(['gamepass_link' => $request->gamepass_link]);
            } elseif ($purchaseMethod === 'group') {
                // Clear gamepass_link if method is group
                session()->forget('gamepass_link');
            }

            // Force session save to ensure data is persisted
            session()->save();

            Log::info('Order session stored successfully:', [
                'username' => $request->username,
                'amount' => $request->amount,
                'purchase_method' => $purchaseMethod,
                'base_price' => $request->base_price,
                'final_price' => $request->final_price
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data pesanan berhasil disimpan'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in storeOrderSession:', [
                'errors' => $e->errors(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . implode(', ', array_map(function($errors) {
                    return implode(', ', $errors);
                }, $e->errors()))
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in storeOrderSession:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data pesanan. Silakan coba lagi.'
            ], 500);
        }
    }
    
    /**
     * Apply email configuration from database settings
     * Real case: Pakai database settings
     */
    private function applyEmailConfig()
    {
        try {
            // PAKAI DATABASE SETTINGS (Real Case Implementation)
            $mailer = Setting::getValue('mail_mailer', 'log');
            $host = Setting::getValue('mail_host', '');
            $port = Setting::getValue('mail_port', '587');
            $username = Setting::getValue('mail_username', '');
            $password = Setting::getValue('mail_password', '');
            $encryption = Setting::getValue('mail_encryption', 'tls');
            $fromAddress = Setting::getValue('mail_from_address', 'hello@example.com');
            $fromName = Setting::getValue('mail_from_name', 'Valtus');
            
            // Normalize encryption
            if ($encryption === 'null' || $encryption === null || $encryption === '') {
                $encryption = null;
            }
            
            config([
                'mail.default' => $mailer ?: 'log',
                'mail.mailers.smtp.host' => $host ?: '127.0.0.1',
                'mail.mailers.smtp.port' => $port ?: '2525',
                'mail.mailers.smtp.username' => $username,
                'mail.mailers.smtp.password' => $password,
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.mailers.smtp.timeout' => 60, // 60 seconds timeout
                'mail.from.address' => $fromAddress ?: 'hello@example.com',
                'mail.from.name' => $fromName ?: 'Valtus',
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to apply email config from database: ' . $e->getMessage());
        }
    }
}
