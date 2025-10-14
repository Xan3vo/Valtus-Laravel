@extends('layouts.app')
@section('title', 'Admin • Dashboard')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Admin Dashboard</h1>
        <p class="text-white/60 mt-2">Welcome to the Valtus Admin Panel</p>
    </div>

    <div class="mt-8 grid md:grid-cols-4 gap-4">
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/60 text-sm">Total Revenue</div>
                    <div class="mt-2 text-2xl text-white/90">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/60 text-sm">Completed Orders</div>
                    <div class="mt-2 text-2xl text-white/90">{{ $totalOrders }}</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-green-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/60 text-sm">Pending Orders</div>
                    <div class="mt-2 text-2xl text-white/90">{{ $pendingOrders }}</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-yellow-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-white/60 text-sm">Today's Orders</div>
                    <div class="mt-2 text-2xl text-white/90">{{ $todayOrders }}</div>
                </div>
                <div class="h-12 w-12 rounded-lg bg-blue-500/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 grid md:grid-cols-2 gap-8">
        <div class="rounded-md border border-white/10 p-6 bg-white/[0.02]">
            <h3 class="text-xl font-medium text-white/90 mb-4">Recent Orders</h3>
            <div class="space-y-3">
                @forelse($recentOrders as $order)
                <div class="flex justify-between items-center p-3 bg-white/[0.05] rounded-md">
                    <div>
                        <div class="text-white/90 font-medium">{{ $order->username }}</div>
                        <div class="text-white/60 text-sm">{{ $order->game_type }} - {{ $order->amount }} items</div>
                    </div>
                    <div class="text-right">
                        <div class="text-white/90 font-medium">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                        <div class="text-green-400 text-sm">{{ $order->payment_status }}</div>
                    </div>
                </div>
                @empty
                <div class="text-white/60 text-center py-8">No recent orders</div>
                @endforelse
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center gap-2">
                <img src="/assets/images/robux.png" alt="Robux" class="h-6 w-6">
                Current Settings
            </h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/90 font-medium">Per 1 Robux</div>
                    <div class="text-emerald-400 font-bold">Rp {{ number_format(($settings['robux_price_per_100'] ?? '10000') / 100, 0, ',', '.') }}</div>
                </div>
                <div class="flex justify-between items-center p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/90 font-medium">Per 100 Robux</div>
                    <div class="text-emerald-400 font-bold">Rp {{ number_format($settings['robux_price_per_100'] ?? '10000', 0, ',', '.') }}</div>
                </div>
                <div class="flex justify-between items-center p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/90 font-medium">GamePass Tax</div>
                    <div class="text-emerald-400 font-bold">{{ $settings['gamepass_tax_rate'] ?? '30' }}%</div>
                </div>
                <div class="flex justify-between items-center p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/90 font-medium">Minimal Purchase</div>
                    <div class="text-emerald-400 font-bold">Rp {{ number_format($settings['minimal_purchase'], 0, ',', '.') }}</div>
                </div>
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('admin.robux-pricing') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2">
                    <img src="/assets/images/robux.png" alt="Robux" class="h-4 w-4">
                    Robux Pricing
                </a>
                <a href="{{ route('admin.products') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    Manage Products
                </a>
                <a href="{{ route('admin.settings') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                    General Settings
                </a>
            </div>
        </div>
    </div>
</main>
@endsection