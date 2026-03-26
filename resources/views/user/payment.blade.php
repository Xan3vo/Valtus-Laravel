@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('body')

<header class="sticky top-0 z-50 backdrop-blur-md bg-gray-900/80 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="/assets/images/iconv.jpg" alt="Valtus" class="h-10 w-10 rounded-lg object-cover ring-2 ring-white/10">
                <div class="absolute -top-1 -right-1 h-4 w-4 bg-emerald-500 rounded-full border-2 border-gray-900"></div>
            </div>
            <div>
                <span class="text-xl tracking-wide font-bold text-white">Valtus</span>
                <div class="text-xs text-emerald-400 font-medium">Verified Store</div>
            </div>
        </div>
        
    </div>
</header>
<main class="max-w-6xl mx-auto px-6 py-10">
    <!-- Back Button - Outside section -->
    <div class="mb-4">
        @php
            $purchaseMethod = session('selected_purchase_method', 'gamepass');
            $backRoute = $purchaseMethod === 'group' ? route('user.search-group') : route('user.search');
        @endphp
        <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-white/15 text-white/80 hover:text-white hover:bg-white/5 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="hidden sm:inline">Kembali ke Pilih Produk</span>
            <span class="sm:hidden">Kembali</span>
        </a>
    </div>
    
    <!-- Steps header -->
    <div class="mb-6 rounded-xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-4 sm:p-5">
        <!-- Steps -->
        <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm overflow-x-auto">
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center text-xs">1</span>
                <span class="text-white/70">Memilih Produk</span>
            </div>
            <div class="text-white/30">/</div>
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-white text-black flex items-center justify-center font-medium text-xs">2</span>
                <span class="text-white">Detail Pesanan</span>
            </div>
            <div class="text-white/30">/</div>
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center text-xs">3</span>
                <span class="text-white/70">Pembayaran</span>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold">Detail Pesanan</h2>
            <!-- Robux Product Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                        <img src="/assets/images/robux.png" class="h-6 w-6" alt="Robux">
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium" id="p_name">{{ session('selected_amount', 0) }} Robux</div>
                        <div class="text-white/60 text-sm">Roblox Digital Currency</div>
                    </div>
                    <div class="text-right">
                        <div class="text-white/60 text-sm">Harga</div>
                        <div class="text-white font-semibold" id="p_price">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center overflow-hidden">
                        <img id="p_avatar" src="/assets/images/robux.png" class="h-12 w-12 object-cover" alt="Avatar">
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium">Pembeli</div>
                        <div class="text-white/60 text-sm">Username: <span id="p_username">{{ session('selected_username') }}</span></div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30">
                        <div class="h-2 w-2 bg-emerald-400 rounded-full"></div>
                        <span class="text-emerald-300 text-xs font-medium">Valid</span>
                    </div>
                </div>
            </div>

            @if(session('gamepass_link') && session('selected_purchase_method') !== 'group')
            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">GamePass Anda</div>
                <div class="text-white/60 text-sm mb-3">GamePass yang akan digunakan admin untuk top-up Robux ke akun Anda:</div>
                <div class="text-white/80 text-sm font-mono bg-black/30 px-3 py-2 rounded border border-white/10 break-all">
                    {{ session('gamepass_link') }}
                </div>
                <div class="mt-2 text-white/50 text-xs">Admin akan menggunakan GamePass ini untuk mengirim Robux ke akun Anda</div>
            </div>
            @endif
            
            @if(session('selected_purchase_method') === 'group')
            <div class="mt-6 rounded-lg border border-purple-400/30 p-4 bg-purple-500/10">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium mb-1">Pembelian via Group</div>
                        <div class="text-purple-200/80 text-sm">Robux akan dikirim melalui group {{ $groupName ?? 'Valtus Studios' }}. Pastikan Anda sudah bergabung dengan group.</div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Promo Code Section -->
            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">Kode Promo / Kode Unik <span class="text-yellow-400">🎁</span></div>
                @php
                    $activeReferralCode = request()->cookie('referral_code') ?? session('referral_code');
                    $activeReferralCode = $activeReferralCode ? strtoupper(trim((string) $activeReferralCode)) : null;
                @endphp
                @if($activeReferralCode)
                    <div class="mb-3 p-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-white/70">Referral aktif:</span>
                            <span class="text-emerald-300 font-medium">{{ $activeReferralCode }}</span>
                        </div>
                        <div class="mt-1 text-emerald-200/80 text-xs">Kode promo tidak bisa digunakan bersamaan dengan referral.</div>
                    </div>
                @endif
                <div class="flex gap-2">
                    <input id="promo-code-input" 
                           type="text" 
                           autocomplete="off"
                           class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/50 focus:border-yellow-500 focus:ring-1 focus:ring-yellow-500/50 focus:outline-none uppercase" 
                           placeholder="Masukkan kode promo"
                           maxlength="50">
                    <button id="apply-promo-btn" type="button" class="px-4 py-3 rounded-md bg-white/10 hover:bg-white/20 border border-white/20 text-white font-medium transition-colors disabled:bg-gray-600/50 disabled:cursor-not-allowed disabled:border-gray-600/30">
                        Terapkan
                    </button>
                </div>
                <div id="promo-error" class="mt-2 text-red-400 text-sm hidden"></div>
                <div id="promo-success" class="mt-2 text-yellow-400 text-sm hidden"></div>
                <!-- Discount Info (hidden by default) -->
                <div id="promo-discount-info" class="mt-3 p-3 rounded-lg bg-yellow-500/10 border border-yellow-500/20 hidden">
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-white/70">Diskon:</span>
                        <span id="promo-discount-amount" class="text-yellow-300 font-medium">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">Notifikasi Pesanan (Email) <span class="text-red-400">*</span></div>
                <input id="email-input" 
                       type="email" 
                       required 
                       autocomplete="email"
                       inputmode="email"
                       class="w-full px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/50 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 focus:outline-none" 
                       placeholder="contoh@email.com"
                       value="{{ old('email', session('user_email', '')) }}">
                <div id="email-error" class="mt-2 text-red-400 text-sm hidden">Email wajib diisi untuk notifikasi pesanan</div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-medium">Detail Harga</div>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between"><dt class="text-white/60">Total Pesanan</dt><dd id="s_price" class="text-white">Rp 0</dd></div>
                    <!-- Robux Discount (hidden by default) -->
                    <div id="robux-discount-row" class="flex items-center justify-between hidden">
                        <dt class="text-white/60">🎉 Diskon Robux</dt>
                        <dd id="s_robux_discount" class="text-yellow-300 font-medium">-Rp 0</dd>
                    </div>
                    <!-- Promo Code Discount (hidden by default) -->
                    <div id="promo-discount-row" class="flex items-center justify-between hidden">
                        <dt class="text-white/60">Diskon Kode Promo</dt>
                        <dd id="s_discount" class="text-yellow-300 font-medium">-Rp 0</dd>
                    </div>
                    <div class="flex items-center justify-between"><dt class="text-white/60">Biaya Admin</dt><dd class="text-white">Rp0</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-white/60">Garansi</dt><dd class="text-emerald-300">Gratis!</dd></div>
                    @if(session('gamepass_link'))
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">GamePass</dt>
                        <dd class="text-emerald-300 text-xs">✓ Tersedia</dd>
                    </div>
                    @endif
                </dl>
                <div class="mt-4 h-px bg-white/10"></div>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                    <div class="text-white/90">Total Pembayaran</div>
                    <div id="s_total" class="text-white">Rp 0</div>
                </div>
                <button id="payment-btn" class="mt-4 inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 text-white font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span id="payment-btn-text">Pilih Pembayaran</span>
                </button>
            </div>
        </aside>
    </div>

    <script>
        // Enhanced cache and session cleanup for security
        function clearAllSensitiveData() {
            // Clear sessionStorage
            sessionStorage.clear();
            
            // Clear localStorage (if any sensitive data stored)
            localStorage.removeItem('user_data');
            localStorage.removeItem('order_data');
            localStorage.removeItem('payment_data');
            
            // Clear any cached form data
            if (typeof Storage !== "undefined") {
                // Clear any custom cache keys
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && (key.includes('user') || key.includes('order') || key.includes('payment'))) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));
            }
        }
        
        // Browser navigation handlers
        window.addEventListener('beforeunload', function(e) {
            // Clear all sensitive data when leaving
            clearAllSensitiveData();
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(e) {
            // Clear all cached data when navigating back
            clearAllSensitiveData();
            // Refresh page to ensure clean state
            window.location.reload();
        });
        
        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Clear data on page load for security
        document.addEventListener('DOMContentLoaded', function() {
            // Clear any leftover sensitive data
            clearAllSensitiveData();
        });
        
        (function(){
            // Session validation
            let amount = Number(@json(session('selected_amount')));
            const username = (@json(session('selected_username', '')) || '').toString();
            const referralCode = (@json($activeReferralCode) || '').toString();
            const hasReferral = referralCode && referralCode.trim() !== '';
            // CRITICAL: Get purchase_method from session, but validate it - don't default to gamepass
            // This prevents race condition where group order becomes gamepass
            const purchaseMethodRaw = (@json(session('selected_purchase_method', null)) || '').toString();
            const purchaseMethod = (purchaseMethodRaw === 'gamepass' || purchaseMethodRaw === 'group') ? purchaseMethodRaw : null;
            const pricePer100 = {{ (int) (\App\Models\Setting::getValue('robux_price_per_100', '10000')) }};
            const groupRobuxPricePer100 = {{ (int) ($groupRobuxPricePer100 ?? 10000) }};
            
            if (!username || username.trim() === '') {
                alert('Username tidak ditemukan. Silakan buat pesanan baru.');
                window.location.href = '/';
                return;
            }
            
            // CRITICAL: Validate purchase_method - must be either 'gamepass' or 'group'
            // This prevents bug where group order becomes gamepass due to missing session
            if (!purchaseMethod || (purchaseMethod !== 'gamepass' && purchaseMethod !== 'group')) {
                console.error('Invalid or missing purchase_method:', purchaseMethodRaw);
                alert('Metode pembelian tidak valid atau tidak ditemukan. Silakan buat pesanan baru dari halaman yang benar.');
                window.location.href = '/';
                return;
            }
            
            // Get discount data from session (if available from search page)
            const robuxBasePrice = @json(session('robux_base_price'));
            const robuxDiscountAmount = Number(@json(session('robux_discount_amount', 0))) || 0;
            const robuxFinalPrice = @json(session('robux_final_price'));
            const robuxHasDiscount = Boolean(@json(session('robux_has_discount', false)));
            
            // CRITICAL: Calculate base price - MUST use correct price per 100 based on purchase method
            // This ensures group orders use group price, gamepass orders use gamepass price
            const currentPricePer100 = purchaseMethod === 'group' ? groupRobuxPricePer100 : pricePer100;
            
            // Validate required session data after recovery attempt
            if (!amount || amount <= 0) {
                alert('Data pesanan tidak valid. Silakan buat pesanan baru.');
                window.location.href = '/';
                return;
            }
            
            // CRITICAL: Use price from session (already calculated correctly from API in search page)
            // Only recalculate if session price is missing or invalid
            // This prevents price manipulation while preserving correct prices from search page
            const expectedBasePrice = currentPricePer100 * (amount / 100);
            let basePriceFromSearch;
            
            if (typeof robuxBasePrice === 'number' && robuxBasePrice > 0) {
                // Validate that session base price is reasonable (within 5% of expected for tighter validation)
                // This allows for small rounding differences but prevents major manipulation
                const priceDiff = Math.abs(robuxBasePrice - expectedBasePrice);
                const priceDiffPercent = (priceDiff / expectedBasePrice) * 100;
                
                if (priceDiffPercent > 5) {
                    // Price mismatch detected - log warning but use session price if it's reasonable
                    // Only reject if price is completely unreasonable (more than 50% difference)
                    if (priceDiffPercent > 50) {
                        console.error('Price mismatch too large. Recalculating base price.', {
                            session_price: robuxBasePrice,
                            expected_price: expectedBasePrice,
                            difference_percent: priceDiffPercent,
                            purchase_method: purchaseMethod
                        });
                        basePriceFromSearch = expectedBasePrice;
                    } else {
                        // Use session price if difference is reasonable (5-50%)
                        // This handles cases where API returns slightly different prices due to discount rules
                        console.warn('Price difference detected but within acceptable range. Using session price.', {
                            session_price: robuxBasePrice,
                            expected_price: expectedBasePrice,
                            difference_percent: priceDiffPercent,
                            purchase_method: purchaseMethod
                        });
                        basePriceFromSearch = robuxBasePrice;
                    }
                } else {
                    // Price is within 5% - use session price (most accurate, from API)
                    basePriceFromSearch = robuxBasePrice;
                }
            } else {
                // No base price in session or invalid - calculate from current method
                console.warn('No valid base price in session. Calculating from current method.', {
                    session_price: robuxBasePrice,
                    purchase_method: purchaseMethod
                });
                basePriceFromSearch = expectedBasePrice;
            }
            
            // CRITICAL: Use final_price from session if available and valid
            // This ensures discount is applied correctly
            let finalPriceFromSession = null;
            if (robuxHasDiscount && typeof robuxFinalPrice === 'number' && robuxFinalPrice > 0) {
                // Validate final price is reasonable (should be <= base price)
                if (robuxFinalPrice <= basePriceFromSearch) {
                    finalPriceFromSession = robuxFinalPrice;
                } else {
                    console.warn('Final price from session is greater than base price. Recalculating.', {
                        session_final_price: robuxFinalPrice,
                        base_price: basePriceFromSearch
                    });
                }
            }
            
            // Final price calculation - use session final price if available, otherwise calculate
            const price = finalPriceFromSession !== null ? finalPriceFromSession : basePriceFromSearch;
            const robuxDiscount = robuxHasDiscount ? robuxDiscountAmount : 0;
            
            // Additional validation: Ensure final price is not negative or zero
            if (price <= 0) {
                console.error('Invalid final price calculated:', price);
                alert('Harga tidak valid. Silakan buat pesanan baru.');
                window.location.href = '/';
                return;
            }
            
            // Promo code state
            let promoCodeData = null;
            let finalPrice = price;
            
            function fmt(n){return new Intl.NumberFormat('id-ID').format(n)}
            
            function updatePriceDisplay() {
                // Base price (before any discounts)
                const basePrice = basePriceFromSearch;
                
                // Robux discount (from search page)
                const robuxDiscountAmount = robuxHasDiscount ? robuxDiscount : 0;
                
                // Promo code discount
                const promoDiscountAmount = promoCodeData ? promoCodeData.discount_amount : 0;
                
                // Total discount
                const totalDiscountAmount = robuxDiscountAmount + promoDiscountAmount;
                
                // Final price
                finalPrice = basePrice - totalDiscountAmount;
                
                // Display base price
                document.getElementById('p_price').textContent = 'Rp ' + fmt(basePrice);
                document.getElementById('s_price').textContent = 'Rp ' + fmt(basePrice);
                
                // Show discount info if any discount exists
                if (robuxHasDiscount || promoCodeData) {
                    // Show strikethrough on base price
                    document.getElementById('s_price').innerHTML = '<span class="line-through text-white/50">Rp ' + fmt(basePrice) + '</span>';
                    
                    // Show Robux discount if exists
                    if (robuxHasDiscount) {
                        document.getElementById('robux-discount-row').classList.remove('hidden');
                        document.getElementById('s_robux_discount').textContent = '-Rp ' + fmt(robuxDiscountAmount);
                    } else {
                        document.getElementById('robux-discount-row').classList.add('hidden');
                    }
                    
                    // Show promo discount if exists
                    if (promoCodeData) {
                        document.getElementById('promo-discount-row').classList.remove('hidden');
                        document.getElementById('s_discount').textContent = '-Rp ' + fmt(promoDiscountAmount);
                    } else {
                        document.getElementById('promo-discount-row').classList.add('hidden');
                    }
                } else {
                    document.getElementById('robux-discount-row').classList.add('hidden');
                    document.getElementById('promo-discount-row').classList.add('hidden');
                    document.getElementById('s_price').textContent = 'Rp ' + fmt(basePrice);
                }
                
                // Show final price
                document.getElementById('s_total').textContent = 'Rp ' + fmt(finalPrice);
            }
            
            updatePriceDisplay();
            
            // Email validation and payment button
            const emailInput = document.getElementById('email-input');
            const paymentBtn = document.getElementById('payment-btn');
            const emailError = document.getElementById('email-error');
            
            // Force focus and enable input
            if (emailInput) {
                emailInput.focus();
                emailInput.disabled = false;
                emailInput.readOnly = false;
            }
            
            function validateEmail() {
                const email = emailInput.value.trim();
                const isValid = email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                
                if (!isValid) {
                    emailError.classList.remove('hidden');
                    paymentBtn.disabled = true;
                } else {
                    emailError.classList.add('hidden');
                    paymentBtn.disabled = false;
                }
            }
            
            // Multiple event listeners for better compatibility
            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('blur', validateEmail);
            emailInput.addEventListener('keyup', validateEmail);
            emailInput.addEventListener('change', validateEmail);
            
            // Force validation on page load
            setTimeout(validateEmail, 100);
            
            // Check payment mode and set button behavior
            let paymentMode = 'manual'; // Default to manual
            
            // Fetch payment mode from API
            fetch('/api/payment-methods')
                .then(response => response.json())
                .then(data => {
                    if (data.payment_mode === 'gateway') {
                        paymentMode = 'gateway';
                        // Change button text to "Lanjut Pembayaran"
                        document.getElementById('payment-btn-text').textContent = 'Lanjut Pembayaran';
                    } else {
                        paymentMode = 'manual';
                        // Keep button text as "Pilih Pembayaran"
                        document.getElementById('payment-btn-text').textContent = 'Pilih Pembayaran';
                    }
                })
                .catch(() => {
                    // Default to manual if API fails
                    paymentMode = 'manual';
                });
            
            // Payment button click handler
            paymentBtn.addEventListener('click', function() {
                const email = emailInput.value.trim();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailError.classList.remove('hidden');
                    emailInput.focus();
                    return;
                }
                
                if (paymentMode === 'gateway') {
                    // Gateway mode: show confirmation popup directly, then proceed to Midtrans
                    showGatewayConfirmationPopup(email);
                } else {
                    // Manual mode: show payment method selection popup first
                    showPaymentMethodPopup(email);
                }
            });
            
            function showGatewayConfirmationPopup(email) {
                // Create confirmation popup for gateway mode
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                
                // Calculate current discount info for display
                const currentRobuxDiscount = robuxHasDiscount ? robuxDiscount : 0;
                const currentBasePrice = basePriceFromSearch;
                const currentRobuxFinalPrice = robuxHasDiscount ? robuxFinalPrice : currentBasePrice;
                
                popup.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,500px)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xl font-semibold text-white">Konfirmasi Pembayaran</div>
                            <button class="text-white/60 hover:text-white" id="closeGatewayConfirmPopup">✕</button>
                        </div>
                        <div class="space-y-4">
                            <div class="text-white/70 text-sm">
                                Anda akan melanjutkan ke halaman pembayaran Midtrans dengan detail:
                            </div>
                            <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Produk:</span>
                                        <span class="text-white">${amount} Robux</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Username:</span>
                                        <span class="text-white">${username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Email:</span>
                                        <span class="text-white">${email}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Metode Pembayaran:</span>
                                        <span class="text-emerald-300">Via Midtrans (Pilih di halaman berikutnya)</span>
                                    </div>
                                    ${robuxHasDiscount ? `
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Harga Asli:</span>
                                        <span class="text-white line-through">Rp ${new Intl.NumberFormat('id-ID').format(currentBasePrice)}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Diskon Robux:</span>
                                        <span class="text-yellow-300">-Rp ${new Intl.NumberFormat('id-ID').format(currentRobuxDiscount)}</span>
                                    </div>
                                    ` : ''}
                                    ${promoCodeData ? `
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Harga ${robuxHasDiscount ? 'Setelah Diskon Robux' : 'Asli'}:</span>
                                        <span class="text-white ${robuxHasDiscount ? '' : 'line-through'}">Rp ${new Intl.NumberFormat('id-ID').format(currentRobuxFinalPrice)}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Diskon Kode Promo:</span>
                                        <span class="text-yellow-300">-Rp ${new Intl.NumberFormat('id-ID').format(promoCodeData.discount_amount)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-white/60">Total:</span>
                                        <span class="text-white">Rp ${new Intl.NumberFormat('id-ID').format(finalPrice)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="p-3 rounded-lg bg-blue-500/10 border border-blue-500/20">
                                <div class="flex items-start gap-2">
                                    <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="text-blue-200 text-xs">
                                        Anda akan diarahkan ke halaman pembayaran Midtrans. Di sana, Anda dapat memilih metode pembayaran yang tersedia (QRIS, E-Wallet, Bank Transfer, atau Kartu Kredit).
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-3">
                                <button id="cancelGatewayPayment" class="flex-1 px-4 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                    Batal
                                </button>
                                <button id="confirmGatewayPayment" class="flex-1 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                    Lanjutkan ke Midtrans
                                </button>
                            </div>
                        </div>
                    </div>`;
                
                document.body.appendChild(popup);
                
                // Close popup handlers
                popup.querySelector('#closeGatewayConfirmPopup').addEventListener('click', () => popup.remove());
                popup.querySelector('#cancelGatewayPayment').addEventListener('click', () => popup.remove());
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) popup.remove();
                });
                
                // Confirm payment handler - create order with gateway mode (no selected method, will be chosen in Midtrans)
                popup.querySelector('#confirmGatewayPayment').addEventListener('click', () => {
                    popup.remove();
                    // Create order with gateway mode, no selected method (will choose in Midtrans)
                    createOrderAndRedirect(email, 'gateway', null);
                });
            }
            
            function showPaymentMethodPopup(email) {
                // Create payment method selection popup
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                popup.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,600px)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xl font-semibold text-white">Pilih Metode Pembayaran</div>
                            <button class="text-white/60 hover:text-white" id="closeMethodPopup">✕</button>
                        </div>
                        <div class="text-white/70 text-sm mb-6">Pilih metode pembayaran yang Anda inginkan:</div>
                        <div id="paymentMethodsContainer" class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
                            <div class="text-center text-white/50 py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto mb-2"></div>
                                <div>Memuat metode pembayaran...</div>
                            </div>
                        </div>
                    </div>`;
                
                document.body.appendChild(popup);
                
                // Close popup handlers
                popup.querySelector('#closeMethodPopup').addEventListener('click', () => popup.remove());
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) popup.remove();
                });
                
                // Load payment methods based on admin settings
                loadPaymentMethods(email, popup);
            }
            
            function loadPaymentMethods(email, popup) {
                fetch('/api/payment-methods')
                    .then(response => response.json())
                    .then(data => {
                        const container = popup.querySelector('#paymentMethodsContainer');
                        
                        if (data.error) {
                            container.innerHTML = `
                                <div class="text-center text-red-400 py-4">
                                    <div class="text-sm">${data.message || 'Error memuat metode pembayaran'}</div>
                                </div>
                            `;
                            return;
                        }
                        
                        if (!data.methods || data.methods.length === 0) {
                            container.innerHTML = `
                                <div class="text-center text-red-400 py-4">
                                    <div class="text-sm">Tidak ada metode pembayaran tersedia</div>
                                </div>
                            `;
                            return;
                        }
                        
                        // Render all payment methods dynamically
                        const methodsHtml = data.methods.map(method => {
                            const iconHtml = getPaymentMethodIcon(method.icon, method.code);
                            const typeLabel = data.payment_mode === 'gateway' ? 'Gateway' : 'Manual';
                            
                            return `
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="${method.code}">
                                    <div class="flex items-center gap-4">
                                        ${iconHtml}
                                        <div class="flex-1">
                                            <div class="text-white font-medium">${method.name}</div>
                                            <div class="text-white/60 text-sm">${method.description}</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">${typeLabel}</div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                        
                        container.innerHTML = methodsHtml;
                        
                        // Add click handlers for payment methods
                        popup.querySelectorAll('.payment-method').forEach(method => {
                            method.addEventListener('click', () => {
                                const methodType = method.dataset.method;
                                popup.remove();
                                
                                if (data.payment_mode === 'manual') {
                                    // Manual payment - show confirmation
                                    showPaymentConfirmationPopup(email, 'manual', methodType);
                                } else if (data.payment_mode === 'gateway') {
                                    // Gateway payment - show confirmation
                                    showPaymentConfirmationPopup(email, 'gateway', methodType);
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading payment methods:', error);
                        const container = popup.querySelector('#paymentMethodsContainer');
                        container.innerHTML = `
                            <div class="text-center text-red-400 py-4">
                                <div class="text-sm">Error memuat metode pembayaran</div>
                            </div>
                        `;
                    });
            }
            
            // Helper function to get payment method icon HTML
            function getPaymentMethodIcon(iconType, code) {
                const icons = {
                    'qris': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center"><svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg></div>',
                    'bca': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">BCA</span></div>',
                    'mandiri': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-yellow-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">MDR</span></div>',
                    'bni': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">BNI</span></div>',
                    'permata': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">PMT</span></div>',
                    'gopay': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">GOPAY</span></div>',
                    'dana': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">DANA</span></div>',
                    'ovo': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">OVO</span></div>',
                    'linkaja': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">LINK</span></div>',
                    'shopeepay': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-orange-500/30 to-red-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">SPAY</span></div>',
                    'credit_card': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center"><svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>',
                    'alfamart': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">ALFA</span></div>',
                    'indomaret': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-cyan-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">INDO</span></div>'
                };
                
                return icons[iconType] || icons[code] || '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-gray-500/30 to-gray-600/30 flex items-center justify-center"><span class="text-white font-bold text-xs">PAY</span></div>';
            }
            
            function showPaymentConfirmationPopup(email, paymentMode, selectedMethod) {
                // Get method display name - fetch from API to get accurate name
                fetch('/api/payment-methods')
                    .then(response => response.json())
                    .then(data => {
                        const method = data.methods ? data.methods.find(m => m.code === selectedMethod) : null;
                        const methodName = method ? method.name : selectedMethod;
                        showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName);
                    })
                    .catch(() => {
                        // Fallback if API fails
                        const methodNames = {
                            'qris': 'QRIS Transfer',
                            'bca_va': 'BCA Virtual Account', 'bca': 'BCA Virtual Account',
                            'mandiri_va': 'Mandiri Virtual Account', 'mandiri': 'Mandiri Virtual Account',
                            'bni_va': 'BNI Virtual Account', 'bni': 'BNI Virtual Account',
                            'permata_va': 'Permata Virtual Account',
                            'dana': 'DANA E-Wallet',
                            'gopay': 'GoPay E-Wallet',
                            'ovo': 'OVO E-Wallet',
                            'linkaja': 'LinkAja',
                            'shopeepay': 'ShopeePay',
                            'credit_card': 'Kartu Kredit/Debit',
                            'alfamart': 'Alfamart',
                            'indomaret': 'Indomaret'
                        };
                        const methodName = methodNames[selectedMethod] || selectedMethod;
                        showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName);
                    });
            }
            
            function showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName) {
                
                // Calculate current discount info for display
                const currentRobuxDiscount = robuxHasDiscount ? robuxDiscount : 0;
                const currentBasePrice = basePriceFromSearch;
                const currentRobuxFinalPrice = robuxHasDiscount ? robuxFinalPrice : currentBasePrice;
                
                // Create confirmation popup
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                popup.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,500px)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xl font-semibold text-white">Konfirmasi Pembayaran</div>
                            <button class="text-white/60 hover:text-white" id="closeConfirmPopup">✕</button>
                        </div>
                        <div class="space-y-4">
                            <div class="text-white/70 text-sm">
                                Anda akan melanjutkan ke halaman pembayaran dengan detail:
                            </div>
                            <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Produk:</span>
                                        <span class="text-white">${amount} Robux</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Username:</span>
                                        <span class="text-white">${username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Email:</span>
                                        <span class="text-white">${email}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Metode Pembayaran:</span>
                                        <span class="text-emerald-300">${methodName}</span>
                                    </div>
                                    ${robuxHasDiscount ? `
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Harga Asli:</span>
                                        <span class="text-white line-through">Rp ${new Intl.NumberFormat('id-ID').format(currentBasePrice)}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Diskon Robux:</span>
                                        <span class="text-yellow-300">-Rp ${new Intl.NumberFormat('id-ID').format(currentRobuxDiscount)}</span>
                                    </div>
                                    ` : ''}
                                    ${promoCodeData ? `
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Harga ${robuxHasDiscount ? 'Setelah Diskon Robux' : 'Asli'}:</span>
                                        <span class="text-white ${robuxHasDiscount ? '' : 'line-through'}">Rp ${new Intl.NumberFormat('id-ID').format(currentRobuxFinalPrice)}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Diskon Kode Promo:</span>
                                        <span class="text-yellow-300">-Rp ${new Intl.NumberFormat('id-ID').format(promoCodeData.discount_amount)}</span>
                                    </div>
                                    ` : ''}
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-white/60">Total:</span>
                                        <span class="text-white">Rp ${new Intl.NumberFormat('id-ID').format(finalPrice)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-white/60 text-xs">
                                ⏰ Waktu pembayaran: 10 menit dari sekarang
                            </div>
                            <div class="flex gap-3">
                                <button id="cancelPayment" class="flex-1 px-4 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                    Batal
                                </button>
                                <button id="confirmPayment" class="flex-1 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                    Lanjutkan
                                </button>
    </div>
    </div>
                    </div>`;
                
                document.body.appendChild(popup);
                
                // Close popup handlers
                popup.querySelector('#closeConfirmPopup').addEventListener('click', () => popup.remove());
                popup.querySelector('#cancelPayment').addEventListener('click', () => popup.remove());
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) popup.remove();
                });
                
                // Confirm payment handler
                popup.querySelector('#confirmPayment').addEventListener('click', () => {
                    popup.remove();
                    
                    // Create order with selected payment method
                    createOrderAndRedirect(email, paymentMode, selectedMethod);
                });
            }
            
            function createOrderAndRedirect(email, paymentMode, selectedMethod) {
                // CRITICAL: Get purchase_method from session - validate it matches current page context
                // Don't default to 'gamepass' - this prevents race condition where group order becomes gamepass
                const purchaseMethodRaw = '{{ session("selected_purchase_method", "") }}';
                const purchaseMethod = (purchaseMethodRaw === 'gamepass' || purchaseMethodRaw === 'group') ? purchaseMethodRaw : null;
                
                // Validate purchase_method is valid - MUST be either 'gamepass' or 'group'
                if (!purchaseMethod || (purchaseMethod !== 'gamepass' && purchaseMethod !== 'group')) {
                    console.error('Invalid or missing purchase_method:', purchaseMethodRaw);
                    alert('Metode pembelian tidak valid atau tidak ditemukan. Silakan buat pesanan baru dari halaman yang benar.');
                    window.location.href = '/';
                    return;
                }
                
                // CRITICAL: Price validation - use session price as source of truth
                // Don't reject if price is from session (already validated in search page)
                // Only validate if price is completely unreasonable (>50% difference)
                const expectedPricePer100 = purchaseMethod === 'group' ? groupRobuxPricePer100 : pricePer100;
                const expectedBasePrice = expectedPricePer100 * (amount / 100);
                const priceDiff = Math.abs(basePriceFromSearch - expectedBasePrice);
                const priceDiffPercent = (priceDiff / expectedBasePrice) * 100;
                
                // Only reject if price difference is completely unreasonable (>50%)
                // This allows for discount rules and small rounding differences
                if (priceDiffPercent > 50) {
                    console.error('Price validation failed - difference too large:', {
                        purchase_method: purchaseMethod,
                        session_base_price: basePriceFromSearch,
                        expected_base_price: expectedBasePrice,
                        difference_percent: priceDiffPercent
                    });
                    alert('Harga tidak sesuai dengan metode pembelian. Silakan buat pesanan baru.');
                    window.location.href = '/';
                    return;
                } else if (priceDiffPercent > 5) {
                    // Log warning but allow (might be due to discount rules)
                    console.warn('Price difference detected but within acceptable range:', {
                        purchase_method: purchaseMethod,
                        session_base_price: basePriceFromSearch,
                        expected_base_price: expectedBasePrice,
                        difference_percent: priceDiffPercent
                    });
                }
                
                // Only include gamepass_link if method is gamepass
                const gamepassLink = purchaseMethod === 'group' ? '' : '{{ session("gamepass_link") }}';
                
                // Use final price (with all discounts applied)
                const orderPrice = finalPrice;
                const originalPriceForOrder = basePriceFromSearch; // Base price before any discounts
                
                // For gateway mode, if no selectedMethod, use empty string (will be chosen in Midtrans)
                const methodToSubmit = (paymentMode === 'gateway' && !selectedMethod) ? '' : selectedMethod;
                
                // Debug logging
                console.log('Creating order with data:', {
                    amount, username, email, 
                    base_price: basePriceFromSearch,
                    robux_discount: robuxHasDiscount ? robuxDiscount : 0,
                    promo_discount: promoCodeData ? promoCodeData.discount_amount : 0,
                    original_price: originalPriceForOrder,
                    final_price: orderPrice, 
                    paymentMode, selectedMethod: methodToSubmit,
                    purchase_method: purchaseMethod,
                    gamepass_link: gamepassLink,
                    promo_code_data: promoCodeData
                });
                
                // Create form and submit to create order securely
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/user/create-order';
                form.style.display = 'none';
                
                const fields = {
                    'amount': amount,
                    'username': username,
                    'email': email,
                    'price': orderPrice, // Use final price (after discount)
                    'payment_mode': paymentMode,
                    'selected_method': methodToSubmit,
                    'purchase_method': purchaseMethod,
                    '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                // Add promo code data if available
                if (promoCodeData) {
                    fields['promo_code_id'] = promoCodeData.promo_code_id;
                    fields['promo_code'] = promoCodeData.code;
                    fields['original_price'] = originalPriceForOrder; // Use base price before discounts
                    fields['discount_amount'] = promoCodeData.discount_amount;
                }
                
                // Only add gamepass_link if method is gamepass
                if (purchaseMethod !== 'group' && gamepassLink) {
                    fields['gamepass_link'] = gamepassLink;
                }
                
                Object.entries(fields).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
            
            
            // Fetch avatar for username display (secure)
            if (username && /^[a-zA-Z0-9_-]+$/.test(username)) {
                fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`)
                    .then(r=>r.json()).then(j=>{
                        if(j && j.ok && j.avatar){
                            const av = document.getElementById('p_avatar');
                            av.src = j.avatar;
                        }
                    }).catch(()=>{});
            }
            
            // Initial validation
            validateEmail();
            
            // Promo Code Logic
            const promoCodeInput = document.getElementById('promo-code-input');
            const applyPromoBtn = document.getElementById('apply-promo-btn');
            const promoError = document.getElementById('promo-error');
            const promoSuccess = document.getElementById('promo-success');
            const promoDiscountInfo = document.getElementById('promo-discount-info');
            const promoDiscountAmountEl = document.getElementById('promo-discount-amount');

            if (hasReferral && promoCodeInput && applyPromoBtn) {
                promoCodeInput.value = '';
                promoCodeInput.disabled = true;
                promoCodeInput.placeholder = 'Referral aktif (promo code nonaktif)';
                applyPromoBtn.disabled = true;
            }
            
            function resetPromoMessages() {
                promoError.classList.add('hidden');
                promoSuccess.classList.add('hidden');
                promoDiscountInfo.classList.add('hidden');
            }
            
            applyPromoBtn.addEventListener('click', async () => {
                if (hasReferral) {
                    resetPromoMessages();
                    promoError.textContent = 'Kode promo tidak bisa digunakan bersamaan dengan referral.';
                    promoError.classList.remove('hidden');
                    return;
                }
                const code = promoCodeInput.value.trim().toUpperCase();
                resetPromoMessages();
                
                if (!code) {
                    promoError.textContent = 'Kode promo tidak boleh kosong.';
                    promoError.classList.remove('hidden');
                    return;
                }
                
                applyPromoBtn.disabled = true;
                applyPromoBtn.textContent = 'Menerapkan...';
                
                try {
                    const response = await fetch('{{ route('api.promo-code.validate') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ code: code, amount: basePriceFromSearch }) // Pass base price (before any discounts)
                    });
                    
                    // Check content type first
                    const contentType = response.headers.get('content-type');
                    const isJson = contentType && contentType.includes('application/json');
                    
                    if (!isJson) {
                        // Not JSON response - likely HTML error page
                        const errorText = await response.text();
                        throw new Error('Server mengembalikan response non-JSON. Pastikan route API tersedia.');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        promoCodeData = data;
                        promoSuccess.textContent = data.message || 'Kode promo berhasil diterapkan!';
                        promoSuccess.classList.remove('hidden');
                        promoDiscountAmountEl.textContent = '-Rp ' + fmt(data.discount_amount);
                        promoDiscountInfo.classList.remove('hidden');
                        // Clear any previous errors
                        promoError.classList.add('hidden');
                    } else {
                        promoCodeData = null;
                        promoError.textContent = data.message || 'Kode promo tidak valid atau sudah tidak dapat digunakan.';
                        promoError.classList.remove('hidden');
                        // Clear success message
                        promoSuccess.classList.add('hidden');
                        promoDiscountInfo.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error applying promo code:', error);
                    promoCodeData = null;
                    
                    // Set error message
                    if (error.message && error.message.includes('Server mengembalikan')) {
                        promoError.textContent = 'Terjadi kesalahan pada server. Pastikan route API tersedia dan coba lagi.';
                    } else if (error.message && error.message.includes('fetch')) {
                        promoError.textContent = 'Gagal terhubung ke server. Periksa koneksi internet Anda dan coba lagi.';
                    } else {
                        promoError.textContent = 'Kode promo tidak valid atau terjadi kesalahan. Silakan coba lagi.';
                    }
                    
                    promoError.classList.remove('hidden');
                    promoSuccess.classList.add('hidden');
                    promoDiscountInfo.classList.add('hidden');
                } finally {
                    applyPromoBtn.disabled = false;
                    applyPromoBtn.textContent = 'Terapkan';
                    updatePriceDisplay(); // Update display regardless of success/failure
                }
            });
            
            promoCodeInput.addEventListener('input', () => {
                resetPromoMessages();
                if (promoCodeData) {
                    promoCodeData = null; // Clear applied promo if input changes
                    updatePriceDisplay();
                }
            });
            
            // Allow Enter key to submit promo code
            promoCodeInput.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' && !applyPromoBtn.disabled) {
                    e.preventDefault();
                    applyPromoBtn.click();
                }
            });
        })();
    </script>
</main>
@endsection



