@extends('layouts.app')
@section('title', 'Admin • Reports')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Laporan & Analisis</h1>
        <p class="text-white/60 mt-2">Analisis pemasukan, keuntungan, dan performa bisnis</p>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white/5 border border-white/10 rounded-xl p-6 mb-8">
        <!-- Quick Date Presets -->
        <div class="flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('admin.reports', ['start_date' => now()->startOfWeek()->format('Y-m-d'), 'end_date' => now()->endOfWeek()->format('Y-m-d')]) }}" 
               class="px-3 py-2 text-xs bg-white/10 text-white/80 rounded-lg hover:bg-white/20 transition-colors">
                Minggu Ini
            </a>
            <a href="{{ route('admin.reports', ['start_date' => now()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->endOfMonth()->format('Y-m-d')]) }}" 
               class="px-3 py-2 text-xs bg-white/10 text-white/80 rounded-lg hover:bg-white/20 transition-colors">
                Bulan Ini
            </a>
            <a href="{{ route('admin.reports', ['start_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'), 'end_date' => now()->subMonth()->endOfMonth()->format('Y-m-d')]) }}" 
               class="px-3 py-2 text-xs bg-white/10 text-white/80 rounded-lg hover:bg-white/20 transition-colors">
                Bulan Lalu
            </a>
            <a href="{{ route('admin.reports', ['start_date' => now()->subDays(7)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
               class="px-3 py-2 text-xs bg-white/10 text-white/80 rounded-lg hover:bg-white/20 transition-colors">
                7 Hari Terakhir
            </a>
            <a href="{{ route('admin.reports', ['start_date' => now()->subDays(30)->format('Y-m-d'), 'end_date' => now()->format('Y-m-d')]) }}" 
               class="px-3 py-2 text-xs bg-white/10 text-white/80 rounded-lg hover:bg-white/20 transition-colors">
                30 Hari Terakhir
            </a>
        </div>
        
        <form method="GET" action="{{ route('admin.reports') }}" class="flex gap-4 flex-wrap items-end">
            <div class="flex-1 min-w-48">
                <label class="block text-white/80 text-sm font-medium mb-2">Tanggal Mulai</label>
                <input 
                    type="date" 
                    name="start_date" 
                    value="{{ $startDate }}"
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 text-white"
                />
            </div>
            <div class="flex-1 min-w-48">
                <label class="block text-white/80 text-sm font-medium mb-2">Tanggal Akhir</label>
                <input 
                    type="date" 
                    name="end_date" 
                    value="{{ $endDate }}"
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/10 text-white"
                />
            </div>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Filter
            </button>
        </form>
    </div>

    <!-- Main Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-emerald-500/20 to-green-500/20 border border-emerald-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-emerald-200/80 text-sm font-medium">Total Pemasukan</p>
                    <p class="text-3xl font-bold text-emerald-200 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-emerald-300/60 text-xs mt-1">Periode terpilih</p>
                </div>
                <div class="p-4 bg-emerald-500/20 rounded-xl">
                    <svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-200/80 text-sm font-medium">Total Order</p>
                    <p class="text-3xl font-bold text-blue-200 mt-1">{{ number_format($stats['total_orders']) }}</p>
                    <p class="text-blue-300/60 text-xs mt-1">Semua status</p>
                </div>
                <div class="p-4 bg-blue-500/20 rounded-xl">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-200/80 text-sm font-medium">Robux Orders</p>
                    <p class="text-3xl font-bold text-yellow-200 mt-1">{{ number_format($stats['robux_orders']) }}</p>
                    <p class="text-yellow-300/60 text-xs mt-1">Rp {{ number_format($stats['robux_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-yellow-500/20 rounded-xl">
                    <img src="/assets/images/robux.png" alt="Robux" class="w-8 h-8">
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-purple-500/30 rounded-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-200/80 text-sm font-medium">Other Products</p>
                    <p class="text-3xl font-bold text-purple-200 mt-1">{{ number_format($stats['other_orders']) }}</p>
                    <p class="text-purple-300/60 text-xs mt-1">Rp {{ number_format($stats['other_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="p-4 bg-purple-500/20 rounded-xl">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Detailed Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Daily Revenue Chart -->
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white/90 mb-6">Pemasukan Harian</h3>
            <div class="h-80 overflow-x-auto">
                <div class="h-64 flex items-end gap-2 min-w-max px-2">
                    @php
                        $maxRevenue = max(array_column($dailyRevenue, 'revenue'));
                        $isWeekly = str_contains($dailyRevenue[0]['date'] ?? '', ' - ');
                    @endphp
                    @foreach($dailyRevenue as $index => $day)
                        <div class="flex flex-col items-center min-w-0 flex-shrink-0" style="width: {{ count($dailyRevenue) > 20 ? '24px' : (count($dailyRevenue) > 10 ? '32px' : '40px') }};">
                            <div 
                                class="w-full bg-gradient-to-t from-blue-500 to-indigo-500 rounded-t-sm transition-all duration-300 hover:from-blue-400 hover:to-indigo-400 cursor-pointer"
                                style="height: {{ $maxRevenue > 0 ? ($day['revenue'] / $maxRevenue) * 180 : 2 }}px"
                                title="{{ $day['date'] }}: Rp {{ number_format($day['revenue'], 0, ',', '.') }}"
                            ></div>
                            @if($isWeekly || $index % 2 === 0 || count($dailyRevenue) <= 10)
                                <span class="text-xs text-white/60 mt-2 text-center leading-tight whitespace-nowrap">
                                    {{ $isWeekly ? explode(' - ', $day['date'])[0] : $day['date'] }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @if(count($dailyRevenue) > 10)
                <div class="mt-4 text-center">
                    <p class="text-xs text-white/50">
                        @if($isWeekly)
                            Data ditampilkan per minggu
                        @else
                            Geser ke kanan untuk melihat data lebih lengkap
                        @endif
                    </p>
                </div>
            @endif
        </div>

        <!-- Order Status Distribution -->
        <div class="bg-white/5 border border-white/10 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-white/90 mb-6">Distribusi Status Order</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-emerald-500 rounded-full"></div>
                        <span class="text-white/80">Completed</span>
                    </div>
                    <div class="text-right">
                        <div class="text-emerald-400 font-semibold">{{ number_format($stats['completed_orders']) }}</div>
                        <div class="text-white/60 text-sm">
                            {{ $stats['total_orders'] > 0 ? round(($stats['completed_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                        <span class="text-white/80">Pending</span>
                    </div>
                    <div class="text-right">
                        <div class="text-yellow-400 font-semibold">{{ number_format($stats['pending_orders']) }}</div>
                        <div class="text-white/60 text-sm">
                            {{ $stats['total_orders'] > 0 ? round(($stats['pending_orders'] / $stats['total_orders']) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Customers -->
    <div class="bg-white/5 border border-white/10 rounded-xl p-6">
        <h3 class="text-xl font-semibold text-white/90 mb-6">Top 10 Pelanggan</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="text-white/60">
                    <tr>
                        <th class="px-4 py-3">Rank</th>
                        <th class="px-4 py-3">Username</th>
                        <th class="px-4 py-3">Total Order</th>
                        <th class="px-4 py-3">Total Pemasukan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-white/80">
                    @forelse($topCustomers as $index => $customer)
                    <tr>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                @if($index < 3)
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                        @if($index === 0) bg-yellow-500 text-black
                                        @elseif($index === 1) bg-gray-400 text-black
                                        @else bg-orange-500 text-black
                                        @endif">
                                        {{ $index + 1 }}
                                    </div>
                                @else
                                    <span class="text-white/60">#{{ $index + 1 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3 font-medium">{{ $customer['username'] }}</td>
                        <td class="px-4 py-3">{{ number_format($customer['total_orders']) }}</td>
                        <td class="px-4 py-3 font-semibold text-emerald-400">Rp {{ number_format($customer['total_revenue'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-white/60">
                            Tidak ada data pelanggan dalam periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection