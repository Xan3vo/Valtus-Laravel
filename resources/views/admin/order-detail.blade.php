@extends('layouts.app')
@section('title', 'Admin • Order Details')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Order Details</h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Detailed view of order #{{ $order->order_id }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @php
    // Check payment gateway from database column
    $isMidtrans = $order->payment_gateway === 'midtrans';
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        <!-- Order Information -->
        <div class="rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-lg sm:text-xl font-medium text-white/90">Order Information</h3>
                @if($isMidtrans)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-500/40 text-blue-200 shadow-md">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Via Midtrans
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs sm:text-sm font-medium bg-gradient-to-r from-gray-500/20 to-slate-500/20 border border-gray-500/40 text-gray-200 shadow-md">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Via Manual
                    </span>
                @endif
            </div>
            <div class="space-y-2 sm:space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Order ID:</span>
                    <span class="text-white/90 font-medium text-sm sm:text-base">#{{ $order->order_id }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Username:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->username }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Email:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->email ?? 'Not provided' }}</span>
                </div>
                @if($order->game_type === 'Robux')
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm sm:text-base">Game Type:</span>
                        <div class="flex items-center gap-2">
                            <img src="/assets/images/robux.png" alt="Robux" class="h-5 w-5">
                            <span class="text-white/90 text-sm sm:text-base font-medium">{{ $order->game_type }}</span>
                        </div>
                    </div>
                    @if($order->purchase_method)
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-sm sm:text-base">Metode Pembelian:</span>
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
                @else
                    @php
                        // Get product image if available
                        $product = null;
                        if ($order->product_name) {
                            $product = \App\Models\Product::where('name', $order->product_name)
                                ->where('game_type', $order->game_type)
                                ->first();
                        }
                    @endphp
                    <div class="space-y-3 sm:space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-sm sm:text-base">Game Type:</span>
                            <span class="text-white/90 text-sm sm:text-base font-medium">{{ $order->game_type }}</span>
                        </div>
                        @if($order->product_name)
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-sm sm:text-base">Product Name:</span>
                            <span class="text-white/90 text-sm sm:text-base font-medium">{{ $order->product_name }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="text-white/60 text-sm sm:text-base mb-2 block">Foto Produk:</span>
                            <div class="flex items-center">
                                @if($product && $product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $order->product_name }}" class="h-20 w-20 rounded-lg object-cover border border-white/10 shadow-md">
                                @elseif($product && $product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $order->product_name }}" class="h-20 w-20 rounded-lg object-cover border border-white/10 shadow-md">
                                @else
                                    <div class="h-20 w-20 rounded-lg bg-gradient-to-br from-gray-500/30 to-gray-600/30 flex items-center justify-center border border-white/10">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Amount:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ number_format($order->amount ?? 0, 0, ',', '.') }} {{ $order->game_type === 'Robux' ? 'R$' : 'items' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Price per Unit:</span>
                    <span class="text-white/90 text-sm sm:text-base">Rp {{ number_format($order->price ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Tax:</span>
                    <span class="text-white/90 text-sm sm:text-base">Rp {{ number_format($order->tax ?? 0, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-white/10 pt-2 sm:pt-3">
                    <span class="text-white/90 font-medium text-sm sm:text-base">Total Amount:</span>
                    <span class="text-white/90 font-bold text-base sm:text-lg">Rp {{ number_format($order->total_amount ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
            <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Payment Information</h3>
            <div class="space-y-2 sm:space-y-3">

                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Order Status:</span>
                    <span class="px-2 py-1 rounded text-xs
                        @if($order->order_status === 'completed') bg-green-600 text-white
                        @elseif($order->order_status === 'pending') bg-yellow-600 text-white
                        @else bg-gray-600 text-white
                        @endif">
                        {{ ucfirst($order->order_status ?? 'Unknown') }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Payment Method:</span>
                    <span class="text-white/90 text-sm sm:text-base">
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
                            $methodName = $methodNames[$order->payment_method] ?? $order->payment_method ?? 'Not specified';
                        @endphp
                        {{ $methodName }}
                    </span>
                </div>
                @if($isMidtrans && $order->payment_reference)
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Midtrans Transaction ID:</span>
                    <span class="text-white/90 text-sm sm:text-base font-mono">{{ $order->payment_reference }}</span>
                </div>
                @elseif(!$isMidtrans && $order->transaction_id)
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Transaction ID:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->transaction_id }}</span>
                </div>
                @endif
                @if($isMidtrans)
                <div class="mt-3 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-blue-200 font-medium text-xs sm:text-sm mb-1">Pembayaran via Midtrans</p>
                            <p class="text-blue-300/80 text-xs">Pembayaran diproses otomatis oleh Midtrans. Tidak ada bukti transfer karena pembayaran otomatis terverifikasi.</p>
                        </div>
                    </div>
                </div>
                @endif
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Order Date:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->created_at->format('M d, Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Last Updated:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->updated_at->format('M d, Y H:i') }}</span>
                </div>
                @if($order->completed_at)
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">
                        @if($order->game_type === 'Robux' && $order->purchase_method === 'group')
                            Harus Dikerjakan Sebelum:
                        @else
                            Akan Selesai:
                        @endif
                    </span>
                    <span class="text-white/90 font-medium text-emerald-400 text-sm sm:text-base">{{ $order->completed_at->format('M d, Y H:i') }}</span>
                </div>
                @endif
            </div>

            <!-- Order Processing Actions -->
            @if($order->payment_status === 'Completed' && $order->order_status === 'pending')
            <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10">
                <h4 class="text-white/90 mb-3 text-sm sm:text-base">Proses Orderan</h4>
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 sm:p-4 mb-3 sm:mb-4">
                    <div class="flex items-start gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-yellow-200 font-medium mb-1 text-sm sm:text-base">Konfirmasi Proses Orderan</p>
                            <p class="text-yellow-300/80 text-xs sm:text-sm">Setelah mengklik tombol "Proses Orderan", order akan langsung diproses dan 
                                @if($order->game_type === 'Robux')
                                    @if($order->purchase_method === 'group')
                                        <span class="font-semibold text-yellow-200">harus dikerjakan dalam 1 jam ke depan</span>. Stok Group akan berkurang.
                                    @else
                                        robux akan masuk ke customer setelah <span class="font-semibold">{{ \App\Models\Setting::getIntValue('auto_complete_days', 5) }} hari</span>. Stok Gamepass akan berkurang.
                                    @endif
                                @else
                                    produk akan dikirim ke customer dalam <span class="font-semibold">5 menit</span>.
                                @endif
                                Pastikan Anda siap untuk memproses order ini.</p>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex flex-col sm:flex-row gap-2">
                    <button type="button" 
                            onclick="showProcessOrderModal()"
                            class="w-full sm:flex-1 inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 font-medium text-xs sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Proses Orderan
                    </button>

                    <form id="reject-order-form" method="POST" action="{{ route('admin.orders.reject', $order) }}" class="w-full sm:flex-1">
                        @csrf
                        <input type="hidden" name="notes" value="Rejected by admin">
                        <button type="button"
                                class="w-full inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition-all duration-200 shadow-lg hover:shadow-red-500/25 font-medium text-xs sm:text-sm"
                                onclick="openRejectOrderModal()">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Tolak Order
                        </button>
                    </form>
                </div>
            </div>

            <div id="reject-order-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
                <div class="w-full max-w-md rounded-xl border border-white/15 bg-[#111827] shadow-xl">
                    <div class="p-5 sm:p-6">
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-lg bg-red-500/15 border border-red-500/30 flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9 9-4.03 9-9-4.03-9-9-9z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <div class="text-white text-lg font-semibold">Tolak Order?</div>
                                <div class="mt-1 text-white/70 text-sm">
                                    Order akan diubah menjadi gagal. Jika stok sudah terpotong, stok akan dikembalikan otomatis.
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:justify-end">
                            <button type="button" class="px-4 py-2 rounded-lg border border-white/15 text-white/80 hover:text-white hover:bg-white/5 transition" onclick="closeRejectOrderModal()">
                                Batal
                            </button>
                            <button type="button" class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition" onclick="submitRejectOrderForm()">
                                Ya, Tolak
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function openRejectOrderModal() {
                    const modal = document.getElementById('reject-order-modal');
                    if (!modal) return;
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                function closeRejectOrderModal() {
                    const modal = document.getElementById('reject-order-modal');
                    if (!modal) return;
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function submitRejectOrderForm() {
                    const form = document.getElementById('reject-order-form');
                    if (!form) return;
                    form.submit();
                }

                document.addEventListener('click', function(e) {
                    const modal = document.getElementById('reject-order-modal');
                    if (!modal) return;
                    if (e.target === modal) {
                        closeRejectOrderModal();
                    }
                });
            </script>
            @elseif($order->order_status === 'completed')
            <div class="mt-4 sm:mt-6 pt-4 sm:pt-6 border-t border-white/10">
                <div class="bg-emerald-500/10 border border-emerald-500/30 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center gap-2 sm:gap-3">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <div>
                            <p class="text-emerald-200 font-medium text-sm sm:text-base">Order Telah Diproses</p>
                            <p class="text-emerald-300/80 text-xs sm:text-sm">Order ini telah selesai diproses dan dikirim ke customer.</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Proof of Payment - Only show for manual payment -->
    @if(!$isMidtrans)
        @if($order->proof_file)
        <div class="mt-6 sm:mt-8 rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
            <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Bukti Pembayaran</h3>
            <div class="space-y-3 sm:space-y-4">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="p-2 sm:p-3 rounded-lg bg-emerald-500/20">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium text-sm sm:text-base">Bukti pembayaran telah diupload</p>
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
                    <a href="{{ route('admin.payments.download-proof', $order) }}" 
                       class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 rounded-xl bg-gradient-to-r from-blue-500/20 to-indigo-500/20 text-blue-200 border border-blue-500/30 hover:from-blue-500/30 hover:to-indigo-500/30 transition-all duration-200 shadow-lg hover:shadow-blue-500/25">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <span class="font-medium text-sm sm:text-base">Download</span>
                    </a>
                </div>
            </div>
        </div>
        @else
        <div class="mt-6 sm:mt-8 rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
            <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Bukti Pembayaran</h3>
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="p-2 sm:p-3 rounded-lg bg-gray-500/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-white/60 font-medium text-sm sm:text-base">Belum ada bukti pembayaran</p>
                    <p class="text-white/40 text-xs sm:text-sm">Customer belum mengupload bukti pembayaran</p>
                </div>
            </div>
        </div>
        @endif
    @else
    <!-- Midtrans Payment Info - No proof needed -->
    <div class="mt-6 sm:mt-8 rounded-md border border-blue-500/30 p-4 sm:p-6 bg-blue-500/10">
        <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Informasi Pembayaran Midtrans</h3>
        <div class="space-y-3 sm:space-y-4">
            <div class="flex items-start gap-3">
                <div class="p-2 sm:p-3 rounded-lg bg-blue-500/20 flex-shrink-0">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-blue-200 font-medium text-sm sm:text-base mb-1">Pembayaran via Midtrans</p>
                    <p class="text-blue-300/80 text-xs sm:text-sm mb-3">Pembayaran ini diproses otomatis oleh Midtrans. Tidak ada bukti transfer karena pembayaran sudah terverifikasi secara otomatis oleh sistem Midtrans.</p>
                    @if($order->payment_reference)
                    <div class="mt-3 p-3 bg-white/5 rounded-lg border border-white/10">
                        <div class="flex justify-between items-center">
                            <span class="text-blue-200/80 text-xs sm:text-sm">Midtrans Transaction ID:</span>
                            <span class="text-white font-mono text-xs sm:text-sm">{{ $order->payment_reference }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- GamePass Information -->
    @if($order->gamepass_link)
    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
        <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">GamePass Information</h3>
        <div class="bg-blue-500/10 border border-blue-500/30 rounded-lg p-3 sm:p-4">
            <div class="flex items-start gap-2 sm:gap-3">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <div>
                    <p class="text-blue-200 font-medium mb-1 text-sm sm:text-base">GamePass Customer</p>
                    <p class="text-blue-300/80 text-xs sm:text-sm mb-2">Customer telah menyediakan GamePass untuk proses top-up Robux.</p>
                    <a href="{{ $order->gamepass_link }}" target="_blank" 
                       class="inline-flex items-center gap-2 px-3 sm:px-4 py-2 bg-blue-500/20 text-blue-200 border border-blue-500/30 rounded-lg hover:bg-blue-500/30 transition-colors text-sm sm:text-base">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Buka GamePass
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    
    <!-- Order Timeline -->
    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
        <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Order Timeline</h3>
        <div class="space-y-3 sm:space-y-4">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-blue-500 rounded-full"></div>
                <div>
                    <div class="text-white/90 font-medium text-sm sm:text-base">Order Dibuat</div>
                    <div class="text-white/60 text-xs sm:text-sm">{{ $order->created_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
            
            @if($order->payment_status === 'Completed')
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-emerald-500 rounded-full"></div>
                <div>
                    <div class="text-white/90 font-medium text-sm sm:text-base">
                        Pembayaran Dikonfirmasi
                        @if($isMidtrans)
                            <span class="ml-2 text-xs text-blue-300">(via Midtrans)</span>
                        @else
                            <span class="ml-2 text-xs text-gray-300">(via Manual)</span>
                        @endif
                    </div>
                    <div class="text-white/60 text-xs sm:text-sm">Status: {{ $order->payment_status }}</div>
                    @if($isMidtrans && $order->payment_reference)
                    <div class="text-white/50 text-xs mt-1 font-mono">Transaction ID: {{ $order->payment_reference }}</div>
                    @endif
                </div>
            </div>
            @endif
            
            @if($order->order_status === 'completed')
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 rounded-full"></div>
                <div>
                    <div class="text-white/90 font-medium text-sm sm:text-base">Order Diproses & Selesai</div>
                    <div class="text-white/60 text-xs sm:text-sm">Status: {{ ucfirst($order->order_status) }}</div>
                    @if($order->completed_at)
                    <div class="text-emerald-400 text-xs sm:text-sm font-medium">Akan selesai: {{ $order->completed_at->format('M d, Y H:i') }}</div>
                    @endif
                </div>
            </div>
            @elseif($order->order_status === 'pending')
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-yellow-500 rounded-full animate-pulse"></div>
                <div>
                    <div class="text-white/90 font-medium text-sm sm:text-base">Menunggu Proses</div>
                    <div class="text-white/60 text-xs sm:text-sm">Status: {{ ucfirst($order->order_status) }}</div>
                </div>
            </div>
            @endif
            
            @if($order->updated_at->ne($order->created_at))
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-gray-500 rounded-full"></div>
                <div>
                    <div class="text-white/90 font-medium text-sm sm:text-base">Terakhir Diupdate</div>
                    <div class="text-white/60 text-xs sm:text-sm">{{ $order->updated_at->format('M d, Y H:i') }}</div>
                </div>
            </div>
            @endif
        </div>
    </div>

    @php
        $adminLogs = isset($adminLogs) && $adminLogs ? $adminLogs : collect();
        $auditNotes = [];
        if (is_array($order->notes)) {
            $auditNotes = $order->notes;
        } elseif (is_string($order->notes) && !empty($order->notes)) {
            $decoded = @json_decode((string) $order->notes, true);
            if (is_array($decoded)) {
                $auditNotes = $decoded;
            }
        }
        $adminActions = is_array($auditNotes['admin_actions'] ?? null) ? $auditNotes['admin_actions'] : [];
    @endphp

    @if($adminLogs->count() > 0 || count($adminActions) > 0)
    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
        <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Riwayat Admin</h3>
        <div class="space-y-2">
            @foreach($adminLogs as $log)
                <div class="flex items-start gap-3 p-3 rounded-lg bg-white/5 border border-white/10">
                    <div class="h-2 w-2 mt-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="text-white/90 text-sm font-medium">
                            {{ $log->action ?? 'action' }}
                        </div>
                        <div class="text-white/60 text-xs">
                            @php
                                $who = trim((string) ($log->admin_name ?? ''));
                                $email = trim((string) ($log->admin_email ?? ''));
                            @endphp
                            @if($who !== '' && $email !== '')
                                {{ $who }} ({{ $email }})
                            @elseif($who !== '')
                                {{ $who }}
                            @elseif($email !== '')
                                {{ $email }}
                            @else
                                Admin
                            @endif
                            @if($log->created_at)
                                • {{ $log->created_at->format('M d, Y H:i') }}
                            @endif
                        </div>
                        @if(!empty($log->notes))
                            <div class="text-white/70 text-xs mt-1">
                                {{ $log->notes }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @foreach(array_reverse($adminActions) as $action)
                <div class="flex items-start gap-3 p-3 rounded-lg bg-white/5 border border-white/10">
                    <div class="h-2 w-2 mt-2 rounded-full bg-blue-400 flex-shrink-0"></div>
                    <div class="flex-1">
                        <div class="text-white/90 text-sm font-medium">
                            {{ $action['action'] ?? 'action' }}
                        </div>
                        <div class="text-white/60 text-xs">
                            @php
                                $who = trim((string) ($action['admin_name'] ?? ''));
                                $email = trim((string) ($action['admin_email'] ?? ''));
                                $at = $action['at'] ?? null;
                            @endphp
                            @if($who !== '' && $email !== '')
                                {{ $who }} ({{ $email }})
                            @elseif($who !== '')
                                {{ $who }}
                            @elseif($email !== '')
                                {{ $email }}
                            @else
                                Admin
                            @endif
                            @if($at)
                                • {{ \Carbon\Carbon::parse($at)->format('M d, Y H:i') }}
                            @endif
                        </div>
                        @if(!empty($action['notes']))
                            <div class="text-white/70 text-xs mt-1">
                                {{ $action['notes'] }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Process Order Modal -->
    <div id="processOrderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-3 sm:p-4">
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl max-w-md w-full p-4 sm:p-6 transform transition-all duration-300 scale-95 opacity-0" id="modalContent">
            <div class="text-center">
                <!-- Icon -->
                <div class="mx-auto w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                
                <!-- Title -->
                <h3 class="text-lg sm:text-xl font-bold text-white mb-2">Konfirmasi Proses Orderan</h3>
                <p class="text-white/70 mb-4 sm:mb-6 text-sm sm:text-base">Apakah Anda yakin ingin memproses orderan ini?</p>
                
                <!-- Order Info -->
                <div class="bg-white/5 border border-white/10 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6 text-left">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-white/60 text-xs sm:text-sm">Order ID:</span>
                        <span class="text-white font-medium text-xs sm:text-sm">#{{ $order->order_id }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-white/60 text-xs sm:text-sm">Customer:</span>
                        <span class="text-white font-medium text-xs sm:text-sm">{{ $order->username }}</span>
                    </div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-white/60 text-xs sm:text-sm">Amount:</span>
                        <span class="text-white font-medium text-xs sm:text-sm">{{ number_format($order->amount ?? 0, 0, ',', '.') }} R$</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-white/60 text-xs sm:text-sm">
                            @if($order->game_type === 'Robux' && $order->purchase_method === 'group')
                                Harus dikerjakan:
                            @else
                                Akan selesai:
                            @endif
                        </span>
                        <span class="text-emerald-400 font-medium text-xs sm:text-sm">
                            @if($order->game_type === 'Robux')
                                @if($order->purchase_method === 'group')
                                    1 jam
                                @else
                                    {{ (int) \App\Models\Setting::getValue('auto_complete_days', 5) }} hari
                                @endif
                            @else
                                5 menit
                            @endif
                        </span>
                    </div>
                </div>
                
                <!-- Warning -->
                <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-3 mb-4 sm:mb-6">
                    <div class="flex items-start gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <p class="text-yellow-200 text-xs sm:text-sm">Setelah dikonfirmasi, order akan langsung diproses dan 
                            @if($order->game_type === 'Robux')
                                @if($order->purchase_method === 'group')
                                    <span class="font-semibold">Harus dikerjakan dalam 1 jam ke depan</span>. Stok Group akan berkurang.
                                @else
                                    Robux akan masuk ke customer setelah {{ (int) \App\Models\Setting::getValue('auto_complete_days', 5) }} hari. Stok Gamepass akan berkurang.
                                @endif
                            @else
                                produk akan dikirim ke customer dalam 5 menit.
                            @endif
                        </p>
                    </div>
                </div>
                
                <!-- Buttons -->
                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                    <button type="button" 
                            onclick="hideProcessOrderModal()"
                            class="flex-1 px-3 sm:px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors text-sm sm:text-base">
                        Batal
                    </button>
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" id="processOrderForm" class="flex-1">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="order_status" value="completed">
                        <button type="button" 
                                onclick="confirmProcessOrder()"
                                class="w-full px-3 sm:px-4 py-2 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 font-medium text-sm sm:text-base">
                            Ya, Proses Orderan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
function showProcessOrderModal() {
    const modal = document.getElementById('processOrderModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    
    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);
}

function hideProcessOrderModal() {
    const modal = document.getElementById('processOrderModal');
    const modalContent = document.getElementById('modalContent');
    
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function confirmProcessOrder() {
    // Show loading state
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = `
        <svg class="w-4 h-4 animate-spin mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Memproses...
    `;
    
    // Submit form
    document.getElementById('processOrderForm').submit();
}

// Close modal when clicking outside
document.getElementById('processOrderModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideProcessOrderModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideProcessOrderModal();
    }
});
</script>
@endsection
