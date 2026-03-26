@extends('layouts.app')

@section('title', 'Valtus — Top Up Robux')

@section('body')
@php
    $robuxMinOrder = $robuxMinOrder ?? (int) \App\Models\Setting::getValue('robux_min_order', 100);
@endphp
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
            <a href="{{ route('products') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Produk</a>
            <a href="{{ route('user.status') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="javascript:void(0);" onclick="showHelpModal(event); return false;" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
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
            <a href="{{ route('products') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Produk</a>
            <a href="{{ route('user.status') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Cek Pesanan</a>
            <a href="javascript:void(0);" onclick="showHelpModal(event); return false;" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
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
                    <div class="mt-6 text-gray-200 max-w-2xl">
                        <p class="text-lg sm:text-xl font-bold text-white mb-4 leading-relaxed">
                            Beli Robux dengan harga terbaik langsung dari developer resmi!
                        </p>
                        
                        <!-- Simple Features List -->
                        <div class="space-y-2 mb-6">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-yellow-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Proses cepat & aman</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Terjamin keasliannya</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">Validasi username otomatis</span>
                            </div>
                        </div>
                    </div>
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
                                    <img src="/assets/images/robux.png" alt="Robux" class="w-5 h-5 opacity-70">
                                @else
                                    @php
                                        $product = \App\Models\Product::where('game_type', $activity->game_type)->first();
                                    @endphp
                                    @if($product && ($product->image || $product->image_url))
                                        @if($product->image)
                                            <img src="{{ asset($product->image) }}" alt="{{ $activity->game_type }}" class="w-6 h-6 rounded object-cover opacity-80">
                                        @elseif($product->image_url)
                                            <img src="{{ $product->image_url }}" alt="{{ $activity->game_type }}" class="w-6 h-6 rounded object-cover opacity-80">
                                        @endif
                                    @else
                                        <div class="w-6 h-6 rounded bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center">
                                            <span class="text-white text-sm font-bold">P</span>
                                        </div>
                                    @endif
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

            <div class="mt-4 sm:mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <!-- Stok Robux via Gamepass -->
                <div class="rounded-xl border border-white/20 p-5 bg-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <img src="/assets/images/robux.png" class="h-4 w-4 opacity-80" alt="Robux">
                            <div class="font-medium text-sm">Via Gamepass</div>
                        </div>
                        @if($stockStatus['is_low'])
                            <span class="text-xs px-2 py-1 rounded bg-red-500/20 text-red-300 border border-red-500/30">Low</span>
                        @elseif($stockStatus['status'] === 'high')
                            <span class="text-xs px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">High</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Normal</span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-semibold flex items-center gap-2">
                        <span id="stockRbx">{{ number_format($robuxStock ?? 100000, 0, ',', '.') }}</span>
                        <span id="stockDelta" class="text-xs px-1.5 py-0.5 rounded hidden"></span>
                    </div>
                    @if($stockStatus['is_low'])
                        <div class="mt-2 text-xs text-red-300">
                            ⚠️ Stok rendah
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

                <!-- Stok Robux via Group -->
                <div class="rounded-xl border border-purple-500/30 p-5 bg-purple-500/5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-purple-400 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <div class="font-medium text-sm">Via Group</div>
                        </div>
                        @if($groupStockStatus['is_low'])
                            <span class="text-xs px-2 py-1 rounded bg-red-500/20 text-red-300 border border-red-500/30">Low</span>
                        @elseif($groupStockStatus['status'] === 'high')
                            <span class="text-xs px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">High</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded bg-blue-500/20 text-blue-300 border border-blue-500/30">Normal</span>
                        @endif
                    </div>
                    <div class="mt-2 text-2xl font-semibold flex items-center gap-2">
                        <span id="stockGroupRbx">{{ number_format($groupRobuxStock ?? 50000, 0, ',', '.') }}</span>
                        <span id="stockGroupDelta" class="text-xs px-1.5 py-0.5 rounded hidden"></span>
                    </div>
                    @if($groupStockStatus['is_low'])
                        <div class="mt-2 text-xs text-red-300">
                            ⚠️ Stok rendah
                        </div>
                    @endif
                    <script>
                        // Group stock display
                        (function(){
                            const el = document.getElementById('stockGroupRbx');
                            const deltaEl = document.getElementById('stockGroupDelta');
                            
                            function fmtRbx(n){
                                if(n >= 1000){ return (Math.round(n/100)/10).toFixed(1) + 'k+'; }
                                return n.toString();
                            }
                            
                            // Set initial stock from database
                            el.textContent = fmtRbx({{ $groupRobuxStock ?? 50000 }});
                            
                            // Function to update group stock when there's a real change
                            window.updateGroupStockDisplay = function(newStock, change) {
                                const actualChange = change || (newStock - lastGroupStock);
                                
                                if (actualChange !== 0) {
                                    const startValue = lastGroupStock;
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
                                            el.textContent = fmtRbx(newStock);
                                            if (Math.abs(actualChange) > 0) {
                                                deltaEl.textContent = (actualChange > 0 ? '▲ ' : '▼ ') + Math.abs(actualChange).toLocaleString('id-ID');
                                                deltaEl.className = 'text-xs px-1.5 py-0.5 rounded ' + (actualChange > 0 ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/15 text-red-300 border border-red-500/30');
                                                deltaEl.style.opacity = '1';
                                                deltaEl.classList.remove('hidden');
                                                
                                                try { 
                                                    el.animate([
                                                        {transform:'scale(1)'},
                                                        {transform:'scale(1.05)'},
                                                        {transform:'scale(1)'}
                                                    ], {duration: 400}); 
                                                } catch(e) {}
                                                
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
                            
                            window.lastGroupStock = {{ $groupRobuxStock ?? 50000 }};
                        })();
                    </script>
                    <a href="{{ route('user.search-group') }}" class="mt-5 inline-flex items-center gap-2 rounded-md px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span>Beli via Group</span>
                    </a>
                </div>
            </div>

            <div class="mt-4 sm:mt-6">
                <div class="rounded-xl border border-white/20 p-5 bg-white/5">
                    <div class="font-medium mb-3">Pilih Cepat</div>
                    <div class="relative">
                        <button type="button" id="scrollLeftBtn" class="flex absolute left-0 top-1/2 -translate-y-1/2 z-10 h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 group hidden" onclick="scrollQuickSelect('left')" aria-label="Scroll left">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <div id="quickScroll" class="overflow-x-auto no-scrollbar scroll-smooth px-1 grid grid-flow-col grid-rows-2 auto-cols-max gap-3">
                            @php
                                $quick = [100, 500, 1000, 2000, 5000, 10000, 25000, 50000];
                            @endphp
                            @foreach($quick as $pkg)
                                @php 
                                    $pkgPrice = ($robuxPricePer100 ?? 10000) * ($pkg / 100);
                                    // Check discount for gamepass and group
                                    $gamepassDiscount = \App\Models\RobuxDiscountRule::findMatchingRule($pkg, 'gamepass');
                                    $groupDiscount = \App\Models\RobuxDiscountRule::findMatchingRule($pkg, 'group');
                                    
                                    $gamepassBasePrice = ($robuxPricePer100 ?? 10000) * ($pkg / 100);
                                    $groupBasePrice = (\App\Models\Setting::getValue('group_robux_price_per_100', '10000')) * ($pkg / 100);
                                    
                                    $gamepassFinalPrice = $gamepassBasePrice;
                                    $groupFinalPrice = $groupBasePrice;
                                    $gamepassDiscountAmount = 0;
                                    $groupDiscountAmount = 0;
                                    $gamepassDiscountPercent = 0;
                                    $groupDiscountPercent = 0;
                                    
                                    if ($gamepassDiscount) {
                                        $gamepassDiscountAmount = $gamepassDiscount->calculateDiscount($gamepassBasePrice);
                                        $gamepassFinalPrice = max(0, $gamepassBasePrice - $gamepassDiscountAmount);
                                        $gamepassDiscountPercent = $gamepassBasePrice > 0 ? ($gamepassDiscountAmount / $gamepassBasePrice) * 100 : 0;
                                    }
                                    
                                    if ($groupDiscount) {
                                        $groupDiscountAmount = $groupDiscount->calculateDiscount($groupBasePrice);
                                        $groupFinalPrice = max(0, $groupBasePrice - $groupDiscountAmount);
                                        $groupDiscountPercent = $groupBasePrice > 0 ? ($groupDiscountAmount / $groupBasePrice) * 100 : 0;
                                    }
                                    
                                    $hasDiscount = $gamepassDiscount || $groupDiscount;
                                    $lowestFinalPrice = $hasDiscount ? min($gamepassFinalPrice, $groupFinalPrice) : $pkgPrice;
                                @endphp
                                <a href="#" onclick="selectAmount({{ $pkg }})" class="inline-flex shrink-0 flex-col items-center text-center rounded-md border {{ $hasDiscount ? 'border-yellow-500/50' : 'border-white/15' }} px-4 py-3 text-sm hover:border-white/30 hover:bg-white/5 transition-all duration-200 mx-1 min-w-[140px] group select-none relative" data-amount="{{ $pkg }}" data-gamepass-discount="{{ $gamepassDiscount ? '1' : '0' }}" data-group-discount="{{ $groupDiscount ? '1' : '0' }}">
                                    @if($hasDiscount)
                                        <div class="absolute -top-1.5 -right-1.5 px-1.5 py-0.5 rounded-full bg-yellow-500 text-black text-[10px] font-bold z-10">
                                            🎉
                                        </div>
                                    @endif
                                    <div class="flex items-center gap-2">
                                        <img src="/assets/images/robux.png" class="h-4 w-4 opacity-80 group-hover:opacity-100 transition-opacity duration-200" alt="Robux">
                                        <span class="font-medium">{{ $pkg }} RBX</span>
                                    </div>
                                    @if($hasDiscount)
                                        <div class="text-[10px] text-yellow-300 mt-0.5 font-medium leading-tight">
                                            @if($gamepassDiscount && $groupDiscount)
                                                Diskon Gamepass & Group
                                            @elseif($gamepassDiscount)
                                                Diskon Gamepass
                                            @else
                                                Diskon Group
                                            @endif
                                        </div>
                                        <div class="text-white/60 line-through text-[11px] mt-0.5">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</div>
                                        <div class="text-emerald-300 font-bold text-sm mt-0.5 group-hover:text-emerald-200 transition-colors duration-200">Rp {{ number_format($lowestFinalPrice, 0, ',', '.') }}</div>
                                        @if(($gamepassDiscountPercent > 0 || $groupDiscountPercent > 0))
                                            <div class="text-yellow-400 text-[10px] mt-0.5 font-medium">
                                                @if($gamepassDiscount && $groupDiscount)
                                                    {{ number_format(max($gamepassDiscountPercent, $groupDiscountPercent), 0) }}% off
                                                @elseif($gamepassDiscount)
                                                    {{ number_format($gamepassDiscountPercent, 0) }}% off
                                                @else
                                                    {{ number_format($groupDiscountPercent, 0) }}% off
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <div class="text-white/90 mt-1 group-hover:text-white transition-colors duration-200">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        <button type="button" id="scrollRightBtn" class="flex absolute right-0 top-1/2 -translate-y-1/2 z-10 h-8 w-8 sm:h-9 sm:w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20 transition-all duration-200 group hidden" onclick="scrollQuickSelect('right')" aria-label="Scroll right">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white group-hover:scale-110 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                    <style>
                        .no-scrollbar::-webkit-scrollbar{display:none}
                        .no-scrollbar{-ms-overflow-style:none;scrollbar-width:none}
                        
                        /* Optimized animations for better performance */
                        #quickScroll a {
                            will-change: transform, opacity;
                            backface-visibility: hidden;
                            transform: translateZ(0);
                        }
                        
                        #quickScroll a:hover {
                            transform: translateZ(0) scale(1.02);
                        }
                        
                        /* Hardware acceleration for scroll buttons */
                        #scrollLeftBtn, #scrollRightBtn {
                            will-change: opacity, transform;
                            backface-visibility: hidden;
                            transform: translateZ(0);
                        }
                        
                        /* Smooth transitions without lag */
                        .transition-all {
                            transition: all 0.2s ease-out;
                        }
                        
                        /* Optimize for touch devices */
                        @media (hover: none) and (pointer: coarse) {
                            #quickScroll a:hover {
                                transform: none;
                            }
                        }
                        
                        /* Reduce motion for users who prefer it */
                        @media (prefers-reduced-motion: reduce) {
                            #quickScroll a,
                            #scrollLeftBtn, 
                            #scrollRightBtn {
                                transition: none;
                            }
                        }
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
                    <h3 class="font-medium">Eksplor Produk Lain Kami</h3>
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

    <!-- Guarantees Section -->
    <section class="py-14">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- subtle top gradient divider -->
            <div class="h-px w-full bg-gradient-to-r from-white/10 via-white/20 to-white/10 mb-10"></div>

            <div class="text-center mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-white mb-3">Mengapa Memilih Valtus?</h2>
                <p class="text-gray-400 text-base sm:text-lg max-w-2xl mx-auto">Komitmen kami untuk pengalaman top-up Robux yang aman, cepat, dan andal</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Garansi Akun 100% Aman -->
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl ring-1 ring-white/15 flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Garansi Akun 100% Aman</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Kami tidak akan pernah meminta data pribadi Anda. Keamanan akun adalah prioritas.</p>
                </div>

                <!-- Pengalaman 10+ Tahun -->
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl ring-1 ring-white/15 flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Pengalaman 10+ Tahun</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Profesional sejak 2013, memahami ekosistem Roblox untuk layanan yang konsisten.</p>
                </div>

                <!-- Garansi Robux Masuk -->
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl ring-1 ring-white/15 flex items-center justify-center">
                        <svg class="w-7 h-7 text-gray-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Garansi Robux Masuk</h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Robux dipastikan masuk. Jika ada kendala, tim kami siap membantu 24/7.</p>
                </div>
            </div>
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

    <!-- Email Modal -->
    <div id="emailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 rounded-xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-white">Email Customer Service</h3>
                    <button onclick="hideEmailModal()" class="text-gray-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="text-sm text-white/70 mb-2 block">Alamat Email</label>
                        <div class="flex items-center gap-2">
                            <input type="text" id="emailAddressDisplay" readonly class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white text-lg font-mono" />
                            <button onclick="copyEmailAddress()" id="copyEmailBtn" class="px-4 py-3 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                            </button>
                        </div>
                        <div id="emailCopySuccess" class="hidden mt-2 text-sm text-emerald-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Email berhasil disalin!
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <a id="openEmailClientLink" href="#" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-3 rounded-md bg-blue-600 hover:bg-blue-700 text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Buka Email Client
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Cache DOM elements untuk performa
        let cachedElements = {};
        const ROBUX_MIN_ORDER = {{ (int) $robuxMinOrder }};
        
        function getCachedElement(id) {
            if (!cachedElements[id]) {
                cachedElements[id] = document.getElementById(id);
            }
            return cachedElements[id];
        }
        
        // Optimized selectAmount function with better performance
        let isProcessing = false;
        
        function selectAmount(amount) {
            if (isProcessing) return; // Prevent multiple calls
            isProcessing = true;
            const parsedAmount = parseInt(amount, 10);
            const safeAmount = Math.max(!isNaN(parsedAmount) ? parsedAmount : 0, ROBUX_MIN_ORDER);
            
            // Get clicked button for visual feedback
            const clickedBtn = event?.target?.closest('a[data-amount]');
            
            // Disable all quick select buttons to prevent double clicks
            const quickSelectButtons = document.querySelectorAll('#quickScroll a[data-amount]');
            quickSelectButtons.forEach(btn => {
                btn.style.pointerEvents = 'none';
                btn.style.opacity = '0.6';
                btn.style.cursor = 'not-allowed';
            });
            
            // Show loading state on clicked button
            if (clickedBtn) {
                clickedBtn.style.opacity = '0.9';
                clickedBtn.style.transform = 'scale(0.98)';
                clickedBtn.style.borderColor = 'rgba(16, 185, 129, 0.5)'; // emerald border
            }
            
            // Store amount in session (non-blocking)
            fetch('/user/store-amount', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ amount: safeAmount })
            }).then(() => {
                console.log('Amount stored successfully:', safeAmount);
            }).catch(error => {
                console.error('Error storing amount:', error);
            });
            
            // Immediate redirect untuk responsivitas maksimal
            requestAnimationFrame(() => {
                window.location.href = '{{ route("user.search") }}';
            });
        }

        // Make loadContactInfo globally available FIRST (before showHelpModal)
        window.loadContactInfo = async function() {
            try {
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                
                // Show loading state immediately
                if (contactList) {
                    contactList.innerHTML = '<div class="text-center py-4"><div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-500"></div><div class="text-white/60 text-sm mt-2">Memuat kontak...</div></div>';
                    contactList.classList.remove('hidden');
                }
                if (noContactMessage) {
                    noContactMessage.classList.add('hidden');
                }
                
                // Fetch contact info immediately (no delay)
                const response = await fetch('/api/contact-info', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    cache: 'no-cache'
                });
                
                if (!response.ok) {
                    throw new Error('Failed to fetch contact info');
                }
                
                const data = await response.json();
                
                if (data.contacts && data.contacts.length > 0) {
                    if (noContactMessage) {
                        noContactMessage.classList.add('hidden');
                    }
                    
                    // Clear loading and populate contacts
                    if (contactList) {
                        contactList.innerHTML = '';
                    }
                    
                    data.contacts.forEach(contact => {
                        const contactItem = document.createElement('div');
                        contactItem.className = 'flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors';
                        
                        // Check if it's an email (mailto:) link
                        const isEmail = contact.url && contact.url.startsWith('mailto:');
                        const emailAddress = isEmail ? contact.url.replace('mailto:', '') : '';
                        
                        if (isEmail) {
                            // For email, create button that shows modal
                            contactItem.innerHTML = `
                                <div class="flex-shrink-0">
                                    ${contact.icon}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-white font-medium">${contact.name}</div>
                                    <div class="text-gray-400 text-sm">${contact.description}</div>
                                </div>
                                <div class="flex-shrink-0">
                                    <button onclick="showEmailModal('${emailAddress}')" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                        Buka
                                    </button>
                                </div>
                            `;
                        } else {
                            // For other contacts, use normal link
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
                        }
                        
                        if (contactList) {
                            contactList.appendChild(contactItem);
                        }
                    });
                } else {
                    if (contactList) {
                        contactList.classList.add('hidden');
                    }
                    if (noContactMessage) {
                        noContactMessage.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading contact info:', error);
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                if (contactList) {
                    contactList.classList.add('hidden');
                }
                if (noContactMessage) {
                    noContactMessage.classList.remove('hidden');
                }
            }
        };

        // Make functions globally available
        window.showHelpModal = function(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            const helpModal = document.getElementById('helpModal');
            if (helpModal) {
                helpModal.classList.remove('hidden');
                // Call loadContactInfo immediately - it's now globally available
                window.loadContactInfo();
            }
            return false;
        };

        window.hideHelpModal = function() {
            const helpModal = document.getElementById('helpModal');
            if (helpModal) {
                helpModal.classList.add('hidden');
            }
        };

        // Email Modal Functions
        window.showEmailModal = function(emailAddress) {
            const emailModal = document.getElementById('emailModal');
            const emailDisplay = document.getElementById('emailAddressDisplay');
            const emailClientLink = document.getElementById('openEmailClientLink');
            const copySuccess = document.getElementById('emailCopySuccess');
            
            if (emailModal && emailDisplay && emailClientLink) {
                emailDisplay.value = emailAddress;
                emailClientLink.href = 'mailto:' + emailAddress;
                copySuccess.classList.add('hidden');
                emailModal.classList.remove('hidden');
            }
        };

        window.hideEmailModal = function() {
            const emailModal = document.getElementById('emailModal');
            if (emailModal) {
                emailModal.classList.add('hidden');
            }
        };

        function copyEmailAddress() {
            const emailDisplay = document.getElementById('emailAddressDisplay');
            const copySuccess = document.getElementById('emailCopySuccess');
            const copyBtn = document.getElementById('copyEmailBtn');
            
            if (emailDisplay) {
                emailDisplay.select();
                emailDisplay.setSelectionRange(0, 99999); // For mobile devices
                
                try {
                    navigator.clipboard.writeText(emailDisplay.value).then(() => {
                        if (copySuccess) {
                            copySuccess.classList.remove('hidden');
                            setTimeout(() => {
                                copySuccess.classList.add('hidden');
                            }, 3000);
                        }
                    }).catch(() => {
                        // Fallback for older browsers
                        document.execCommand('copy');
                        if (copySuccess) {
                            copySuccess.classList.remove('hidden');
                            setTimeout(() => {
                                copySuccess.classList.add('hidden');
                            }, 3000);
                        }
                    });
                } catch (err) {
                    // Fallback
                    document.execCommand('copy');
                    if (copySuccess) {
                        copySuccess.classList.remove('hidden');
                        setTimeout(() => {
                            copySuccess.classList.add('hidden');
                        }, 3000);
                    }
                }
            }
        }

        // Setup help modal event listeners after DOM is ready
        function setupHelpModalListeners() {
            const helpModal = document.getElementById('helpModal');
            if (helpModal) {
                // Close modal when clicking outside
                helpModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideHelpModal();
                    }
                });
            }
            
            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    hideHelpModal();
                    hideEmailModal();
                }
            });
            
            // Close email modal when clicking outside
            const emailModal = document.getElementById('emailModal');
            if (emailModal) {
                emailModal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        hideEmailModal();
                    }
                });
            }
        }
        
        // Setup listeners when DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupHelpModalListeners);
        } else {
            setupHelpModalListeners();
        }

        // Optimized scroll functionality with throttling
        let scrollTimeout;
        let isScrolling = false;
        
        function scrollQuickSelect(direction) {
            if (isScrolling) return; // Prevent multiple scrolls
            
            const scrollContainer = getCachedElement('quickScroll');
            if (!scrollContainer) return;
            
            isScrolling = true;
            
            // Responsive scroll amount: smaller on mobile, larger on desktop
            const isMobile = window.innerWidth < 640;
            const scrollAmount = isMobile ? 200 : 300;
            
            if (direction === 'left') {
                scrollContainer.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                scrollContainer.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
            
            // Reset scrolling flag after animation
            setTimeout(() => {
                isScrolling = false;
            }, 300);
            
            // Throttled button update
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(updateScrollButtons, 150);
        }
        
        // Throttled updateScrollButtons function
        let lastScrollUpdate = 0;
        function updateScrollButtons() {
            const now = Date.now();
            if (now - lastScrollUpdate < 100) return; // Throttle to max 10fps
            lastScrollUpdate = now;
            
            const scrollContainer = getCachedElement('quickScroll');
            const leftBtn = getCachedElement('scrollLeftBtn');
            const rightBtn = getCachedElement('scrollRightBtn');
            
            if (!scrollContainer || !leftBtn || !rightBtn) return;
            
            const scrollLeft = scrollContainer.scrollLeft;
            const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
            
            // Check if scrolling is needed
            const needsScroll = maxScroll > 10;
            
            if (!needsScroll) {
                leftBtn.classList.add('hidden');
                rightBtn.classList.add('hidden');
                return;
            }
            
            // Show both buttons if scroll is needed
            leftBtn.classList.remove('hidden');
            rightBtn.classList.remove('hidden');
            
            // Batch DOM updates
            requestAnimationFrame(() => {
                // Left button state
                if (scrollLeft <= 0) {
                    leftBtn.style.opacity = '0.5';
                    leftBtn.style.pointerEvents = 'none';
                } else {
                    leftBtn.style.opacity = '1';
                    leftBtn.style.pointerEvents = 'auto';
                }
                
                // Right button state
                if (scrollLeft >= maxScroll - 10) {
                    rightBtn.style.opacity = '0.5';
                    rightBtn.style.pointerEvents = 'none';
                } else {
                    rightBtn.style.opacity = '1';
                    rightBtn.style.pointerEvents = 'auto';
                }
            });
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
         let lastGroupStock = {{ $groupRobuxStock ?? 50000 }};

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
             console.log('📊 Current lastGroupStock:', lastGroupStock);
             
             // Update regular stock (Gamepass)
             if (stockData.current_stock !== lastStock) {
                 const change = stockData.current_stock - lastStock;
                 console.log('📊 Stock changed:', lastStock, '->', stockData.current_stock, 'change:', change);
                 
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
             
             // Update group stock
             if (stockData.group_stock && stockData.group_stock.current_stock !== lastGroupStock) {
                 const groupChange = stockData.group_stock.current_stock - lastGroupStock;
                 console.log('📊 Group stock changed:', lastGroupStock, '->', stockData.group_stock.current_stock, 'change:', groupChange);
                 
                 // Update group stock display with animation
                 if (window.updateGroupStockDisplay) {
                     window.updateGroupStockDisplay(stockData.group_stock.current_stock, groupChange);
         }

                 // Update lastGroupStock after animation starts
                 lastGroupStock = stockData.group_stock.current_stock;
                 
                 // Update group stock status badges
                 updateGroupStockStatus(stockData.group_stock);
             } else {
                 console.log('📊 No group stock change detected');
             }
         }

         // Update stock status badges (Gamepass)
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
                         statusBadge.textContent = 'Low';
                     } else if (data.status === 'high') {
                         statusBadge.classList.add('bg-emerald-500/20', 'text-emerald-300', 'border', 'border-emerald-500/30');
                         statusBadge.textContent = 'High';
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

         // Update group stock status badges
         function updateGroupStockStatus(data) {
             const groupStockContainer = document.querySelector('.rounded-xl.border.border-purple-500\\/30.p-5.bg-purple-500\\/5');
             if (groupStockContainer) {
                 const statusBadge = groupStockContainer.querySelector('.text-xs.px-2.py-1.rounded');
                 const lowStockWarning = groupStockContainer.querySelector('.mt-2.text-xs.text-red-300');
                 
                 // Update status badge
                 if (statusBadge) {
                     statusBadge.className = 'text-xs px-2 py-1 rounded';
                     if (data.is_low) {
                         statusBadge.classList.add('bg-red-500/20', 'text-red-300', 'border', 'border-red-500/30');
                         statusBadge.textContent = 'Low';
                     } else if (data.status === 'high') {
                         statusBadge.classList.add('bg-emerald-500/20', 'text-emerald-300', 'border', 'border-emerald-500/30');
                         statusBadge.textContent = 'High';
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
            
            // Determine product info - simplified logic
            let productText = '';
            let productIcon = '';
            
            if (activity.product_info) {
                productText = activity.product_info.amount;
                if (activity.product_info.type === 'robux') {
                    productIcon = '<img src="/assets/images/robux.png" alt="Robux" class="w-5 h-5 opacity-70">';
                } else {
                    // For non-robux items, use product image if available
                    if (activity.product_info.image) {
                        productIcon = `<img src="${activity.product_info.image}" alt="${activity.game_type}" class="w-6 h-6 rounded object-cover opacity-80">`;
                    } else {
                        productIcon = '<div class="w-6 h-6 rounded bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center"><span class="text-white text-sm font-bold">P</span></div>';
                    }
                }
            } else {
                // Fallback for old data
                if (activity.game_type === 'Robux') {
                    productText = activity.formatted_amount + ' Robux';
                    productIcon = '<img src="/assets/images/robux.png" alt="Robux" class="w-5 h-5 opacity-70">';
                } else {
                    productText = activity.formatted_amount + ' ' + activity.game_type;
                    productIcon = '<div class="w-6 h-6 rounded bg-gradient-to-r from-blue-500 to-purple-500 flex items-center justify-center"><span class="text-white text-sm font-bold">P</span></div>';
                }
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

        // Enhanced cache and session cleanup for security
        function clearAllSensitiveData() {
            // Clear sessionStorage
            sessionStorage.clear();
            
            // Clear localStorage (if any sensitive data stored)
            localStorage.removeItem('user_data');
            localStorage.removeItem('order_data');
            localStorage.removeItem('payment_data');
            
            // Clear any cached form data
            if (typeof Storage !== "undefined") {
                // Clear any custom cache keys
                const keysToRemove = [];
                for (let i = 0; i < localStorage.length; i++) {
                    const key = localStorage.key(i);
                    if (key && (key.includes('user') || key.includes('order') || key.includes('payment'))) {
                        keysToRemove.push(key);
                    }
                }
                keysToRemove.forEach(key => localStorage.removeItem(key));
            }
        }
        
        // Browser navigation handlers
        window.addEventListener('beforeunload', function(e) {
            // Clear all sensitive data when leaving
            clearAllSensitiveData();
        });
        
        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(e) {
            // Clear all cached data when navigating back
            clearAllSensitiveData();
            // Refresh page to ensure clean state
            window.location.reload();
        });
        
        // Prevent form resubmission on refresh
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
        
        // Clear data on page load for security
        document.addEventListener('DOMContentLoaded', function() {
            // Clear any leftover sensitive data
            clearAllSensitiveData();
        });
        
        // Optimized DOMContentLoaded with debouncing
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = getCachedElement('mobile-menu-btn');
            const mobileMenu = getCachedElement('mobile-menu');
            
            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });
                
                // Close menu when clicking outside (throttled)
                let clickTimeout;
                document.addEventListener('click', function(event) {
                    clearTimeout(clickTimeout);
                    clickTimeout = setTimeout(() => {
                        if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                            mobileMenu.classList.add('hidden');
                        }
                    }, 10);
                });
            }
            
            // Initialize scroll buttons with optimized event listeners
            const scrollContainer = getCachedElement('quickScroll');
            if (scrollContainer) {
                // Throttled scroll listener
                let scrollThrottle;
                scrollContainer.addEventListener('scroll', function() {
                    clearTimeout(scrollThrottle);
                    scrollThrottle = setTimeout(updateScrollButtons, 16); // ~60fps
                });
                
                // Initial button state - check after layout is complete
                requestAnimationFrame(() => {
                    setTimeout(updateScrollButtons, 50);
                });
                
                // Debounced resize listener
                let resizeTimeout;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimeout);
                    resizeTimeout = setTimeout(updateScrollButtons, 100);
                });
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
                     
                     // Refresh activities every 15 seconds (lebih responsif untuk real-time)
                     activitiesInterval = setInterval(() => {
                         if (isPageVisible && !document.hidden) {
                             refreshLiveFeed();
                         }
                     }, 15000);
                     
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
    
    <!-- Prevent back button if coming from payment page -->
    <script>
    (function() {
        // Check if coming from payment page
        const urlParams = new URLSearchParams(window.location.search);
        const fromPayment = urlParams.get('from') === 'payment';
        
        if (fromPayment) {
            // Clear the URL parameter (clean URL)
            if (window.history.replaceState) {
                window.history.replaceState(null, '', window.location.pathname);
            }
            
            // Clear all history entries to prevent back to payment page
            // Push multiple states to fill history stack
            for (let i = 0; i < 5; i++) {
                history.pushState(null, null, location.href);
            }
            
            // Prevent back button - redirect to home if user tries to go back
            window.addEventListener('popstate', function(event) {
                // If user tries to go back, redirect to home again
                window.location.replace('{{ route("home") }}');
            });
            
            // Also handle onpopstate
            window.onpopstate = function(event) {
                window.location.replace('{{ route("home") }}');
            };
            
            // Prevent back on mobile browsers
            window.addEventListener('focus', function() {
                // Push state to prevent back
                history.pushState(null, null, location.href);
            });
        }
    })();
    </script>
@endsection



