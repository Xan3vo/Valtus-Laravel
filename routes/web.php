<?php

use Illuminate\Support\Facades\Route;
use App\Models\Setting;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RobuxPricingController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Api\RobloxController;
use App\Http\Controllers\User\UserOrderController;
use Illuminate\Support\Facades\Log;

Route::get('/', function () {
    $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
    $taxRate = (float) Setting::getValue('gamepass_tax_rate', '30');
    $pricePer1 = $pricePer100 / 100.0;
    $robuxMinOrder = (int) Setting::getValue('robux_min_order', '100');
    
    // Get real stock status
    $stockStatus = \App\Services\RobuxStockService::getStockStatus();
    $robuxStock = $stockStatus['current'];
    
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
        'recentActivities' => $recentActivities,
        'otherProducts' => $otherProducts,
    ]);
})->name('home');

// Lightweight API route (no auth) for username check
Route::get('/api/roblox/username', [RobloxController::class, 'checkUsername'])->name('api.roblox.username');
Route::get('/api/roblox/gamepass-check', [RobloxController::class, 'checkGamepass'])->name('api.roblox.gamepass');

// API route for payment mode check
Route::get('/api/payment-mode', function() {
    return response()->json([
        'payment_mode' => \App\Models\Setting::getValue('payment_mode', 'manual'),
        'payment_enabled' => \App\Models\Setting::getValue('payment_enabled', '0') === '1'
    ]);
})->name('api.payment-mode');

// Public products page
Route::get('/products', function () {
    $gameType = request('game_type');
    
    $query = \App\Models\Product::where('is_active', true);
    
    if ($gameType) {
        $query->where('game_type', $gameType);
    }
    
    $products = $query->orderBy('sort_order')
        ->orderBy('name')
        ->paginate(12);
    
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

// API route for recent activities (max 3)
Route::get('/api/recent-activities', function() {
    // Always get exactly 3 most recent activities
    $activities = \App\Models\Activity::getRecentActivities(3);
    
    return response()->json([
        'activities' => $activities->map(function($activity) {
            // Get avatar data dynamically
            $avatarData = \App\Services\RobloxAvatarService::getAvatarWithFallback($activity->username);
            
            return [
                'id' => $activity->id,
                'username' => $activity->username,
                'masked_username' => $activity->masked_username,
                'formatted_amount' => $activity->formatted_amount,
                'time_ago' => $activity->processed_at->diffForHumans(),
                'processed_at' => $activity->processed_at->toISOString(),
                'avatar_data' => $avatarData,
                'product_info' => $activity->product_info
            ];
        })
    ]);
})->name('api.recent-activities');

// API route for current stock
Route::get('/api/current-stock', function() {
    $stockStatus = \App\Services\RobuxStockService::getStockStatus();
    
    return response()->json([
        'current_stock' => $stockStatus['current'],
        'minimum_stock' => $stockStatus['minimum'],
        'is_low' => $stockStatus['is_low'],
        'status' => $stockStatus['status']
    ]);
})->name('api.current-stock');



// Product order routes
Route::get('/products/{gameType}', [App\Http\Controllers\User\ProductOrderController::class, 'show'])->name('user.product-order');
Route::post('/user/store-product-data', [App\Http\Controllers\User\ProductOrderController::class, 'storeProductData'])->name('user.store-product-data');
Route::get('/user/product-payment', [App\Http\Controllers\User\ProductOrderController::class, 'payment'])->name('user.product-payment');
Route::post('/user/create-product-order', [App\Http\Controllers\User\ProductOrderController::class, 'createOrder'])->name('user.create-product-order');

// User flow (UI only)
Route::get('/user/search', function() {
    $pricePer100 = (float) Setting::getValue('robux_price_per_100', '10000');
    $minOrder = (int) Setting::getValue('robux_min_order', '100');
    return view('user.search', [
        'robuxPricePer100' => $pricePer100,
        'robuxMinOrder' => $minOrder,
        'gamepassTaxRate' => (float) Setting::getValue('gamepass_tax_rate', '30'),
    ]);
})->name('user.search');
Route::get('/user/amount', fn() => view('user.amount'))->name('user.amount');
Route::get('/user/gamepass', fn() => view('user.gamepass'))->name('user.gamepass');
Route::get('/user/payment', fn() => view('user.payment'))->name('user.payment');
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
    
    return view('user.payment-methods', compact('order'));
})->name('user.payment-methods');
Route::post('/user/create-order', [UserOrderController::class, 'createOrder'])->name('user.create-order')->middleware('web');
Route::post('/user/update-payment-method', [UserOrderController::class, 'updatePaymentMethod'])->name('user.update-payment-method');
Route::post('/user/upload-proof', [UserOrderController::class, 'uploadProof'])->name('user.upload-proof');
Route::post('/user/store-gamepass-link', [UserOrderController::class, 'storeGamepassLink'])->name('user.store-gamepass-link');
Route::post('/user/store-amount', [UserOrderController::class, 'storeAmount'])->name('user.store-amount');
Route::post('/user/store-username', [UserOrderController::class, 'storeUsername'])->name('user.store-username');
Route::get('/user/status', [App\Http\Controllers\User\StatusController::class, 'index'])->name('user.status');
Route::get('/user/status/search', [App\Http\Controllers\User\StatusController::class, 'search'])->name('user.status.search');
Route::get('/user/status/{orderId}', [App\Http\Controllers\User\StatusController::class, 'show'])->name('user.status.show');

// Admin Authentication Routes - Secure URLs
Route::prefix('system')->name('admin.')->group(function () {
    Route::get('/access', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/access', [AuthController::class, 'login']);
    Route::post('/exit', [AuthController::class, 'logout'])->name('logout');
    
    // Protected Admin Routes
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/payments', [App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments');
        Route::get('/payments/{order}', [App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('payments.show');
        Route::post('/payments/{order}/confirm', [App\Http\Controllers\Admin\PaymentController::class, 'confirmPayment'])->name('payments.confirm');
        Route::get('/payments/{order}/download-proof', [App\Http\Controllers\Admin\PaymentController::class, 'downloadProof'])->name('payments.download-proof');
        Route::get('/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
        Route::get('/settings', [SettingController::class, 'index'])->name('settings');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::get('/settings/download-script', [SettingController::class, 'downloadScript'])->name('settings.download-script');
        Route::get('/settings/view-script', [SettingController::class, 'viewScript'])->name('settings.view-script');
        Route::get('/robux-pricing', [RobuxPricingController::class, 'index'])->name('robux-pricing');
        Route::put('/robux-pricing', [RobuxPricingController::class, 'update'])->name('robux-pricing.update');
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
    });
});