@php
// Parse notes to get timeline data
$notes = $order->notes ?? [];
$proofUploadedAt = $notes['proof_uploaded_at'] ?? null;
$confirmedAt = $notes['confirmed_at'] ?? null;
$adminNotes = $notes['admin_notes'] ?? null;

// Determine actual order status based on order_status and completed_at
$isActuallyCompleted = false;
$timeRemaining = null;
$showProcessing = false;

// Check if order_status is completed and if completed_at has passed
if (strtolower($order->order_status) === 'completed' && $order->completed_at) {
    $completedAt = \Carbon\Carbon::parse($order->completed_at);
    $now = \Carbon\Carbon::now();
    
    if ($now->gte($completedAt)) {
        $isActuallyCompleted = true;
    } else {
        $timeRemaining = $now->diff($completedAt);
        $showProcessing = true;
    }
}

$orderStatusText = '';
$orderStatusColor = '';

if ($isActuallyCompleted) {
    $orderStatusText = 'Selesai';
    $orderStatusColor = 'text-emerald-400 bg-emerald-500/20 border-emerald-500/30';
} elseif ($order->payment_status === 'pending') {
    $orderStatusText = 'Menunggu Pembayaran';
    $orderStatusColor = 'text-yellow-400 bg-yellow-500/20 border-yellow-500/30';
} elseif ($order->payment_status === 'waiting_confirmation') {
    $orderStatusText = 'Menunggu Konfirmasi';
    $orderStatusColor = 'text-blue-400 bg-blue-500/20 border-blue-500/30';
} elseif ($order->payment_status === 'Failed') {
    $orderStatusText = 'Ditolak';
    $orderStatusColor = 'text-red-400 bg-red-500/20 border-red-500/30';
} elseif (strtolower($order->order_status) === 'pending') {
    $orderStatusText = 'Pending';
    $orderStatusColor = 'text-orange-400 bg-orange-500/20 border-orange-500/30';
} elseif ($showProcessing) {
    $orderStatusText = 'Sedang diproses';
    $orderStatusColor = 'text-orange-400 bg-orange-500/20 border-orange-500/30';
} else {
    $orderStatusText = ucfirst($order->order_status ?? 'Unknown');
    $orderStatusColor = 'text-gray-400 bg-gray-500/20 border-gray-500/30';
}
@endphp

