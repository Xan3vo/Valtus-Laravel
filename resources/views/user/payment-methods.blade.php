@extends('layouts.app')
@section('title', 'Pilih Pembayaran')
@section('body')

@php
$order = null;
$orderPaymentMode = 'manual';
$currentOrderId = session('current_order_id');
$stockSufficient = true;
$availableStock = null;
$purchaseMethod = null;

if($currentOrderId) {
    $order = \App\Models\Order::where('order_id', $currentOrderId)->first();
    
    if($order && $order->notes) {
        $notes = [];
        if (is_array($order->notes)) {
            $notes = $order->notes;
        } elseif (is_string($order->notes) && !empty($order->notes)) {
            $decoded = @json_decode((string) $order->notes, true);
            if (is_array($decoded)) {
                $notes = $decoded;
            }
        }
        $orderPaymentMode = $notes['payment_mode'] ?? 'manual';
    }

    if ($order && $order->game_type === 'Robux' && $order->amount) {
        $purchaseMethod = $order->purchase_method ?? 'gamepass';
        $availableStock = $purchaseMethod === 'group'
            ? \App\Services\RobuxStockService::getCurrentGroupStock()
            : \App\Services\RobuxStockService::getCurrentStock();
        $stockSufficient = $availableStock >= (int) $order->amount;
    }
}
@endphp

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
    {{-- <div class="mb-4">
        <a href="{{ route('user.payment') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-white/15 text-white/80 hover:text-white hover:bg-white/5 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="hidden sm:inline">Kembali ke Detail Pesanan</span>
            <span class="sm:hidden">Kembali</span>
        </a>
    </div> --}}
    
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
                <span class="h-6 w-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-medium text-xs">3</span>
                <span class="text-white">Pembayaran</span>
            </div>
        </div>
    </div>

    <!-- Countdown Timer -->
    @if($order && $order->expires_at)
    <div class="mb-6 rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-white font-medium">Waktu Pembayaran</div>
                    <div class="text-white/60 text-sm">Selesaikan pembayaran sebelum waktu habis</div>
                </div>
            </div>
            <div class="text-right">
                <div id="countdown-timer" class="text-2xl font-bold text-emerald-400 font-mono">10:00</div>
                <div class="text-white/60 text-xs">menit tersisa</div>
                <div class="text-white/40 text-xs mt-1" id="timezone-info">
                    <span id="user-timezone"></span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold mb-4">Pembayaran</h2>

            @if($order && $order->game_type === 'Robux' && !$stockSufficient)
            <div class="mb-4 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                <div class="text-red-300 font-medium mb-1">Stok Robux tidak mencukupi</div>
                <div class="text-white/70 text-sm">
                    Stok saat ini mungkin tidak mencukupi untuk menyelesaikan pesanan ini. Silakan tunggu pengisian ulang.
                    @if($availableStock !== null)
                        Sisa stok: {{ number_format((int) $availableStock, 0, ',', '.') }} Robux.
                    @endif
                </div>
            </div>
            @endif
            
            @php
            $paymentEnabled = \App\Models\Setting::getValue('payment_enabled', '0') === '1';
            @endphp

            @if(!$paymentEnabled)
            <div class="text-center py-8">
                <div class="text-red-400 text-lg mb-2">Sistem Pembayaran Sedang Dinonaktifkan</div>
                <div class="text-white/60">Silakan hubungi admin untuk informasi lebih lanjut</div>
            </div>
            @elseif($orderPaymentMode === 'manual')
            <!-- QRIS Payment -->
            <div class="space-y-4">
                @if(\App\Models\Setting::getValue('manual_qris_enabled', '0') === '1' && \App\Models\Setting::getValue('manual_qris_image'))
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">QRIS Payment</h3>
                    <div class="text-center">
                        <!-- Nominal Transfer -->
                        <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                            <div class="text-emerald-300 text-sm font-medium mb-1">Nominal yang harus ditransfer:</div>
                            <div class="text-white text-2xl font-bold">{{ $order ? 'Rp ' . number_format($order->price, 0, ',', '.') : '-' }}</div>
                        </div>
                        
                        <div class="inline-block p-4 bg-white rounded-lg">
                            <img src="/{{ \App\Models\Setting::getValue('manual_qris_image') }}" alt="QR Code" class="h-48 w-48 object-contain">
                        </div>
                        @if(\App\Models\Setting::getValue('manual_qris_name'))
                        <div class="mt-2 text-white/60 text-sm">Pemilik: {{ \App\Models\Setting::getValue('manual_qris_name') }}</div>
                        @endif
                        @if(\App\Models\Setting::getValue('manual_qris_instructions'))
                        <div class="mt-3 p-3 bg-white/10 rounded-lg">
                            <div class="text-white/80 text-sm">{{ \App\Models\Setting::getValue('manual_qris_instructions') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Upload Bukti Transfer -->
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Upload Bukti Transfer</h3>
                    <div class="space-y-4">
                        <label for="proof-upload" class="block border-2 border-dashed border-white/20 rounded-lg p-6 text-center cursor-pointer hover:border-white/40 hover:bg-white/5 transition-all duration-200 active:scale-95">
                            <svg class="mx-auto h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <div class="mt-2">
                                <span class="text-white font-medium">Klik atau drag & drop file di sini</span>
                            </div>
                            <p class="text-white/60 text-sm mt-1">PNG, JPG, GIF hingga 5MB</p>
                            <input id="proof-upload" type="file" accept="image/*" class="hidden">
                        </label>
                        
                        <!-- File Preview Area -->
                        <div id="file-preview" class="hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Gateway Payment - Midtrans -->
            <div class="space-y-4">
                <div class="rounded-lg border border-emerald-500/30 p-6 bg-emerald-500/10">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-emerald-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <div class="text-emerald-300 font-medium mb-1">Pembayaran Otomatis via Midtrans</div>
                            <div class="text-white/80 text-sm">Pembayaran Anda akan diproses secara otomatis. Anda akan diarahkan ke halaman pembayaran Midtrans untuk menyelesaikan transaksi.</div>
                        </div>
                    </div>
                </div>
                
                @php
                $methodNames = [
                    'qris' => 'QRIS',
                    'bca_va' => 'BCA Virtual Account',
                    'mandiri_va' => 'Mandiri Virtual Account',
                    'bni_va' => 'BNI Virtual Account',
                    'permata_va' => 'Permata Virtual Account',
                    'gopay' => 'GoPay',
                    'dana' => 'DANA',
                    'ovo' => 'OVO',
                    'linkaja' => 'LinkAja',
                    'shopeepay' => 'ShopeePay',
                    'credit_card' => 'Kartu Kredit/Debit',
                ];
                $selectedMethod = $order->payment_method ?? 'qris';
                $methodName = $methodNames[$selectedMethod] ?? $selectedMethod;
                @endphp
                
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Metode Pembayaran Terpilih</h3>
                    <div class="flex items-center gap-4 p-4 rounded-lg bg-white/5 border border-white/10">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                            @if(strpos($selectedMethod, 'bca') !== false)
                                <span class="text-white font-bold text-sm">BCA</span>
                            @elseif(strpos($selectedMethod, 'mandiri') !== false)
                                <span class="text-white font-bold text-sm">MDR</span>
                            @elseif(strpos($selectedMethod, 'bni') !== false)
                                <span class="text-white font-bold text-sm">BNI</span>
                            @elseif(strpos($selectedMethod, 'permata') !== false)
                                <span class="text-white font-bold text-sm">PMT</span>
                            @elseif(strpos($selectedMethod, 'gopay') !== false)
                                <span class="text-white font-bold text-sm">GOPAY</span>
                            @elseif(strpos($selectedMethod, 'dana') !== false)
                                <span class="text-white font-bold text-sm">DANA</span>
                            @elseif(strpos($selectedMethod, 'ovo') !== false)
                                <span class="text-white font-bold text-sm">OVO</span>
                            @elseif(strpos($selectedMethod, 'credit') !== false)
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <div class="text-white font-medium">{{ $methodName }}</div>
                            <div class="text-white/60 text-sm">Pembayaran via Midtrans</div>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Lanjutkan Pembayaran</h3>
                    <div class="text-white/70 text-sm mb-4">
                        Klik tombol di bawah untuk melanjutkan ke halaman pembayaran Midtrans. Setelah pembayaran berhasil, pesanan Anda akan otomatis terkonfirmasi.
                    </div>
                    <a href="{{ route('user.midtrans-payment', ['orderId' => $order->order_id]) }}" class="inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 py-3 text-white font-medium transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Lanjutkan ke Pembayaran Midtrans
                    </a>
                </div>
                
                <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                    <div class="text-blue-300 text-sm font-medium mb-2">ℹ️ Informasi Penting:</div>
                    <ul class="text-white/70 text-xs space-y-1 list-disc pl-5">
                        <li><strong>Tidak perlu upload bukti transfer</strong> - Pembayaran diproses otomatis</li>
                        <li>Setelah pembayaran berhasil, status pesanan akan otomatis berubah</li>
                        <li>Notifikasi email akan dikirim otomatis ke {{ $order->email }}</li>
                        <li>Waktu pembayaran: 10 menit dari sekarang</li>
                    </ul>
                </div>
            </div>
            @endif

            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/70 text-sm font-medium mb-2">Instruksi Pembayaran</div>
                <ul class="text-white/60 text-sm list-disc pl-5 space-y-1">
                    <li>Lakukan pembayaran sebelum 10 menit untuk menghindari pembatalan otomatis</li>
                    <li>Upload bukti transfer setelah melakukan pembayaran</li>
                    <li>Proses konfirmasi: 3-5 jam (di luar jam kerja bisa lebih lama)</li>
                    @if($order && $order->game_type === 'Robux')
                        <li>Robux akan otomatis masuk ke akun Anda dalam 5 hari setelah konfirmasi</li>
                    @else
                        <li>Item akan langsung masuk ke akun Anda setelah konfirmasi oleh admin</li>
                    @endif
                    <li>Notifikasi akan dikirim ke email yang telah Anda berikan</li>
                </ul>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-medium mb-3">Ringkasan Pesanan</div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Order ID</span>
                        <span class="text-white font-mono text-xs">{{ $order ? $order->order_id : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Produk</span>
                        <span class="text-white">
                            @if($order)
                                @if($order->game_type === 'Robux')
                                    {{ number_format($order->amount, 0, ',', '.') }} Robux
                                @else
                                    {{ $order->product_name ?? $order->game_type }}
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Username</span>
                        <span class="text-white">{{ $order ? $order->username : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Email</span>
                        <span class="text-white text-xs">{{ $order ? $order->email : '-' }}</span>
                    </div>
                    @if($order && $order->gamepass_link)
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">GamePass Anda</span>
                        <span class="text-emerald-300 text-xs">✓ Tersedia</span>
                    </div>
                    @endif
                </div>
                <div class="mt-4 h-px bg-white/10"></div>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                    <div class="text-white/90">Total Pembayaran</div>
                    <div class="text-white">{{ $order ? 'Rp ' . number_format($order->price, 0, ',', '.') : '-' }}</div>
                </div>
                <button id="submit-proof-btn" class="mt-4 w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 text-white font-medium transition-colors" disabled>
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kirim Bukti Transfer
                </button>
            </div>
        </aside>
    </div>
</main>

@if($order)
<script>
// Session validation
if (!{{ $order ? 'true' : 'false' }}) {
    alert('Pesanan tidak ditemukan. Silakan buat pesanan baru.');
    window.location.href = '/';
}

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
let uploadCompleted = false;

window.addEventListener('beforeunload', function(e) {
    // Only show warning if upload is in progress and not completed
    if (!uploadCompleted) {
        // Clear all sensitive data when leaving
        clearAllSensitiveData();
        // Don't show warning for normal navigation
        return;
    }
});

// Handle browser back/forward buttons
window.addEventListener('popstate', function(e) {
    // Clear all cached data when navigating back
    clearAllSensitiveData();
    // Refresh page to ensure clean state
    window.location.reload();
});

// Prevent form resubmission on refresh and handle Method Not Allowed
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

// Handle Method Not Allowed error on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a page that might have Method Not Allowed issues
    const currentPath = window.location.pathname;
    
    // If user tries to access create-order with GET, redirect to home
    if (currentPath === '/user/create-order') {
        console.log('Redirecting from create-order to home due to Method Not Allowed');
        window.location.href = '/';
        return;
    }
    
    // Add beforeunload warning for upload pages
    if (currentPath.includes('payment-methods') || currentPath.includes('upload')) {
        window.addEventListener('beforeunload', function(e) {
            // Only show warning if there's an active upload and not completed
            if (!uploadCompleted) {
                const uploadButton = document.getElementById('submit-proof-btn');
                if (uploadButton && uploadButton.disabled && uploadButton.innerHTML.includes('Mengupload')) {
                    e.preventDefault();
                    e.returnValue = 'Upload sedang berlangsung. Yakin ingin meninggalkan halaman?';
                    return e.returnValue;
                }
            }
            // Don't show warning for completed uploads
            return;
        });
    }
});

// Clear data on page load for security
document.addEventListener('DOMContentLoaded', function() {
    // Clear any leftover sensitive data
    clearAllSensitiveData();
});

// Countdown Timer with Timezone Handling
@if($order->expires_at)
(function(){
    const timerElement = document.getElementById('countdown-timer');
    
    // Get server time in UTC to avoid timezone issues
    const serverTimeUTC = new Date('{{ now()->utc()->toISOString() }}').getTime();
    const clientTimeUTC = new Date().getTime();
    
    // Calculate time difference between server and client
    const timeOffset = clientTimeUTC - serverTimeUTC;
    
    // Get order expires time in UTC
    const serverExpiresAtUTC = new Date('{{ $order->expires_at->utc()->toISOString() }}').getTime();
    
    // Calculate client-side expires time with offset correction
    const expiresAt = serverExpiresAtUTC + timeOffset;
    const now = new Date().getTime();
    
    // Display user timezone info
    const userTimezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
    const timezoneOffset = new Date().getTimezoneOffset();
    const timezoneElement = document.getElementById('user-timezone');
    
    if (timezoneElement) {
        const offsetHours = Math.floor(Math.abs(timezoneOffset) / 60);
        const offsetMinutes = Math.abs(timezoneOffset) % 60;
        const offsetSign = timezoneOffset <= 0 ? '+' : '-';
        const offsetString = `UTC${offsetSign}${offsetHours.toString().padStart(2, '0')}:${offsetMinutes.toString().padStart(2, '0')}`;
        
        timezoneElement.textContent = `${userTimezone} (${offsetString})`;
    }
    
    // Debug info (remove in production)
    console.log('🕐 Timer Debug Info:', {
        serverTimeUTC: new Date(serverTimeUTC).toISOString(),
        clientTimeUTC: new Date(clientTimeUTC).toISOString(),
        serverExpiresAtUTC: new Date(serverExpiresAtUTC).toISOString(),
        timeOffset: timeOffset + 'ms',
        timezone: userTimezone,
        clientOffset: timezoneOffset + ' minutes'
    });
    
    // Check if order is already expired on server side
    if (now >= serverExpiresAtUTC) {
        // Order already expired, show expired message immediately
        timerElement.textContent = '00:00';
        timerElement.className = 'text-2xl font-bold text-red-400 font-mono';
        
        const expiredModal = document.createElement('div');
        expiredModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
        expiredModal.innerHTML = `
            <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                <div class="text-red-400 text-2xl mb-4">⏰</div>
                <div class="text-white text-xl font-semibold mb-2">Waktu Pembayaran Habis</div>
                <div class="text-white/70 text-sm mb-4">Pesanan Anda telah expired. Silakan buat pesanan baru.</div>
                <button onclick="window.location.href='/'" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                    Kembali ke Beranda
                </button>
            </div>`;
        document.body.appendChild(expiredModal);
        return;
    }
    
    function updateTimer() {
        // Use current time with offset correction for accuracy
        const now = new Date().getTime();
        const distance = expiresAt - now;
        
        if (distance < 0) {
            // Time expired
            timerElement.textContent = '00:00';
            timerElement.className = 'text-2xl font-bold text-red-400 font-mono';
            
            // Show expired message and redirect
            const expiredModal = document.createElement('div');
            expiredModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
            expiredModal.innerHTML = `
                <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                    <div class="text-red-400 text-2xl mb-4">⏰</div>
                    <div class="text-white text-xl font-semibold mb-2">Waktu Pembayaran Habis</div>
                    <div class="text-white/70 text-sm mb-4">Pesanan Anda telah expired. Silakan buat pesanan baru.</div>
                    <button onclick="window.location.href='/'" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                        Kembali ke Beranda
                    </button>
                </div>`;
            document.body.appendChild(expiredModal);
            
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const timeString = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
        timerElement.textContent = timeString;
        
        // Change color when less than 2 minutes
        if (minutes < 2) {
            timerElement.className = 'text-2xl font-bold text-red-400 font-mono';
        } else if (minutes < 5) {
            timerElement.className = 'text-2xl font-bold text-yellow-400 font-mono';
        } else {
            timerElement.className = 'text-2xl font-bold text-emerald-400 font-mono';
        }
        
        // Debug info every 30 seconds (remove in production)
        if (Math.floor(distance / 1000) % 30 === 0) {
            console.log('🕐 Timer Update:', {
                remaining: timeString,
                distance: Math.floor(distance / 1000) + 's',
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone
            });
        }
    }
    
    // Update timer immediately and then every second
    updateTimer();
    const timerInterval = setInterval(updateTimer, 1000);
    
    // Clear interval when page is hidden to save resources
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(timerInterval);
        } else {
            // Restart timer when page becomes visible
            updateTimer();
            setInterval(updateTimer, 1000);
        }
    });
})();
@endif

(function(){
    const orderId = '{{ $order->order_id }}';
    const stockSufficient = {{ $stockSufficient ? 'true' : 'false' }};
    
    // No payment method selection needed - order already has payment method set
    
    // Upload proof functionality
    const proofUpload = document.getElementById('proof-upload');
    const submitProofBtn = document.getElementById('submit-proof-btn');
    let selectedFile = null;
    
    if (proofUpload && submitProofBtn) {
        // Add click feedback and drag & drop to upload area
        const uploadLabel = document.querySelector('label[for="proof-upload"]');
        if (uploadLabel) {
            // Click feedback
            uploadLabel.addEventListener('click', function() {
                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
            
            // Drag & drop functionality
            uploadLabel.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-emerald-400', 'bg-emerald-500/10');
            });
            
            uploadLabel.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-emerald-400', 'bg-emerald-500/10');
            });
            
            uploadLabel.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-emerald-400', 'bg-emerald-500/10');
                
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    proofUpload.files = files;
                    proofUpload.dispatchEvent(new Event('change'));
                }
            });
        }
        
        proofUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan');
                    return;
                }
                
                // Validate file size (5MB untuk performa lebih baik)
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    const errorModal = document.createElement('div');
                    errorModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                    errorModal.innerHTML = `
                        <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                            <div class="text-red-400 text-2xl mb-4">⚠️</div>
                            <div class="text-white text-xl font-semibold mb-2">File Terlalu Besar</div>
                            <div class="text-white/70 text-sm mb-4">Ukuran file: ${(file.size / 1024 / 1024).toFixed(2)} MB<br>Maksimal: 5 MB</div>
                            <button onclick="this.closest('.fixed').remove()" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                                OK
                            </button>
                        </div>`;
                    document.body.appendChild(errorModal);
                    return;
                }
                
                // Show file size info
                const fileSizeMB = (file.size / 1024 / 1024).toFixed(2);
                console.log(`File selected: ${file.name} (${fileSizeMB} MB)`);
                
                selectedFile = file;
                submitProofBtn.disabled = false;
                
                // Show file preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('file-preview');
                    previewContainer.innerHTML = `
                        <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="flex items-center gap-3">
                                <img src="${e.target.result}" alt="Preview" class="h-16 w-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <div class="text-white font-medium">${file.name}</div>
                                    <div class="text-white/60 text-sm">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                                </div>
                                <button onclick="removePreview()" class="text-red-400 hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Rate limiting protection
        let lastUploadTime = 0;
        const UPLOAD_COOLDOWN = 5000; // 5 seconds between uploads
        
        submitProofBtn.addEventListener('click', function() {
            if (!selectedFile) return;
            
            // Check rate limiting
            const now = Date.now();
            if (now - lastUploadTime < UPLOAD_COOLDOWN) {
                const remainingTime = Math.ceil((UPLOAD_COOLDOWN - (now - lastUploadTime)) / 1000);
                alert(`Tunggu ${remainingTime} detik sebelum upload lagi`);
                return;
            }
            
            lastUploadTime = now;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white mr-2"></div>Mengupload...';
            this.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('proof_file', selectedFile);
            formData.append('order_id', orderId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Upload with retry mechanism
            uploadWithRetry(formData, 3, this, originalText);
        });
        
        // Upload with retry mechanism
        function uploadWithRetry(formData, maxRetries, button, originalText) {
            let retryCount = 0;
            
            function attemptUpload() {
                retryCount++;
                
                // Create AbortController for timeout
                const controller = new AbortController();
                const timeoutId = setTimeout(() => controller.abort(), 30000); // 30 second timeout
                
                // Update button text with retry info
                if (retryCount > 1) {
                    button.innerHTML = `<div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white mr-2"></div>Mengupload... (Percobaan ${retryCount}/${maxRetries})`;
                }
                
                fetch('/user/upload-proof', {
                    method: 'POST',
                    body: formData,
                    signal: controller.signal,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    clearTimeout(timeoutId);
                    
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, it's probably an HTML error page
                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Mark upload as completed to prevent beforeunload warning
                        uploadCompleted = true;
                        
                        // Show success message
                        const successModal = document.createElement('div');
                        successModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                        successModal.innerHTML = `
                            <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                                <div class="text-emerald-400 text-2xl mb-4">✅</div>
                                <div class="text-white text-xl font-semibold mb-2">Bukti Transfer Terkirim</div>
                                <div class="text-white/70 text-sm mb-4">Bukti transfer Anda telah berhasil dikirim. Tim kami akan memproses dalam 3-5 jam.</div>
                                <div class="flex gap-3 justify-center">
                                    <button onclick="disableBeforeUnloadWarnings(); goToStatus();" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                                        Lihat Status Pesanan
                                    </button>
                                    <button onclick="disableBeforeUnloadWarnings(); goToHome();" class="px-6 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                        Kembali ke Beranda
                                    </button>
                                </div>
                            </div>`;
                        document.body.appendChild(successModal);
                    } else {
                        throw new Error(data.message || 'Upload gagal');
                    }
                })
                .catch(error => {
                    clearTimeout(timeoutId);
                    console.error(`Upload attempt ${retryCount} failed:`, error);
                    
                    if (retryCount < maxRetries && (error.name === 'AbortError' || error.message.includes('Failed to fetch'))) {
                        // Retry for timeout or network errors
                        console.log(`Retrying upload... (${retryCount}/${maxRetries})`);
                        setTimeout(attemptUpload, 2000 * retryCount); // Exponential backoff
                    } else {
                        // Show error message
                        let errorMessage = 'Upload bukti transfer gagal: ';
                        if (error.name === 'AbortError') {
                            errorMessage += 'Timeout - file terlalu besar atau koneksi lambat. Coba lagi.';
                        } else if (error.message.includes('Failed to fetch')) {
                            errorMessage += 'Koneksi terputus. Periksa internet dan coba lagi.';
                        } else {
                            errorMessage += error.message;
                        }
                        
                        // Show error modal instead of alert
                        const errorModal = document.createElement('div');
                        errorModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                        errorModal.innerHTML = `
                            <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                                <div class="text-red-400 text-2xl mb-4">❌</div>
                                <div class="text-white text-xl font-semibold mb-2">Upload Gagal</div>
                                <div class="text-white/70 text-sm mb-4">${errorMessage}</div>
                                <div class="flex gap-3">
                                    <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                        Tutup
                                    </button>
                                    <button onclick="location.reload()" class="px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                        Coba Lagi
                                    </button>
                                </div>
                            </div>`;
                        document.body.appendChild(errorModal);
                        
                        // Reset button
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                });
            }
            
            attemptUpload();
        }
    }
    
    // Function to remove file preview
    window.removePreview = function() {
        selectedFile = null;
        document.getElementById('proof-upload').value = '';
        document.getElementById('submit-proof-btn').disabled = true;
        document.getElementById('file-preview').classList.add('hidden');
        document.getElementById('file-preview').innerHTML = '';
    };
    
    // Function to navigate to status page without warning
    window.goToStatus = function() {
        // Clear all sensitive data first
        clearAllSensitiveData();
        // Navigate without warning
        window.location.replace('/user/status');
    };
    
    // Function to navigate to home page without warning
    window.goToHome = function() {
        // Disable all beforeunload warnings
        uploadCompleted = true;
        // Clear all sensitive data first
        clearAllSensitiveData();
        // Navigate without warning
        window.location.replace('/');
    };
    
    // Function to disable all beforeunload warnings
    window.disableBeforeUnloadWarnings = function() {
        uploadCompleted = true;
        // Remove all beforeunload event listeners
        window.removeEventListener('beforeunload', function() {});
    };
})();
</script>
@else
<div class="text-center py-8">
    <div class="text-red-400 text-lg mb-2">Pesanan Tidak Ditemukan</div>
    <div class="text-white/60 mb-4">Session mungkin telah expired atau pesanan tidak valid.</div>
    <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
        Kembali ke Beranda
    </a>
</div>
<script>
// Auto redirect after 3 seconds
setTimeout(() => {
    window.location.href = '/';
}, 3000);
</script>
@endif


@endsection
