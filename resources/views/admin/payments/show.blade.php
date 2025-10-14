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
                        <div class="flex items-center gap-1 sm:gap-2">
                            @if($order->game_type === 'Robux')
                                <img src="/assets/images/robux.png" alt="Robux" class="h-3 w-3 sm:h-4 sm:w-4">
                                <span class="text-white text-sm sm:text-base">{{ $order->game_type }}</span>
                            @else
                                <span class="text-white text-sm sm:text-base">{{ $order->game_type }}</span>
                            @endif
                        </div>
                    </div>
                    @if($order->product_name && $order->game_type !== 'Robux')
                    <div>
                        <p class="text-white/60 text-xs sm:text-sm">Product Name</p>
                        <p class="text-white font-medium text-sm sm:text-base">{{ $order->product_name }}</p>
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
                        @elseif($order->payment_status === 'completed')
                            <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-emerald-500/20 to-green-500/20 text-emerald-200 border border-emerald-500/30 shadow-lg">
                                <div class="h-2 w-2 sm:h-3 sm:w-3 bg-emerald-400 rounded-full"></div>
                                <span class="font-semibold text-xs sm:text-sm">Selesai</span>
                            </div>
                        @elseif($order->payment_status === 'failed')
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
                        <a href="/proofs/{{ $order->proof_file }}" target="_blank" 
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
                <form method="POST" action="{{ route('admin.payments.confirm', $order) }}">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 sm:gap-3">
                        <button type="submit" name="action" value="approve" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">Setujui</span>
                            </div>
                        </button>
                        <button type="submit" name="action" value="reject" 
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold transition-all duration-200 shadow-lg hover:shadow-red-500/25">
                            <div class="flex items-center justify-center gap-1 sm:gap-2">
                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                <span class="text-xs sm:text-sm">Tolak</span>
                            </div>
                        </button>
                    </div>
                </form>
                @else
                <div class="text-center py-6 sm:py-8">
                    <div class="p-3 sm:p-4 rounded-full bg-gray-500/20 w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 flex items-center justify-center">
                        @if($order->payment_status === 'completed')
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @elseif($order->payment_status === 'failed')
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
                        @if($order->payment_status === 'completed')
                            Pembayaran telah disetujui
                        @elseif($order->payment_status === 'failed')
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
@endsection

