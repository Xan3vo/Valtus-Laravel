@extends('layouts.app')
@section('title', 'Cek Status Pesanan')
@section('body')

<header class="sticky top-0 z-50 backdrop-blur-md bg-gray-900/80 border-b border-white/10 shadow-lg">
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
        
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beranda</a>
            <a href="{{ route('user.search') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beli Robux</a>
            <a href="{{ route('user.status') }}" class="text-emerald-400 hover:text-emerald-300 transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="#" onclick="showHelpModal()" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
        </nav>
        
        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4">
            <!-- Admin access removed for security -->
        </div>
        
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/10 bg-gray-900/95 backdrop-blur-md">
        <div class="px-4 py-4 space-y-4">
            <a href="{{ route('home') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beranda</a>
            <a href="{{ route('user.search') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beli Robux</a>
            <a href="#" onclick="showHelpModal()" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
           
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-3">Cek Status Pesanan</h1>
        <p class="text-white/70 text-sm sm:text-base">Cari pesanan Anda menggunakan ID order atau username</p>
    </div>

    <!-- Search Form -->
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6 sm:p-8 mb-8">
        <form method="GET" action="{{ route('user.status.search') }}" class="space-y-6">
            <!-- Search Input -->
            <div class="space-y-2">
                <label class="block text-white/80 text-sm font-medium">Cari Pesanan</label>
                <div class="relative">
                    <input 
                        type="text" 
                        name="query" 
                        value="{{ $searchQuery ?? '' }}"
                        class="w-full px-4 py-3 pl-12 rounded-xl bg-black/30 border border-white/15 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200" 
                        placeholder="Masukkan ID order atau username..."
                        required
                    >
                    <div class="absolute left-4 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Search Type Selection -->
            <div class="space-y-3">
                <label class="block text-white/80 text-sm font-medium">Cari berdasarkan</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="id" {{ ($searchType ?? 'id') === 'id' ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-4 rounded-xl border border-white/15 bg-white/5 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-medium text-sm">ID Order</div>
                                    <div class="text-white/60 text-xs">Cari dengan ID pesanan</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="relative cursor-pointer">
                        <input type="radio" name="type" value="username" {{ ($searchType ?? '') === 'username' ? 'checked' : '' }} class="sr-only peer">
                        <div class="p-4 rounded-xl border border-white/15 bg-white/5 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-medium text-sm">Username</div>
                                    <div class="text-white/60 text-xs">Cari dengan username</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>
            
            <!-- Search Button -->
            <button type="submit" class="w-full sm:w-auto sm:px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-medium transition-all duration-200 shadow-lg hover:shadow-emerald-500/25 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cari Pesanan
            </button>
        </form>
    </div>

    <!-- Error Message -->
    @if(isset($error))
    <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-6 mb-8">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-red-300 font-medium">{{ $error }}</div>
        </div>
    </div>
    @endif

    <!-- Single Order Result -->
    @if(isset($order) && $searchType === 'id')
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-semibold text-white mb-6">Detail Pesanan</h2>
        @include('user.partials.order-detail', ['order' => $order])
    </div>
    @endif

    <!-- Multiple Orders Result -->
    @if(isset($orders) && $searchType === 'username')
    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-semibold text-white mb-6">
            Ditemukan {{ $orders->count() }} pesanan untuk "{{ $searchQuery }}"
        </h2>
        
        <div class="grid gap-4 sm:gap-6">
            @foreach($orders as $order)
            <div class="rounded-xl border border-white/10 p-4 sm:p-6 hover:border-emerald-500/50 hover:bg-white/5 transition-all duration-200 cursor-pointer group" onclick="window.location.href='{{ route('user.status.show', $order->order_id) }}'">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @if($order->game_type === 'Robux')
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                <img src="/assets/images/robux.png" class="h-6 w-6" alt="Robux">
                            </div>
                            <div>
                                <div class="text-white font-semibold text-lg">{{ number_format($order->amount, 0, ',', '.') }} Robux</div>
                                <div class="text-white/60 text-sm">Order ID: {{ $order->order_id }}</div>
                                <div class="text-white/50 text-xs mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        @else
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center group-hover:scale-105 transition-transform duration-200">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-white font-semibold text-lg">{{ $order->product_name ?? $order->game_type }}</div>
                                <div class="text-white/60 text-sm">{{ $order->game_type }} • Order ID: {{ $order->order_id }}</div>
                                <div class="text-white/50 text-xs mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col sm:items-end gap-2">
                        <div class="text-white font-bold text-lg">Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                        <div class="text-sm">
                            @php
                                // Determine actual order status based on completed_at
                                $isActuallyCompleted = false;
                                $timeRemaining = null;

                                if (strtolower($order->order_status) === 'completed' && $order->completed_at && strtolower($order->payment_status) === 'completed') {
                                    $completedAt = \Carbon\Carbon::parse($order->completed_at);
                                    $now = \Carbon\Carbon::now();
                                    
                                    if ($now->gte($completedAt)) {
                                        $isActuallyCompleted = true;
                                    } else {
                                        $timeRemaining = $now->diff($completedAt);
                                    }
                                }
                            @endphp
                            
                            @if($isActuallyCompleted)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <div class="h-2 w-2 bg-emerald-400 rounded-full"></div>
                                    Selesai
                                </span>
                            @elseif($order->payment_status === 'pending')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                    <div class="h-2 w-2 bg-yellow-400 rounded-full"></div>
                                    Menunggu Pembayaran
                                </span>
                            @elseif($order->payment_status === 'waiting_confirmation')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-500/20 text-blue-300 border border-blue-500/30">
                                    <div class="h-2 w-2 bg-blue-400 rounded-full"></div>
                                    Menunggu Konfirmasi
                                </span>
                            @elseif($order->payment_status === 'completed')
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30">
                                        <div class="h-2 w-2 bg-orange-400 rounded-full animate-pulse"></div>
                                        Sedang diproses
                                    </span>
                                    <div class="text-xs text-orange-200">
                                        @if($order->game_type === 'Robux')
                                            Sedang diproses - Robux akan masuk
                                        @else
                                            Sedang diproses - Item akan dikirim
                                        @endif
                                    </div>
                                    @if($timeRemaining && ($timeRemaining->days > 0 || $timeRemaining->h > 0 || $timeRemaining->i > 0))
                                        <div class="text-xs text-orange-300">
                                            @if($timeRemaining->days > 0)
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->days }} hari
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->days }} hari
                                                @endif
                                            @elseif($timeRemaining->h > 0)
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->h }} jam
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->h }} jam
                                                @endif
                                            @else
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->i }} menit
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->i }} menit
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @elseif($order->payment_status === 'failed')
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-red-500/20 text-red-300 border border-red-500/30">
                                    <div class="h-2 w-2 bg-red-400 rounded-full"></div>
                                    Gagal
                                </span>
                            @else
                                <div class="space-y-1">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-orange-500/20 text-orange-300 border border-orange-500/30">
                                        <div class="h-2 w-2 bg-orange-400 rounded-full animate-pulse"></div>
                                        Sedang diproses
                                    </span>
                                    
                                    @if($timeRemaining && ($timeRemaining->days > 0 || $timeRemaining->h > 0 || $timeRemaining->i > 0))
                                        <div class="text-xs text-orange-300">
                                            @if($timeRemaining->days > 0)
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->days }} hari
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->days }} hari
                                                @endif
                                            @elseif($timeRemaining->h > 0)
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->h }} jam
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->h }} jam
                                                @endif
                                            @else
                                                @if($order->game_type === 'Robux')
                                                    Robux akan masuk dalam {{ $timeRemaining->i }} menit
                                                @else
                                                    Item akan masuk dalam {{ $timeRemaining->i }} menit
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Help Section -->
    <div class="mt-8 rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6 sm:p-8">
        <h3 class="text-white font-semibold text-lg mb-6">Cara Mencari Pesanan</h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                        </svg>
                    </div>
            <div>
                <h4 class="text-white font-medium mb-2">Dengan ID Order</h4>
                <ul class="space-y-1 text-sm text-white/70 list-disc pl-5">
                    <li>Masukkan ID order yang Anda terima</li>
                    <li>Contoh: ABC123, XYZ789</li>
                    <li>Hanya menampilkan pesanan yang masih aktif</li>
                    <li>Langsung menampilkan detail pesanan</li>
                </ul>
            </div>
            </div>
        </div>
            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
            <div>
                <h4 class="text-white font-medium mb-2">Dengan Username</h4>
                <ul class="space-y-1 text-sm text-white/70 list-disc pl-5">
                    <li>Masukkan username Roblox Anda</li>
                    <li>Menampilkan pesanan yang masih aktif</li>
                    <li>Pesanan kadaluarsa tidak ditampilkan</li>
                    <li>Klik pesanan untuk melihat detail</li>
            </ul>
        </div>
    </div>
            </div>
        </div>
    </div>
