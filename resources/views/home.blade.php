@extends('layouts.app')

@section('title', 'Valtus — Top Up Robux')

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
            <a href="#topup" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beli Robux</a>
            <a href="{{ route('user.status') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="#" onclick="showHelpModal()" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
        </nav>
        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4">
            <a href="{{ route('user.status') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-200 text-sm font-medium">
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
            <a href="#topup" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beli Robux</a>
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
            <div class="grid md:grid-cols-2 gap-8 md:gap-10 items-center">
                <div class="order-2 md:order-1">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-semibold leading-tight">
                        Andalan Robux Murah Asli dari Developer
                        <img src="/assets/images/verif.png" alt="Verified" class="inline-block h-6 w-6 sm:h-8 sm:w-8 ml-2 align-middle">
                    </h1>
                    <p class="mt-4 text-gray-200 max-w-xl text-sm sm:text-base">
                        Beli Robux dengan harga terbaik langsung dari developer resmi. 
                        Proses cepat, aman, dan terjamin keasliannya. Validasi username 
                        otomatis untuk pengalaman yang mulus.
                    </p>
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="#topup" class="inline-flex items-center justify-center gap-2 rounded-md px-4 py-3 bg-white text-black hover:bg-gray-200 transition text-sm sm:text-base">
                            <img src="/assets/images/robux.png" class="h-5 w-5" alt="Robux">
                            <span>Beli Robux</span>
                        </a>
                        <a href="{{ route('user.status') }}" class="inline-flex items-center justify-center gap-2 rounded-md px-4 py-3 border border-white/15 hover:border-white/30 transition text-sm sm:text-base">
                            Cek Pesanan
                        </a>
                    </div>
                </div>
                <div class="relative order-1 md:order-2">
                    <div class="aspect-[16/9] md:aspect-[4/3] rounded-xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 flex items-center justify-center overflow-hidden">
                        @php
                            $homeHeroImage = \App\Models\Setting::getValue('home_hero_image', '');
                            $homeHeroImageType = \App\Models\Setting::getValue('home_hero_image_type', 'file');
                            $homeHeroImageUrl = \App\Models\Setting::getValue('home_hero_image_url', '');
                        @endphp
                        @if($homeHeroImageType === 'file' && $homeHeroImage)
                            <img src="{{ asset($homeHeroImage) }}" alt="Valtus Banner" class="h-full w-full object-cover rounded-xl">
                        @elseif($homeHeroImageType === 'url' && $homeHeroImageUrl)
                            <img src="{{ $homeHeroImageUrl }}" alt="Valtus Banner" class="h-full w-full object-cover rounded-xl">
                        @else
                            <img src="/assets/images/iconv.jpg" alt="Valtus Banner" class="h-20 sm:h-24 md:h-28 w-auto opacity-90">
                        @endif
                    </div>
                </div>
            </div>
            <div class="mt-8 sm:mt-10 grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-sm">
                <div class="rounded-lg border border-white/20 p-3 sm:p-4 bg-white/5">
                    <div class="text-white/70 text-xs sm:text-sm">Harga per 1 Robux</div>
                    <div class="mt-1 text-lg sm:text-xl md:text-2xl font-semibold">Rp {{ number_format($robuxPricePer1 ?? 100, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-lg border border-white/20 p-3 sm:p-4 bg-white/5">
                    <div class="text-white/70 text-xs sm:text-sm">Harga per 100 Robux</div>
                    <div class="mt-1 text-lg sm:text-xl md:text-2xl font-semibold">Rp {{ number_format($robuxPricePer100 ?? 10000, 0, ',', '.') }}</div>
                </div>
                <div class="rounded-lg border border-white/20 p-3 sm:p-4 bg-white/5">
                    <div class="text-white/70 text-xs sm:text-sm">Dukungan</div>
                    <div class="mt-1 text-lg sm:text-xl md:text-2xl font-semibold">24/7 Live Support</div>
                </div>
            </div>

            <!-- Live Feed Section -->
            @if($recentActivities->count() > 0)
            <div class="mt-8 sm:mt-10">
                <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
                     <div class="flex items-center gap-2 mb-4">
                         <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse" id="liveIndicator"></div>
                         <h3 class="text-white/90 font-medium text-sm sm:text-base">Aktivitas Terbaru</h3>
                         <div class="text-xs text-white/50" id="lastUpdate">Auto-refresh setiap 30s</div>
                     </div>
                    <div class="space-y-3" id="liveFeed">
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10 activity-item" data-activity-id="{{ $activity->id }}" data-username="{{ $activity->username }}">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-r from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0" id="avatar-{{ $activity->id }}">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr($activity->username, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-white/90 text-sm">
                                    <span class="font-medium">{{ $activity->masked_username }}</span>
                                    <span class="text-white/60">berhasil membeli</span>
                                    @if($activity->game_type === 'Robux')
                                        <span class="font-semibold text-emerald-400">{{ $activity->formatted_amount }} Robux</span>
                                    @else
                                        <span class="font-semibold text-emerald-400">{{ $activity->formatted_amount }} {{ $activity->product_name ?: $activity->game_type }}</span>
                                    @endif
                                </div>
                                <div class="text-white/50 text-xs">
                                    {{ $activity->processed_at->diffForHumans() }}
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if($activity->game_type === 'Robux')
                                    <img src="/assets/images/robux.png" alt="Robux" class="w-4 h-4 opacity-70">
                                @else
                                    <div class="w-4 h-4 rounded bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                        <span class="text-white text-xs font-bold">P</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Top Up Section -->
    <section id="topup" class="py-10 sm:py-14 border-t border-white/10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                <h2 class="text-lg sm:text-xl font-semibold">Top Up Robux</h2>
                <a href="#" class="text-sm text-gray-400 hover:text-white self-start sm:self-auto">Lihat Ranking →</a>
            </div>

            <div class="mt-4 sm:mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                <div class="rounded-xl border border-white/20 p-5 bg-white/10">
                    <div class="flex items-center justify-between">
                        <div class="font-medium">Stok Robux</div>
                        @if($stockStatus['is_low'])
                            <span class="text-xs px-2 py-1 rounded bg-red-500/20 text-red-300 border border-red-500/30">Low Stock</span>
                        @elseif($stockStatus['status'] === 'high')
                            <span class="text-xs px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">High Stock</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Normal</span>
                        @endif
                    </div>
                    <div class="mt-3 text-2xl font-semibold flex items-center gap-2">
                        <span id="stockRbx">{{ number_format($robuxStock ?? 100000, 0, ',', '.') }}</span>
                        <span id="stockDelta" class="text-xs px-1.5 py-0.5 rounded hidden"></span>
                    </div>
                    @if($stockStatus['is_low'])
                        <div class="mt-2 text-xs text-red-300">
                            ⚠️ Stok rendah! Segera isi ulang.
                        </div>
                    @endif
                    <script>
                        // Real stock display - no fake animation
                        (function(){
                            const el = document.getElementById('stockRbx');
                            const deltaEl = document.getElementById('stockDelta');
                            
                            function fmtRbx(n){
                                if(n >= 1000){ return (Math.round(n/100)/10).toFixed(1) + 'k+'; }
                                return n.toString();
                            }
                            
                            // Set initial stock from database
                            el.textContent = fmtRbx({{ $robuxStock ?? 100000 }});
                            
                            // Function to update stock when there's a real change
                            window.updateStockDisplay = function(newStock, change) {
                                // Use the actual change value passed from the API
                                const actualChange = change || (newStock - lastStock);
                                console.log('🎯 updateStockDisplay called:', {
                                    newStock: newStock,
                                    change: change,
                                    actualChange: actualChange,
                                    lastStock: lastStock,
                                    expectedFinal: lastStock + actualChange
                                });
                                
                                if (actualChange !== 0) {
                                    // Store the starting value for animation
                                    const startValue = lastStock;
                                    // Animate to new value
                                    const start = performance.now();
                                    const duration = 800;
                                    
                                    function easeOutQuad(t) { return 1 - (1 - t) * (1 - t); }
                                    
                                    function frame(now) {
                                        const p = Math.min(1, (now - start) / duration);
                                        const value = Math.round(startValue + (actualChange * easeOutQuad(p)));
                                        el.textContent = fmtRbx(value);
                                        
                                        if (p < 1) {
                                            requestAnimationFrame(frame);
                                        } else {
                                            // Set final value to exact newStock
                                            el.textContent = fmtRbx(newStock);
                                            // Show delta indicator with actual change
                                            if (Math.abs(actualChange) > 0) {
                                                deltaEl.textContent = (actualChange > 0 ? '▲ ' : '▼ ') + Math.abs(actualChange).toLocaleString('id-ID');
                                                deltaEl.className = 'text-xs px-1.5 py-0.5 rounded ' + (actualChange > 0 ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/15 text-red-300 border border-red-500/30');
                                                deltaEl.style.opacity = '1';
                                                deltaEl.classList.remove('hidden');
                                                
                                                // Scale animation
                                                try { 
                                                    el.animate([
                                                        {transform:'scale(1)'},
                                                        {transform:'scale(1.05)'},
                                                        {transform:'scale(1)'}
                                                    ], {duration: 400}); 
                                                } catch(e) {}
                                                
                                                // Hide delta after 2 seconds
                                                setTimeout(() => { 
                                                    deltaEl.style.transition = 'opacity 600ms'; 
                                                    deltaEl.style.opacity = '0'; 
                                                }, 2000);
                                                setTimeout(() => { 
                                                    deltaEl.classList.add('hidden'); 
                                                    deltaEl.style.transition = ''; 
                                                }, 2600);
                                            }
                                        }
                                    }
                                    
                                    requestAnimationFrame(frame);
                                }
                            };
                        })();
                    </script>
                    <a href="#" onclick="selectAmount(0)" class="mt-5 inline-flex items-center gap-2 rounded-md px-4 py-2 bg-white text-black hover:bg-gray-200 transition">
                        <img src="/assets/images/robux.png" class="h-5 w-5" alt="Robux">
                        <span>Beli Robux Sekarang</span>
                    </a>
                </div>

                <div class="rounded-xl border border-white/20 p-5 md:col-span-2 bg-white/5">
                    <div class="font-medium mb-3">Pilih Cepat</div>
                    <div class="relative">
                        <button type="button" id="scrollLeftBtn" class="flex absolute left-0 top-1/2 -translate-y-1/2 z-10 h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 group" onclick="scrollQuickSelect('left')" aria-label="Scroll left">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div id="quickScroll" class="overflow-x-auto no-scrollbar scroll-smooth px-1 grid grid-flow-col grid-rows-2 auto-cols-max gap-3">
                            @php
                                $quick = [100, 500, 1000, 2000, 5000, 10000, 25000, 50000];
                            @endphp
                            @foreach($quick as $pkg)
                                @php $pkgPrice = ($robuxPricePer100 ?? 10000) * ($pkg / 100); @endphp
                                <a href="#" onclick="selectAmount({{ $pkg }})" class="inline-flex shrink-0 flex-col items-center text-center rounded-md border border-white/15 px-4 py-3 text-sm hover:border-white/30 hover:bg-white/5 transition mx-1 min-w-[140px] group">
                                    <div class="flex items-center gap-2">
                                        <img src="/assets/images/robux.png" class="h-4 w-4 opacity-80 group-hover:opacity-100 transition-opacity duration-200" alt="Robux">
                                        <span class="font-medium">{{ $pkg }} RBX</span>
                                    </div>
                                    <div class="text-white/90 mt-1 group-hover:text-white transition-colors duration-200">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</div>
                                </a>
                            @endforeach
                        </div>
                        <button type="button" id="scrollRightBtn" class="flex absolute right-0 top-1/2 -translate-y-1/2 z-10 h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 group" onclick="scrollQuickSelect('right')" aria-label="Scroll right">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                    <style>
                        .no-scrollbar::-webkit-scrollbar{display:none}
                        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
                    </style>
                </div>
            </div>
        </div>
    </section>

    <!-- Other Products Sections -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid md:grid-cols-2 gap-6">
            <!-- Cobain Game Lainnya -->
            <div class="rounded-xl border border-white/20 p-6 bg-white/5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium">Cobain Game Lainnya Juga</h3>
                    <a href="{{ route('products') }}" class="text-sm text-gray-300 hover:text-white">Lihat Semua →</a>
                </div>
                @if($otherProducts->count() > 0)
                    @php
                        $gameTypes = $otherProducts->groupBy('game_type');
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($gameTypes->take(4) as $gameType => $products)
                            @php
                                $sampleProduct = $products->first();
                                $productCount = $products->count();
                            @endphp
                            <a href="{{ route('products', ['game_type' => $gameType]) }}" class="group rounded-lg border border-white/10 p-3 bg-white/5 hover:bg-white/10 transition-all duration-200">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($sampleProduct->image)
                                        <img src="{{ asset($sampleProduct->image) }}" alt="{{ $gameType }}" class="h-8 w-8 rounded object-cover group-hover:scale-110 transition-transform duration-200">
                                    @elseif($sampleProduct->image_url)
                                        <img src="{{ $sampleProduct->image_url }}" alt="{{ $gameType }}" class="h-8 w-8 rounded object-cover group-hover:scale-110 transition-transform duration-200">
                                    @else
                                        <div class="h-8 w-8 rounded bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <img src="/assets/images/robux.png" alt="{{ $gameType }}" class="h-5 w-5">
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="text-white font-medium text-sm truncate">{{ $gameType }}</div>
                                        <div class="text-white/60 text-xs">{{ $productCount }} produk</div>
                                    </div>
                                </div>
                                <div class="text-emerald-300 font-semibold text-sm">Mulai dari Rp {{ number_format($products->min('total_price'), 0, ',', '.') }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="h-32 flex items-center justify-center text-center text-gray-300">
                        <div>
                            <div class="font-semibold text-white">Belum Ada Produk</div>
                            <div class="text-xs mt-1">Admin belum menambahkan produk selain Robux</div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Produk Terpopuler -->
            <div class="rounded-xl border border-white/20 p-6 bg-white/5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium">Produk Terpopuler</h3>
                    <a href="{{ route('products') }}" class="text-sm text-gray-300 hover:text-white">Lihat Semua →</a>
                </div>
                @if($otherProducts->count() > 0)
                    @php
                        $gameTypes = $otherProducts->groupBy('game_type');
                    @endphp
                    <div class="space-y-3">
                        @foreach($gameTypes->take(3) as $gameType => $gameTypeProducts)
                            @php
                                $products = $gameTypeProducts;
                                $sampleProduct = $products->first();
                                $productCount = $products->count();
                            @endphp
                            <a href="{{ route('products', ['game_type' => $gameType]) }}" class="group flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 transition-colors">
                                <div class="flex-shrink-0 w-6 h-6 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                    <span class="text-emerald-300 text-xs font-bold">{{ $loop->iteration }}</span>
                                </div>
                                <div class="flex items-center gap-2 flex-1 min-w-0">
                                    @if($sampleProduct->image)
                                        <img src="{{ asset($sampleProduct->image) }}" alt="{{ $gameType }}" class="h-8 w-8 rounded object-cover group-hover:scale-110 transition-transform duration-200">
                                    @elseif($sampleProduct->image_url)
                                        <img src="{{ $sampleProduct->image_url }}" alt="{{ $gameType }}" class="h-8 w-8 rounded object-cover group-hover:scale-110 transition-transform duration-200">
                                    @else
                                        <div class="h-8 w-8 rounded bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                                            <img src="/assets/images/robux.png" alt="{{ $gameType }}" class="h-5 w-5">
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <div class="text-white font-medium text-sm truncate">{{ $gameType }}</div>
                                        <div class="text-white/60 text-xs">{{ $productCount }} produk tersedia</div>
                                    </div>
                                </div>
                                <div class="text-emerald-300 font-semibold text-sm">Mulai dari Rp {{ number_format($products->min('total_price'), 0, ',', '.') }}</div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="h-32 flex items-center justify-center text-center text-gray-300">
                        <div>
                            <div class="font-semibold text-white">Belum Ada Produk</div>
                            <div class="text-xs mt-1">Admin belum menambahkan produk selain Robux</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Guarantees moved to bottom -->
    <section class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div class="rounded-md border border-white/20 p-4 bg-white/5">
                    <dt class="text-gray-300">Garansi Akun 100% Aman</dt>
                    <dd class="mt-2 font-medium">Fokus Keamanan</dd>
                </div>
                <div class="rounded-md border border-white/20 p-4 bg-white/5">
                    <dt class="text-gray-300">Pengalaman 5+ Tahun</dt>
                    <dd class="mt-2 font-medium">Profesional</dd>
                </div>
                <div class="rounded-md border border-white/20 p-4 bg-white/5">
                    <dt class="text-gray-300">Garansi Robux Masuk</dt>
                    <dd class="mt-2 font-medium">Transparan</dd>
                </div>
            </dl>
        </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 text-sm text-gray-300 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div>© <span class="font-medium text-white">Valtus</span> — Dibuat untuk menjaga Robloxian</div>
            <div class="flex items-center gap-3">
                <img src="/assets/images/robux.png" alt="Robux" class="h-4 w-4 opacity-70">
                <span>Valtus — Top Up Robux</span>
            </div>
        </div>
    </footer>

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
        async function selectAmount(amount) {
            // Store amount in session
            await fetch('/user/store-amount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ amount: amount })
            });
            
            // Redirect to search page without URL parameters
            window.location.href = '{{ route("user.search") }}';
        }

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

        // Quick select scroll functionality
        function scrollQuickSelect(direction) {
            const scrollContainer = document.getElementById('quickScroll');
            // Responsive scroll amount: smaller on mobile, larger on desktop
            const isMobile = window.innerWidth < 640; // sm breakpoint
            const scrollAmount = isMobile ? 200 : 300;
            
            if (direction === 'left') {
                scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
            
            // Update button visibility after scroll
            setTimeout(updateScrollButtons, 100);
        }
        
        function updateScrollButtons() {
            const scrollContainer = document.getElementById('quickScroll');
            const leftBtn = document.getElementById('scrollLeftBtn');
            const rightBtn = document.getElementById('scrollRightBtn');
            
            if (!scrollContainer || !leftBtn || !rightBtn) return;
            
            const scrollLeft = scrollContainer.scrollLeft;
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            
            // Show/hide left button
            if (scrollLeft <= 0) {
                leftBtn.style.opacity = '0.5';
                leftBtn.style.pointerEvents = 'none';
            } else {
                leftBtn.style.opacity = '1';
                leftBtn.style.pointerEvents = 'auto';
            }
            
            // Show/hide right button
            if (scrollLeft >= maxScroll - 10) { // 10px tolerance
                rightBtn.style.opacity = '0.5';
                rightBtn.style.pointerEvents = 'none';
            } else {
                rightBtn.style.opacity = '1';
                rightBtn.style.pointerEvents = 'auto';
            }
        }

         // Live feed auto-refresh (activities only)
         function refreshLiveFeed() {
             console.log('🔄 Refreshing live feed...');
             updateLastRefreshTime();
             
             fetch('/api/recent-activities')
                 .then(response => response.json())
                 .then(data => {
                     console.log('✅ Activities API response received:', data);
                     
                     // Update activities (max 3)
                     if (data.activities && data.activities.length > 0) {
                         console.log('📊 Updating activities:', data.activities.length);
                         const liveFeed = document.getElementById('liveFeed');
                         if (liveFeed) {
                             // Always replace with latest 3 activities
                             liveFeed.innerHTML = '';
                             
                             // Add all activities (max 3)
                             data.activities.forEach(activity => {
                                 console.log('👤 Creating activity element for:', activity.username);
                                 const activityElement = createActivityElement(activity);
                                 liveFeed.appendChild(activityElement);
                                 
                                 // Load avatar for this activity
                                 loadAvatarForActivity(activity);
                                 
                                 // Add animation
                                 activityElement.style.opacity = '0';
                                 activityElement.style.transform = 'translateY(-10px)';
                                 setTimeout(() => {
                                     activityElement.style.transition = 'all 0.3s ease';
                                     activityElement.style.opacity = '1';
                                     activityElement.style.transform = 'translateY(0)';
                                 }, 100);
                             });
                         }
                     }
                 })
                 .catch(error => {
                     console.log('❌ Live feed refresh failed:', error);
                     updateLastRefreshTime('error');
                 });
         }

         // Stock refresh function (separate)
         function refreshStock() {
             console.log('💰 Refreshing stock...');
             fetch('/api/current-stock')
                 .then(response => response.json())
                 .then(data => {
                     console.log('✅ Stock API response received:', data);
                     updateStockFromData(data);
                 })
                 .catch(error => {
                     console.log('❌ Stock refresh failed:', error);
                 });
         }

         // Global variable for tracking stock
         let lastStock = {{ $robuxStock ?? 100000 }};

         // Update last refresh time display
         function updateLastRefreshTime(status = 'success') {
             const lastUpdateEl = document.getElementById('lastUpdate');
             const indicatorEl = document.getElementById('liveIndicator');
             
             if (lastUpdateEl) {
                 const now = new Date();
                 const timeStr = now.toLocaleTimeString('id-ID', { 
                     hour: '2-digit', 
                     minute: '2-digit', 
                     second: '2-digit' 
                 });
                 
                 if (status === 'error') {
                     lastUpdateEl.textContent = `Terakhir update: ${timeStr} (Error)`;
                     lastUpdateEl.className = 'text-xs text-red-400';
                 } else {
                     lastUpdateEl.textContent = `Terakhir update: ${timeStr}`;
                     lastUpdateEl.className = 'text-xs text-white/50';
                 }
             }
             
             if (indicatorEl) {
                 if (status === 'error') {
                     indicatorEl.className = 'w-2 h-2 bg-red-500 rounded-full animate-pulse';
                 } else {
                     indicatorEl.className = 'w-2 h-2 bg-emerald-500 rounded-full animate-pulse';
                 }
             }
         }

         // Update stock from API data
         function updateStockFromData(stockData) {
             console.log('📊 Stock data received:', stockData);
             console.log('📊 Current lastStock:', lastStock);
             
             if (stockData.current_stock !== lastStock) {
                 const change = stockData.current_stock - lastStock;
                 console.log('📊 Stock changed:', lastStock, '->', stockData.current_stock, 'change:', change);
                 console.log('📊 Validation: lastStock + change =', lastStock + change, 'should equal newStock:', stockData.current_stock);
                 
                 // Update stock display with animation (pass the actual change)
                 if (window.updateStockDisplay) {
                     window.updateStockDisplay(stockData.current_stock, change);
                 }
                 
                 // Update lastStock after animation starts
                 lastStock = stockData.current_stock;
                 
                 // Update stock status badges
                 updateStockStatus(stockData);
             } else {
                 console.log('📊 No stock change detected');
             }
         }

         // Update stock status badges
         function updateStockStatus(data) {
             const stockContainer = document.querySelector('.rounded-xl.border.border-white\\/20.p-5.bg-white\\/10');
             if (stockContainer) {
                 const statusBadge = stockContainer.querySelector('.text-xs.px-2.py-1.rounded');
                 const lowStockWarning = stockContainer.querySelector('.mt-2.text-xs.text-red-300');
                 
                 // Update status badge
                 if (statusBadge) {
                     statusBadge.className = 'text-xs px-2 py-1 rounded';
                     if (data.is_low) {
                         statusBadge.classList.add('bg-red-500/20', 'text-red-300', 'border', 'border-red-500/30');
                         statusBadge.textContent = 'Low Stock';
                     } else if (data.status === 'high') {
                         statusBadge.classList.add('bg-emerald-500/20', 'text-emerald-300', 'border', 'border-emerald-500/30');
                         statusBadge.textContent = 'High Stock';
                     } else {
                         statusBadge.classList.add('bg-blue-500/20', 'text-blue-300', 'border', 'border-blue-500/30');
                         statusBadge.textContent = 'Normal';
                     }
                 }
                 
                 // Update low stock warning
                 if (lowStockWarning) {
                     if (data.is_low) {
                         lowStockWarning.classList.remove('hidden');
                     } else {
                         lowStockWarning.classList.add('hidden');
                     }
                 }
             }
         }

        // Load avatar for specific activity (menggunakan API lama yang sudah bekerja)
        function loadAvatarForActivity(activity) {
            const avatarContainer = document.getElementById(`avatar-${activity.id}`);
            if (!avatarContainer) return;

            // Use full username for API call (not masked)
            const fullUsername = activity.username; // Use original username, not masked
            
            console.log('🖼️ Loading avatar for:', fullUsername);
            
            // Fetch avatar dari API lama yang sudah bekerja
            fetch(`/api/roblox/username?username=${encodeURIComponent(fullUsername)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('🖼️ Avatar response for', fullUsername, ':', data);
                    
                    if (data && data.ok && data.found && data.avatar) {
                        // Replace initials with actual avatar
                        avatarContainer.innerHTML = `<img src="${data.avatar}" alt="${fullUsername}" class="w-8 h-8 rounded-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"><div class="w-8 h-8 rounded-full bg-gradient-to-r from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0 hidden"><span class="text-white text-xs font-bold">${fullUsername.charAt(0).toUpperCase()}</span></div>`;
                        console.log('✅ Avatar loaded for', fullUsername);
                    } else {
                        console.log('❌ No avatar found for', fullUsername);
                    }
                })
                .catch(error => {
                    console.log('❌ Failed to load avatar for', fullUsername, error);
                });
        }

        function createActivityElement(activity) {
            const div = document.createElement('div');
            div.className = 'flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10 activity-item';
            div.setAttribute('data-activity-id', activity.id);
            div.setAttribute('data-username', activity.username); // Add full username
            
            // Create avatar with initials first, will be replaced by actual avatar
            const avatarHtml = `
                <div class="w-8 h-8 rounded-full bg-gradient-to-r from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0" id="avatar-${activity.id}">
                    <span class="text-white text-xs font-bold">${activity.username.charAt(0).toUpperCase()}</span>
                </div>
            `;
            
            // Determine product info
            let productText = '';
            let productIcon = '';
            
            if (activity.product_info) {
                productText = activity.product_info.amount;
                productIcon = activity.product_info.type === 'robux' 
                    ? '<img src="/assets/images/robux.png" alt="Robux" class="w-4 h-4 opacity-70">'
                    : '<div class="w-4 h-4 rounded bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center"><span class="text-white text-xs font-bold">P</span></div>';
            } else {
                // Fallback for old data
                productText = activity.formatted_amount + ' Robux';
                productIcon = '<img src="/assets/images/robux.png" alt="Robux" class="w-4 h-4 opacity-70">';
            }
            
            div.innerHTML = `
                ${avatarHtml}
                <div class="flex-1 min-w-0">
                    <div class="text-white/90 text-sm">
                        <span class="font-medium">${activity.masked_username}</span>
                        <span class="text-white/60">berhasil membeli</span>
                        <span class="font-semibold text-emerald-400">${productText}</span>
                    </div>
                    <div class="text-white/50 text-xs">
                        ${activity.time_ago}
                    </div>
                </div>
                <div class="flex-shrink-0">
                    ${productIcon}
                </div>
            `;
            
            return div;
        }

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
            
            // Initialize scroll buttons
            const scrollContainer = document.getElementById('quickScroll');
            if (scrollContainer) {
                // Update buttons on scroll
                scrollContainer.addEventListener('scroll', updateScrollButtons);
                
                // Initial button state
                updateScrollButtons();
            }
            
            // Initialize live feed refresh
            const liveFeed = document.getElementById('liveFeed');
            if (liveFeed) {
                // Load avatars for existing activities (only once on page load)
                const existingActivities = document.querySelectorAll('.activity-item');
                existingActivities.forEach(activityElement => {
                    const activityId = activityElement.getAttribute('data-activity-id');
                    // Get username from data attribute instead of parsing from display text
                    const username = activityElement.getAttribute('data-username');
                    if (activityId && username) {
                        loadAvatarForActivity({ id: activityId, username: username });
                    }
                });
                
                 // Only refresh if page is visible and user is active
                 let activitiesInterval;
                 let stockInterval;
                 let isPageVisible = true;
                 let lastActivity = null;
                 
                 function startRefresh() {
                     // Clear existing intervals
                     if (activitiesInterval) clearInterval(activitiesInterval);
                     if (stockInterval) clearInterval(stockInterval);
                     
                     // Refresh activities every 30 seconds (lebih responsif)
                     activitiesInterval = setInterval(() => {
                         if (isPageVisible && !document.hidden) {
                             refreshLiveFeed();
                         }
                     }, 30000);
                     
                     // Refresh stock every 15 seconds (lebih responsif)
                     stockInterval = setInterval(() => {
                         if (isPageVisible && !document.hidden) {
                             refreshStock();
                         }
                     }, 15000);
                 }
                 
                 function stopRefresh() {
                     if (activitiesInterval) {
                         clearInterval(activitiesInterval);
                         activitiesInterval = null;
                     }
                     if (stockInterval) {
                         clearInterval(stockInterval);
                         stockInterval = null;
                     }
                 }
                
                
                // Start refresh when page becomes visible
                document.addEventListener('visibilitychange', () => {
                    isPageVisible = !document.hidden;
                    if (isPageVisible) {
                        startRefresh();
                    } else {
                        stopRefresh();
                    }
                });
                
                 // Start initial refresh
                 console.log('Starting refresh intervals...');
                 startRefresh();
                 
                 // Test initial calls
                 console.log('Testing initial API calls...');
                 refreshLiveFeed();
                 refreshStock();
            }
        });
    </script>
@endsection



