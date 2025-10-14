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
            <a href="{{ route('user.status') }}" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-200 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cek Status
            </a>
            <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                </svg>
                Admin
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
            <div class="pt-4 border-t border-white/10">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Admin
                </a>
            </div>
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
                        Valtus: Andalan Robux Murah Asli dari Developer
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
                        <span class="text-xs px-2 py-1 rounded bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Live</span>
                    </div>
                    <div class="mt-3 text-2xl font-semibold flex items-center gap-2">
                        <span id="stockRbx">92.0k+</span>
                        <span id="stockDelta" class="text-xs px-1.5 py-0.5 rounded hidden"></span>
                    </div>
                    <script>
                        (function(){
                            const el = document.getElementById('stockRbx');
                            const deltaEl = document.getElementById('stockDelta');
                            function parseNum(text){
                                const s = String(text).toLowerCase();
                                if (s.includes('k')) {
                                    const f = parseFloat(s);
                                    return isNaN(f) ? 92000 : Math.round(f * 1000);
                                }
                                const n = parseInt(s.replace(/[^0-9]/g,''),10);
                                return isNaN(n)?92000:n; // default 92k
                            }
                            function fmtRbx(n){
                                if(n >= 1000){ return (Math.round(n/100)/10).toFixed(1) + 'k+'; }
                                return n.toString();
                            }
                            let current = Math.max(60000, parseNum(el.textContent));

                            function animateTo(target){
                                const start = performance.now();
                                const from = current;
                                const to = Math.max(60000, target);
                                const duration = 1200; // ms
                                function easeOutQuad(t){ return 1 - (1 - t) * (1 - t); }
                                function frame(now){
                                    const p = Math.min(1, (now - start) / duration);
                                    const value = Math.round(from + (to - from) * easeOutQuad(p));
                                    el.textContent = fmtRbx(value);
                                    if (p < 1) {
                                        requestAnimationFrame(frame);
                                    } else {
                                        const diff = to - from;
                                        if (diff !== 0){
                                            deltaEl.textContent = (diff > 0 ? '▲ ' : '▼ ') + Math.abs(diff).toLocaleString('id-ID');
                                            deltaEl.className = 'text-xs px-1.5 py-0.5 rounded ' + (diff>0 ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/30' : 'bg-red-500/15 text-red-300 border border-red-500/30');
                                            deltaEl.style.opacity = '1';
                                            deltaEl.classList.remove('hidden');
                                            try { el.animate([{transform:'scale(1)'},{transform:'scale(1.06)'},{transform:'scale(1)'}], {duration:500}); } catch(e) {}
                                            setTimeout(()=>{ deltaEl.style.transition='opacity 600ms'; deltaEl.style.opacity='0'; }, 1200);
                                            setTimeout(()=>{ deltaEl.classList.add('hidden'); deltaEl.style.transition=''; }, 1900);
                                        }
                                        current = to;
                                    }
                                }
                                requestAnimationFrame(frame);
                            }

                            let cycle = 0; // 0..3 (3 turun, 1 naik)
                            let downSum = 0;
                            function rand(min, max){ return Math.floor(Math.random()*(max-min+1))+min; }
                            function tick(){
                                let delta = 0;
                                if (cycle < 3) {
                                    // turunkan stok 3 kali berturut-turut
                                    delta = -rand(400, 1000);
                                    downSum += Math.abs(delta);
                                    cycle += 1;
                                } else {
                                    // naikkan sekali dengan total turunan + 100
                                    delta = downSum + 100;
                                    downSum = 0;
                                    cycle = 0;
                                }
                                animateTo(current + delta);
                            }
                            setInterval(tick, 15000);
                            setTimeout(tick, 300);
                            if (document.visibilityState === 'visible') tick();
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
                        <button type="button" class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 z-10 h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20" onclick="document.getElementById('quickScroll').scrollBy({left:-400,behavior:'smooth'})" aria-label="Scroll left">‹</button>
                        <div id="quickScroll" class="overflow-x-auto no-scrollbar scroll-smooth px-1 grid grid-flow-col grid-rows-2 auto-cols-max gap-3">
                            @php
                                $min = max(($robuxMinOrder ?? 100), 1);
                                $quick = [$min];
                                for ($i = 100; $i <= 5000; $i += 100) { $quick[] = $i; }
                                $quick = array_values(array_unique($quick)); sort($quick);
                            @endphp
                            @foreach($quick as $pkg)
                                @php $pkgPrice = ($robuxPricePer100 ?? 10000) * ($pkg / 100); @endphp
                                <a href="#" onclick="selectAmount({{ $pkg }})" class="inline-flex shrink-0 flex-col items-center text-center rounded-md border border-white/15 px-4 py-3 text-sm hover:border-white/30 hover:bg-white/5 transition mx-1 min-w-[140px]">
                                    <div class="flex items-center gap-2">
                                        <img src="/assets/images/robux.png" class="h-4 w-4 opacity-80" alt="Robux">
                                        <span class="font-medium">{{ $pkg }} RBX</span>
                                    </div>
                                    <div class="text-white/90 mt-1">Rp {{ number_format($pkgPrice, 0, ',', '.') }}</div>
                                </a>
                            @endforeach
                        </div>
                        <button type="button" class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 z-10 h-9 w-9 items-center justify-center rounded-full bg-white/10 hover:bg-white/20 border border-white/20" onclick="document.getElementById('quickScroll').scrollBy({left:400,behavior:'smooth'})" aria-label="Scroll right">›</button>
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