<div class="grid lg:grid-cols-3 gap-6">
    <!-- Order Info -->
    <div class="lg:col-span-2 space-y-4 sm:space-y-6">
        <!-- Product Info -->
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    @if($order->game_type === 'Robux')
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                            <img src="/assets/images/robux.png" class="h-6 w-6" alt="Robux">
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap">
                                <div class="text-white font-semibold text-lg">{{ number_format($order->amount ?? 0, 0, ',', '.') }} Robux</div>
                                @if($order->purchase_method === 'group')
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/40 text-purple-200 shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        Via Group
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-emerald-500/20 to-blue-500/20 border border-emerald-500/40 text-emerald-200 shadow-sm">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                        </svg>
                                        Via Gamepass
                                    </span>
                                @endif
                            </div>
                            <div class="text-white/60 text-sm mt-1">Roblox Digital Currency</div>
                        </div>
                    @else
                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center">
                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-white font-semibold text-lg">{{ $order->product_name ?? $order->game_type }}</div>
                            <div class="text-white/60 text-sm">{{ $order->game_type }} • {{ number_format($order->amount ?? 0, 0, ',', '.') }} item</div>
                        </div>
                    @endif
                </div>
                <div class="sm:ml-auto text-left sm:text-right">
                    <div class="text-white/60 text-sm">Harga</div>
                    <div class="text-white font-bold text-lg">Rp {{ number_format($order->price ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Buyer Info -->
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white font-semibold text-lg">Pembeli</div>
                        <div class="text-white/60 text-sm">Username: {{ $order->username }}</div>
                    </div>
                </div>
                <div class="sm:ml-auto">
                    <div class="text-right">
                        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $orderStatusColor }}">
                            <div class="h-2 w-2 bg-current rounded-full"></div>
                            <span class="text-sm font-medium">{{ $orderStatusText }}</span>
                        </div>
                        @if($showProcessing)
                            @if($timeRemaining && ($timeRemaining->days > 0 || $timeRemaining->h > 0 || $timeRemaining->i > 0))
                                @if($timeRemaining->days > 0)
                                    <div class="mt-1 text-xs text-orange-300">
                                        @if($order->game_type === 'Robux')
                                            Robux akan masuk dalam {{ $timeRemaining->days }} hari
                                        @else
                                            Item akan dikirim dalam {{ $timeRemaining->days }} hari
                                        @endif
                                    </div>
                                @elseif($timeRemaining->h > 0)
                                    <div class="mt-1 text-xs text-orange-300">
                                        @if($order->game_type === 'Robux')
                                            Robux akan masuk dalam {{ $timeRemaining->h }} jam
                                        @else
                                            Item akan dikirim dalam {{ $timeRemaining->h }} jam
                                        @endif
                                    </div>
                                @else
                                    <div class="mt-1 text-xs text-orange-300">
                                        @if($order->game_type === 'Robux')
                                            Robux akan masuk dalam {{ $timeRemaining->i }} menit
                                        @else
                                            Item akan dikirim dalam {{ $timeRemaining->i }} menit
                                        @endif
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- GamePass Info -->
        @if($order->gamepass_link)
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="text-white font-semibold text-lg mb-3">GamePass Anda</div>
            <div class="text-white/70 text-sm mb-4">GamePass yang akan digunakan admin untuk top-up:</div>
            <div class="text-white/80 text-sm font-mono bg-black/30 px-4 py-3 rounded-xl border border-white/10 break-all">
                {{ $order->gamepass_link }}
            </div>
            <div class="mt-3 text-white/50 text-xs">Admin akan menggunakan GamePass ini untuk mengirim Robux ke akun Anda</div>
        </div>
        @endif

        <!-- Payment Info -->
        @if($order->payment_method)
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="text-white font-semibold text-lg mb-2">Metode Pembayaran</div>
            <div class="text-white/70 text-sm">{{ ucfirst($order->payment_method) }}</div>
        </div>
        @endif

        <!-- Proof Upload Status -->
        @if($order->proof_file)
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="text-white font-semibold text-lg mb-3">Bukti Transfer</div>
            <div class="flex items-center gap-3">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Terkirim
                </div>
            </div>
        </div>
        @endif

        <!-- Order Timeline -->
        <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
            <div class="text-white font-semibold text-lg mb-6">Timeline Pesanan</div>
            <div class="space-y-4">
                <!-- Order Created -->
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-sm">Pesanan dibuat</div>
                        <div class="text-white/60 text-xs">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                
                <!-- Payment Uploaded -->
                @if($proofUploadedAt)
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-sm">Bukti pembayaran dikirim</div>
                        <div class="text-white/60 text-xs">{{ \Carbon\Carbon::parse($proofUploadedAt)->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
                
                <!-- Payment Confirmed -->
                @if($confirmedAt)
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-sm">Pembayaran dikonfirmasi</div>
                        <div class="text-white/60 text-xs">{{ \Carbon\Carbon::parse($confirmedAt)->format('d M Y, H:i') }}</div>
                        @if($adminNotes)
                        <div class="text-white/50 text-xs mt-1">Catatan: {{ $adminNotes }}</div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Order Processing -->
                @if($showProcessing)
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-full bg-orange-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-sm">
                            @if($order->game_type === 'Robux')
                                Sedang diproses - Robux akan masuk
                            @else
                                Sedang diproses - Item akan dikirim
                            @endif
                        </div>
                        @if($timeRemaining)
                            @if($timeRemaining->days > 0)
                                <div class="text-orange-300 text-xs">
                                    @if($order->game_type === 'Robux')
                                        Robux akan masuk dalam {{ $timeRemaining->days }} hari
                                    @else
                                        Item akan dikirim dalam {{ $timeRemaining->days }} hari
                                    @endif
                                </div>
                            @elseif($timeRemaining->h > 0)
                                <div class="text-orange-300 text-xs">
                                    @if($order->game_type === 'Robux')
                                        Robux akan masuk dalam {{ $timeRemaining->h }} jam
                                    @else
                                        Item akan dikirim dalam {{ $timeRemaining->h }} jam
                                    @endif
                                </div>
                            @else
                                <div class="text-orange-300 text-xs">
                                    @if($order->game_type === 'Robux')
                                        Robux akan masuk dalam {{ $timeRemaining->i }} menit
                                    @else
                                        Item akan dikirim dalam {{ $timeRemaining->i }} menit
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="text-orange-300 text-xs">
                                @if($order->game_type === 'Robux')
                                    Sedang diproses oleh admin - Robux akan masuk
                                @else
                                    Sedang diproses oleh admin - Item akan dikirim
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
                @endif
                
                <!-- Order Completed -->
                @if($isActuallyCompleted)
                <div class="flex items-center gap-3">
                    <div class="h-6 w-6 rounded-full bg-emerald-500 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-white text-sm">
                            @if($order->game_type === 'Robux')
                                Robux berhasil dikirim
                            @else
                                Produk berhasil dikirim
                            @endif
                        </div>
                        <div class="text-emerald-300 text-xs">Selesai - {{ $order->completed_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="space-y-4">
        <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6">
            <div class="text-white font-semibold text-lg mb-6">Ringkasan Pesanan</div>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-white/60">Order ID</span>
                    <span class="text-white font-mono text-xs">{{ $order->order_id }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-white/60">Produk</span>
                    <span class="text-white">
                        @if($order->game_type === 'Robux')
                            {{ number_format($order->amount ?? 0, 0, ',', '.') }} Robux
                        @else
                            {{ $order->product_name ?? $order->game_type }} ({{ number_format($order->amount ?? 0, 0, ',', '.') }} item)
                        @endif
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-white/60">Username</span>
                    <span class="text-white">{{ $order->username }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-white/60">Email</span>
                    <span class="text-white text-xs">{{ $order->email }}</span>
                </div>
                @if($order->gamepass_link)
                <div class="flex items-center justify-between">
                    <span class="text-white/60">GamePass Anda</span>
                    <span class="text-emerald-300 text-xs">✓ Tersedia</span>
                </div>
                @endif
            </div>
            <div class="mt-4 h-px bg-white/10"></div>
            <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                <div class="text-white/90">Total Pembayaran</div>
                <div class="text-white">Rp {{ number_format($order->price ?? 0, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Rejection Information for Failed Orders -->
        @if($order->payment_status === 'Failed')
        <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-4 mb-6">
            <div class="flex items-start gap-3">
                <div class="p-2 rounded-lg bg-red-500/20">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-red-300 font-semibold text-sm mb-2">Pembayaran Ditolak</h4>
                    <p class="text-red-200 text-sm mb-3">Pembayaran Anda telah ditolak oleh admin. Silakan periksa bukti transfer dan coba lagi.</p>
                    
                    @if($adminNotes)
                    <div class="bg-red-500/20 rounded-lg p-3 mb-3">
                        <div class="text-red-300 text-xs font-medium mb-1">Catatan Admin:</div>
                        <div class="text-red-200 text-sm">{{ $adminNotes }}</div>
                    </div>
                    @endif
                    
                    @if($order->proof_file)
                    <div class="flex items-center gap-2 mb-3">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-red-300 text-sm font-medium">Bukti transfer sudah terkirim</span>
                    </div>
                    @endif
                    
                    <!-- Customer Service Button -->
                    <div class="bg-red-500/20 rounded-lg p-3">
                        <div class="flex items-start gap-3">
                            <div class="p-1.5 rounded-lg bg-red-500/30">
                                <svg class="w-4 h-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-red-300 text-xs font-medium mb-1">Tidak Setuju dengan Penolakan?</div>
                                <div class="text-red-200 text-sm mb-3">Jika Anda merasa tidak ada kesalahan dalam bukti transfer, silakan hubungi customer service untuk bantuan lebih lanjut.</div>
                                <button onclick="showHelpModal(); return false;" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-500/30 hover:bg-red-500/40 text-red-200 text-sm font-medium transition-colors duration-200 border border-red-500/30">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                    Hubungi Customer Service
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="space-y-3">
            @if($order->payment_status === 'pending')
            <a href="{{ route('user.payment-methods') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                Lanjutkan Pembayaran
            </a>
            @elseif($order->payment_status === 'Failed')
            <a href="{{ route('user.search') }}" class="block w-full text-center px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                Buat Pesanan Baru
            </a>
            @endif
            
            <a href="{{ route('user.status') }}" class="block w-full text-center px-6 py-3 rounded-xl border border-white/20 hover:bg-white/5 hover:border-white/40 text-white font-medium transition-all duration-200">
                Cari Pesanan Lain
            </a>
        </div>
    </div>
</div>
