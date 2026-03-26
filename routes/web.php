<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RobuxPricingController;
use App\Http\Controllers\Admin\GroupRobuxSettingsController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PromoCodeController;
use App\Http\Controllers\Admin\ReferralCodeController;
use App\Http\Controllers\Admin\BlacklistController as AdminBlacklistController;
use App\Http\Controllers\Admin\ActivityHistoryController;
use App\Http\Controllers\Api\RobloxController;
use App\Http\Controllers\Api\BlacklistController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\ReferralController;

Route::get('/', function (\Illuminate\Http\Request $request) {
    // Handle order_id from Midtrans return (when user clicks back)
    if ($request->has('order_id')) {
        $orderId = $request->get('order_id');
        
        // Find order
        $order = \App\Models\Order::where('order_id', $orderId)->first();
        
        if ($order) {
            // Redirect to status page to show order details
            return redirect()->route('user.status.show', ['orderId' => $orderId])
                ->with('info', 'Anda kembali dari halaman pembayaran. Silakan cek status pesanan Anda.');
        } else {
            // Order not found, redirect to status page with error
            return redirect()->route('user.status')
                ->with('error', 'Order dengan ID "' . $orderId . '" tidak ditemukan.');
        }
    }
    
    $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
    $taxRate = (float) Setting::getValue('gamepass_tax_rate', '30');
    $pricePer1 = $pricePer100 / 100.0;
    $robuxMinOrder = (int) Setting::getValue('robux_min_order', '100');
    
    // Get real stock status for regular Robux
    $stockStatus = \App\Services\RobuxStockService::getStockStatus();
    $robuxStock = $stockStatus['current'];
    
    // Get group Robux stock status
    $groupRobuxStock = (int) Setting::getValue('group_robux_stock', '50000');
    $groupRobuxStockMin = (int) Setting::getValue('group_robux_stock_minimum', '5000');
    $groupStockIsLow = $groupRobuxStock <= $groupRobuxStockMin;
    $groupStockStatus = $groupStockIsLow ? 'low' : ($groupRobuxStock > $groupRobuxStockMin * 2 ? 'high' : 'normal');
    $groupStockStatusArray = [
        'current' => $groupRobuxStock,
        'minimum' => $groupRobuxStockMin,
        'is_low' => $groupStockIsLow,
        'status' => $groupStockStatus,
        'percentage' => $groupRobuxStockMin > 0 ? round(($groupRobuxStock / $groupRobuxStockMin) * 100, 1) : 0
    ];
    
    // Get recent activities for live feed
    $recentActivities = \App\Models\Activity::getRecentActivities(5);
    
    // Get other products (non-Robux)
    $otherProducts = \App\Models\Product::where('category', '!=', 'robux')
        ->where('is_active', true)
        ->orderBy('sort_order')
        ->orderBy('name')
        ->limit(6)
        ->get();
    
    return view('home', [
        'robuxPricePer100' => $pricePer100,
        'robuxPricePer1' => $pricePer1,
        'gamepassTaxRate' => $taxRate,
        'robuxMinOrder' => $robuxMinOrder,
        'robuxStock' => $robuxStock,
        'stockStatus' => $stockStatus,
        'groupRobuxStock' => $groupRobuxStock,
        'groupStockStatus' => $groupStockStatusArray,
        'recentActivities' => $recentActivities,
        'otherProducts' => $otherProducts,
    ]);
})->name('home');

// Lightweight API route (no auth) for username check
Route::get('/api/roblox/username', [RobloxController::class, 'checkUsername'])->name('api.roblox.username');
Route::get('/api/blacklist/check', [BlacklistController::class, 'check'])->name('api.blacklist.check');
Route::get('/api/roblox/gamepass-check', [RobloxController::class, 'checkGamepass'])->name('api.roblox.gamepass');

// Promo Code API
Route::post('/api/promo-code/validate', [App\Http\Controllers\Api\PromoCodeController::class, 'validate'])->name('api.promo-code.validate');

