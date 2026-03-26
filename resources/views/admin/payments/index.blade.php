@extends('layouts.app')
@section('title', 'Admin • Payments')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Payment Management</h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">
            @if(request('status') === 'all')
                Menampilkan: Semua Status
            @elseif(request('status'))
                Menampilkan: {{ ucfirst(str_replace('_', ' ', request('status'))) }}
            @else
                Menampilkan: Menunggu Konfirmasi (Default)
            @endif
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white/5 rounded-lg p-4 sm:p-6 border border-white/10">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-yellow-500/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-white/60 text-xs sm:text-sm">Pending Confirmation</p>
                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $allOrders->where('payment_status', 'waiting_confirmation')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/5 rounded-lg p-4 sm:p-6 border border-white/10">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-emerald-500/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-white/60 text-xs sm:text-sm">Uang Bersih (Completed)</p>
                    <p class="text-xl sm:text-2xl font-bold text-white">Rp {{ number_format($allOrders->where('payment_status', 'Completed')->sum('total_amount'), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white/5 rounded-lg p-4 sm:p-6 border border-white/10">
            <div class="flex items-center">
                <div class="p-2 sm:p-3 rounded-lg bg-red-500/20">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div class="ml-3 sm:ml-4">
                    <p class="text-white/60 text-xs sm:text-sm">Ditolak (Failed)</p>
                    <p class="text-xl sm:text-2xl font-bold text-white">{{ $allOrders->where('payment_status', 'Failed')->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('admin.payments') }}" class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
        <input 
            name="search" 
            value="{{ request('search') }}"
            class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white placeholder-white/30 flex-1 text-sm sm:text-base" 
            placeholder="Cari username atau ID order" 
        />
        <select 
            name="status" 
            class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base"
        >
            <option value="">Menunggu Konfirmasi (Default)</option>
            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>Semua Status</option>
            <option value="waiting_confirmation" {{ request('status') === 'waiting_confirmation' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Ditolak</option>
        </select>
        <div class="flex gap-2 sm:gap-3">
            <input 
                name="date_from" 
                type="date" 
                value="{{ request('date_from') }}"
                class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base flex-1" 
            />
            <input 
                name="date_to" 
                type="date" 
                value="{{ request('date_to') }}"
                class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base flex-1" 
            />
        </div>
        <div class="flex gap-2 sm:gap-3">
            <button type="submit" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-white text-black text-sm sm:text-base">Cari</button>
            <a href="{{ route('admin.payments') }}" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-gray-600 text-white text-sm sm:text-base">Bersihkan</a>
        </div>
    </form>

    @if(session('success'))
        <div class="mt-4 p-4 bg-green-600 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 bg-white/[0.02]">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
            <table class="w-full text-left min-w-[700px]">
                <thead class="text-white/60">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Order ID</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm hidden lg:table-cell">Username</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Game Type</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Quantity</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Price</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Status</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Proof</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-white/80">
                    @forelse($orders as $order)
                    <tr>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            <div class="font-mono text-xs sm:text-sm text-white">#{{ $order->order_id }}</div>
                            <div class="text-xs text-white/50 mt-1">{{ $order->created_at->format('M d, Y H:i') }}</div>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3 hidden lg:table-cell">
                            <div class="text-white font-medium text-sm">{{ $order->username }}</div>
                            <div class="text-xs text-white/50">{{ $order->email ?? 'No email' }}</div>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            @if($order->game_type === 'Robux')
                                <div class="flex items-center gap-2 flex-wrap">
                                    <div class="flex items-center gap-1.5">
                                        <img src="/assets/images/robux.png" alt="Robux" class="h-4 w-4 flex-shrink-0">
                                        <span class="text-xs sm:text-sm text-white font-medium">{{ $order->game_type }}</span>
                                    </div>
                                    @if($order->purchase_method === 'group')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/40 text-purple-200 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            Group
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-emerald-500/20 to-blue-500/20 border border-emerald-500/40 text-emerald-200 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                            </svg>
                                            Gamepass
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div>
                                    <div class="text-white font-medium text-xs sm:text-sm">{{ $order->product_name ?? $order->game_type }}</div>
                                    <div class="text-xs text-white/50">{{ $order->game_type }}</div>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            <div class="text-xs sm:text-sm">{{ number_format($order->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            <div class="text-xs sm:text-sm">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
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
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            @if($order->proof_file)
                                <a href="{{ route('admin.payments.download-proof', $order) }}" target="_blank" 
                                   class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-emerald-500/20 to-teal-500/20 text-emerald-200 border border-emerald-500/30 hover:from-emerald-500/30 hover:to-teal-500/30 transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="font-medium text-xs sm:text-sm hidden sm:inline">Lihat</span>
                                </a>
                            @else
                                <span class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gray-500/20 text-gray-400 border border-gray-500/30">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    <span class="text-xs sm:text-sm hidden sm:inline">Tidak ada</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            <div class="flex gap-1 sm:gap-2">
                                <a href="{{ route('admin.payments.show', $order) }}" 
                                   class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-blue-500/20 to-indigo-500/20 text-blue-200 border border-blue-500/30 hover:from-blue-500/30 hover:to-indigo-500/30 transition-all duration-200 shadow-lg hover:shadow-blue-500/25">
                                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                    <span class="font-medium text-xs sm:text-sm hidden sm:inline">Review</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-2 sm:px-4 py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center gap-3 sm:gap-4">
                                <div class="p-3 sm:p-4 rounded-full bg-gray-500/20">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/60 text-base sm:text-lg font-medium">Tidak ada pembayaran ditemukan</p>
                                    <p class="text-white/40 text-xs sm:text-sm mt-1">Semua pembayaran telah diproses atau belum ada yang masuk</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-2 sm:px-4 py-3 sm:py-4 border-t border-white/10">
            <div class="flex items-center justify-between">
                <div class="text-xs sm:text-sm text-white/60">
                    Menampilkan {{ $orders->firstItem() ?? 0 }} - {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} hasil
                </div>
                <div class="flex items-center space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($orders->onFirstPage())
                        <span class="px-2 py-1 text-xs sm:text-sm text-white/30 bg-white/5 rounded cursor-not-allowed">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                    @else
                        <a href="{{ $orders->appends(request()->query())->previousPageUrl() }}" class="px-2 py-1 text-xs sm:text-sm text-white hover:text-emerald-400 bg-white/10 hover:bg-white/20 rounded transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                    @endif

                    {{-- Pagination Elements with smart pagination --}}
                    @php
                        $currentPage = $orders->currentPage();
                        $lastPage = $orders->lastPage();
                        $onEachSide = 2; // Show 2 pages on each side of current page
                    @endphp
                    
                    {{-- First page --}}
                    @if ($currentPage > $onEachSide + 1)
                        <a href="{{ $orders->appends(request()->query())->url(1) }}" class="px-2 py-1 text-xs sm:text-sm text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded transition-colors">1</a>
                        @if ($currentPage > $onEachSide + 2)
                            <span class="px-2 py-1 text-xs sm:text-sm text-white/30">...</span>
                        @endif
                    @endif
                    
                    {{-- Pages around current page --}}
                    @for ($page = max(1, $currentPage - $onEachSide); $page <= min($lastPage, $currentPage + $onEachSide); $page++)
                        @if ($page == $currentPage)
                            <span class="px-2 py-1 text-xs sm:text-sm text-emerald-400 bg-emerald-500/20 rounded font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $orders->appends(request()->query())->url($page) }}" class="px-2 py-1 text-xs sm:text-sm text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded transition-colors">{{ $page }}</a>
                        @endif
                    @endfor
                    
                    {{-- Last page --}}
                    @if ($currentPage < $lastPage - $onEachSide)
                        @if ($currentPage < $lastPage - $onEachSide - 1)
                            <span class="px-2 py-1 text-xs sm:text-sm text-white/30">...</span>
                        @endif
                        <a href="{{ $orders->appends(request()->query())->url($lastPage) }}" class="px-2 py-1 text-xs sm:text-sm text-white/70 hover:text-white bg-white/5 hover:bg-white/10 rounded transition-colors">{{ $lastPage }}</a>
                    @endif

                    {{-- Next Page Link --}}
                    @if ($orders->hasMorePages())
                        <a href="{{ $orders->appends(request()->query())->nextPageUrl() }}" class="px-2 py-1 text-xs sm:text-sm text-white hover:text-emerald-400 bg-white/10 hover:bg-white/20 rounded transition-colors">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    @else
                        <span class="px-2 py-1 text-xs sm:text-sm text-white/30 bg-white/5 rounded cursor-not-allowed">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</main>
@endsection
