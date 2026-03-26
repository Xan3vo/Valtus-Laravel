@extends('layouts.app')
@section('title', 'Cari Username')
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
            <a href="{{ route('products') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Produk</a>
            <a href="{{ route('user.status') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="javascript:void(0);" onclick="showHelpModal(); return false;" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
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
        <button id="mobile-menu-btn" type="button" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors" onclick="toggleMobileMenu()" style="z-index: 9999; position: relative;">
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
            <a href="{{ route('products') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Produk</a>
            <a href="{{ route('user.status') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Cek Pesanan</a>
            <a href="javascript:void(0);" onclick="showHelpModal(); return false;" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
            <div class="pt-4 border-t border-white/10">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Admin
                </a>
            </div>
        </div>
</header>

<main class="max-w-5xl mx-auto px-6 py-12">
    <!-- Back Button - Outside section -->
    <div class="mb-4">
        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-3 py-2 rounded-md border border-white/15 text-white/80 hover:text-white hover:bg-white/5 transition-colors text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span class="hidden sm:inline">Kembali ke Beranda</span>
            <span class="sm:hidden">Kembali</span>
        </a>
    </div>
    
    <!-- Subheader -->
    <section class="rounded-xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-4 sm:p-5 mb-6">
        <!-- Title and Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-semibold text-white">Top Up Robux</h1>
                <div class="mt-1 text-sm text-white/70">Minimal order: <span class="text-white font-medium">{{ $robuxMinOrder }} RBX</span></div>
                <div id="referralActiveBanner" class="hidden mt-3">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-emerald-400/30 bg-emerald-500/10 text-emerald-200 text-xs">
                        <span class="font-semibold">Referral aktif</span>
                        <span class="text-emerald-200/80" id="referralActiveCode"></span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="#" onclick="showCaraBeliVideo()" class="inline-flex items-center gap-2 rounded-md border border-white/15 px-3 py-2 text-sm text-white/90 hover:bg-white/5 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Cara Beli</span>
                    <span class="sm:hidden">Cara</span>
                </a>
            </div>
        </div>
    </section>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Left: Form -->
        <div class="lg:col-span-2 rounded-xl border border-white/15 bg-white/5 p-5">
            <!-- Method Selection -->
            <div class="mb-6">
                <span class="text-white/70 text-sm mb-3 block font-medium hidden sm:block">Pilih Metode Pembelian</span>
                <!-- Desktop: Horizontal Layout -->
                <div class="hidden sm:grid sm:grid-cols-2 gap-4">
                    <label class="block cursor-pointer">
                        <input type="radio" name="purchaseMethod" value="gamepass" id="radioGamepass" checked class="hidden peer">
                        <div id="methodGamepass" class="p-4 rounded-xl border-2 border-white/15 bg-white/5 peer-checked:border-blue-400 peer-checked:bg-blue-500/10 peer-checked:shadow-lg peer-checked:shadow-blue-500/20 transition-all duration-200 hover:border-blue-400/50 hover:bg-blue-500/5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-base text-white">Via Gamepass</div>
                                    <div class="text-sm text-white/70 mt-0.5">Buat gamepass sendiri di Roblox</div>
                                    <div class="text-xs text-emerald-400/90 mt-1 font-medium">✓ Langsung bisa beli</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block cursor-pointer">
                        <input type="radio" name="purchaseMethod" value="group" id="radioGroup" class="hidden peer">
                        <div id="methodGroup" class="p-4 rounded-xl border-2 border-white/15 bg-white/5 peer-checked:border-blue-400 peer-checked:bg-blue-500/10 peer-checked:shadow-lg peer-checked:shadow-blue-500/20 transition-all duration-200 hover:border-blue-400/50 hover:bg-blue-500/5">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-base text-white">Via Group</div>
                                    <div class="text-sm text-white/70 mt-0.5">Bergabung dengan group {{ $groupName ?? 'Valtus Studios' }}</div>
                                    <div class="text-xs text-purple-400/90 mt-1 font-medium">⏱️ Tunggu {{ $minMembershipDays ?? 14 }} hari</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
                <!-- Mobile: Horizontal Layout -->
                <div class="sm:hidden grid grid-cols-2 gap-2">
                    <label class="block">
                        <input type="radio" name="purchaseMethod" value="gamepass" id="radioGamepassMobile" checked class="hidden peer">
                        <div class="cursor-pointer p-3 rounded-lg border-2 border-white/15 bg-white/5 peer-checked:border-blue-400 peer-checked:bg-blue-500/10 transition-all duration-200 text-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center mx-auto mb-2 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <div class="font-semibold text-sm text-white">Via Gamepass</div>
                        </div>
                    </label>
                    <label class="block">
                        <input type="radio" name="purchaseMethod" value="group" id="radioGroupMobile" class="hidden peer">
                        <div class="cursor-pointer p-3 rounded-lg border-2 border-white/15 bg-white/5 peer-checked:border-blue-400 peer-checked:bg-blue-500/10 transition-all duration-200 text-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-2 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="font-semibold text-sm text-white">Via Group</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Amount Selection Tabs -->
            <div class="flex items-center gap-2 text-sm">
                <button type="button" id="tabCustom" class="px-4 py-1.5 rounded-md bg-white/10 text-white shadow-sm">Kustom</button>
                <button type="button" id="tabQuick" class="px-4 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white">Pilih Cepat</button>
            </div>

            <form method="GET" action="{{ route('user.amount') }}" class="mt-5 grid gap-4">
                <input type="hidden" name="amount" id="amountInput" value="0">
        <label class="block">
                    <span class="text-white/70">Username Roblox</span>
                    <div class="mt-2 flex items-center gap-3">
                        <input required name="username" id="usernameInput" class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/30" placeholder="mis: builderman" />
                        <button type="button" id="checkBtn" class="px-4 py-3 rounded-md border border-white/15 hover:bg-white/5 text-sm">Cek</button>
                    </div>
                    <div id="userResult" class="mt-3 hidden items-center gap-3 p-3 rounded-md border border-white/10 bg-white/5">
                        <img id="userAvatar" src="" class="w-10 h-10 rounded-md object-cover hidden" alt="" />
                        <div>
                            <div class="text-white/90 font-medium" id="userName"></div>
                            <div class="text-white/60 text-xs hidden" id="userId"></div>
                        </div>
                        <div id="userBadge" class="ml-auto text-xs px-2 py-1 rounded border hidden"></div>
                    </div>
        </label>

        <!-- Group Information Section (Hidden by default) -->
        <div id="groupInfoSection" class="hidden mb-6">
            <div class="rounded-xl border border-purple-400/30 bg-gradient-to-br from-purple-500/10 to-pink-500/5 p-4 sm:p-5">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0 shadow-md">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-white font-semibold text-base sm:text-lg mb-2 sm:mb-3">Pembelian via Group</h4>
                        <div class="space-y-2 sm:space-y-2.5 mb-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-purple-400 mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">Bergabung dengan group <strong class="text-white font-medium">{{ $groupName ?? 'Valtus Studios' }}</strong> terlebih dahulu</p>
                            </div>
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-purple-400 mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">Tunggu <strong class="text-white font-medium">{{ $minMembershipDays ?? 14 }} hari</strong> setelah bergabung untuk melanjutkan pembelian</p>
                            </div>
                            <div class="flex items-start gap-2 sm:gap-3">
                                <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-purple-400 mt-1.5 sm:mt-2 flex-shrink-0"></div>
                                <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">Setelah {{ $minMembershipDays ?? 14 }} hari, Anda bisa membeli Robux dengan harga yang sama</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <a href="{{ $groupLink ?? 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about' }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium transition-colors shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Bergabung Group
                            </a>
                            <div class="text-xs text-purple-300/70 sm:text-purple-300/80">
                                <div class="font-medium">💡 Tips:</div>
                                <div>Setelah bergabung, gunakan tombol "Cek Keanggotaan" untuk melihat status</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- Custom amount -->
                <div id="customWrap" class="grid gap-2">
                    <span class="text-white/70 text-sm">Jumlah Robux</span>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 rounded-md border border-white/15 bg-black/30 px-3 py-2 focus-within:ring-1 focus-within:ring-emerald-400">
                            <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                            <input type="number" step="1" id="customAmount" class="bg-transparent outline-none text-white w-32" value="0" placeholder="{{ $robuxMinOrder }}" />
                        </div>
                        <div class="text-sm text-white/70">Harga per 100: <span class="text-white" id="pricePer100Display">Rp {{ number_format($robuxPricePer100,0,',','.') }}</span></div>
                    </div>
                </div>

                <!-- Quick picks -->
                <div id="quickWrap" class="hidden">
                    <div class="mt-1 text-white/70 text-sm">Pilih nominal cepat:</div>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @php $list = [100, 500, 1000, 2000, 5000, 10000, 25000, 50000]; @endphp
                        @foreach($list as $q)
                        @php 
                            $basePrice = $robuxPricePer100 * ($q/100);
                            // Check discount for gamepass and group
                            $gamepassDiscount = \App\Models\RobuxDiscountRule::findMatchingRule($q, 'gamepass');
                            $groupDiscount = \App\Models\RobuxDiscountRule::findMatchingRule($q, 'group');
                            
                            $gamepassBasePrice = $robuxPricePer100 * ($q / 100);
                            $groupBasePrice = ($groupRobuxPricePer100 ?? 10000) * ($q / 100);
                            
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
                            $lowestFinalPrice = $hasDiscount ? min($gamepassFinalPrice, $groupFinalPrice) : $basePrice;
                        @endphp
                        <button type="button" class="chip rounded-lg border {{ $hasDiscount ? 'border-yellow-500/50' : 'border-white/15' }} bg-white/5 hover:bg-white/10 transition p-4 text-left relative" data-amount="{{ $q }}" data-gamepass-discount="{{ $gamepassDiscount ? '1' : '0' }}" data-group-discount="{{ $groupDiscount ? '1' : '0' }}">
                            @if($hasDiscount)
                                <div class="absolute -top-1.5 -right-1.5 px-1.5 py-0.5 rounded-full bg-yellow-500 text-black text-[10px] font-bold z-10">
                                    🎉
                                </div>
                            @endif
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="/assets/images/robux.png" class="h-4 w-4"> 
                                    <div class="font-medium">{{ $q }} RBX</div>
                                </div>
                            </div>
                            @if($hasDiscount)
                                <div class="text-[10px] text-yellow-300 mt-1 font-medium leading-tight">
                                    @if($gamepassDiscount && $groupDiscount)
                                        Diskon Gamepass & Group
                                    @elseif($gamepassDiscount)
                                        Diskon Gamepass
                                    @else
                                        Diskon Group
                                    @endif
                                </div>
                                <div class="text-white/60 line-through text-[11px] mt-0.5">Rp {{ number_format($basePrice, 0, ',', '.') }}</div>
                                <div class="text-emerald-300 font-bold text-sm mt-0.5">Rp {{ number_format($lowestFinalPrice, 0, ',', '.') }}</div>
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
                                <div class="mt-1 text-white/80 text-sm">Rp {{ number_format($basePrice, 0, ',', '.') }}</div>
                            @endif
                            <input type="hidden" value="{{ $q }}" data-val="{{ $q }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-md border border-white/10 p-4 bg-white/5">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-white/70 text-sm">Total</div>
                        <div class="text-lg font-semibold" id="totalPrice">Rp 0</div>
                    </div>
                    <!-- Discount Info -->
                    <div id="discountInfo" class="hidden mt-2 pt-2 border-t border-white/10">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-yellow-300">🎉 Diskon</span>
                            <span class="text-yellow-300 font-medium" id="discountAmount">-Rp 0</span>
                        </div>
                        <div class="text-white/60 text-xs mt-1" id="discountMethod">Diskon Gamepass</div>
                    </div>
                    <div id="referralInfo" class="hidden mt-2">
                        <div class="flex items-center justify-between text-xs">
                            <span class="text-emerald-300">Referral</span>
                            <span class="text-emerald-300 font-medium" id="referralDiscountAmount">-Rp 0</span>
                        </div>
                    </div>
                    <div id="finalPriceInfo" class="hidden mt-2">
                        <div class="flex items-center justify-between">
                            <span class="text-white/70 text-sm">Total Setelah Diskon</span>
                            <span class="text-emerald-300 font-bold text-lg" id="finalPrice">Rp 0</span>
                        </div>
                    </div>
                </div>

                <div class="text-sm text-white/60">Minimal {{ $robuxMinOrder }} RBX</div>
    </form>
        </div>

        <!-- Right: Summary -->
        <div class="space-y-4 max-w-sm w-full lg:ml-auto">
            <div class="rounded-xl border border-white/15 bg-white/5 p-5">
                <div class="text-sm text-white/70">Total Biaya</div>
                <div class="mt-1 text-2xl font-semibold" id="summaryTotal">Rp 0</div>
                <!-- Discount Info in Summary -->
                <div id="summaryDiscountInfo" class="hidden mt-3 pt-3 border-t border-white/10">
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-yellow-300">🎉 Diskon</span>
                        <span class="text-yellow-300 font-medium" id="summaryDiscountAmount">-Rp 0</span>
                    </div>
                    <div class="text-white/60 text-xs" id="summaryDiscountMethod">Diskon Gamepass</div>
                </div>
                <div id="summaryFinalPrice" class="hidden mt-3">
                    <div class="text-xs text-white/60">Total Setelah Diskon</div>
                    <div class="text-xl font-bold text-emerald-300" id="summaryFinalPriceAmount">Rp 0</div>
                </div>
                <div class="mt-4">
                    <button id="proceedBtnRight" type="button" disabled class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-white text-black py-3 hover:bg-gray-100 opacity-50 cursor-not-allowed">
                        <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                        Lanjutkan Pembelian
                    </button>
                </div>
            </div>
            <div class="rounded-xl border border-white/15 bg-emerald-500/10 p-5">
                <div class="text-sm text-emerald-300 font-medium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M9 12.75L11.25 15 15 9.75"/><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM3.75 12a8.25 8.25 0 1116.5 0 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd"/></svg>
                    Garansi Robux Masuk & Aman
                </div>
                <div class="mt-2 text-white/80 text-sm">Pembelian diproses otomatis. Bila ada kendala, dana aman.</div>
            </div>
            <a href="javascript:void(0);" onclick="showHelpModal(); return false;" class="block rounded-xl border border-white/15 bg-white/5 p-4 hover:border-white/30 hover:bg-white/10 transition">
                <div class="flex items-center gap-3">
                    <img src="/assets/images/cs.png" alt="Customer Service" class="w-12 h-12 rounded-md object-cover">
                <div>
                        <div class="text-sm text-white/80 font-medium">Punya pertanyaan?</div>
                        <div class="mt-1 text-white/60 text-sm">Customer Service siap membantu kapan saja.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        // Global variables for group settings (accessible throughout the script)
        const groupRobuxPricePer100 = {{ (int) ($groupRobuxPricePer100 ?? 10000) }};
        const minMembershipDays = {{ (int) ($minMembershipDays ?? 14) }};
        const groupLink = '{{ addslashes($groupLink ?? "https://www.roblox.com/communities/35148970/Valtus-Studios#!/about") }}';
        const groupName = '{{ addslashes($groupName ?? "Valtus Studios") }}';
        const gamepassMinOrder = {{ (int) $robuxMinOrder }};
        const groupMinOrderSetting = {{ (int) ($groupRobuxMinOrder ?? $robuxMinOrder) }};

        const gamepassAvailableStock = {{ (int) ($gamepassAvailableStock ?? 0) }};
        const groupAvailableStock = {{ (int) ($groupAvailableStock ?? 0) }};
        
        // Method selection variables
        let selectedMethod = 'gamepass'; // Default to gamepass
        let groupMembershipValid = false;

        function showBlacklistedPopup() {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            overlay.innerHTML = `
                <div class="rounded-xl border border-red-500/30 bg-gray-900 p-5 sm:p-6 w-full max-w-sm sm:max-w-md mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Akun diblokir</h3>
                            <p class="text-white/70 text-sm mt-1">Akun anda diblokir. Silakan hubungi admin.</p>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition-colors shadow-lg">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            const btn = overlay.querySelector('button');
            btn.addEventListener('click', () => overlay.remove());
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.remove();
            });
            document.body.appendChild(overlay);
        }

        function getCurrentMinOrder() {
            return selectedMethod === 'group' ? groupMinOrderSetting : gamepassMinOrder;
        }
        
        // Show group info popup
        function showGroupInfoPopup() {
            const minDays = minMembershipDays || 14;
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            
            overlay.innerHTML = `
                <div class="rounded-xl border border-purple-400/30 bg-gray-900 p-5 sm:p-6 w-full max-w-sm sm:max-w-md mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Pembelian via Group</h3>
                        </div>
                    </div>
                    <div class="text-white/80 text-sm leading-relaxed mb-6 space-y-3">
                        <p>Pembelian via Group adalah metode pembelian Robux dengan cara bergabung ke group <strong class="text-white">` + groupName + `</strong>.</p>
                        <p>Jika Anda sudah bergabung selama <strong class="text-white">` + minDays + ` hari</strong> di group, Robux dapat langsung masuk dalam <strong class="text-purple-400">5-7 jam</strong> setelah pemesanan.</p>
                        <div class="bg-purple-500/10 border border-purple-400/30 rounded-lg p-3 mt-4">
                            <p class="text-xs text-purple-300"><strong>Catatan:</strong> Pastikan Anda sudah bergabung group minimal ` + minDays + ` hari sebelum melakukan pemesanan.</p>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button onclick="closeGroupInfoPopupAndSelect()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium transition-colors shadow-lg">
                            Lanjutkan
                        </button>
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            // Close on outside click
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    overlay.remove();
                }
            });
        }
        
        // Close popup and select group method
        function closeGroupInfoPopupAndSelect() {
            const overlay = document.querySelector('.fixed.inset-0.bg-black\\/60');
            if (overlay) overlay.remove();
            
            // Use selectMethod to ensure visual update
            selectMethod('group');
        }
        
        // Method selection handlers
        function selectMethod(method) {
            if (method === 'group') {
                window.location.href = '{{ route('user.search-group') }}';
                return;
            }

            // Update selected method
            selectedMethod = method;
            
            // Update radio buttons (both desktop and mobile)
            const radioGamepass = document.getElementById('radioGamepass');
            const radioGroup = document.getElementById('radioGroup');
            const radioGamepassMobile = document.getElementById('radioGamepassMobile');
            const radioGroupMobile = document.getElementById('radioGroupMobile');
            const methodGamepassDiv = document.getElementById('methodGamepass');
            const methodGroupDiv = document.getElementById('methodGroup');
            const groupInfoSection = document.getElementById('groupInfoSection');
            
            // Get mobile divs
            const mobileGamepassDiv = radioGamepassMobile?.closest('label')?.querySelector('div');
            const mobileGroupDiv = radioGroupMobile?.closest('label')?.querySelector('div');
            
            if (method === 'gamepass') {
                if (radioGamepass) radioGamepass.checked = true;
                if (radioGroup) radioGroup.checked = false;
                if (radioGamepassMobile) radioGamepassMobile.checked = true;
                if (radioGroupMobile) radioGroupMobile.checked = false;
                
                // Update classes for visual feedback (Desktop)
                if (methodGamepassDiv) {
                    methodGamepassDiv.classList.remove('border-white/15', 'bg-white/5');
                    methodGamepassDiv.classList.add('border-blue-400', 'bg-blue-500/10', 'shadow-lg', 'shadow-blue-500/20');
                }
                if (methodGroupDiv) {
                    methodGroupDiv.classList.remove('border-blue-400', 'bg-blue-500/10', 'shadow-lg', 'shadow-blue-500/20');
                    methodGroupDiv.classList.add('border-white/15', 'bg-white/5');
                }
                
                // Update classes for visual feedback (Mobile)
                if (mobileGamepassDiv) {
                    mobileGamepassDiv.classList.remove('border-white/15', 'bg-white/5');
                    mobileGamepassDiv.classList.add('border-blue-400', 'bg-blue-500/10');
                }
                if (mobileGroupDiv) {
                    mobileGroupDiv.classList.remove('border-blue-400', 'bg-blue-500/10');
                    mobileGroupDiv.classList.add('border-white/15', 'bg-white/5');
                }
                
                groupInfoSection.classList.add('hidden');
            } else {
                if (radioGamepass) radioGamepass.checked = false;
                if (radioGroup) radioGroup.checked = true;
                if (radioGamepassMobile) radioGamepassMobile.checked = false;
                if (radioGroupMobile) radioGroupMobile.checked = true;
                
                // Update classes for visual feedback (Desktop)
                if (methodGamepassDiv) {
                    methodGamepassDiv.classList.remove('border-blue-400', 'bg-blue-500/10', 'shadow-lg', 'shadow-blue-500/20');
                    methodGamepassDiv.classList.add('border-white/15', 'bg-white/5');
                }
                if (methodGroupDiv) {
                    methodGroupDiv.classList.remove('border-white/15', 'bg-white/5');
                    methodGroupDiv.classList.add('border-blue-400', 'bg-blue-500/10', 'shadow-lg', 'shadow-blue-500/20');
                }
                
                // Update classes for visual feedback (Mobile)
                if (mobileGamepassDiv) {
                    mobileGamepassDiv.classList.remove('border-blue-400', 'bg-blue-500/10');
                    mobileGamepassDiv.classList.add('border-white/15', 'bg-white/5');
                }
                if (mobileGroupDiv) {
                    mobileGroupDiv.classList.remove('border-white/15', 'bg-white/5');
                    mobileGroupDiv.classList.add('border-blue-400', 'bg-blue-500/10');
                }
                
                groupInfoSection.classList.remove('hidden');
            }
            
            // Reset proceed button state
            updateProceedButton();
            
            // Update harga per 100 display berdasarkan metode
            updatePricePer100Display();
            
            // CRITICAL: Immediately check discount and update total when method changes
            // This ensures discount is checked synchronously when user clicks a method
            // updateTotal is exposed to window object from IIFE below
            if (typeof window.updateTotal === 'function') {
                window.updateTotal();
            } else {
                // If updateTotal not yet defined (IIFE hasn't run yet), wait a bit
                setTimeout(() => {
                    if (typeof window.updateTotal === 'function') {
                        window.updateTotal();
                    }
                }, 100);
            }
        }
        
        // Update harga per 100 display berdasarkan metode yang dipilih
        function updatePricePer100Display() {
            const pricePer100Display = document.getElementById('pricePer100Display');
            if (!pricePer100Display) return;
            
            // Get current method
            const currentMethod = selectedMethod || 'gamepass';
            
            // Get harga berdasarkan metode
            // Gunakan variabel global yang sudah didefinisikan di atas
            const gamepassPrice = {{ (int) $robuxPricePer100 }};
            const groupPrice = typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : gamepassPrice;
            const currentPricePer100 = currentMethod === 'group' ? groupPrice : gamepassPrice;
            
            // Update display
            pricePer100Display.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(currentPricePer100);
        }
        
        // Expose to global scope untuk akses dari IIFE
        window.updatePricePer100Display = updatePricePer100Display;
        
        // Show group check button in user result
        function showGroupCheckButton() {
            const userResult = document.getElementById('userResult');
            const existingButton = userResult.querySelector('#groupCheckBtn');
            
            if (existingButton) {
                existingButton.remove();
            }
            
            const button = document.createElement('button');
            button.id = 'groupCheckBtn';
            button.type = 'button';
            button.className = 'mt-3 w-full sm:w-auto sm:ml-auto inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-purple-400/30 hover:bg-purple-500/20 text-purple-300 text-sm font-medium transition-colors shadow-lg';
            button.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Cek Keanggotaan Group
            `;
            
            button.addEventListener('click', checkGroupMembership);
            userResult.appendChild(button);
        }

        // Check group membership
        async function checkGroupMembership() {
            const username = document.getElementById('usernameInput').value.trim();
            if (!username) {
                showGroupPopup('error', 'Username Kosong', 'Silakan masukkan username Roblox terlebih dahulu.');
                return;
            }
            
            const checkBtn = document.getElementById('groupCheckBtn');
            const originalText = checkBtn.innerHTML;
            
            // Show loading state
            checkBtn.disabled = true;
            checkBtn.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Mengecek...
            `;
            
            try {
                const response = await fetch(`/api/roblox/group-membership?username=${encodeURIComponent(username)}`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                showGroupMembershipPopup(data);
                
            } catch (error) {
                console.error('Error checking group membership:', error);
                showGroupPopup('error', 'Gagal Mengecek', 'Gagal mengecek keanggotaan group. Silakan coba lagi.');
            } finally {
                checkBtn.disabled = false;
                checkBtn.innerHTML = originalText;
            }
        }
        
        // Show group membership popup
        function showGroupMembershipPopup(data) {
            const minDays = minMembershipDays || 14;
            // Use group name and link from API response if available, otherwise use global variables from settings
            const currentGroupName = data.group_name || groupName || 'Valtus Studios';
            const groupUrl = data.group_link || groupLink || 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about';
            
            if (data.success && data.is_member) {
                // User is member - show simple message
                showGroupPopup('success', 'Anda Sudah Bergabung', 
                    '✅ Anda sudah bergabung dengan group ' + currentGroupName + '.\n\nPastikan sudah bergabung selama ' + minDays + ' hari sebelum melakukan pemesanan.\n\nJika sudah memenuhi syarat, silakan lanjutkan pembelian.',
                    'Lanjutkan'
                );
                // Set group membership as valid
                groupMembershipValid = true;
                updateProceedButton();
            } else {
                // User is not a member
                showGroupPopup('error', 'Bukan Member Group', 
                    '❌ Anda belum bergabung dengan group ' + currentGroupName + '.\n\n📝 Langkah-langkah:\n1. Klik "Bergabung Group" di bawah\n2. Bergabung ke group ' + currentGroupName + '\n3. Pastikan sudah bergabung selama ' + minDays + ' hari\n4. Kembali ke sini untuk membeli Robux\n\nHarga tetap sama dengan metode gamepass.',
                    'Bergabung Group',
                    groupUrl
                );
                groupMembershipValid = false;
                updateProceedButton();
            }
        }
        
        // Show group popup
        function showGroupPopup(type, title, message, buttonText = 'OK', buttonUrl = null) {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            
            let iconClass = '';
            let iconSvg = '';
            let buttonClass = '';
            
            switch(type) {
                case 'success':
                    iconClass = 'bg-emerald-500/20';
                    iconSvg = `<svg class="w-8 h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`;
                    buttonClass = 'bg-emerald-600 hover:bg-emerald-700';
                    break;
                case 'warning':
                    iconClass = 'bg-yellow-500/20';
                    iconSvg = `<svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`;
                    buttonClass = 'bg-yellow-600 hover:bg-yellow-700';
                    break;
                case 'error':
                    iconClass = 'bg-red-500/20';
                    iconSvg = `<svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>`;
                    buttonClass = 'bg-red-600 hover:bg-red-700';
                    break;
            }
            
            overlay.innerHTML = `
                <div class="rounded-xl border border-white/15 bg-gray-900 p-5 sm:p-6 w-full max-w-sm sm:max-w-md mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl ${iconClass} flex items-center justify-center flex-shrink-0 shadow-lg">
                            ${iconSvg}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-lg font-semibold text-white">${title}</h3>
                        </div>
                    </div>
                    <p class="text-white/80 text-sm leading-relaxed mb-6 whitespace-pre-line">${message}</p>
                    <div class="flex justify-center">
                        ${buttonUrl ? `
                            <a href="${buttonUrl}" target="_blank" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg ${buttonClass} text-white font-medium transition-colors shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                ${buttonText}
                            </a>
                        ` : `
                            <button onclick="this.closest('.fixed').remove()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg ${buttonClass} text-white font-medium transition-colors shadow-lg">
                                ${buttonText}
                            </button>
                        `}
                    </div>
                </div>
            `;
            
            document.body.appendChild(overlay);
            
            // Close on outside click
            overlay.addEventListener('click', function(e) {
                if (e.target === this) {
                    overlay.remove();
                }
            });
        }
        
        // Update proceed button based on method and validation
        function updateProceedButton() {
            const proceedBtn = document.getElementById('proceedBtnRight');
            const username = document.getElementById('usernameInput').value.trim();
            const amount = parseInt(document.getElementById('amountInput').value) || 0;
            const minOrder = getCurrentMinOrder();

            const availableStock = selectedMethod === 'group' ? groupAvailableStock : gamepassAvailableStock;
            const stockSufficient = availableStock >= amount;

            let stockWarningEl = document.getElementById('stockWarning');
            if (!stockWarningEl) {
                stockWarningEl = document.createElement('div');
                stockWarningEl.id = 'stockWarning';
                stockWarningEl.className = 'mt-3 p-3 rounded-lg border border-red-500/30 bg-red-500/10 text-red-200 text-xs hidden';
                const target = proceedBtn?.parentElement;
                if (target) {
                    target.appendChild(stockWarningEl);
                }
            }
            
            let canProceed = false;
            let buttonText = 'Lanjutkan Pembelian';
            
            if (selectedMethod === 'gamepass') {
                // For gamepass method, check if username is validated
                const userResult = document.getElementById('userResult');
                const userBadge = document.getElementById('userBadge');
                canProceed = !userResult.classList.contains('hidden') && 
                           !userBadge.classList.contains('hidden') && 
                           amount >= minOrder;
            } else if (selectedMethod === 'group') {
                // For group method, allow proceeding but show warning if not validated
                canProceed = username.trim() !== '' && amount >= minOrder;
                buttonText = 'Lanjutkan via Group';
            }

            if (canProceed && !stockSufficient) {
                canProceed = false;
            }
            
            if (canProceed) {
                proceedBtn.disabled = false;
                proceedBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                proceedBtn.classList.add('hover:bg-gray-100');
            } else {
                proceedBtn.disabled = true;
                proceedBtn.classList.add('opacity-50', 'cursor-not-allowed');
                proceedBtn.classList.remove('hover:bg-gray-100');
            }

            if (!stockSufficient && amount > 0) {
                const methodLabel = selectedMethod === 'group' ? 'Group' : 'Gamepass';
                stockWarningEl.textContent = `Stok Robux (${methodLabel}) tidak mencukupi. Sisa stok: ${availableStock.toLocaleString('id-ID')} RBX. Silakan tunggu pengisian ulang.`;
                stockWarningEl.classList.remove('hidden');
            } else {
                stockWarningEl.classList.add('hidden');
            }
            
            proceedBtn.innerHTML = `
                <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                ${buttonText}
            `;
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

        async function persistOrderSession(payload) {
            try {
                const response = await fetch('/user/store-order-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                let data = null;
                try {
                    data = await response.json();
                } catch (parseError) {
                    // If response is not JSON, try to get text
                    const text = await response.text();
                    console.error('Failed to parse response as JSON:', text);
                    throw new Error('Server mengembalikan response yang tidak valid. Silakan refresh halaman dan coba lagi.');
                }

                if (!response.ok) {
                    const message = data && data.message ? data.message : `Gagal menyimpan data pesanan (HTTP ${response.status}).`;
                    console.error('storeOrderSession error:', {
                        status: response.status,
                        statusText: response.statusText,
                        data: data,
                        payload: payload
                    });
                    throw new Error(message);
                }

                if (!data || !data.success) {
                    const message = data && data.message ? data.message : 'Gagal menyimpan data pesanan.';
                    console.error('storeOrderSession failed:', data);
                    throw new Error(message);
                }

                // Success - small delay to ensure session is saved
                await new Promise(resolve => setTimeout(resolve, 100));
            } catch (error) {
                console.error('Error in persistOrderSession:', error);
                // Re-throw with better error message
                if (error.message) {
                    throw error;
                } else {
                    throw new Error('Gagal menyimpan data pesanan. Silakan coba lagi atau refresh halaman.');
                }
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
            
            // Add method selection event listeners
            const radioGamepass = document.getElementById('radioGamepass');
            const radioGroup = document.getElementById('radioGroup');
            const radioGamepassMobile = document.getElementById('radioGamepassMobile');
            const radioGroupMobile = document.getElementById('radioGroupMobile');
            
            // Desktop radio buttons
            if (radioGamepass) {
                radioGamepass.addEventListener('change', function() {
                    if (this.checked) {
                        selectMethod('gamepass');
                        if (radioGamepassMobile) radioGamepassMobile.checked = true;
                        // updateTotal() already called in selectMethod()
                    }
                });
            }
            
            if (radioGroup) {
                radioGroup.addEventListener('change', function() {
                    if (this.checked) {
                        // Direct redirect to search-group page (no popup)
                        window.location.href = '{{ route("user.search-group") }}';
                    }
                });
            }
            
            // Mobile radio buttons
            if (radioGamepassMobile) {
                radioGamepassMobile.addEventListener('change', function() {
                    if (this.checked) {
                        selectMethod('gamepass');
                        if (radioGamepass) radioGamepass.checked = true;
                        // updateTotal() already called in selectMethod()
                    }
                });
            }
            
            if (radioGroupMobile) {
                radioGroupMobile.addEventListener('change', function() {
                    if (this.checked) {
                        // Direct redirect to search-group page (no popup)
                        window.location.href = '{{ route("user.search-group") }}';
                    }
                });
            }
            
            // Prevent default onclick on methodGroup div when radio is clicked
            const methodGroupDiv = document.getElementById('methodGroup');
            if (methodGroupDiv) {
                methodGroupDiv.addEventListener('click', function(e) {
                    if (e.target.closest('input[type="radio"]') || e.target.closest('.rounded-full')) {
                        return;
                    }
                    // Let the label handle it
                });
            }
            
            // Initialize selected state on page load
            const methodGamepassDiv = document.getElementById('methodGamepass');
            if (radioGamepass && radioGamepass.checked && methodGamepassDiv) {
                methodGamepassDiv.classList.remove('border-white/15', 'bg-white/5');
                methodGamepassDiv.classList.add('border-blue-400', 'bg-blue-500/10', 'shadow-lg', 'shadow-blue-500/20');
            }
            
            // Initialize mobile selected state on page load
            if (radioGamepassMobile && radioGamepassMobile.checked) {
                const mobileGamepassDiv = radioGamepassMobile.closest('label')?.querySelector('div');
                if (mobileGamepassDiv) {
                    mobileGamepassDiv.classList.remove('border-white/15', 'bg-white/5');
                    mobileGamepassDiv.classList.add('border-blue-400', 'bg-blue-500/10');
                }
            }
        });
        
        (function(){
            // Session validation
            const pricePer100 = {{ (int) $robuxPricePer100 }};
            // Note: groupRobuxPricePer100, minMembershipDays, and groupLink are already defined globally above
            
            if (!pricePer100 || pricePer100 <= 0) {
                alert('Harga tidak valid. Silakan refresh halaman.');
                window.location.href = '/';
                return;
            }
            
            if (!gamepassMinOrder || gamepassMinOrder <= 0) {
                alert('Minimum order tidak valid. Silakan refresh halaman.');
                window.location.href = '/';
                return;
            }
            
            const checkBtn = document.getElementById('checkBtn');
            const usernameInput = document.getElementById('usernameInput');
            const userResult = document.getElementById('userResult');
            const userAvatar = document.getElementById('userAvatar');
            const userName = document.getElementById('userName');
            const userId = document.getElementById('userId');
            const userBadge = document.getElementById('userBadge');
            const proceedBtnRight = document.getElementById('proceedBtnRight');
            const tabCustom = document.getElementById('tabCustom');
            const tabQuick = document.getElementById('tabQuick');
            const customWrap = document.getElementById('customWrap');
            const quickWrap = document.getElementById('quickWrap');
            const amtInput = document.getElementById('amountInput');
            const customAmount = document.getElementById('customAmount');
            const totalPrice = document.getElementById('totalPrice');
            const summaryAmount = document.getElementById('summaryAmount');
            
            // Define updateTotal function FIRST, before any event listeners
            // This ensures it's available when selectMethod calls it

            function setMode(mode){
                if(mode==='quick'){
                    tabCustom.className = 'px-3 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white';
                    tabQuick.className = 'px-3 py-1.5 rounded-md bg-white/10 text-white';
                    customWrap.classList.add('hidden');
                    quickWrap.classList.remove('hidden');
                } else {
                    tabCustom.className = 'px-3 py-1.5 rounded-md bg-white/10 text-white';
                    tabQuick.className = 'px-3 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white';
                    customWrap.classList.remove('hidden');
                    quickWrap.classList.add('hidden');
                }
            }

            function getRawAmount() {
                if (customAmount && customWrap && !customWrap.classList.contains('hidden')) {
                    const value = customAmount.value.trim();
                    if (value === '') return 0;
                    const parsed = parseInt(value, 10);
                    return isNaN(parsed) ? 0 : parsed;
                }
                const hiddenValue = amtInput.value && amtInput.value.toString().trim();
                if (!hiddenValue) return 0;
                const parsed = parseInt(hiddenValue, 10);
                return isNaN(parsed) ? 0 : parsed;
            }

            async function updateTotal(){
                // 1. Baca jumlah dari input (boleh kosong saat mengetik)
                const rawAmount = Math.max(0, getRawAmount());
                const minOrder = getCurrentMinOrder();
                const isAmountValid = rawAmount >= minOrder;
                
                // Simpan nilai mentah di hidden input
                if (amtInput) {
                    amtInput.value = rawAmount > 0 ? rawAmount : '';
                }
                
                // 2. Ambil metode yang dipilih (terbaru)
                const currentMethod = selectedMethod || 'gamepass';
                
                // 3. Hitung harga dasar berdasarkan metode yang dipilih
                const currentPricePer100 = currentMethod === 'group' 
                    ? (typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : pricePer100)
                    : pricePer100;
                const basePrice = isAmountValid ? currentPricePer100 * (rawAmount / 100) : 0;
                
                // 4. Get DOM elements
                const totalPriceEl = document.getElementById('totalPrice');
                const summaryTotalEl = document.getElementById('summaryTotal');
                const discountInfo = document.getElementById('discountInfo');
                const discountAmountEl = document.getElementById('discountAmount');
                const discountMethodEl = document.getElementById('discountMethod');
                const finalPriceInfo = document.getElementById('finalPriceInfo');
                const finalPriceEl = document.getElementById('finalPrice');
                const referralInfo = document.getElementById('referralInfo');
                const referralDiscountAmountEl = document.getElementById('referralDiscountAmount');
                const summaryDiscountInfo = document.getElementById('summaryDiscountInfo');
                const summaryDiscountAmountEl = document.getElementById('summaryDiscountAmount');
                const summaryDiscountMethodEl = document.getElementById('summaryDiscountMethod');
                const summaryFinalPrice = document.getElementById('summaryFinalPrice');
                const summaryFinalPriceAmountEl = document.getElementById('summaryFinalPriceAmount');
                const referralActiveBanner = document.getElementById('referralActiveBanner');
                const referralActiveCode = document.getElementById('referralActiveCode');
                
                // 5. Reset discount info & tampilkan harga normal dulu
                if (discountInfo) discountInfo.classList.add('hidden');
                if (finalPriceInfo) finalPriceInfo.classList.add('hidden');
                if (referralInfo) referralInfo.classList.add('hidden');
                if (summaryDiscountInfo) summaryDiscountInfo.classList.add('hidden');
                if (summaryFinalPrice) summaryFinalPrice.classList.add('hidden');
                if (referralActiveBanner) referralActiveBanner.classList.add('hidden');
                
                if (totalPriceEl) {
                    totalPriceEl.innerHTML = '';
                    totalPriceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice);
                }
                if (summaryTotalEl) {
                    summaryTotalEl.innerHTML = '';
                    summaryTotalEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(basePrice);
                }
                
                if (!isAmountValid) {
                    if (summaryAmount) {
                        summaryAmount.textContent = rawAmount > 0 ? rawAmount + ' RBX' : '--';
                    }
                    if (typeof updateProceedButton === 'function') {
                        updateProceedButton();
                    }
                    return;
                }
                
                const amt = rawAmount;
                
                // 6. Tarik API diskon untuk metode yang dipilih
                try {
                    const timestamp = new Date().getTime();
                    const discountResponse = await fetch(`/api/robux/discount?amount=${amt}&purchase_method=${currentMethod}&_t=${timestamp}`);
                    const discountData = await discountResponse.json();
                    
                    // Cek metode masih sama setelah fetch
                    const methodAfterFetch = selectedMethod || 'gamepass';
                    if (methodAfterFetch !== currentMethod) {
                        // Metode berubah saat fetch, abaikan hasil ini
                        if (summaryAmount) {
                            summaryAmount.textContent = amt + ' RBX';
                        }
                        return;
                    }
                    
                    // 7. Update tampilan berdasarkan hasil diskon
                    if (discountData.has_discount) {
                        // Ada diskon - tampilkan info diskon
                        // Gunakan base_price dari API (lebih akurat, sudah sesuai dengan method)
                        const apiBasePrice = discountData.base_price || basePrice;
                        const discountMethodName = currentMethod === 'gamepass' ? 'Gamepass' : 'Group';
                        const finalPrice = discountData.final_price;
                        const discountAmount = discountData.discount_amount;
                        const referralDiscountAmount = discountData.referral_discount_amount || 0;
                        const referralApplied = !!discountData.referral_applied;
                        const referralActive = !!discountData.referral_active;
                        const referralCode = discountData.referral_code;
                        
                        // Tampilkan harga dasar dengan strikethrough (gunakan dari API)
                        if (totalPriceEl) totalPriceEl.innerHTML = '<span class="line-through text-white/50 text-base">Rp ' + new Intl.NumberFormat('id-ID').format(apiBasePrice) + '</span>';
                        if (summaryTotalEl) summaryTotalEl.innerHTML = '<span class="line-through text-white/50 text-xl">Rp ' + new Intl.NumberFormat('id-ID').format(apiBasePrice) + '</span>';
                        
                        // Tampilkan info diskon
                        if (discountInfo) discountInfo.classList.remove('hidden');
                        if (discountAmountEl) discountAmountEl.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(discountAmount);
                        if (discountMethodEl) {
                            let discountLabel = 'Diskon ' + discountMethodName;
                            if (referralApplied) {
                                discountLabel += ' + Referral';
                            }
                            discountMethodEl.textContent = discountLabel;
                        }

                        if (referralActive && referralCode && referralActiveBanner && referralActiveCode) {
                            referralActiveCode.textContent = '(' + referralCode + ')';
                            referralActiveBanner.classList.remove('hidden');
                        }
                        if (referralApplied && referralInfo) {
                            referralInfo.classList.remove('hidden');
                            if (referralDiscountAmountEl) {
                                referralDiscountAmountEl.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(referralDiscountAmount);
                            }
                        }
                        
                        if (finalPriceInfo) finalPriceInfo.classList.remove('hidden');
                        if (finalPriceEl) finalPriceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);
                        
                        // Summary discount info
                        if (summaryDiscountInfo) summaryDiscountInfo.classList.remove('hidden');
                        if (summaryDiscountAmountEl) summaryDiscountAmountEl.textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(discountAmount);
                        if (summaryDiscountMethodEl) {
                            let summaryDiscountLabel = 'Diskon ' + discountMethodName;
                            if (referralApplied) {
                                summaryDiscountLabel += ' + Referral';
                            }
                            summaryDiscountMethodEl.textContent = summaryDiscountLabel;
                        }
                        
                        if (summaryFinalPrice) summaryFinalPrice.classList.remove('hidden');
                        if (summaryFinalPriceAmountEl) summaryFinalPriceAmountEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice);
                    } else {
                        // Tidak ada diskon - gunakan base_price dari API (lebih akurat)
                        // API sudah mengembalikan base_price yang sesuai dengan method (gamepass/group)
                        const apiBasePrice = discountData.base_price || basePrice;
                        const referralActive = !!discountData.referral_active;
                        const referralCode = discountData.referral_code;
                        if (totalPriceEl) {
                            totalPriceEl.innerHTML = '';
                            totalPriceEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(apiBasePrice);
                        }
                        if (summaryTotalEl) {
                            summaryTotalEl.innerHTML = '';
                            summaryTotalEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(apiBasePrice);
                        }

                        if (referralActive && referralCode && referralActiveBanner && referralActiveCode) {
                            referralActiveCode.textContent = '(' + referralCode + ')';
                            referralActiveBanner.classList.remove('hidden');
                        }
                    }
                    
                    if (summaryAmount) {
                        summaryAmount.textContent = amt + ' RBX';
                    }
                } catch (error) {
                    console.error('Error fetching discount:', error);
                    // Jika error, tetap tampilkan harga normal
                    if (summaryAmount) {
                        summaryAmount.textContent = amt + ' RBX';
                    }
                }

                if (typeof updateProceedButton === 'function') {
                    updateProceedButton();
                }
            }
            
            // Expose updateTotal to global scope IMMEDIATELY after definition
            // This ensures it's available when selectMethod calls it
            window.updateTotal = updateTotal;

            const chips = Array.from(document.querySelectorAll('.chip'));
            function highlightChip(active){
                chips.forEach(c=>{
                    c.classList.remove('ring-2','ring-emerald-400','bg-white/10');
                });
                if(active){ active.classList.add('ring-2','ring-emerald-400','bg-white/10'); }
            }
            chips.forEach(btn=>{
                btn.addEventListener('click',()=>{
                    highlightChip(btn);
                    const val = parseInt((btn.querySelector('input[type="hidden"]').getAttribute('data-val')),10);
                    amtInput.value = val;
                    customAmount.value = val;
                    updateTotal();
                    // Clear session amount when user manually changes
                    sessionStorage.setItem('amount_changed', 'true');
                    if (typeof updateProceedButton === 'function') {
                        updateProceedButton();
                    }
                });
            });

            customAmount.addEventListener('input',()=>{
                const rawValue = customAmount.value.trim();
                const val = rawValue === '' ? 0 : (parseInt(rawValue,10) || 0);
                amtInput.value = rawValue === '' ? '' : val;
                // Update total with current method
                updateTotal();
                
                // Show warning if below minimum
                const currentMinOrder = getCurrentMinOrder();
                if(val > 0 && val < currentMinOrder) {
                    customAmount.style.borderColor = '#ef4444';
                    customAmount.title = `Minimal ${currentMinOrder} Robux`;
                } else {
                    customAmount.style.borderColor = '';
                    customAmount.title = '';
                }
                
                // Clear session amount when user manually changes
                sessionStorage.setItem('amount_changed', 'true');
                
                if (typeof updateProceedButton === 'function') {
                    updateProceedButton();
                }
            });

            // tab switching
            tabCustom.addEventListener('click', ()=>{
                if(!customWrap.classList.contains('hidden')) return;
                // sinkronkan nilai terakhir (boleh kosong)
                customAmount.value = amtInput.value || '';
                setMode('custom');
                updateTotal();
            });
            tabQuick.addEventListener('click', ()=>{
                setMode('quick');
                // pilih chip minimal order secara default
                const defaultMinOrder = getCurrentMinOrder();
                const defaultChip = chips.find(c => parseInt(c.getAttribute('data-val'),10) === defaultMinOrder) || chips[0];
                if(defaultChip){ defaultChip.click(); }
            });

            // proceed buttons trigger form submit
            const form = document.querySelector('form');
            document.getElementById('proceedBtnRight').addEventListener('click', async ()=> {
                    const minOrder = getCurrentMinOrder();
                    const amt = Math.max(minOrder, parseInt(amtInput.value||0,10));
                    const username = (usernameInput.value||'').trim();
                    
                    // Show warning if user tried to input below minimum
                    if(parseInt(amtInput.value||0,10) < minOrder) {
                        alert(`Jumlah ${amtInput.value} Robux terlalu kecil. Minimal order ${minOrder} Robux. Akan menggunakan ${minOrder} Robux.`);
                        amtInput.value = minOrder;
                        customAmount.value = minOrder;
                        updateTotal();
                        return;
                    }

                // Handle Group Method - Skip gamepass check, go directly to payment
                if (selectedMethod === 'group') {
                    // Just validate username exists
                    if (!username || username.trim() === '') {
                        alert('Silakan masukkan username Roblox terlebih dahulu.');
                        usernameInput.focus();
                        return;
                    }
                    
                    // Quick username validation
                    try {
                        const check = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`);
                        const cj = await check.json();
                        if (!(cj && cj.ok && cj.found)) {
                            alert('Username tidak ditemukan. Silakan cek kembali username Anda.');
                            return;
                        }
                        
                        // Get current discount data before storing
                        const discountData = await fetch(`/api/robux/discount?amount=${amt}&purchase_method=group&_t=${Date.now()}`).then(r => r.json()).catch(() => ({ has_discount: false }));
                        
                        const basePriceFromAPI = typeof discountData.base_price === 'number'
                            ? discountData.base_price
                            : (typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : pricePer100) * (amt / 100);
                        const finalPriceFromAPI = discountData.has_discount && typeof discountData.final_price === 'number'
                            ? discountData.final_price
                            : basePriceFromAPI;
                        const discountAmountFromAPI = discountData.has_discount && typeof discountData.discount_amount === 'number'
                            ? discountData.discount_amount
                            : 0;

                        try {
                            await persistOrderSession({
                                username: username,
                                amount: amt,
                                purchase_method: 'group',
                                base_price: basePriceFromAPI,
                                discount_amount: discountAmountFromAPI,
                                final_price: finalPriceFromAPI,
                                has_discount: !!discountData.has_discount
                            });
                        } catch (storeError) {
                            console.error('Error storing session data:', storeError);
                            alert(storeError.message || 'Gagal menyimpan data pesanan. Silakan coba lagi.');
                            return;
                        }
                        
                        // Redirect directly to payment page
                        window.location.href = `{{ route('user.payment') }}`;
                        return;
                    } catch (error) {
                        console.error('Error validating username for group method:', error);
                        alert('Gagal memvalidasi username. Silakan coba lagi.');
                        return;
                    }
                }

                // Gamepass Method - Show warning popup first
                const warningOverlay = document.createElement('div');
                warningOverlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
                warningOverlay.innerHTML = `
                    <div class="rounded-xl border border-yellow-400/30 bg-gray-900 p-6 w-full max-w-md mx-4">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center flex-shrink-0 shadow-lg">
                                <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-white">Penting: Nama Gamepass</h3>
                            </div>
                        </div>
                        <div class="text-white/80 text-sm leading-relaxed mb-6 space-y-3">
                            <p>Untuk keamanan dan menghindari deteksi Roblox, mohon pastikan:</p>
                            <div class="bg-yellow-500/10 border border-yellow-400/30 rounded-lg p-3">
                                <p class="text-yellow-200 font-medium mb-2">❌ Nama gamepass TIDAK boleh mengandung kata-kata:</p>
                                <ul class="list-disc list-inside text-yellow-200/90 text-xs space-y-1">
                                    <li>Top Up / Topup</li>
                                    <li>Robux</li>
                                    <li>Beli</li>
                                    <li>Jual</li>
                                    <li>Purchase</li>
                                </ul>
                            </div>
                            <p class="text-white/90">Gunakan nama gamepass yang umum, misalnya: "Item", "Package", "Upgrade", dll.</p>
                        </div>
                        <div class="flex gap-3">
                            <button id="warningCancelBtn" class="flex-1 px-4 py-3 rounded-lg border border-white/15 hover:bg-white/10 text-white text-sm font-medium transition-colors">
                                Batal
                            </button>
                            <button id="warningContinueBtn" class="flex-1 px-4 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors">
                                Saya Mengerti, Lanjutkan
                            </button>
                        </div>
                    </div>
                `;
                document.body.appendChild(warningOverlay);
                
                // Handle cancel button
                document.getElementById('warningCancelBtn').addEventListener('click', () => {
                    warningOverlay.remove();
                });
                
                // Handle continue button - proceed with gamepass check
                document.getElementById('warningContinueBtn').addEventListener('click', () => {
                    warningOverlay.remove();
                    
                    // Now proceed with gamepass check
                    proceedWithGamepassCheck();
                });
                
                // Close on outside click
                warningOverlay.addEventListener('click', function(e) {
                    if (e.target === this) {
                        warningOverlay.remove();
                    }
                });
            });
            
            // Extracted function to proceed with gamepass check
            async function proceedWithGamepassCheck() {
                const minOrder = getCurrentMinOrder();
                const amt = Math.max(minOrder, parseInt(amtInput.value||0,10));
                const username = (usernameInput.value||'').trim();
                
                // Popup loading modal
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                overlay.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-white/5 p-6 text-center w-[min(90vw,520px)]">
                        <div class="text-lg font-semibold text-white">Mencari GamePass...</div>
                        <div class="mt-2 text-white/70 text-sm">Kami sedang mengecek apakah tersedia gamepass sesuai harga.</div>
                        <div class="mt-5 inline-block h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white"></div>
                    </div>`;
                document.body.appendChild(overlay);

                try{
                    // Ensure username was validated previously by checking result visible and badge not hidden
                    if(selectedMethod === 'gamepass' && (userResult.classList.contains('hidden') || userBadge.classList.contains('hidden'))){
                        throw new Error('USERNAME_NOT_VERIFIED');
                    }

                    // We need userId -> recheck silently via API (cheap)
                    const check = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`);
                    const cj = await check.json();
                    if(!(cj && cj.ok && cj.found && cj.id)) throw new Error('USERNAME_NOT_FOUND');

                    const gp = await fetch(`{{ route('api.roblox.gamepass') }}?userId=${cj.id}&amount=${amt}`);
                    const gj = await gp.json();
                    overlay.remove();

                    if(gj && gj.ok && gj.found){
                        // Get current discount data before storing
                        const currentMethod = selectedMethod || 'gamepass';
                        const discountData = await fetch(`/api/robux/discount?amount=${amt}&purchase_method=${currentMethod}&_t=${Date.now()}`).then(r => r.json()).catch(() => ({ has_discount: false }));
                        
                        const fallbackPricePer100 = currentMethod === 'group'
                            ? (typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : pricePer100)
                            : pricePer100;
                        const basePriceFromAPI = typeof discountData.base_price === 'number'
                            ? discountData.base_price
                            : fallbackPricePer100 * (amt / 100);
                        const finalPriceFromAPI = discountData.has_discount && typeof discountData.final_price === 'number'
                            ? discountData.final_price
                            : basePriceFromAPI;
                        const discountAmountFromAPI = discountData.has_discount && typeof discountData.discount_amount === 'number'
                            ? discountData.discount_amount
                            : 0;

                        try {
                            await persistOrderSession({
                                username: username,
                                amount: amt,
                                purchase_method: currentMethod,
                                base_price: basePriceFromAPI,
                                discount_amount: discountAmountFromAPI,
                                final_price: finalPriceFromAPI,
                                has_discount: !!discountData.has_discount,
                                gamepass_link: gj.gamepass_link || null
                            });
                        } catch (storeError) {
                            console.error('Error storing session data:', storeError);
                            alert(storeError.message || 'Gagal menyimpan data pesanan. Silakan coba lagi.');
                            return;
                        }
                        
                        // Continue to next step (redirect to payment without params)
                        window.location.href = `{{ route('user.payment') }}`;
                        return;
                    }

                    // Show instruction modal if not found
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,680px)]">
                            <div class="flex items-center justify-between">
                                <div class="text-xl font-semibold text-white">Buat dan Atur Gamepass</div>
                                <button class="text-white/60 hover:text-white" id="modalClose">✕</button>
                            </div>
                                <div class="mt-4 text-center">
                                    <div class="text-2xl font-semibold text-white">Buat Gamepass Seharga <span class="text-emerald-400">R$${(gj.requiredPrice || Math.ceil(amt * (100 / 70)))}</span></div>
                                    <p class="mt-2 text-white/70">Buat gamepass di Roblox dengan harga di atas agar setelah Roblox potong 30%, Anda mendapat <span class="text-emerald-400">${amt} Robux</span> yang pas.</p>
                                    <p class="mt-1 text-white/60 text-sm">Harga yang Anda bayar tetap sama: <span class="text-white font-medium">Rp ${new Intl.NumberFormat('id-ID').format({{ $robuxPricePer100 }} * (amt/100))}</span></p>
                                <div class="mt-5 aspect-video rounded-lg overflow-hidden border border-white/10">
                                    @php
                                        $caraGamepassVideo = \App\Models\Setting::getValue('cara_bikin_gamepass_video', '');
                                        $caraGamepassVideoType = \App\Models\Setting::getValue('cara_bikin_gamepass_video_type', 'file');
                                        $caraGamepassVideoUrl = \App\Models\Setting::getValue('cara_bikin_gamepass_video_url', '');
                                    @endphp
                                    @if($caraGamepassVideoType === 'file' && $caraGamepassVideo)
                                        <video class="w-full h-full" controls>
                                            <source src="{{ asset($caraGamepassVideo) }}" type="video/mp4">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                    @elseif($caraGamepassVideoType === 'url' && $caraGamepassVideoUrl)
                                        @if(str_contains($caraGamepassVideoUrl, 'youtube.com') || str_contains($caraGamepassVideoUrl, 'youtu.be'))
                                            @php
                                                $videoId = '';
                                                if (str_contains($caraGamepassVideoUrl, 'youtube.com/watch?v=')) {
                                                    $videoId = explode('v=', $caraGamepassVideoUrl)[1];
                                                    $videoId = explode('&', $videoId)[0];
                                                } elseif (str_contains($caraGamepassVideoUrl, 'youtu.be/')) {
                                                    $videoId = explode('youtu.be/', $caraGamepassVideoUrl)[1];
                                                    $videoId = explode('?', $videoId)[0];
                                                }
                                            @endphp
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                        @else
                                            <video class="w-full h-full" controls>
                                                <source src="{{ $caraGamepassVideoUrl }}" type="video/mp4">
                                                Browser Anda tidak mendukung video.
                                            </video>
                                        @endif
                                    @else
                                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Cara buat gamepass" allowfullscreen></iframe>
                                    @endif
                                </div>
                                <div class="mt-5 grid gap-3">
                                    <a target="_blank" href="https://www.roblox.com/develop" class="inline-flex items-center justify-center gap-2 rounded-md border border-white/20 px-4 py-2 hover:bg-white/5 text-white">Atur Gamepass ↗</a>
                                    <button id="modalDone" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-white">Sudah Mengatur Gamepass</button>
                                </div>
                            </div>
                        </div>`;
                    document.body.appendChild(modal);
                    
                    // Close modal when clicking outside
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            // Stop all videos in gamepass modal
                            const videos = modal.querySelectorAll('video, iframe');
                            videos.forEach(video => {
                                if (video.tagName === 'VIDEO') {
                                    video.pause();
                                    video.currentTime = 0;
                                } else if (video.tagName === 'IFRAME') {
                                    video.src = video.src; // Reload iframe to stop video
                                }
                            });
                            modal.remove();
                        }
                    });
                    
                    modal.querySelector('#modalClose').addEventListener('click', ()=> {
                        // Stop all videos in gamepass modal
                        const videos = modal.querySelectorAll('video, iframe');
                        videos.forEach(video => {
                            if (video.tagName === 'VIDEO') {
                                video.pause();
                                video.currentTime = 0;
                            } else if (video.tagName === 'IFRAME') {
                                video.src = video.src; // Reload iframe to stop video
                            }
                        });
                        modal.remove();
                    });
                    modal.querySelector('#modalDone').addEventListener('click', ()=> { 
                        // Stop all videos in gamepass modal
                        const videos = modal.querySelectorAll('video, iframe');
                        videos.forEach(video => {
                            if (video.tagName === 'VIDEO') {
                                video.pause();
                                video.currentTime = 0;
                            } else if (video.tagName === 'IFRAME') {
                                video.src = video.src; // Reload iframe to stop video
                            }
                        });
                        modal.remove(); 
                    });

                }catch(err){
                    overlay.remove();
                    // Simple fallback toast
                    alert('Gagal memeriksa gamepass. Coba lagi.');
                }
            }

            // username check via our backend proxy to Roblox
            async function checkUsername(){
                const u = (usernameInput.value || '').trim();
                if(!u){ usernameInput.focus(); return; }
                checkBtn.disabled = true;
                checkBtn.textContent = 'Mengecek...';
                try{
                    const res = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(u)}`);
                    const j = await res.json();
                    if(j && j.ok && j.found){
                        if (j.blacklisted) {
                            userAvatar.classList.add('hidden');
                            userName.textContent = 'Akun diblokir';
                            userId.textContent = '';
                            userId.classList.add('hidden');
                            userBadge.textContent = 'Diblokir';
                            userBadge.classList.remove('hidden');
                            userBadge.classList.remove('bg-emerald-500/15','text-emerald-300','border-emerald-500/30');
                            userBadge.classList.add('bg-red-500/15','text-red-300','border-red-500/30');

                            proceedBtnRight.disabled = true;
                            proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');

                            userResult.classList.remove('hidden');
                            showBlacklistedPopup();
                            return;
                        }
                        if (j.avatar) {
                            userAvatar.src = j.avatar;
                            userAvatar.classList.remove('hidden');
                        } else {
                            userAvatar.classList.add('hidden');
                        }
                        userName.textContent = j.displayName || j.name || u;
                        userId.textContent = '';
                        userId.classList.add('hidden');
                        userBadge.textContent = 'Valid';
                        userBadge.classList.remove('hidden');
                        userBadge.classList.remove('bg-red-500/15','text-red-300','border-red-500/30');
                        userBadge.classList.add('bg-emerald-500/15','text-emerald-300','border-emerald-500/30');
                        
                        // Show group check button if group method is selected
                        if (selectedMethod === 'group') {
                            showGroupCheckButton();
                        }
                        
                        // enable proceed
                        proceedBtnRight.disabled = false;
                        proceedBtnRight.classList.remove('opacity-50','cursor-not-allowed');
                    }else{
                        userAvatar.classList.add('hidden');
                        userName.textContent = 'Username tidak ditemukan';
                        userId.textContent = '';
                        userBadge.classList.add('hidden');
                        // disable proceed
                        proceedBtnRight.disabled = true;
                        proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                    }
                    userResult.classList.remove('hidden');
                }catch(e){
                    userName.textContent = 'Gagal mengecek';
                    userId.textContent = '';
                    userResult.classList.remove('hidden');
                    userBadge.classList.add('hidden');
                    proceedBtnRight.disabled = true;
                    proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                }finally{
                    checkBtn.disabled = false;
                    checkBtn.textContent = 'Cek';
                }
            }
            checkBtn.addEventListener('click', checkUsername);
            usernameInput.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); checkUsername(); }});

            // Set initial amount from session if available and user hasn't changed it
            const sessionAmount = {{ (int) (session('selected_amount') ?? 0) }};
            const amountChanged = sessionStorage.getItem('amount_changed') === 'true';
            
            if(sessionAmount > 0 && !amountChanged) {
                amtInput.value = sessionAmount;
                customAmount.value = sessionAmount;
                setMode('custom');
            } else {
                // Default to minimal order if no session amount or user changed it
                const defaultAmount = Math.max(gamepassMinOrder, 100);
                amtInput.value = defaultAmount;
                customAmount.value = defaultAmount;
                setMode('custom');
            }
            updateTotal();
            
            // Update harga per 100 display saat pertama kali load
            if (typeof window.updatePricePer100Display === 'function') {
                window.updatePricePer100Display();
            }
        })();

        // Simple mobile menu toggle function
        function toggleMobileMenu() {
            console.log('Mobile menu button clicked!');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                console.log('Mobile menu toggled, hidden:', mobileMenu.classList.contains('hidden'));
            } else {
                console.error('Mobile menu not found!');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            
            if (mobileMenu && mobileMenuBtn && 
                !mobileMenuBtn.contains(event.target) && 
                !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        function showCaraBeliVideo() {
            document.getElementById('caraBeliModal').classList.remove('hidden');
        }

        function hideCaraBeliVideo() {
            // Stop all videos in cara beli modal
            const videos = document.querySelectorAll('#caraBeliModal video, #caraBeliModal iframe');
            videos.forEach(video => {
                if (video.tagName === 'VIDEO') {
                    video.pause();
                    video.currentTime = 0;
                } else if (video.tagName === 'IFRAME') {
                    // For YouTube iframes, we can't directly control them, but we can hide them
                    video.src = video.src; // This will reload the iframe and stop the video
                }
            });
            document.getElementById('caraBeliModal').classList.add('hidden');
        }

        function showHelpModal(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            document.getElementById('helpModal').classList.remove('hidden');
            loadContactInfo();
            return false;
        }

        function hideHelpModal() {
            document.getElementById('helpModal').classList.add('hidden');
        }

        async function loadContactInfo() {
            try {
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                
                // Reset states
                if (contactList) {
                    contactList.innerHTML = '';
                    contactList.classList.remove('hidden');
                }
                if (noContactMessage) {
                    noContactMessage.classList.add('hidden');
                }
                
                const response = await fetch('/api/contact-info');
                const data = await response.json();
                
                if (data.contacts && data.contacts.length > 0) {
                    if (noContactMessage) {
                        noContactMessage.classList.add('hidden');
                    }
                    
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
        }

        // Close modal when clicking outside
        document.getElementById('helpModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideHelpModal();
            }
        });

        // Close cara beli modal when clicking outside
        document.getElementById('caraBeliModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideCaraBeliVideo();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideHelpModal();
                hideCaraBeliVideo();
                // Close any open gamepass modal
                const gamepassModal = document.querySelector('.fixed.inset-0.bg-black\\/60');
                if (gamepassModal) {
                    // Stop all videos in gamepass modal
                    const videos = gamepassModal.querySelectorAll('video, iframe');
                    videos.forEach(video => {
                        if (video.tagName === 'VIDEO') {
                            video.pause();
                            video.currentTime = 0;
                        } else if (video.tagName === 'IFRAME') {
                            video.src = video.src; // Reload iframe to stop video
                        }
                    });
                    gamepassModal.remove();
                }
            }
        });
    </script>
</main>

<!-- Cara Beli Video Modal -->
<div id="caraBeliModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/20 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-white">Cara Beli Robux</h3>
                <button onclick="hideCaraBeliVideo()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="aspect-video rounded-lg overflow-hidden border border-white/10">
                @php
                    $caraBeliVideo = \App\Models\Setting::getValue('cara_beli_video', '');
                    $caraBeliVideoType = \App\Models\Setting::getValue('cara_beli_video_type', 'file');
                    $caraBeliVideoUrl = \App\Models\Setting::getValue('cara_beli_video_url', '');
                @endphp
                @if($caraBeliVideoType === 'file' && $caraBeliVideo)
                    <video class="w-full h-full" controls>
                        <source src="{{ asset($caraBeliVideo) }}" type="video/mp4">
                        Browser Anda tidak mendukung video.
                    </video>
                @elseif($caraBeliVideoType === 'url' && $caraBeliVideoUrl)
                    @if(str_contains($caraBeliVideoUrl, 'youtube.com') || str_contains($caraBeliVideoUrl, 'youtu.be'))
                        @php
                            $videoId = '';
                            if (str_contains($caraBeliVideoUrl, 'youtube.com/watch?v=')) {
                                $videoId = explode('v=', $caraBeliVideoUrl)[1];
                                $videoId = explode('&', $videoId)[0];
                            } elseif (str_contains($caraBeliVideoUrl, 'youtu.be/')) {
                                $videoId = explode('youtu.be/', $caraBeliVideoUrl)[1];
                                $videoId = explode('?', $videoId)[0];
                            }
                        @endphp
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    @else
                        <video class="w-full h-full" controls>
                            <source src="{{ $caraBeliVideoUrl }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-800 text-gray-400">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <p>Video cara beli belum tersedia</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

<style>
/* Simple mobile menu styles */
#mobile-menu-btn {
    cursor: pointer !important;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    pointer-events: auto !important;
    z-index: 9999 !important;
    position: relative !important;
}

#mobile-menu-btn:active {
    transform: scale(0.95);
}

#mobile-menu-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Ensure mobile menu is properly positioned */
#mobile-menu {
    z-index: 40;
}

/* Radio button styling improvements */
input[type="radio"]:checked + div {
    border-color: currentColor;
}

/* Custom radio button checked state */
.peer-checked\:border-emerald-400:has(input:checked) {
    border-color: rgb(74 222 128);
}

.peer-checked\:border-purple-400:has(input:checked) {
    border-color: rgb(192 132 252);
}

/* Mobile responsiveness improvements */
@media (max-width: 640px) {
    .space-y-3 > * + * {
        margin-top: 0.5rem;
    }
    
    .p-3.sm\\:p-4 {
        padding: 0.75rem;
    }
    
    .w-5.h-5.sm\\:w-6.sm\\:h-6 {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .w-10.h-10.sm\\:w-12.sm\\:h-12 {
        width: 2.5rem;
        height: 2.5rem;
    }
    
    .w-5.h-5.sm\\:w-6.sm\\:h-6 {
        width: 1.25rem;
        height: 1.25rem;
    }
    
    .text-base.sm\\:text-lg {
        font-size: 0.875rem;
    }
}

/* Improve touch targets on mobile */
@media (max-width: 640px) {
    button, a {
        min-height: 44px;
    }
    
    .px-4.py-3 {
        padding: 0.75rem 1rem;
    }
    
    .px-3.py-2 {
        padding: 0.625rem 0.75rem;
    }
}

/* Better spacing for mobile */
@media (max-width: 640px) {
    .space-y-3 > * + * {
        margin-top: 0.5rem;
    }
    
    .gap-4 {
        gap: 0.75rem;
    }
    
    .gap-3 {
        gap: 0.5rem;
    }
}
</style>

@endsection