// Group membership API routes
Route::get('/api/roblox/group-membership', [App\Http\Controllers\Api\RobloxGroupController::class, 'checkGroupMembership'])->name('api.roblox.group-membership');
Route::get('/api/roblox/group-info', [App\Http\Controllers\Api\RobloxGroupController::class, 'getGroupInfo'])->name('api.roblox.group-info');
Route::get('/api/robux/discount', [App\Http\Controllers\Api\RobuxDiscountController::class, 'getDiscount'])->name('api.robux.discount');

// Referral routes
Route::get('/r/{code}', [ReferralController::class, 'handleReferral'])->name('referral.handle');
Route::get('/ref/{code}/dashboard/{secret}', [ReferralController::class, 'ownerDashboard'])->name('referral.dashboard');

// Debug/Test route for gamepass logic
Route::get('/api/test/gamepass-debug', [RobloxController::class, 'debugGamepass'])->name('api.test.gamepass-debug');

// Debug pages
Route::get('/debug/gamepass', function() {
    return view('debug.gamepass');
})->name('debug.gamepass');

Route::get('/debug/comparison', function() {
    return view('debug.comparison');
})->name('debug.comparison');

// API route for payment mode check
Route::get('/api/payment-mode', function() {
    return response()->json([
        'payment_mode' => \App\Models\Setting::getValue('payment_mode', 'manual'),
        'payment_enabled' => \App\Models\Setting::getValue('payment_enabled', '0') === '1'
    ]);
})->name('api.payment-mode');

// Payment methods API route (returns all available payment methods from Midtrans)
Route::get('/api/payment-methods', [App\Http\Controllers\Api\PaymentMethodController::class, 'getPaymentMethods'])->name('api.payment-methods');

// Public products page
Route::get('/products', function () {
    $gameType = request('game_type');
    
    // Decode URL-encoded game type
    if ($gameType) {
        $gameType = urldecode($gameType);
    }
    
    $query = \App\Models\Product::where('is_active', true);
    
    if ($gameType) {
        $query->where('game_type', $gameType);
    }
    
    $products = $query->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(12)
        ->appends(request()->query()); // Preserve query parameters in pagination
    
    // Get all unique game types for filter
    $gameTypes = \App\Models\Product::where('is_active', true)
        ->distinct()
        ->pluck('game_type')
        ->filter()
        ->sort()
        ->values();
    
    // Get sample images for each game type
    $gameTypeImages = [];
    foreach ($gameTypes as $type) {
        $sampleProduct = \App\Models\Product::where('is_active', true)
            ->where('game_type', $type)
            ->where(function($q) {
                $q->whereNotNull('image')->orWhereNotNull('image_url');
            })
            ->first();
        
        if ($sampleProduct) {
            $gameTypeImages[$type] = $sampleProduct->image ? asset($sampleProduct->image) : $sampleProduct->image_url;
        }
    }
    
    return view('products', compact('products', 'gameTypes', 'gameTypeImages', 'gameType'));
})->name('products');

// API route for contact info
Route::get('/api/contact-info', [App\Http\Controllers\Api\ContactController::class, 'getContactInfo'])->name('api.contact-info');

// API route for recent activities (max 3) - Optimized for multiple users
Route::get('/api/recent-activities', function() {
    // Cache for 10 seconds to balance performance and real-time updates
    return Cache::remember('recent_activities', 10, function() {
        $activities = \App\Models\Activity::getRecentActivities(3);
        
        return response()->json([
            'activities' => $activities->map(function($activity) {
                // Get avatar data dynamically (cached in service)
                $avatarData = \App\Services\RobloxAvatarService::getAvatarWithFallback($activity->username);
                
                return [
                    'id' => $activity->id,
                    'username' => $activity->username,
                    'masked_username' => $activity->masked_username,
                    'formatted_amount' => $activity->formatted_amount,
                    'time_ago' => $activity->processed_at->diffForHumans(),
                    'processed_at' => $activity->processed_at->toISOString(),
                    'avatar_data' => $avatarData,
                    'product_info' => $activity->product_info,
                    'game_type' => $activity->game_type
                ];
            })
        ]);
    });
})->name('api.recent-activities');

