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

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
        <!-- Order Information -->
        <div class="rounded-md border border-white/10 p-4 sm:p-6 bg-white/[0.02]">
            <h3 class="text-lg sm:text-xl font-medium text-white/90 mb-3 sm:mb-4">Order Information</h3>
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
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Game Type:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->game_type }}</span>
                </div>
                @if($order->product_name && $order->game_type !== 'Robux')
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Product Name:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->product_name }}</span>
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
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->payment_method ?? 'Not specified' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-sm sm:text-base">Transaction ID:</span>
                    <span class="text-white/90 text-sm sm:text-base">{{ $order->transaction_id ?? 'Not provided' }}</span>
                </div>
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
                    <span class="text-white/60 text-sm sm:text-base">Akan Selesai:</span>
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
                                    robux akan masuk ke customer setelah <span class="font-semibold">{{ \App\Models\Setting::getIntValue('auto_complete_days', 5) }} hari</span>.
                                @else
                                    produk akan dikirim ke customer dalam <span class="font-semibold">15 jam</span>.
                                @endif
                                Pastikan Anda siap untuk memproses order ini.</p>
                        </div>
                    </div>
                </div>
                <button type="button" 
                        onclick="showProcessOrderModal()"
                        class="inline-flex items-center gap-2 px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 font-medium text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Proses Orderan
                </button>
            </div>
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
                    <div class="text-white/90 font-medium text-sm sm:text-base">Pembayaran Dikonfirmasi</div>
                    <div class="text-white/60 text-xs sm:text-sm">Status: {{ $order->payment_status }}</div>
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
                        <span class="text-white/60 text-xs sm:text-sm">Akan selesai:</span>
                        <span class="text-emerald-400 font-medium text-xs sm:text-sm">
                            @if($order->game_type === 'Robux')
                                {{ (int) \App\Models\Setting::getValue('auto_complete_days', 5) }} hari
                            @else
                                15 jam
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
                                Robux akan masuk ke customer setelah {{ (int) \App\Models\Setting::getValue('auto_complete_days', 5) }} hari.
                            @else
                                produk akan dikirim ke customer dalam 15 jam.
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