</main>

<!-- Help Modal -->
<div id="helpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/20 rounded-xl max-w-md w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-white">Hubungi Kami</h3>
                <button onclick="hideHelpModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="contactList" class="space-y-3">
                <!-- Contact items will be populated by JavaScript -->
            </div>
            
            <div id="noContactMessage" class="text-center py-8 hidden">
                <div class="text-gray-400 mb-2">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-gray-300">Tidak ada kontak yang dapat dihubungi</p>
                <p class="text-gray-400 text-sm mt-1">Silakan coba lagi nanti</p>
            </div>
        </div>
    </div>
</div>

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    }
});

function showHelpModal() {
    document.getElementById('helpModal').classList.remove('hidden');
    loadContactInfo();
}

function hideHelpModal() {
    document.getElementById('helpModal').classList.add('hidden');
}

async function loadContactInfo() {
    try {
        const response = await fetch('/api/contact-info');
        const data = await response.json();
        
        const contactList = document.getElementById('contactList');
        const noContactMessage = document.getElementById('noContactMessage');
        
        // Clear previous content
        contactList.innerHTML = '';
        
        if (data.contacts && data.contacts.length > 0) {
            noContactMessage.classList.add('hidden');
            
            data.contacts.forEach(contact => {
                const contactItem = document.createElement('div');
                contactItem.className = 'flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors';
                
                contactItem.innerHTML = `
                    <div class="flex-shrink-0">
                        ${contact.icon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-white font-medium">${contact.name}</div>
                        <div class="text-gray-400 text-sm">${contact.description}</div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="${contact.url}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Buka
                        </a>
                    </div>
                `;
                
                contactList.appendChild(contactItem);
            });
        } else {
            contactList.classList.add('hidden');
            noContactMessage.classList.remove('hidden');
        }
    } catch (error) {
        console.error('Error loading contact info:', error);
        document.getElementById('contactList').classList.add('hidden');
        document.getElementById('noContactMessage').classList.remove('hidden');
    }
}

// Close modal when clicking outside
document.getElementById('helpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideHelpModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideHelpModal();
    }
});
</script>

@endsection