// API route for current stock
Route::get('/api/current-stock', function() {
    $stockStatus = \App\Services\RobuxStockService::getStockStatus();
    
    // Get group stock status
    $groupRobuxStock = (int) Setting::getValue('group_robux_stock', '50000');
    $groupRobuxStockMin = (int) Setting::getValue('group_robux_stock_minimum', '5000');
    $groupStockIsLow = $groupRobuxStock <= $groupRobuxStockMin;
    $groupStockStatus = $groupStockIsLow ? 'low' : ($groupRobuxStock > $groupRobuxStockMin * 2 ? 'high' : 'normal');
    
    return response()->json([
        'current_stock' => $stockStatus['current'],
        'minimum_stock' => $stockStatus['minimum'],
        'is_low' => $stockStatus['is_low'],
        'status' => $stockStatus['status'],
        'group_stock' => [
            'current_stock' => $groupRobuxStock,
            'minimum_stock' => $groupRobuxStockMin,
            'is_low' => $groupStockIsLow,
            'status' => $groupStockStatus
        ]
    ]);
})->name('api.current-stock');



// Product order routes - handle both slug and query parameter
Route::get('/products/{gameType?}', function($gameType = null) {
    // If gameType is provided as path parameter, use it
    if ($gameType) {
        // Create a mapping of common game types to handle various URL formats
        $gameTypeMap = [
            'blox-fruits' => 'Blox Fruits',
            'bloxfruits' => 'Blox Fruits', 
            'blox_fruits' => 'Blox Fruits',
            'blox+fruits' => 'Blox Fruits',
            'blox%20fruits' => 'Blox Fruits',
            'blox fruits' => 'Blox Fruits',
            // Add more mappings as needed
        ];
        
        // Normalize the game type
        $normalizedGameType = strtolower(str_replace(['+', '%20', '_', '-'], '', $gameType));
        
        // Try to find exact match first
        $exactMatch = \App\Models\Product::where('is_active', true)
            ->where('game_type', $gameType)
            ->first();
        
        if ($exactMatch) {
            $targetGameType = $exactMatch->game_type;
        } else {
            // Try mapping
            $targetGameType = $gameTypeMap[$normalizedGameType] ?? null;
            
            if (!$targetGameType) {
                // Try case-insensitive search
                $targetGameType = \App\Models\Product::where('is_active', true)
                    ->whereRaw('LOWER(game_type) = ?', [strtolower($gameType)])
                    ->value('game_type');
            }
        }
        
        // If still not found, redirect to products page
        if (!$targetGameType) {
            Log::warning('Game type not found', [
                'requested' => $gameType,
                'normalized' => $normalizedGameType,
                'available' => \App\Models\Product::where('is_active', true)->distinct()->pluck('game_type')->toArray()
            ]);
            
            return redirect()->route('products')->with('error', 'Game type tidak ditemukan');
        }
    } else {
        // Check if game_type is provided as query parameter
        $targetGameType = request('game_type');
    }
    
    // Get products based on game type filter
    $query = \App\Models\Product::where('is_active', true);
    
    if ($targetGameType) {
        $query->where('game_type', $targetGameType);
    }
    
    $products = $query->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(12)
        ->appends(request()->query()); // Preserve query parameters in pagination
    
    // Get all unique game types for filter
    $gameTypes = \App\Models\Product::where('is_active', true)
        ->distinct()
        ->pluck('game_type')
        ->filter()
        ->sort()
        ->values();
    
    // Get sample images for each game type
    $gameTypeImages = [];
    foreach ($gameTypes as $type) {
        $sampleProduct = \App\Models\Product::where('is_active', true)
            ->where('game_type', $type)
            ->where(function($q) {
                $q->whereNotNull('image')->orWhereNotNull('image_url');
            })
            ->first();
        
        if ($sampleProduct) {
            $gameTypeImages[$type] = $sampleProduct->image ? asset($sampleProduct->image) : $sampleProduct->image_url;
        }
    }
    
    return view('products', compact('products', 'gameTypes', 'gameTypeImages') + ['gameType' => $targetGameType]);
})->name('user.product-order');

