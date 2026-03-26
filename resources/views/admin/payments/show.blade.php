@extends('layouts.app')
@section('title', 'Admin • Payment Review')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
            <a href="{{ route('admin.payments') }}" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Payment Review</h1>
        </div>
        <p class="text-white/60 text-sm sm:text-base">Review payment details and confirm or reject</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Order Info -->
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Order Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Order ID</p>
                        <p class="text-white font-mono text-sm sm:text-base">{{ $order->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Username</p>
                        <p class="text-white text-sm sm:text-base">{{ $order->username }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Email</p>
                        <p class="text-white text-sm sm:text-base">{{ $order->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Game Type</p>
                        <div class="flex items-center gap-2">
                            @if($order->game_type === 'Robux')
                                <img src="/assets/images/robux.png" alt="Robux" class="h-5 w-5">
                                <span class="text-white text-sm sm:text-base font-medium">{{ $order->game_type }}</span>
                            @else
                                <span class="text-white text-sm sm:text-base font-medium">{{ $order->game_type }}</span>
                            @endif
                        </div>
                    </div>
                    @if($order->product_name && $order->game_type !== 'Robux')
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Product Name</p>
                        <p class="text-white text-sm sm:text-base font-medium">{{ $order->product_name }}</p>
                    </div>
                    @endif
                    @if($order->game_type !== 'Robux' && $order->product_name)
                    @php
                        // Get product image if available
                        $product = null;
                        if ($order->product_name) {
                            $product = \App\Models\Product::where('name', $order->product_name)
                                ->where('game_type', $order->game_type)
                                ->first();
                        }
                    @endphp
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm mb-2">Foto Produk</p>
                        <div class="flex items-center">
                            @if($product && $product->image)
                                <img src="{{ asset($product->image) }}" alt="{{ $order->product_name }}" class="h-16 w-16 rounded-lg object-cover border border-white/10 shadow-md">
                            @elseif($product && $product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $order->product_name }}" class="h-16 w-16 rounded-lg object-cover border border-white/10 shadow-md">
                            @else
                                <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-gray-500/30 to-gray-600/30 flex items-center justify-center border border-white/10">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($order->game_type === 'Robux' && $order->purchase_method)
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm mb-2">Metode Pembelian</p>
                        <div>
                            @if($order->purchase_method === 'group')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/40 text-purple-200 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Via Group
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-medium bg-gradient-to-r from-emerald-500/20 to-blue-500/20 border border-emerald-500/40 text-emerald-200 shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Via Gamepass
                                </span>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Amount</p>
                        <p class="text-white text-sm sm:text-base">
                            @if($order->game_type === 'Robux')
                                {{ number_format($order->amount ?? 0, 0, ',', '.') }} Robux
                            @else
                                {{ number_format($order->amount ?? 0, 0, ',', '.') }} item
                            @endif
                        </p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Total Payment</p>
                        <p class="text-white font-semibold text-sm sm:text-base">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Details -->
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Payment Details</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Payment Method</p>
                        <p class="text-white text-sm sm:text-base">{{ ucfirst($order->payment_method ?? 'N/A') }}</p>
                    </div>
                    @if($order->game_type === 'Robux' && $order->gamepass_link)
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Gamepass Link</p>
                        <a href="{{ $order->gamepass_link }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 text-xs sm:text-sm break-all underline">
                            {{ Str::limit($order->gamepass_link, 50) }}
                        </a>
                    </div>
                    @endif
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Payment Status</p>
                        @if($order->payment_status === 'waiting_confirmation')
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-yellow-500/20 to-orange-500/20 text-yellow-200 border border-yellow-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                <span class="font-semibold text-xs sm:text-sm">Menunggu Konfirmasi</span>
                            </div>
                        @elseif($order->payment_status === 'pending')
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-blue-500/20 to-cyan-500/20 text-blue-200 border border-blue-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-blue-400 rounded-full"></div>
                                <span class="font-semibold text-xs sm:text-sm">Menunggu Pembayaran</span>
                            </div>
                        @elseif($order->payment_status === 'Completed')
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-emerald-500/20 to-green-500/20 text-emerald-200 border border-emerald-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-emerald-400 rounded-full"></div>
                                <span class="font-semibold text-xs sm:text-sm">Selesai</span>
                            </div>
                        @elseif($order->payment_status === 'Failed')
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-red-500/20 to-pink-500/20 text-red-200 border border-red-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-red-400 rounded-full"></div>
                                <span class="font-semibold text-xs sm:text-sm">Ditolak</span>
                            </div>
                        @else
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-gray-500/20 to-slate-500/20 text-gray-200 border border-gray-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-gray-400 rounded-full"></div>
                                <span class="font-semibold text-xs sm:text-sm">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Order Status</p>
                        <p class="text-white text-sm sm:text-base">{{ ucfirst($order->order_status ?? 'N/A') }}</p>
                    </div>
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Created At</p>
                        <p class="text-white text-sm sm:text-base">{{ $order->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                </div>
            </div>


            <!-- Proof of Transfer -->
            @if($order->proof_file)
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Proof of Transfer</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="p-2 sm:p-3 rounded-lg bg-emerald-500/20">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium text-sm sm:text-base">Bukti transfer telah diupload</p>
                            <p class="text-white/60 text-xs sm:text-sm">{{ $order->proof_file }}</p>
                        </div>
                    </div>
                    <div class="flex gap-2 sm:gap-3">
                        <a href="{{ route('admin.payments.download-proof', $order) }}" target="_blank" 
                           class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-200 border border-emerald-500/30 hover:from-emerald-500/30 hover:to-teal-500/30 transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="font-medium text-sm sm:text-base">Lihat Bukti</span>
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Proof of Transfer</h3>
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 rounded-lg bg-gray-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white/60 font-medium text-sm sm:text-base">Belum ada bukti transfer</p>
                        <p class="text-white/40 text-xs sm:text-sm">User belum mengupload bukti pembayaran</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Action Panel -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Payment Actions -->
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Aksi Pembayaran</h3>
                
                @if($order->payment_status === 'waiting_confirmation')
                <form method="POST" action="{{ route('admin.payments.confirm', $order) }}" id="paymentForm">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        <button type="button" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-emerald-500/25"
                                id="approveBtn" onclick="handlePaymentAction('approve')">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm" id="approveText">Setujui</span>
                            </div>
                        </button>
                        <button type="button" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-red-500/25"
                                id="rejectBtn" onclick="handlePaymentAction('reject')">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-xs sm:text-sm" id="rejectText">Tolak</span>
                            </div>
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center py-6 sm:py-8">
                    <div class="p-3 sm:p-4 rounded-full bg-gray-500/20 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                        @if($order->payment_status === 'Completed')
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($order->payment_status === 'Failed')
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        @else
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <p class="text-white/60 font-medium text-sm sm:text-base">
                        @if($order->payment_status === 'Completed')
                            Pembayaran telah disetujui
                        @elseif($order->payment_status === 'Failed')
                            Pembayaran telah ditolak
                        @else
                            Tidak ada aksi yang tersedia
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="bg-white/5 rounded-xl border border-white/10 p-4 sm:p-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-3 sm:mb-4">Ringkasan Pesanan</h3>
                <div class="space-y-3 sm:space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-xs sm:text-sm">Jumlah</span>
                        <span class="text-white font-medium text-xs sm:text-sm">
                            @if($order->game_type === 'Robux')
                                {{ number_format($order->amount ?? 0, 0, ',', '.') }} Robux
                            @else
                                {{ number_format($order->amount ?? 0, 0, ',', '.') }} item
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-xs sm:text-sm">Harga per unit</span>
                        <span class="text-white font-medium text-xs sm:text-sm">Rp {{ number_format(($order->price ?? 0) / ($order->amount ?? 1), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-xs sm:text-sm">Pajak</span>
                        <span class="text-white font-medium text-xs sm:text-sm">Rp {{ number_format($order->tax ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="border-t border-white/10 pt-3 sm:pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white font-semibold text-sm sm:text-lg">Total</span>
                            <span class="text-white font-bold text-sm sm:text-lg">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
let isProcessing = false;

function handlePaymentAction(action) {
    // Prevent double-click
    if (isProcessing) {
        return false;
    }
    
    // Set processing state
    isProcessing = true;
    
    // Get buttons
    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');
    const approveText = document.getElementById('approveText');
    const rejectText = document.getElementById('rejectText');
    
    // Disable both buttons
    approveBtn.disabled = true;
    rejectBtn.disabled = true;
    
    // Add loading state
    if (action === 'approve') {
        approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        approveText.innerHTML = `
            <svg class="animate-spin w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-1">Memproses...</span>
        `;
    } else {
        rejectBtn.classList.add('opacity-50', 'cursor-not-allowed');
        rejectText.innerHTML = `
            <svg class="animate-spin w-3 h-3 sm:w-4 sm:h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-1">Memproses...</span>
        `;
    }
    
    // Get form element
    const form = document.getElementById('paymentForm');
    if (!form) {
        console.error('Form not found!');
        isProcessing = false;
        return false;
    }
    
    // Create or update action input
    let actionInput = form.querySelector('input[name="action"]');
    if (!actionInput) {
        actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        form.appendChild(actionInput);
    }
    actionInput.value = action;
    
    // Submit form
    form.submit();
    
    return false;
}

// Reset processing state on page load
document.addEventListener('DOMContentLoaded', function() {
    isProcessing = false;
});

// Reset processing state if user navigates back
window.addEventListener('pageshow', function(event) {
    isProcessing = false;
});
</script>
@endsection

