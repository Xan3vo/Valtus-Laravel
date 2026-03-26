@extends('layouts.app')
@section('title', 'Top Up Robux via Group')
@section('body')
@php
    $effectiveMinOrder = (int) ($groupRobuxMinOrder ?? $robuxMinOrder ?? \App\Models\Setting::getValue('robux_min_order', 100));
    $defaultGroupAmount = max($effectiveMinOrder, 100);
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
                <div class="mt-1 text-sm text-white/70">Minimal order: <span class="text-white font-medium">{{ $effectiveMinOrder }} RBX</span></div>
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
            <!-- Method Selection - Button style (not radio) -->
            <div class="mb-6">
                <span class="text-white/70 text-sm mb-3 block font-medium hidden sm:block">Pilih Metode Pembelian</span>
                <!-- Desktop: Horizontal Layout -->
                <div class="hidden sm:grid sm:grid-cols-2 gap-4">
                    <button type="button" onclick="window.location.href='{{ route('user.search') }}'" class="p-4 rounded-xl border-2 border-white/15 bg-white/5 transition-all duration-200 hover:border-blue-400/50 hover:bg-blue-500/5 text-left">
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
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('user.search-group') }}'" class="p-4 rounded-xl border-2 border-purple-400 bg-purple-500/10 shadow-lg shadow-purple-500/20 transition-all duration-200 hover:border-purple-400/50 hover:bg-purple-500/15 text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-base text-white">Via Group</div>
                                    <div class="text-sm text-white/70 mt-0.5">Bergabung dengan group {{ $groupName ?? 'Valtus Studios' }}</div>
                                    <div class="text-xs text-purple-400/90 mt-1 font-medium">✓ Langsung bisa beli setelah bergabung</div>
                                </div>
                            </div>
                    </button>
                </div>
                <!-- Mobile: Horizontal Layout -->
                <div class="sm:hidden grid grid-cols-2 gap-2">
                    <button type="button" onclick="window.location.href='{{ route('user.search') }}'" class="p-3 rounded-lg border-2 border-white/15 bg-white/5 transition-all duration-200 text-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center mx-auto mb-2 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                </svg>
                            </div>
                            <div class="font-semibold text-sm text-white">Via Gamepass</div>
                    </button>
                    <button type="button" onclick="window.location.href='{{ route('user.search-group') }}'" class="p-3 rounded-lg border-2 border-purple-400 bg-purple-500/10 transition-all duration-200 text-center">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mx-auto mb-2 shadow-md">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="font-semibold text-sm text-white">Via Group</div>
                    </button>
                        </div>
            </div>
            
            <!-- Group Information Section (shown when group method is selected) -->
            <div class="mb-6">
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
                                    <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">Setelah bergabung, Anda bisa langsung membeli Robux dengan harga yang sama</p>
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
                                    <div>Setelah bergabung, gunakan tombol "Cek" untuk melihat status keanggotaan</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Amount Selection Tabs -->
            <div class="flex items-center gap-2 text-sm">
                <button type="button" id="tabCustom" class="px-4 py-1.5 rounded-md bg-white/10 text-white shadow-sm">Kustom</button>
                <button type="button" id="tabQuick" class="px-4 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white">Pilih Cepat</button>
            </div>

            <form method="GET" action="{{ route('user.amount') }}" class="mt-5 grid gap-4">
                <input type="hidden" name="amount" id="amountInput" value="{{ $defaultGroupAmount }}">
        <label class="block">
                    <span class="text-white/70">Username Roblox</span>
                    <div class="mt-2 flex items-center gap-3">
                        <input required name="username" id="usernameInput" class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/30" placeholder="mis: builderman" />
                        <button type="button" id="checkBtn" class="px-4 py-3 rounded-md border border-white/15 hover:bg-white/5 text-sm">Cek</button>
                    </div>
                    <div id="userResult" class="mt-3 hidden p-3 rounded-md border border-white/10 bg-white/5">
                        <div class="flex items-center gap-3 mb-2">
                            <img id="userAvatar" src="" class="w-10 h-10 rounded-md object-cover hidden" alt="" />
                            <div class="flex-1">
                                <div class="text-white/90 font-medium" id="userName"></div>
                                <div class="text-white/60 text-xs hidden" id="userId"></div>
                            </div>
                            <div id="userBadge" class="text-xs px-2 py-1 rounded border hidden"></div>
                        </div>
                        <!-- Group Membership Status (shown when group method is selected) -->
                        <div id="groupMembershipStatus" class="hidden mt-2 pt-2 border-t border-white/10">
                            <div class="flex items-start gap-2">
                                <div id="groupStatusIcon" class="flex-shrink-0 mt-0.5"></div>
                                <div class="flex-1">
                                    <div id="groupStatusText" class="text-sm text-white/80"></div>
                                    <div id="groupStatusActions" class="mt-2 flex flex-wrap gap-2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
        </label>

        <!-- Group Information Section removed - already shown above -->
        <div id="groupInfoSection" class="hidden mb-6" style="display: none !important;">
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
                                <p class="text-xs sm:text-sm text-purple-200/90 leading-relaxed">Setelah bergabung, Anda bisa langsung membeli Robux dengan harga yang sama</p>
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
                            <input type="number" step="1" id="customAmount" class="bg-transparent outline-none text-white w-32" value="{{ $defaultGroupAmount }}" placeholder="{{ $effectiveMinOrder }}" />
                        </div>
                        <div class="text-sm text-white/70">Harga per 100: <span class="text-white" id="pricePer100Display">Rp {{ number_format($robuxPricePer100,0,',','.') }}</span></div>
                    </div>
                    <div id="amountWarning" class="text-xs text-red-400 mt-1 hidden">Minimal pembelian {{ $effectiveMinOrder }} Robux</div>
                </div>

                <!-- Quick picks -->
                <div id="quickWrap" class="hidden">
                    <div class="mt-1 text-white/70 text-sm">Pilih nominal cepat:</div>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @php $list = [100, 500, 1000, 2000, 5000, 10000, 25000, 50000]; @endphp
                        @foreach($list as $q)
                        @php 
                            // Always use group price for this page
                            $groupBasePrice = ($groupRobuxPricePer100 ?? 10000) * ($q / 100);
                            $groupDiscount = \App\Models\RobuxDiscountRule::findMatchingRule($q, 'group');
                            
                            $groupFinalPrice = $groupBasePrice;
                            $groupDiscountAmount = 0;
                            $groupDiscountPercent = 0;
                            
                            if ($groupDiscount) {
                                $groupDiscountAmount = $groupDiscount->calculateDiscount($groupBasePrice);
                                $groupFinalPrice = max(0, $groupBasePrice - $groupDiscountAmount);
                                $groupDiscountPercent = $groupBasePrice > 0 ? ($groupDiscountAmount / $groupBasePrice) * 100 : 0;
                            }
                            
                            $hasDiscount = $groupDiscount !== null;
                        @endphp
                        <button type="button" class="chip rounded-lg border {{ $hasDiscount ? 'border-yellow-500/50' : 'border-white/15' }} bg-white/5 hover:bg-white/10 transition p-4 text-left relative" data-amount="{{ $q }}" data-group-discount="{{ $groupDiscount ? '1' : '0' }}">
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
                                        Diskon Group
                                </div>
                                <div class="text-white/60 line-through text-[11px] mt-0.5">Rp {{ number_format($groupBasePrice, 0, ',', '.') }}</div>
                                <div class="text-emerald-300 font-bold text-sm mt-0.5">Rp {{ number_format($groupFinalPrice, 0, ',', '.') }}</div>
                                @if($groupDiscountPercent > 0)
                                    <div class="text-yellow-400 text-[10px] mt-0.5 font-medium">
                                            {{ number_format($groupDiscountPercent, 0) }}% off
                                    </div>
                                @endif
                            @else
                                <div class="mt-1 text-white/80 text-sm">Rp {{ number_format($groupBasePrice, 0, ',', '.') }}</div>
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
                        <div class="text-white/60 text-xs mt-1" id="discountMethod">Diskon Group</div>
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

                <div class="text-sm text-white/60">Minimal {{ $effectiveMinOrder }} RBX</div>
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
                    <div class="text-white/60 text-xs" id="summaryDiscountMethod">Diskon Group</div>
                </div>
                <div id="summaryFinalPrice" class="hidden mt-3">
                    <div class="text-xs text-white/60">Total Setelah Diskon</div>
                    <div class="text-xl font-bold text-emerald-300" id="summaryFinalPriceAmount">Rp 0</div>
                </div>
                <div class="mt-4">
                    <button id="proceedBtnRight" type="button" disabled class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-white text-black py-3 hover:bg-gray-100 opacity-50 cursor-not-allowed">
                        <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                        Lanjutkan via Group
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
        const groupMinOrder = {{ (int) $effectiveMinOrder }};

        const groupAvailableStock = {{ (int) ($groupAvailableStock ?? 0) }};
        
        // Method selection variables - FIXED to group for this page
        const selectedMethod = 'group'; // Fixed to group, cannot be changed
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
        
        function showGroupInfoPopup() {
            if (document.getElementById('groupIntroOverlay')) {
                return;
            }

            const minDays = minMembershipDays || 14;
            const overlay = document.createElement('div');
            overlay.id = 'groupIntroOverlay';
            overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';

            overlay.innerHTML = `
                <div class="rounded-xl border border-purple-400/30 bg-gray-900 p-5 sm:p-6 w-full max-w-md mx-4">
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
                        <p>Metode ini mengirim Robux melalui group <strong class="text-white">${groupName}</strong>.</p>
                        <p>Pastikan kamu sudah bergabung minimal <strong class="text-white">${minDays} hari</strong> sebelum melakukan pembelian.</p>
                        <div class="bg-purple-500/10 border border-purple-400/30 rounded-lg p-3">
                            <p class="text-xs text-purple-200">Minimal order: <strong class="text-white">${groupMinOrder} Robux</strong>. Proses estimasi 5-7 jam setelah pembayaran berhasil.</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="${groupLink}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg border border-white/20 text-white text-sm font-medium hover:bg-white/5 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                            Bergabung Group
                        </a>
                        <button onclick="closeGroupInfoPopupAndSelect()" class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium transition">
                            Saya Mengerti
                        </button>
                    </div>
                </div>
            `;

            document.body.appendChild(overlay);

            overlay.addEventListener('click', function(e) {
                if (e.target === overlay) {
                    closeGroupInfoPopupAndSelect();
                }
            });
        }
        
        function closeGroupInfoPopupAndSelect() {
            const overlay = document.getElementById('groupIntroOverlay');
            if (overlay) {
                overlay.remove();
            }
        }
        
        // Method selection handlers - redirect when switching method
        function selectMethod(method) {
            // If user tries to select gamepass, redirect to gamepass page
            if (method === 'gamepass') {
                window.location.href = '{{ route("user.search") }}';
                return;
                }
            // Otherwise, no-op (already on group page)
        }
            
        // Update harga per 100 display - always group price for this page
        function updatePricePer100Display() {
            const pricePer100Display = document.getElementById('pricePer100Display');
            if (!pricePer100Display) return;
            
            // Always use group price
            const groupPrice = typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : {{ (int) $robuxPricePer100 }};
            
            // Update display
            pricePer100Display.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(groupPrice);
        }
        
        // Expose to global scope untuk akses dari IIFE
        window.updatePricePer100Display = updatePricePer100Display;
        
        // Legacy function - removed, not needed for this page (inline check is used instead)
        async function checkGroupMembership() {
            const username = document.getElementById('usernameInput').value.trim();
            if (!username) {
                alert('Silakan masukkan username Roblox terlebih dahulu.');
                return;
            }
            // Use inline check
            await checkGroupMembershipInline(username);
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
        
            // Update proceed button - always require group membership validation
        function updateProceedButton() {
            const proceedBtn = document.getElementById('proceedBtnRight');
            const username = document.getElementById('usernameInput').value.trim();
            const amount = parseInt(document.getElementById('amountInput').value) || 0;
            const minOrder = {{ (int) $effectiveMinOrder }};

                const availableStock = groupAvailableStock;
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
            
                // For group method, require username validation AND group membership validation
                const userResult = document.getElementById('userResult');
                const userBadge = document.getElementById('userBadge');
                const isUsernameValid = !userResult.classList.contains('hidden') && 
                                       !userBadge.classList.contains('hidden');
                let canProceed = isUsernameValid && groupMembershipValid && amount >= minOrder;

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
                    stockWarningEl.textContent = `Stok Robux (Group) tidak mencukupi. Sisa stok: ${availableStock.toLocaleString('id-ID')} RBX. Silakan tunggu pengisian ulang.`;
                    stockWarningEl.classList.remove('hidden');
                } else {
                    stockWarningEl.classList.add('hidden');
                }
            
            proceedBtn.innerHTML = `
                <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                    Lanjutkan via Group
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
            
            // No method selection needed - always group for this page
            // Initialize price display for group method
            
            // CRITICAL: Store purchase_method immediately on page load to prevent race condition
            // This ensures the method is set correctly even if user navigates quickly
            fetch('/user/store-purchase-method', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ purchase_method: 'group' })
            }).catch(err => console.error('Error storing purchase method:', err));

            // Show info popup shortly after load
            setTimeout(showGroupInfoPopup, 150);
        });
        
        (function(){
            // Session validation
            const pricePer100 = {{ (int) $robuxPricePer100 }};
            const minOrder = {{ (int) $effectiveMinOrder }};
            // Note: groupRobuxPricePer100, minMembershipDays, and groupLink are already defined globally above
            
            if (!pricePer100 || pricePer100 <= 0) {
                alert('Harga tidak valid. Silakan refresh halaman.');
                window.location.href = '/';
                return;
            }
            
            if (!minOrder || minOrder <= 0) {
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
                const rawAmount = Math.max(0, getRawAmount());
                const isAmountValid = rawAmount >= minOrder;
                
                // Keep hidden input in sync with raw value
                if (amtInput) {
                    amtInput.value = rawAmount > 0 ? rawAmount : '';
                }
                
                // 2. Method is always 'group' for this page
                const currentMethod = 'group';
                
                // 3. Hitung harga dasar - selalu gunakan group price (hanya jika valid)
                const currentPricePer100 = typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : pricePer100;
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
                
                // Jika jumlah belum memenuhi minimal, hentikan di sini
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
                    const discountResponse = await fetch(`/api/robux/discount?amount=${amt}&purchase_method=group&_t=${timestamp}`);
                    const discountData = await discountResponse.json();
                    
                    // 7. Update tampilan berdasarkan hasil diskon
                    if (discountData.has_discount) {
                        // Ada diskon - tampilkan info diskon
                        // Gunakan base_price dari API (lebih akurat, sudah sesuai dengan method)
                        const apiBasePrice = discountData.base_price || basePrice;
                        const discountMethodName = 'Group';
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
                    const warningEl = document.getElementById('amountWarning');
                    amtInput.value = val;
                    customAmount.value = val;
                    updateTotal();

                    // Hide warning when using quick pick at or above min
                    if (warningEl) {
                        if (val >= minOrder) {
                            warningEl.classList.add('hidden');
                        } else {
                            warningEl.classList.remove('hidden');
                        }
                    }

                    // Clear session amount when user manually changes
                    sessionStorage.setItem('amount_changed', 'true');

                    // Update proceed button state
                    if (typeof updateProceedButton === 'function') {
                        updateProceedButton();
                    }
                });
            });

            // Debounce timer untuk validasi
            let validationTimeout = null;
            
            customAmount.addEventListener('input',()=>{
                const rawValue = customAmount.value.trim();
                const val = rawValue === '' ? 0 : (parseInt(rawValue,10) || 0);
                const warningEl = document.getElementById('amountWarning');
                
                // Update hidden input dengan nilai yang diketik (boleh kosong saat mengetik)
                amtInput.value = rawValue === '' ? '' : val;
                
                // Update total dengan nilai saat ini (boleh 0 saat mengetik)
                updateTotal();
                
                // Clear previous timeout
                if (validationTimeout) {
                    clearTimeout(validationTimeout);
                }
                
                // Hapus warning dan border error saat sedang mengetik
                // Biarkan user mengetik bebas tanpa gangguan
                customAmount.style.borderColor = '';
                customAmount.title = '';
                if (warningEl) warningEl.classList.add('hidden');
                
                // Validasi dengan debounce (500ms setelah user berhenti mengetik)
                validationTimeout = setTimeout(() => {
                    const finalVal = parseInt(customAmount.value||0,10) || 0;
                    
                    // Hanya tampilkan warning jika user sudah selesai mengetik dan nilai di bawah minimal
                    if (finalVal > 0 && finalVal < minOrder) {
                        customAmount.style.borderColor = '#ef4444';
                        customAmount.title = `Minimal ${minOrder} Robux`;
                        if (warningEl) warningEl.classList.remove('hidden');
                    } else {
                        customAmount.style.borderColor = '';
                        customAmount.title = '';
                        if (warningEl) warningEl.classList.add('hidden');
                    }
                    
                    // Update proceed button state setelah validasi
                    if (typeof updateProceedButton === 'function') {
                        updateProceedButton();
                    }
                }, 500);
                
                // Clear session amount when user manually changes
                sessionStorage.setItem('amount_changed', 'true');

                // Update proceed button state (disable jika di bawah minimal, tapi jangan tampilkan error)
                if (typeof updateProceedButton === 'function') {
                    updateProceedButton();
                }
            });
            
            // Validasi juga saat blur (user klik keluar dari input)
            customAmount.addEventListener('blur',()=>{
                const val = parseInt(customAmount.value||0,10) || 0;
                const warningEl = document.getElementById('amountWarning');
                
                // Clear timeout karena sudah blur
                if (validationTimeout) {
                    clearTimeout(validationTimeout);
                    validationTimeout = null;
                }
                
                // Validasi final saat blur
                if (val > 0 && val < minOrder) {
                    customAmount.style.borderColor = '#ef4444';
                    customAmount.title = `Minimal ${minOrder} Robux`;
                    if (warningEl) warningEl.classList.remove('hidden');
                } else {
                    customAmount.style.borderColor = '';
                    customAmount.title = '';
                    if (warningEl) warningEl.classList.add('hidden');
                }
                
                // Update proceed button state
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
                const defaultChip = chips.find(c => parseInt(c.getAttribute('data-val'),10) === minOrder) || chips[0];
                if(defaultChip){ defaultChip.click(); }
            });

            // proceed buttons trigger form submit
            const form = document.querySelector('form');
            document.getElementById('proceedBtnRight').addEventListener('click', async ()=> {
                    // ============================================
                    // VALIDASI PERTAMA: Cek jumlah Robux
                    // HARUS dilakukan SEBELUM validasi username/group membership
                    // ============================================
                    // CRITICAL: Force update total first to ensure amount is synced
                    await updateTotal();
                    
                    const inputAmount = parseInt(amtInput.value||0,10) || 0;
                    const customInputAmount = parseInt(customAmount.value||0,10) || 0;
                    let finalAmount = Math.max(inputAmount, customInputAmount, minOrder);
                    
                    // CRITICAL: If amount is 0 or invalid, use minOrder as fallback
                    // This prevents "data pesanan tidak valid" error
                    if (finalAmount < minOrder || finalAmount === 0) {
                        finalAmount = minOrder;
                    }

                    // Ensure inputs reflect sanitized amount
                    if (amtInput.value != finalAmount.toString()) {
                        amtInput.value = finalAmount;
                    }
                    if (customAmount.value != finalAmount.toString()) {
                        customAmount.value = finalAmount;
                    }
                    // Update total display after normalization
                    await updateTotal();
                    
                    const amt = finalAmount;
                    const username = (usernameInput.value||'').trim();

                // Handle Group Method - Validate username and group membership (hanya dilakukan jika jumlah sudah valid)
                {
                    // Validate username exists
                    if (!username || username.trim() === '') {
                        alert('Silakan masukkan username Roblox terlebih dahulu.');
                        usernameInput.focus();
                        return;
                    }
                    
                    // Check if username is validated
                    const userResult = document.getElementById('userResult');
                    const userBadge = document.getElementById('userBadge');
                    if (userResult.classList.contains('hidden') || userBadge.classList.contains('hidden')) {
                        alert('Silakan cek username terlebih dahulu dengan menekan tombol "Cek".');
                        usernameInput.focus();
                        return;
                    }
                    
                    // Check if group membership is valid
                    if (!groupMembershipValid) {
                        alert('Anda belum bergabung dengan group. Silakan bergabung dengan group terlebih dahulu.');
                        return;
                    }
                    
                    // Quick username validation
                    try {
                        const check = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`);
                        const cj = await check.json();
                        if (cj && cj.ok && cj.found && cj.blacklisted) {
                            showBlacklistedPopup();
                            alert('Akun anda diblokir. Silakan hubungi admin.');
                            return;
                        }
                        if (!(cj && cj.ok && cj.found)) {
                            alert('Username tidak ditemukan. Silakan cek kembali username Anda.');
                            return;
                        }
                        
                        // Get current discount data before storing
                        const discountData = await fetch(`/api/robux/discount?amount=${amt}&purchase_method=group&_t=${Date.now()}`).then(r => r.json()).catch(() => ({ has_discount: false }));
                        
                        // CRITICAL: Always use base_price from API (more accurate, already calculated correctly)
                        // API already returns base_price that matches the purchase method (gamepass/group)
                        // Don't recalculate - use the API's calculated price to prevent inconsistencies
                        const basePriceFromAPI = discountData.base_price || (groupRobuxPricePer100 * (amt/100));
                        const finalPriceFromAPI = discountData.has_discount ? discountData.final_price : basePriceFromAPI;
                        const discountAmountFromAPI = discountData.has_discount ? discountData.discount_amount : 0;
                        
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
            });


            // username check via our backend proxy to Roblox
            async function checkUsername(){
                const u = (usernameInput.value || '').trim();
                if(!u){ usernameInput.focus(); return; }
                checkBtn.disabled = true;
                checkBtn.textContent = 'Mengecek...';
                
                // Hide group membership status initially
                const groupMembershipStatus = document.getElementById('groupMembershipStatus');
                if (groupMembershipStatus) {
                    groupMembershipStatus.classList.add('hidden');
                }
                
                try{
                    // Step 1: Check username
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
                            if (groupMembershipStatus) {
                                groupMembershipStatus.classList.add('hidden');
                            }
                            userResult.classList.remove('hidden');

                            proceedBtnRight.disabled = true;
                            proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');

                            showBlacklistedPopup();
                            return;
                        }
                        // Username is valid - display user info
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
                        
                        userResult.classList.remove('hidden');
                        
                        // Step 2: Always check group membership for group page
                            await checkGroupMembershipInline(u);
                    }else{
                        // Username not found
                        userAvatar.classList.add('hidden');
                        userName.textContent = 'Username tidak ditemukan';
                        userId.textContent = '';
                        userBadge.classList.add('hidden');
                        groupMembershipStatus.classList.add('hidden');
                        userResult.classList.remove('hidden');
                        
                        // disable proceed
                        proceedBtnRight.disabled = true;
                        proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                    }
                }catch(e){
                    console.error('Error checking username:', e);
                    userName.textContent = 'Gagal mengecek';
                    userId.textContent = '';
                    userResult.classList.remove('hidden');
                    userBadge.classList.add('hidden');
                    if (groupMembershipStatus) {
                        groupMembershipStatus.classList.add('hidden');
                    }
                    proceedBtnRight.disabled = true;
                    proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                }finally{
                    checkBtn.disabled = false;
                    checkBtn.textContent = 'Cek';
                }
            }
            
            // Check group membership and display inline (without popup)
            async function checkGroupMembershipInline(username) {
                // If username not provided, get it from input field
                if (!username) {
                    username = document.getElementById('usernameInput').value.trim();
                }
                
                if (!username) {
                    alert('Silakan masukkan username terlebih dahulu.');
                    return;
                }
                
                const groupMembershipStatus = document.getElementById('groupMembershipStatus');
                const groupStatusIcon = document.getElementById('groupStatusIcon');
                const groupStatusText = document.getElementById('groupStatusText');
                const groupStatusActions = document.getElementById('groupStatusActions');
                
                if (!groupMembershipStatus) return;
                
                // Show loading state
                groupMembershipStatus.classList.remove('hidden');
                groupStatusIcon.innerHTML = `
                    <svg class="w-5 h-5 animate-spin text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                `;
                groupStatusText.textContent = 'Mengecek keanggotaan group...';
                groupStatusActions.innerHTML = '';
                
                try {
                    const response = await fetch(`/api/roblox/group-membership?username=${encodeURIComponent(username)}`, {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    const currentGroupName = data.group_name || groupName || 'Valtus Studios';
                    const groupUrl = data.group_link || groupLink || 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about';
                    
                    if (data.success && data.is_member) {
                        // User is a member - allow purchase immediately
                        groupStatusIcon.innerHTML = `
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        `;
                        groupStatusText.innerHTML = `
                            <span class="text-emerald-300 font-medium">✅ Anda sudah bergabung dengan group ${currentGroupName}</span><br>
                            <span class="text-white/60 text-xs">Anda dapat melanjutkan pembelian</span>
                        `;
                        groupStatusActions.innerHTML = '';
                        
                        groupMembershipValid = true;
                        proceedBtnRight.disabled = false;
                        proceedBtnRight.classList.remove('opacity-50','cursor-not-allowed');
                    } else {
                        // User is not a member
                        groupStatusIcon.innerHTML = `
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        `;
                        groupStatusText.innerHTML = `
                            <span class="text-red-300 font-medium">❌ Belum bergabung dengan group ${currentGroupName}</span><br>
                            <span class="text-white/60 text-xs">Bergabunglah terlebih dahulu untuk melanjutkan pembelian</span>
                        `;
                        groupStatusActions.innerHTML = `
                            <a href="${groupUrl}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs rounded-lg bg-purple-600 hover:bg-purple-700 text-white transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Bergabung Group
                            </a>
                            <button onclick="checkGroupMembershipInline('${username}')" class="px-3 py-1.5 text-xs rounded-lg border border-white/20 hover:bg-white/5 text-white/80 transition-colors">
                                Cek Ulang
                            </button>
                        `;
                        
                        groupMembershipValid = false;
                        proceedBtnRight.disabled = true;
                        proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                    }
                } catch (error) {
                    console.error('Error checking group membership:', error);
                    groupStatusIcon.innerHTML = `
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    `;
                    groupStatusText.innerHTML = `
                        <span class="text-red-300 font-medium">Gagal mengecek keanggotaan</span><br>
                        <span class="text-white/60 text-xs">Silakan coba lagi</span>
                    `;
                    groupStatusActions.innerHTML = `
                        <button onclick="checkGroupMembershipInline('${username}')" class="px-3 py-1.5 text-xs rounded-lg border border-white/20 hover:bg-white/5 text-white/80 transition-colors">
                            Coba Lagi
                        </button>
                    `;
                    
                    groupMembershipValid = false;
                    proceedBtnRight.disabled = true;
                    proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                }
            }
            
            // Expose function to global scope for inline re-check
            window.checkGroupMembershipInline = checkGroupMembershipInline;
            checkBtn.addEventListener('click', checkUsername);
            usernameInput.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); checkUsername(); }});

            // Set initial amount from session if available and user hasn't changed it
            const sessionAmount = {{ (int) (session('selected_amount') ?? 0) }};
            const amountChanged = sessionStorage.getItem('amount_changed') === 'true';
            
            // CRITICAL: Always ensure amount is set to at least minOrder
            if(sessionAmount > 0 && !amountChanged && sessionAmount >= minOrder) {
                amtInput.value = sessionAmount;
                customAmount.value = sessionAmount;
                setMode('custom');
            } else {
                // Default to minimal order if no session amount or user changed it
                const defaultAmount = Math.max(minOrder, 100);
                amtInput.value = defaultAmount;
                customAmount.value = defaultAmount;
                setMode('custom');
            }
            // CRITICAL: Always call updateTotal to ensure amount is synced and validated
            updateTotal();
            
            // Update harga per 100 display saat pertama kali load - always group price
            const pricePer100DisplayInit = document.getElementById('pricePer100Display');
            if (pricePer100DisplayInit) {
                const groupPrice = typeof groupRobuxPricePer100 !== 'undefined' ? groupRobuxPricePer100 : pricePer100;
                pricePer100DisplayInit.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(groupPrice);
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
        }

        window.hideEmailModal = function() {
            const emailModal = document.getElementById('emailModal');
            if (emailModal) {
                emailModal.classList.add('hidden');
            }
        }

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
                hideEmailModal();
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
        
        // Close email modal when clicking outside
        document.getElementById('emailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideEmailModal();
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


