@extends('layouts.app')
@section('title', 'Pembayaran Midtrans')
@section('body')

<!-- Additional meta tags for mobile QRIS display -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="mobile-web-app-capable" content="yes">

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
    <!-- Steps header -->
    <div class="mb-6 rounded-xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-4 sm:p-5">
        <div class="flex items-center gap-2 sm:gap-4 text-xs sm:text-sm overflow-x-auto">
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center text-xs">1</span>
                <span class="text-white/70">Memilih Produk</span>
            </div>
            <div class="text-white/30">/</div>
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center text-xs">2</span>
                <span class="text-white/70">Detail Pesanan</span>
            </div>
            <div class="text-white/30">/</div>
            <div class="flex items-center gap-2 whitespace-nowrap">
                <span class="h-6 w-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-medium text-xs">3</span>
                <span class="text-white">Pembayaran</span>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold mb-4">Pembayaran via Midtrans</h2>
            
            <div class="mb-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <div class="text-blue-300 font-medium mb-1">Pembayaran Otomatis</div>
                        <div class="text-white/70 text-sm">Pembayaran Anda akan diproses otomatis oleh Midtrans. Setelah pembayaran berhasil, pesanan akan otomatis terkonfirmasi. <strong>Tidak perlu upload bukti transfer.</strong></div>
                    </div>
                </div>
            </div>

            <!-- Midtrans Snap Payment Container -->
            <div id="snap-container" class="rounded-lg border border-white/10 bg-white p-3 sm:p-6 w-full" style="min-height: 500px; overflow: visible;">
                <div id="snap-loading" class="text-center py-20">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500 mx-auto mb-4"></div>
                    <div class="text-gray-600">Memuat halaman pembayaran...</div>
                    <div class="text-gray-400 text-xs mt-2">Mohon tunggu sebentar</div>
                </div>
                <!-- DEBUG: Device info for testing (hidden by default, shown via JS) -->
                <div id="debug-info" class="hidden mt-4 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg text-xs">
                    <div class="text-yellow-300 font-medium mb-2">🔍 Debug Info:</div>
                    <div id="debug-content" class="text-yellow-200/80 font-mono text-xs"></div>
                </div>
            </div>
            
            @if(session('error'))
            <div class="mt-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                <div class="text-red-300 font-medium mb-1">⚠️ Error</div>
                <div class="text-white/70 text-sm">{{ session('error') }}</div>
            </div>
            @endif
            
            <div class="mt-4 p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                <div class="text-yellow-300 text-sm font-medium mb-2">⚠️ Perhatian:</div>
                <ul class="text-white/70 text-xs space-y-1 list-disc pl-5">
                    <li>Jangan tutup halaman ini saat proses pembayaran berlangsung</li>
                    <li>Setelah pembayaran berhasil, Anda akan otomatis diarahkan ke halaman status pesanan</li>
                    <li>Jika pembayaran gagal, Anda dapat mencoba lagi</li>
                    <li>Waktu pembayaran: 10 menit dari sekarang</li>
                </ul>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-medium mb-4">Detail Pesanan</div>
                
                <!-- Product Card -->
                <div class="mb-4 p-4 rounded-lg border border-white/10 bg-white/5">
                    <div class="flex items-center gap-3 mb-3">
                        @if($order->game_type === 'Robux')
                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                                <img src="/assets/images/robux.png" class="h-6 w-6" alt="Robux">
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1">
                            <div class="text-white font-medium">
                                @if($order->game_type === 'Robux')
                                    {{ number_format((int)($order->amount ?? 0), 0, ',', '.') }} Robux
                                @else
                                    {{ $order->product_name ?? $order->game_type }}
                                @endif
                            </div>
                            <div class="text-white/60 text-xs">
                                @if($order->game_type === 'Robux')
                                    Roblox Digital Currency
                                    @if($order->purchase_method === 'group')
                                        • Via Group
                                    @else
                                        • Via Gamepass
                                    @endif
                                @else
                                    {{ $order->game_type }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Order ID</span>
                        <span class="text-white font-mono text-xs">{{ $order->order_id }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Username</span>
                        <span class="text-white font-medium">{{ $order->username }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Email</span>
                        <span class="text-white text-xs break-all">{{ $order->email }}</span>
                    </div>
                    @if($order->game_type === 'Robux' && $order->purchase_method === 'group')
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Metode</span>
                        <span class="text-purple-300 text-xs">Via Group</span>
                    </div>
                    @elseif($order->game_type === 'Robux' && $order->gamepass_link)
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">GamePass</span>
                        <span class="text-emerald-300 text-xs">✓ Tersedia</span>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Metode Pembayaran</span>
                        <span class="text-emerald-300 text-xs font-medium">
                            @php
                                $methodNames = [
                                    'qris' => 'QRIS',
                                    'bca_va' => 'BCA VA',
                                    'mandiri_va' => 'Mandiri VA',
                                    'bni_va' => 'BNI VA',
                                    'permata_va' => 'Permata VA',
                                    'gopay' => 'GoPay',
                                    'dana' => 'DANA',
                                    'ovo' => 'OVO',
                                    'linkaja' => 'LinkAja',
                                    'shopeepay' => 'ShopeePay',
                                    'credit_card' => 'Kartu Kredit',
                                ];
                            @endphp
                            {{ $methodNames[$order->payment_method] ?? $order->payment_method }}
                        </span>
                    </div>
                    @if($order->expires_at)
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Waktu Pembayaran</span>
                        <span class="text-yellow-300 text-xs">{{ $order->expires_at->format('H:i') }}</span>
                    </div>
                    @endif
                </div>
                
                <div class="mt-4 pt-4 border-t border-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white/60 text-sm">Subtotal</span>
                        <span class="text-white text-sm">Rp {{ number_format((int)($order->price ?? 0), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-white/60 text-sm">Biaya Admin</span>
                        <span class="text-white text-sm">Rp 0</span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-white/10 flex items-center justify-between text-lg font-semibold">
                        <div class="text-white/90">Total Pembayaran</div>
                        <div class="text-white">Rp {{ number_format((int)($order->price ?? 0), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

<!-- Midtrans Snap.js -->
@php
$environment = \App\Models\Setting::getValue('midtrans_environment', 'sandbox');
$snapUrl = $environment === 'production' 
    ? 'https://app.midtrans.com/snap/snap.js' 
    : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp

<!-- Prevent back button after cancel or expired -->
<script>
// Prevent back button - more aggressive approach
(function() {
    // Push history state to prevent back
    history.pushState(null, null, location.href);
    
    // Prevent back button - multiple approaches for better compatibility
    window.addEventListener('popstate', function(event) {
        // Immediately redirect to home if user tries to go back
        window.location.replace('{{ route("home") }}?from=payment');
    });
    
    // Also prevent back with onpopstate
    window.onpopstate = function(event) {
        // Redirect to home immediately
        window.location.replace('{{ route("home") }}?from=payment');
    };
    
    // Add another history entry to make it harder to go back
    setTimeout(function() {
        history.pushState(null, null, location.href);
    }, 100);
    
    // Prevent browser back button on mobile and desktop
    window.addEventListener('pageshow', function(event) {
        if (event.persisted) {
            // Page was loaded from cache, prevent back
            window.location.replace('{{ route("home") }}?from=payment');
        }
    });
    
    // Also prevent back on focus (for mobile browsers)
    window.addEventListener('focus', function() {
        // Check if user is trying to go back by checking history
        if (window.history.length > 1) {
            // Push another state to prevent back
            history.pushState(null, null, location.href);
        }
    });
})();
</script>

<script type="text/javascript" src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>
<style>
/* Fix for Midtrans Snap.js on mobile - ensure QRIS displays correctly */
#snap-container {
    width: 100% !important;
    max-width: 100% !important;
    overflow: visible !important;
    position: relative;
    display: block !important;
}

/* Ensure Snap.js iframe renders correctly on mobile */
#snap-container iframe {
    width: 100% !important;
    min-height: 500px !important;
    border: none !important;
    overflow: visible !important;
    display: block !important;
}

/* Fix for mobile viewport - ensure QRIS is visible */
@media (max-width: 640px) {
    #snap-container {
        min-height: 700px !important;
        padding: 0.5rem !important;
        margin: 0 !important;
    }
    
    #snap-container iframe {
        min-height: 700px !important;
        width: 100% !important;
        max-width: 100% !important;
        display: block !important;
    }
    
    /* Force QRIS to be visible on mobile */
    #snap-container * {
        max-width: 100% !important;
    }
}

/* Ensure QRIS code is visible - multiple selectors to catch all QRIS elements */
/* CRITICAL: Handle both QRIS and GoPay payment types (Midtrans v1.34.0+ compatibility) */
/* CRITICAL: These rules ensure QRIS appears on mobile devices */
#snap-container img[alt*="QR"],
#snap-container img[alt*="qr"],
#snap-container img[alt*="QRIS"],
#snap-container img[alt*="qris"],
#snap-container img[src*="qr"],
#snap-container img[src*="QR"],
#snap-container img[src*="QRIS"],
#snap-container img[src*="qris"],
#snap-container canvas,
#snap-container svg[class*="qr"],
#snap-container svg[class*="QR"],
#snap-container div[class*="qr"],
#snap-container div[class*="QR"],
#snap-container div[class*="QRIS"],
#snap-container div[class*="qris"],
#snap-container div[data-payment-type="qris"],
#snap-container div[data-payment-type="gopay"],
#snap-container button[data-payment-type="qris"],
#snap-container button[data-payment-type="gopay"],
#snap-container a[data-payment-type="qris"],
#snap-container a[data-payment-type="gopay"],
/* GoPay specific selectors */
#snap-container div[class*="gopay"],
#snap-container div[class*="GoPay"],
#snap-container button[class*="gopay"],
#snap-container button[class*="GoPay"],
/* Additional QRIS/QR code selectors for mobile */
#snap-container [id*="qr"],
#snap-container [id*="QR"],
#snap-container [class*="payment-qr"],
#snap-container [class*="payment-QR"] {
    max-width: 100% !important;
    width: auto !important;
    height: auto !important;
    display: block !important;
    margin: 1rem auto !important;
    visibility: visible !important;
    opacity: 1 !important;
    position: relative !important;
    z-index: 1 !important;
}

/* Force payment method selection buttons to be visible on mobile */
/* CRITICAL: Ensure both QRIS and GoPay buttons are visible on all devices */
#snap-container button,
#snap-container a[role="button"],
#snap-container div[role="button"],
/* QRIS and GoPay specific buttons */
#snap-container button[data-payment-type="qris"],
#snap-container button[data-payment-type="gopay"],
#snap-container a[data-payment-type="qris"],
#snap-container a[data-payment-type="gopay"],
#snap-container div[data-payment-type="qris"],
#snap-container div[data-payment-type="gopay"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    min-height: 44px !important; /* Better touch target on mobile */
}

/* Ensure payment method buttons are visible */
/* CRITICAL: Handle both QRIS and GoPay payment methods */
#snap-container button,
#snap-container a[class*="payment"],
#snap-container div[class*="payment-method"],
#snap-container div[class*="qris"],
#snap-container div[class*="gopay"],
#snap-container div[class*="QRIS"],
#snap-container div[class*="GoPay"] {
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}
</style>
<script type="text/javascript">
(function() {
    const snapToken = '{{ $snapToken }}';
    const snapContainer = document.getElementById('snap-container');
    const snapLoading = document.getElementById('snap-loading');

    function openSnap() {
        // Hide loading indicator
        if (snapLoading) {
            snapLoading.style.display = 'none';
        }
        
        // Clear container before rendering
        snapContainer.innerHTML = '';
        
        // Wait a bit to ensure container is ready
        setTimeout(function() {
            // DEBUG: Detect device type for debugging
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
            const isTablet = /iPad|Android/i.test(navigator.userAgent) && window.innerWidth >= 768;
            const deviceInfo = {
                isMobile: isMobile,
                isTablet: isTablet,
                userAgent: navigator.userAgent,
                screenWidth: window.innerWidth,
                screenHeight: window.innerHeight,
                platform: navigator.platform
            };
            console.log('Device detection:', deviceInfo);
            
            // Show debug info on page (for testing)
            const debugInfo = document.getElementById('debug-info');
            const debugContent = document.getElementById('debug-content');
            if (debugInfo && debugContent) {
                debugInfo.classList.remove('hidden');
                debugContent.innerHTML = `
                    <div><strong>Device:</strong> ${isMobile ? 'Mobile' : isTablet ? 'Tablet' : 'Desktop'}</div>
                    <div><strong>Screen:</strong> ${window.innerWidth}x${window.innerHeight}</div>
                    <div><strong>Platform:</strong> ${navigator.platform}</div>
                    <div><strong>User Agent:</strong> ${navigator.userAgent.substring(0, 50)}...</div>
                `;
            }
            
            // Prepare options for snap.pay()
            // CRITICAL: Use gopayMode: 'auto' to ensure GoPay and QRIS appear on all devices
            // This fixes issue where QRIS doesn't appear on mobile and GoPay doesn't appear on PC
            // Midtrans v1.34.0+ changed how they handle GoPay/QRIS payment_type
            const snapOptions = {
            // CRITICAL: Enable gopayMode: 'auto' to show both GoPay and QRIS on all devices
            // This ensures:
            // - QRIS appears on mobile devices (shows QR code)
            // - GoPay appears on PC/desktop (shows app deeplink or QR code)
            // - Both payment methods are available regardless of device type
            // Note: Must be string 'auto', not boolean or other value
            gopayMode: 'auto',
            // Force Midtrans UI to stay in QR mode on mobile so the QR code is still reachable
            // while keeping 'auto' elsewhere (docs: Snap advanced feature -> options.uiMode)
            uiMode: isMobile ? 'qr' : 'auto',
            onSuccess: function(result) {
                console.log('Payment success:', result);
                console.log('Payment type:', result.payment_type);
                // Redirect dengan parameter untuk prevent back button
                window.location.replace('{{ route("home") }}?from=payment&payment=success');
            },
            onPending: function(result) {
                console.log('Payment pending:', result);
                console.log('Payment type:', result.payment_type);
                // CRITICAL: Handle both 'qris' and 'gopay' payment_type
                // Midtrans may return 'qris' even when user selected GoPay on mobile
                const paymentType = result.payment_type || 'unknown';
                const paymentMessage = paymentType === 'qris' || paymentType === 'gopay' 
                    ? 'Pembayaran Anda sedang menunggu konfirmasi. Silakan selesaikan pembayaran melalui QRIS atau GoPay.'
                    : 'Pembayaran Anda sedang menunggu konfirmasi. Silakan cek status pesanan Anda.';
                alert(paymentMessage);
                // Redirect dengan parameter untuk prevent back button
                window.location.replace('{{ route("home") }}?from=payment&payment=pending');
            },
            onError: function(result) {
                console.log('Payment error:', result);
                console.log('Payment type:', result.payment_type);
                // Redirect ke home untuk semua error
                window.location.replace('{{ route("home") }}?from=payment&payment=error');
            },
            onClose: function() {
                // Show custom cancel popup with two options
                const cancelModal = document.createElement('div');
                cancelModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                cancelModal.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,420px)]">
                        <div class="text-white text-lg font-semibold mb-2">Batalkan Pembayaran?</div>
                        <div class="text-white/70 text-sm mb-4">Anda dapat melanjutkan pembayaran atau kembali ke beranda.</div>
                        <div class="flex gap-3 justify-end">
                            <button id="btn-continue-payment" class="px-5 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">Lanjutkan Pembayaran</button>
                            <button id="btn-cancel-payment" class="px-5 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white transition-colors">Berhenti & Ke Home</button>
                        </div>
                    </div>`;
                document.body.appendChild(cancelModal);

                // Continue: close modal and reopen Snap
                cancelModal.querySelector('#btn-continue-payment').addEventListener('click', function() {
                    cancelModal.remove();
                    openSnap();
                });

                // Cancel: go home (replace to prevent back) and clear history
                cancelModal.querySelector('#btn-cancel-payment').addEventListener('click', function() {
                    // Clear all history entries to prevent back
                    // Use multiple approaches for maximum compatibility
                    try {
                        // Clear history stack
                        if (window.history.replaceState) {
                            // Replace current state with home URL
                            window.history.replaceState(null, '', '{{ route("home") }}?from=payment');
                            // Add another state to prevent back
                            window.history.pushState(null, '', '{{ route("home") }}?from=payment');
                        }
                        
                        // Redirect with replace (prevents back button)
                        window.location.replace('{{ route("home") }}?from=payment');
                    } catch(e) {
                        // Fallback: just redirect
                        window.location.replace('{{ route("home") }}?from=payment');
                    }
                });
                
                // Close modal when clicking outside
                cancelModal.addEventListener('click', function(e) {
                    if (e.target === cancelModal) {
                        // If user clicks outside, treat as "continue payment"
                        cancelModal.remove();
                        openSnap();
                    }
                });
            }
            };
            
            // For mobile devices, ensure QRIS is visible by NOT using deeplink mode
            // deeplink mode would hide QRIS and force app opening
            // Instead, let Midtrans show all payment options including QRIS
            
            // DEBUG: Log snap options before calling
            console.log('Snap options:', {
                gopayMode: snapOptions.gopayMode,
                hasOnSuccess: !!snapOptions.onSuccess,
                hasOnPending: !!snapOptions.onPending,
                hasOnError: !!snapOptions.onError,
                hasOnClose: !!snapOptions.onClose,
                snapTokenLength: snapToken.length
            });
            
            // Call snap.pay with options
            // CRITICAL: gopayMode: 'auto' ensures both QRIS and GoPay appear on all devices
            try {
                snap.pay(snapToken, snapOptions);
                console.log('snap.pay() called successfully');
            } catch (error) {
                console.error('Error calling snap.pay():', error);
                if (snapLoading) {
                    snapLoading.innerHTML = '<div class="text-red-500">Error memuat pembayaran. Silakan refresh halaman.</div>';
                }
            }
        }, 100); // Small delay to ensure container is ready
    }

    // Wait for Snap.js to be fully loaded before opening
    if (typeof snap !== 'undefined') {
        openSnap();
    } else {
        // Wait for Snap.js to load
        window.addEventListener('load', function() {
            if (typeof snap !== 'undefined') {
                openSnap();
            } else {
                // Fallback: try again after a short delay
                setTimeout(function() {
                    if (typeof snap !== 'undefined') {
                        openSnap();
                    } else {
                        console.error('Snap.js failed to load');
                        if (snapLoading) {
                            snapLoading.innerHTML = '<div class="text-red-500">Gagal memuat halaman pembayaran. Silakan refresh halaman.</div>';
                        }
                    }
                }, 1000);
            }
        });
    }
})();
</script>

@endsection

