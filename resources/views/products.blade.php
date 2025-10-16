@extends('layouts.app')

@section('title', 'Produk — Valtus')

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
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beranda</a>
            <a href="{{ route('home') }}#topup" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beli Robux</a>
            <a href="{{ route('user.status') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="#" onclick="showHelpModal()" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
        </nav>
        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4">
            <a href="{{ route('user.status') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-200 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cek Status
            </a>
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
            <a href="{{ route('home') }}#topup" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beli Robux</a>
            <a href="{{ route('user.status') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Cek Pesanan</a>
            <a href="#" onclick="showHelpModal()" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
        </div>
    </div>
</header>

<main>
    <!-- Hero -->
    <section class="relative overflow-hidden bg-gradient-to-br from-gray-800/50 to-gray-900">
        <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/5 to-white/0 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
            <div class="text-center">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-semibold leading-tight">
                    Semua Produk Valtus
                    <img src="/assets/images/verif.png" alt="Verified" class="inline-block h-8 w-8 ml-2 align-middle">
                </h1>
                <p class="mt-4 text-gray-200 max-w-2xl mx-auto">
                    Jelajahi berbagai produk game terbaik dengan harga kompetitif. 
                    Dari Robux hingga item game lainnya, semua tersedia di Valtus.
                </p>
            </div>
        </div>
    </section>

    <!-- Game Type Filter -->
    <section class="py-8 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-semibold text-white mb-2">Pilih Kategori Game</h2>
                <p class="text-gray-400">Filter produk berdasarkan game yang ingin Anda mainkan</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                <!-- All Games -->
                <a href="{{ route('products') }}" 
                   class="group relative rounded-xl border-2 transition-all duration-200 overflow-hidden {{ !$gameType ? 'border-emerald-500 bg-emerald-500/10' : 'border-white/20 hover:border-white/40 bg-white/5' }}">
                    <div class="aspect-square p-4 flex flex-col items-center justify-center text-center">
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                        </div>
                        <span class="text-white font-medium text-sm">Semua Game</span>
                        <span class="text-white/60 text-xs mt-1">{{ $products->total() }} produk</span>
                    </div>
                    @if(!$gameType)
                        <div class="absolute top-2 right-2 w-3 h-3 bg-emerald-500 rounded-full"></div>
                    @endif
                </a>
                
                <!-- Game Type Filters -->
                @foreach($gameTypes as $type)
                    <a href="{{ route('products', ['game_type' => $type]) }}" 
                       class="group relative rounded-xl border-2 transition-all duration-200 overflow-hidden {{ $gameType === $type ? 'border-emerald-500 bg-emerald-500/10' : 'border-white/20 hover:border-white/40 bg-white/5' }}">
                        <div class="aspect-square p-4 flex flex-col items-center justify-center text-center">
                            @if(isset($gameTypeImages[$type]))
                                <img src="{{ $gameTypeImages[$type] }}" alt="{{ $type }}" class="w-12 h-12 rounded-lg object-cover mb-3 group-hover:scale-110 transition-transform duration-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform duration-200">
                                    <img src="/assets/images/robux.png" alt="{{ $type }}" class="w-6 h-6">
                                </div>
                            @endif
                            <span class="text-white font-medium text-sm">{{ $type }}</span>
                            <span class="text-white/60 text-xs mt-1">{{ \App\Models\Product::where('is_active', true)->where('game_type', $type)->count() }} produk</span>
                        </div>
                        @if($gameType === $type)
                            <div class="absolute top-2 right-2 w-3 h-3 bg-emerald-500 rounded-full"></div>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Products Grid -->
    <section class="py-10 sm:py-14 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($gameType)
                <div class="mb-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-semibold text-white">Produk {{ $gameType }}</h3>
                        <p class="text-gray-400 text-sm">{{ $products->total() }} produk ditemukan</p>
                    </div>
                    <a href="{{ route('products') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        Lihat Semua Produk →
                    </a>
                </div>
            @endif
            
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        <a href="{{ route('user.product-order', $product->game_type) }}" class="block rounded-xl border border-white/20 p-6 bg-white/5 hover:bg-white/10 transition-all duration-200 group">
                            <!-- Product Image -->
                            <div class="aspect-square rounded-lg overflow-hidden mb-4 bg-gradient-to-br from-white/5 to-white/0">
                                @if($product->image)
                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                @elseif($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center">
                                            <img src="/assets/images/robux.png" alt="Product" class="h-10 w-10">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Product Info -->
                            <div class="space-y-3">
                                <div>
                                    <h3 class="text-white font-semibold text-lg">{{ $product->name }}</h3>
                                    <p class="text-white/60 text-sm">{{ ucfirst($product->category) }} • {{ $product->game_type }}</p>
                                </div>

                                @if($product->description)
                                    <p class="text-white/50 text-sm line-clamp-2">{{ $product->description }}</p>
                                @endif

                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-emerald-300 font-bold text-xl">Rp {{ number_format($product->total_price, 0, ',', '.') }}</div>
                                        @if($product->tax_rate > 0)
                                            <div class="text-white/50 text-xs">Termasuk pajak {{ $product->tax_rate }}%</div>
                                        @endif
                                    </div>
                                    <div class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-medium border border-emerald-500/30">
                                        Tersedia
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                    <div class="mt-12 flex justify-center">
                        {{ $products->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-16">
                    <div class="text-white/60 text-lg mb-4">Belum ada produk yang tersedia</div>
                    <div class="text-white/40 text-sm">Admin belum menambahkan produk ke sistem</div>
                </div>
            @endif
        </div>
    </section>
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
</script>
@endsection