// Product detail page (for specific product ordering)
Route::get('/product/{productId}', function($productId) {
    $selectedProduct = \App\Models\Product::where('id', $productId)
        ->where('is_active', true)
        ->first();
    
    if (!$selectedProduct) {
        return redirect()->route('products')->with('error', 'Produk tidak ditemukan');
    }
    
    // Only pass the selected product, no other products
    return view('user.product-order', compact('selectedProduct') + ['gameType' => $selectedProduct->game_type]);
})->name('user.product-detail');

Route::post('/user/store-product-data', [App\Http\Controllers\User\ProductOrderController::class, 'storeProductData'])->name('user.store-product-data');
Route::get('/user/product-payment', [App\Http\Controllers\User\ProductOrderController::class, 'payment'])->name('user.product-payment');
Route::post('/user/create-product-order', [App\Http\Controllers\User\ProductOrderController::class, 'createOrder'])->name('user.create-product-order');

// User flow (UI only)
Route::get('/user/search', function() {
    $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
    $minOrder = (int) Setting::getValue('robux_min_order', '100');

    $gamepassStockStatus = \App\Services\RobuxStockService::getStockStatus('gamepass');
    $groupStockStatus = \App\Services\RobuxStockService::getStockStatus('group');
    $gamepassAvailableStock = \App\Services\RobuxStockService::getCurrentStock();
    $groupAvailableStock = \App\Services\RobuxStockService::getCurrentGroupStock();
    return view('user.search', [
        'robuxPricePer100' => $pricePer100,
        'robuxMinOrder' => $minOrder,
        'groupRobuxMinOrder' => (int) Setting::getValue('group_robux_min_order', $minOrder),
        'gamepassTaxRate' => (float) Setting::getValue('gamepass_tax_rate', '30'),
        // Group settings
        'groupRobuxPricePer100' => (float) Setting::getValue('group_robux_price_per_100', '10000'),
        'groupLink' => Setting::getValue('group_link', 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about'),
        'minMembershipDays' => (int) Setting::getValue('min_membership_days', '14'),
        'groupName' => Setting::getValue('group_name', 'Valtus Studios'),
        'gamepassStockStatus' => $gamepassStockStatus,
        'groupStockStatus' => $groupStockStatus,
        'gamepassAvailableStock' => $gamepassAvailableStock,
        'groupAvailableStock' => $groupAvailableStock,
    ]);
})->name('user.search');

Route::get('/user/search-group', function() {
    $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
    $minOrder = (int) Setting::getValue('robux_min_order', '100');

    $groupStockStatus = \App\Services\RobuxStockService::getStockStatus('group');
    $groupAvailableStock = \App\Services\RobuxStockService::getCurrentGroupStock();
    return view('user.search-group', [
        'robuxPricePer100' => $pricePer100,
        'robuxMinOrder' => $minOrder,
        'groupRobuxMinOrder' => (int) Setting::getValue('group_robux_min_order', $minOrder),
        'gamepassTaxRate' => (float) Setting::getValue('gamepass_tax_rate', '30'),
        // Group settings
        'groupRobuxPricePer100' => (float) Setting::getValue('group_robux_price_per_100', '10000'),
        'groupLink' => Setting::getValue('group_link', 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about'),
        'minMembershipDays' => (int) Setting::getValue('min_membership_days', '14'),
        'groupName' => Setting::getValue('group_name', 'Valtus Studios'),
        'groupStockStatus' => $groupStockStatus,
        'groupAvailableStock' => $groupAvailableStock,
    ]);
})->name('user.search-group');
Route::get('/user/amount', fn() => view('user.amount'))->name('user.amount');
Route::get('/user/gamepass', fn() => view('user.gamepass'))->name('user.gamepass');
Route::get('/user/payment', function() {
    return view('user.payment', [
        'groupName' => Setting::getValue('group_name', 'Valtus Studios'),
        'minMembershipDays' => (int) Setting::getValue('min_membership_days', '14'),
    ]);
})->name('user.payment');
Route::get('/user/payment-methods', function() {
    $order = null;
    $orderId = session('current_order_id');
    
    Log::info('Payment Methods Route - Order ID:', ['order_id' => $orderId]);
    
    if($orderId) {
        $order = \App\Models\Order::where('order_id', $orderId)->first();
        
        Log::info('Payment Methods Route - Order Found:', ['order' => $order ? $order->toArray() : 'Not found']);
        
        if (!$order) {
            Log::warning('Payment Methods Route - Order not found, redirecting to home');
            return redirect()->route('home')->with('error', 'Order tidak ditemukan');
        }
        
        // Check if order is expired
        if ($order->expires_at && now()->gt($order->expires_at)) {
            $order->update(['order_status' => 'expired']);
            return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis. Pesanan dibatalkan.');
        }
    } else {
        Log::warning('Payment Methods Route - No order ID in session, redirecting to home');
        return redirect()->route('home')->with('error', 'Session expired, silakan buat pesanan baru');
    }
    
    // Check payment mode - if gateway, redirect directly to Midtrans payment (skip this page)
    $notes = [];
    if ($order->notes) {
        // Order model casts notes to array, so we only accept array here
        if (is_array($order->notes)) {
            $notes = $order->notes;
        }
    }
    
    $paymentMode = $notes['payment_mode'] ?? 'manual';
    
    // If gateway mode, redirect directly to Midtrans payment (skip this page)
    if ($paymentMode === 'gateway') {
        return redirect()->route('user.midtrans-payment', ['orderId' => $order->order_id]);
    }
    
    // Only show payment-methods page for manual mode
    return view('user.payment-methods', compact('order'));
})->name('user.payment-methods');
Route::post('/user/create-order', [UserOrderController::class, 'createOrder'])->name('user.create-order')->middleware('web');
Route::post('/user/update-payment-method', [UserOrderController::class, 'updatePaymentMethod'])->name('user.update-payment-method');
Route::post('/user/upload-proof', [UserOrderController::class, 'uploadProof'])->name('user.upload-proof');

// Midtrans Payment Routes
Route::get('/user/midtrans-payment/{orderId}', [App\Http\Controllers\User\MidtransPaymentController::class, 'show'])->name('user.midtrans-payment');
Route::post('/api/midtrans/webhook', [App\Http\Controllers\User\MidtransPaymentController::class, 'webhook'])
    ->withoutMiddleware(['web'])
    ->name('api.midtrans.webhook');
Route::post('/user/store-gamepass-link', [UserOrderController::class, 'storeGamepassLink'])->name('user.store-gamepass-link');
Route::post('/user/store-amount', [UserOrderController::class, 'storeAmount'])->name('user.store-amount');
Route::post('/user/store-username', [UserOrderController::class, 'storeUsername'])->name('user.store-username');
Route::post('/user/store-purchase-method', [UserOrderController::class, 'storePurchaseMethod'])->name('user.store-purchase-method');
Route::post('/user/store-discount-data', [UserOrderController::class, 'storeDiscountData'])->name('user.store-discount-data');
Route::post('/user/store-order-session', [UserOrderController::class, 'storeOrderSession'])->name('user.store-order-session');
Route::get('/user/status', [App\Http\Controllers\User\StatusController::class, 'index'])->name('user.status');
Route::get('/user/status/search', [App\Http\Controllers\User\StatusController::class, 'search'])->name('user.status.search');
Route::get('/user/status/{orderId}', [App\Http\Controllers\User\StatusController::class, 'show'])->name('user.status.show');

// Admin Authentication Routes - Secure URLs
Route::prefix('system')->name('admin.')->group(function () {
    Route::get('/access', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/access', [AuthController::class, 'login'])->name('login.post');
    Route::post('/exit', [AuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/activity-history', [ActivityHistoryController::class, 'index'])->name('activity-history');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject');
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments');
        Route::get('/payments/{order}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{order}/confirm', [App\Http\Controllers\Admin\PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        Route::get('/payments/{order}/download-proof', [App\Http\Controllers\Admin\PaymentController::class, 'downloadProof'])->name('payments.download-proof');
        Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement');
        Route::put('/announcement', [AnnouncementController::class, 'update'])->name('announcement.update');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/download-script', [SettingController::class, 'downloadScript'])->name('settings.download-script');
        Route::get('/settings/view-script', [SettingController::class, 'viewScript'])->name('settings.view-script');
            
            // Email Test Route
            Route::get('/email-test', [App\Http\Controllers\Admin\EmailTestController::class, 'index'])->name('email-test');
            Route::post('/email-test', [App\Http\Controllers\Admin\EmailTestController::class, 'sendTestEmail'])->name('email-test.send');
        Route::get('/robux-pricing', [RobuxPricingController::class, 'index'])->name('robux-pricing');
        Route::put('/robux-pricing', [RobuxPricingController::class, 'update'])->name('robux-pricing.update');
        Route::get('/group-robux-settings', [GroupRobuxSettingsController::class, 'index'])->name('group-robux-settings');
        Route::put('/group-robux-settings', [GroupRobuxSettingsController::class, 'update'])->name('group-robux-settings.update');
        Route::get('/payment-settings', [PaymentSettingsController::class, 'index'])->name('payment-settings');
        Route::put('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');
        Route::get('/media', [MediaController::class, 'index'])->name('media');
        Route::put('/media', [MediaController::class, 'update'])->name('media.update');
        Route::post('/media/remove', [MediaController::class, 'remove'])->name('media.remove');
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
        Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
        
        // Product Management Routes
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
        
        // Promo Code Management Routes
        Route::get('/promo-codes', [PromoCodeController::class, 'index'])->name('promo-codes');
        Route::get('/promo-codes/create', [PromoCodeController::class, 'create'])->name('promo-codes.create');
        Route::post('/promo-codes', [PromoCodeController::class, 'store'])->name('promo-codes.store');
        Route::get('/promo-codes/{promoCode}/edit', [PromoCodeController::class, 'edit'])->name('promo-codes.edit');
        Route::put('/promo-codes/{promoCode}', [PromoCodeController::class, 'update'])->name('promo-codes.update');
        Route::delete('/promo-codes/{promoCode}', [PromoCodeController::class, 'destroy'])->name('promo-codes.destroy');
        Route::post('/promo-codes/{promoCode}/toggle-status', [PromoCodeController::class, 'toggleStatus'])->name('promo-codes.toggle-status');
        Route::get('/promo-codes/{promoCode}/usages', [PromoCodeController::class, 'usages'])->name('promo-codes.usages');
        Route::post('/promo-codes/sync-usages', [PromoCodeController::class, 'syncUsages'])->name('promo-codes.sync-usages');

        // Referral Code Management Routes
        Route::get('/referral-codes', [ReferralCodeController::class, 'index'])->name('referral-codes');
        Route::get('/referral-codes/create', [ReferralCodeController::class, 'create'])->name('referral-codes.create');
        Route::post('/referral-codes', [ReferralCodeController::class, 'store'])->name('referral-codes.store');
        Route::get('/referral-codes/{referralCode}', [ReferralCodeController::class, 'show'])->name('referral-codes.show');
        Route::get('/referral-codes/{referralCode}/edit', [ReferralCodeController::class, 'edit'])->name('referral-codes.edit');
        Route::put('/referral-codes/{referralCode}', [ReferralCodeController::class, 'update'])->name('referral-codes.update');
        Route::delete('/referral-codes/{referralCode}', [ReferralCodeController::class, 'destroy'])->name('referral-codes.destroy');
        Route::post('/referral-codes/{referralCode}/toggle-status', [ReferralCodeController::class, 'toggleStatus'])->name('referral-codes.toggle-status');

        // Blacklist Management Routes
        Route::get('/blacklists', [AdminBlacklistController::class, 'index'])->name('blacklists');
        Route::get('/blacklists/create', [AdminBlacklistController::class, 'create'])->name('blacklists.create');
        Route::post('/blacklists', [AdminBlacklistController::class, 'store'])->name('blacklists.store');
        Route::get('/blacklists/{blacklist}/edit', [AdminBlacklistController::class, 'edit'])->name('blacklists.edit');
        Route::put('/blacklists/{blacklist}', [AdminBlacklistController::class, 'update'])->name('blacklists.update');
        Route::delete('/blacklists/{blacklist}', [AdminBlacklistController::class, 'destroy'])->name('blacklists.destroy');
        Route::post('/blacklists/{blacklist}/toggle-status', [AdminBlacklistController::class, 'toggleStatus'])->name('blacklists.toggle-status');
        
        // Robux Discount Rules Management Routes
        Route::get('/robux-discount-rules', [App\Http\Controllers\Admin\RobuxDiscountRuleController::class, 'index'])->name('robux-discount-rules');
        Route::post('/robux-discount-rules', [App\Http\Controllers\Admin\RobuxDiscountRuleController::class, 'store'])->name('robux-discount-rules.store');
        Route::put('/robux-discount-rules/{robuxDiscountRule}', [App\Http\Controllers\Admin\RobuxDiscountRuleController::class, 'update'])->name('robux-discount-rules.update');
        Route::delete('/robux-discount-rules/{robuxDiscountRule}', [App\Http\Controllers\Admin\RobuxDiscountRuleController::class, 'destroy'])->name('robux-discount-rules.destroy');
        Route::post('/robux-discount-rules/{robuxDiscountRule}/toggle-status', [App\Http\Controllers\Admin\RobuxDiscountRuleController::class, 'toggleStatus'])->name('robux-discount-rules.toggle-status');
        
        // Admin Management Routes
        Route::get('/admins', [AdminController::class, 'index'])->name('admins.index');
        Route::get('/admins/create', [AdminController::class, 'create'])->name('admins.create');
        Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
        Route::get('/admins/{admin}', [AdminController::class, 'show'])->name('admins.show');
        Route::get('/admins/{admin}/edit', [AdminController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');

    });
    
}); 

// Security Test Page (Public Access)
Route::get('/keamanan/valtus/{hash}', [App\Http\Controllers\Test\SecurityTestController::class, 'showTestPage'])->name('test.security');
Route::get('/keamanan/valtus/{hash}/check-username', [App\Http\Controllers\Test\SecurityTestController::class, 'checkUsername'])->name('test.check-username');
Route::get('/keamanan/valtus/{hash}/check-gamepass', [App\Http\Controllers\Test\SecurityTestController::class, 'checkGamepass'])->name('test.check-gamepass');
Route::get('/keamanan/valtus/{hash}/check-group-membership', [App\Http\Controllers\Test\SecurityTestController::class, 'checkGroupMembership'])->name('test.check-group-membership');
Route::post('/keamanan/valtus/{hash}/create-order', [App\Http\Controllers\Test\SecurityTestController::class, 'createTestOrder'])->name('test.create-order');

// Spreadsheet Order Test (Public Access)
Route::get('/tes/orderan/{hash}', [App\Http\Controllers\Test\OrderSpreadsheetTestController::class, 'index'])->name('test.orderspreadsheet');
Route::post('/tes/orderan/{hash}/create-and-send', [App\Http\Controllers\Test\OrderSpreadsheetTestController::class, 'createAndSend'])->name('test.orderspreadsheet.create-and-send');