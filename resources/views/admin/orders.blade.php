@extends('layouts.app')
@section('title', 'Admin • Orders')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Orders Management</h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">
            @if(request('status') === 'completed')
                Menampilkan: Completed Orders
            @elseif(request('status') === 'pending' || !request('status'))
                Menampilkan: Pending Orders (Default)
            @else
                Menampilkan: Semua Status
            @endif
        </p>
    </div>

    <!-- Order Type Tabs -->
    <div class="mb-4 sm:mb-6">
        <div class="flex space-x-1 bg-white/5 rounded-lg p-1">
            <a href="{{ route('admin.orders', array_merge(['type' => 'robux'], request()->except(['type', 'page']))) }}" 
               class="flex-1 px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium text-center transition-colors {{ $gameType === 'robux' ? 'bg-white text-black' : 'text-white/70 hover:text-white' }}">
                <div class="flex items-center justify-center gap-1 sm:gap-2">
                    <img src="/assets/images/robux.png" alt="Robux" class="h-3 w-3 sm:h-4 sm:w-4">
                    <span class="hidden sm:inline">Robux Orders</span>
                    <span class="sm:hidden">Robux</span>
                </div>
            </a>
            <a href="{{ route('admin.orders', array_merge(['type' => 'other'], request()->except(['type', 'page']))) }}" 
               class="flex-1 px-3 sm:px-4 py-2 rounded-md text-xs sm:text-sm font-medium text-center transition-colors {{ $gameType === 'other' ? 'bg-white text-black' : 'text-white/70 hover:text-white' }}">
                <div class="flex items-center justify-center gap-1 sm:gap-2">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <span class="hidden sm:inline">Other Products</span>
                    <span class="sm:hidden">Other</span>
                </div>
            </a>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('admin.orders') }}" class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
        <input type="hidden" name="type" value="{{ $gameType }}">
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
            <option value="pending" {{ request('status') === 'pending' || !request('status') ? 'selected' : '' }}>Pending</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="">Semua Status</option>
        </select>
        @if($gameType === 'robux')
        <select 
            name="purchase_method" 
            class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base"
        >
            <option value="" {{ request('purchase_method') ? '' : 'selected' }}>Semua Metode</option>
            <option value="gamepass" {{ request('purchase_method') === 'gamepass' ? 'selected' : '' }}>Gamepass</option>
            <option value="group" {{ request('purchase_method') === 'group' ? 'selected' : '' }}>Group</option>
        </select>
        @endif
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
            <a href="{{ route('admin.orders', ['type' => $gameType]) }}" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-gray-600 text-white text-sm sm:text-base">Bersihkan</a>
        </div>
    </form>

    @if(session('success'))
        <div class="mt-4 p-4 bg-green-600 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 bg-white/[0.02]">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
            <table class="w-full text-left min-w-[600px]">
                <thead class="text-white/60">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Order ID</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm hidden lg:table-cell">Username</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Game Type</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Quantity</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Price</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Status</th>
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
                            @php
                            // Check payment gateway from database column
                            $isMidtrans = $order->payment_gateway === 'midtrans';
                            @endphp
                            
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
                                    @if($isMidtrans)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-500/40 text-blue-200 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                            </svg>
                                            Midtrans
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-gray-500/20 to-slate-500/20 border border-gray-500/40 text-gray-200 shadow-sm">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Manual
                                        </span>
                                    @endif
                                </div>
                            @else
                                <div>
                                    <div class="text-white font-medium text-xs sm:text-sm">{{ $order->product_name ?? $order->game_type }}</div>
                                    <div class="text-xs text-white/50 flex items-center gap-2 mt-1">
                                        <span>{{ $order->game_type }}</span>
                                        @if($isMidtrans)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-500/40 text-blue-200 shadow-sm">
                                                Midtrans
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-medium bg-gradient-to-r from-gray-500/20 to-slate-500/20 border border-gray-500/40 text-gray-200 shadow-sm">
                                                Manual
                                            </span>
                                        @endif
                                    </div>
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
                            @if($order->order_status === 'completed')
                                <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-emerald-500/20 to-green-500/20 text-emerald-200 border border-emerald-500/30 shadow-lg">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 bg-emerald-400 rounded-full"></div>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-xs sm:text-sm">Selesai</span>
                                        @if($order->completed_at)
                                        <span class="text-xs text-emerald-300/80 hidden sm:block">{{ $order->completed_at->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @elseif($order->order_status === 'pending')
                                <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-yellow-500/20 to-orange-500/20 text-yellow-200 border border-yellow-500/30 shadow-lg">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 bg-yellow-400 rounded-full animate-pulse"></div>
                                    <span class="font-semibold text-xs sm:text-sm">Pending</span>
                                </div>
                            @else
                                <div class="inline-flex items-center gap-1 sm:gap-2 px-2 sm:px-4 py-1 sm:py-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-gray-500/20 to-slate-500/20 text-gray-200 border border-gray-500/30 shadow-lg">
                                    <div class="h-2 w-2 sm:h-3 sm:w-3 bg-gray-400 rounded-full"></div>
                                    <span class="font-semibold text-xs sm:text-sm">{{ ucfirst($order->order_status ?? 'Unknown') }}</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-2 sm:px-4 py-2 sm:py-3">
                            <div class="flex gap-1 sm:gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" 
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
                        <td colspan="7" class="px-2 sm:px-4 py-8 sm:py-12 text-center">
                            <div class="flex flex-col items-center gap-3 sm:gap-4">
                                <div class="p-3 sm:p-4 rounded-full bg-gray-500/20">
                                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-white/60 text-base sm:text-lg font-medium">Tidak ada order ditemukan</p>
                                    <p class="text-white/40 text-xs sm:text-sm mt-1">Semua order yang ditampilkan sudah memiliki status pembayaran completed</p>
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