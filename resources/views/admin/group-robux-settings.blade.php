@extends('layouts.app')
@section('title', 'Admin • Group Robux Settings')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <span class="break-words">Group Robux Settings</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Atur pengaturan untuk pembelian Robux via Group</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-600 text-white rounded-lg">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.group-robux-settings.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Current Settings Display -->
        <div class="mt-8 sm:mt-12 rounded-lg border border-purple-500/30 p-4 sm:p-6 bg-purple-500/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Current Group Robux Settings
            </h3>
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4">
                <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-xs sm:text-sm">Nama Group</div>
                    <div class="text-white text-sm sm:text-lg font-bold">{{ $settings['group_name'] ?? 'Valtus Studios' }}</div>
                </div>
                <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-xs sm:text-sm">Harga per 100</div>
                    <div class="text-white text-sm sm:text-lg font-bold">Rp {{ number_format($settings['group_robux_price_per_100'] ?? '10000', 0, ',', '.') }}</div>
                </div>
                <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-xs sm:text-sm">Stok Tersedia</div>
                    <div class="text-white text-sm sm:text-lg font-bold">{{ number_format($settings['group_robux_stock'] ?? 0, 0, ',', '.') }}</div>
                    @php
                        $currentStock = (int)($settings['group_robux_stock'] ?? 0);
                        $minStock = (int)($settings['group_robux_stock_minimum'] ?? 5000);
                        $isLow = $currentStock <= $minStock;
                    @endphp
                    <div class="text-xs text-white/50 mt-1">
                        @if($isLow)
                            <span class="text-red-300">⚠️ Stok Rendah</span>
                        @elseif($currentStock > $minStock * 2)
                            <span class="text-emerald-300">✅ Stok Tinggi</span>
                        @else
                            <span class="text-blue-300">📊 Normal</span>
                        @endif
                    </div>
                </div>
                <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-xs sm:text-sm">Min. Membership</div>
                    <div class="text-white text-sm sm:text-lg font-bold">{{ $settings['min_membership_days'] ?? '14' }} Hari</div>
                </div>
                <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-xs sm:text-sm">Processing Time</div>
                    <div class="text-white text-sm sm:text-lg font-bold">{{ $settings['processing_hours'] ?? '5-7' }} Jam</div>
                </div>
            </div>
        </div>

        <!-- Group Information -->
        <div class="rounded-lg border border-purple-500/30 p-4 sm:p-6 bg-purple-500/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Informasi Group</h3>
            
            <div class="grid md:grid-cols-1 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Nama Group</span>
                    <input 
                        name="group_name" 
                        type="text" 
                        value="{{ old('group_name', $settings['group_name'] ?? 'Valtus Studios') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                        maxlength="255"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Nama group yang akan ditampilkan kepada user (contoh: Valtus Studios)</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Group Link</span>
                    <input 
                        name="group_link" 
                        type="url" 
                        value="{{ old('group_link', $settings['group_link'] ?? 'https://www.roblox.com/communities/35148970/Valtus-Studios#!/about') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Link URL group Roblox untuk user bergabung</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Group ID</span>
                    <input 
                        name="group_id" 
                        type="number" 
                        step="1" 
                        min="1"
                        value="{{ old('group_id', $settings['group_id'] ?? '35148970') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">ID Group Roblox (untuk validasi API)</p>
                </label>
            </div>
        </div>

        <!-- Pricing Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Harga Jual Robux via Group</h3>
            
            <div class="grid md:grid-cols-1 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Harga per 100 Robux (Rp)</span>
                    <input 
                        name="group_robux_price_per_100" 
                        type="number" 
                        step="0.01" 
                        min="0"
                        value="{{ old('group_robux_price_per_100', $settings['group_robux_price_per_100'] ?? '10000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Harga jual per 100 Robux untuk metode via Group</p>
                </label>
            </div>

            <!-- Pricing Preview -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-purple-500/10 border border-purple-500/20 rounded-lg">
                <h4 class="text-purple-300 font-medium mb-3 text-sm sm:text-base">Preview Harga:</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-white/60">Per 1 Robux:</span>
                        <div class="text-white font-medium" id="group-preview-per-unit">Rp 100</div>
                    </div>
                    <div>
                        <span class="text-white/60">Per 100 Robux:</span>
                        <div class="text-white font-medium" id="group-preview-per-100">Rp 10,000</div>
                    </div>
                    <div>
                        <span class="text-white/60">Per 1000 Robux:</span>
                        <div class="text-white font-medium" id="group-preview-per-1000">Rp 100,000</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Pengaturan Stok Robux via Group</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Stok Robux Tersedia</span>
                    <input 
                        name="group_robux_stock" 
                        type="number" 
                        step="1" 
                        min="0"
                        value="{{ old('group_robux_stock', $settings['group_robux_stock'] ?? '50000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Jumlah Robux yang tersedia untuk metode via Group (terpisah dari stok biasa)</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Stok Minimum Alert</span>
                    <input 
                        name="group_robux_stock_minimum" 
                        type="number" 
                        step="1" 
                        min="0"
                        value="{{ old('group_robux_stock_minimum', $settings['group_robux_stock_minimum'] ?? '5000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Alert ketika stok via Group di bawah jumlah ini</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Minimal Order Robux via Group</span>
                    <input 
                        name="group_robux_min_order" 
                        type="number" 
                        step="1" 
                        min="1"
                        value="{{ old('group_robux_min_order', $settings['group_robux_min_order'] ?? ($settings['robux_min_order'] ?? '100')) }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Minimal pembelian Robux untuk metode via Group</p>
                </label>
            </div>

            <!-- Stock Status Information -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gray-500/10 border border-gray-500/20 rounded-lg">
                <h4 class="text-gray-300 font-medium mb-3 text-sm sm:text-base">Status Stok Group:</h4>
                <div class="space-y-2 text-xs sm:text-sm">
                    @php
                        $currentStock = (int)($settings['group_robux_stock'] ?? 0);
                        $minStock = (int)($settings['group_robux_stock_minimum'] ?? 5000);
                        $isLow = $currentStock <= $minStock;
                        $percentage = $minStock > 0 ? round(($currentStock / $minStock) * 100, 1) : 0;
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-white/60">Stok Tersedia:</span>
                        <span class="text-white font-medium">{{ number_format($currentStock, 0, ',', '.') }} Robux</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Minimum Alert:</span>
                        <span class="text-white font-medium">{{ number_format($minStock, 0, ',', '.') }} Robux</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-2">
                        <span class="text-white/60">Status:</span>
                        @if($isLow)
                            <span class="text-red-300 font-medium">⚠️ Stok Rendah ({{ $percentage }}%)</span>
                        @elseif($currentStock > $minStock * 2)
                            <span class="text-emerald-300 font-medium">✅ Stok Tinggi ({{ $percentage }}%)</span>
                        @else
                            <span class="text-blue-300 font-medium">📊 Normal ({{ $percentage }}%)</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Rules Management for Group -->
        <div class="rounded-lg border border-purple-500/30 p-4 sm:p-6 bg-purple-500/5">
            <div class="flex items-center justify-between mb-4 sm:mb-6">
                <h3 class="text-lg sm:text-xl font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5 sm:w-6 sm:w-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Diskon Robux Via Group
                </h3>
                <a href="{{ route('admin.robux-discount-rules', ['method' => 'group']) }}" class="px-3 sm:px-4 py-2 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white text-sm sm:text-base transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Kelola Rules Diskon
                </a>
            </div>
            @php
                $groupRules = \App\Models\RobuxDiscountRule::where('purchase_method', 'group')
                    ->where('is_active', true)
                    ->orderBy('sort_order')
                    ->orderBy('min_amount', 'desc')
                    ->get();
            @endphp
            @if($groupRules->count() > 0)
                <div class="space-y-2">
                    @foreach($groupRules as $rule)
                        <div class="p-3 bg-white/5 rounded-lg border border-white/10">
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <div class="text-white font-medium text-sm">
                                        {{ $rule->description }}
                                    </div>
                                    <div class="text-yellow-300 text-xs mt-1">
                                        Diskon: 
                                        @if($rule->discount_method === 'percentage')
                                            {{ number_format($rule->discount_value, 0, ',', '.') }}%
                                        @else
                                            Rp {{ number_format($rule->discount_value, 0, ',', '.') }}
                                        @endif
                                    </div>
                                </div>
                                <span class="px-2 py-1 rounded text-xs bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    Aktif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-white/50 text-sm">Belum ada discount rules. Klik "Kelola Rules Diskon" untuk membuat.</p>
            @endif
        </div>

        <!-- Membership Settings -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Pengaturan Membership</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Minimal Membership Days</span>
                    <input 
                        name="min_membership_days" 
                        type="number" 
                        step="1" 
                        min="1" 
                        max="365"
                        value="{{ old('min_membership_days', $settings['min_membership_days'] ?? '14') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Minimal hari keanggotaan group untuk bisa membeli (default: 14 hari)</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Processing Time</span>
                    <input 
                        name="processing_hours" 
                        type="text" 
                        value="{{ old('processing_hours', $settings['processing_hours'] ?? '5-7') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-purple-400 focus:ring-1 focus:ring-purple-400 text-sm sm:text-base" 
                        required 
                        placeholder="5-7"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Waktu pemrosesan setelah pemesanan (contoh: 5-7 jam)</p>
                </label>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-medium transition text-sm sm:text-base">
                Update Group Settings
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Back to Dashboard
            </a>
        </div>
    </form>
</main>

<script>
    // Price preview calculation for group
    function updateGroupPreview() {
        const pricePer100 = parseFloat(document.querySelector('input[name="group_robux_price_per_100"]').value) || 10000;
        
        // Calculate price per 1 Robux from price per 100 Robux
        const pricePerUnit = pricePer100 / 100;
        const pricePer1000 = pricePer100 * 10;
        
        document.getElementById('group-preview-per-unit').textContent = 'Rp ' + pricePerUnit.toLocaleString('id-ID');
        document.getElementById('group-preview-per-100').textContent = 'Rp ' + pricePer100.toLocaleString('id-ID');
        document.getElementById('group-preview-per-1000').textContent = 'Rp ' + pricePer1000.toLocaleString('id-ID');
    }
    
    // Add event listener
    document.querySelector('input[name="group_robux_price_per_100"]')?.addEventListener('input', updateGroupPreview);
    
    // Initial calculation
    updateGroupPreview();
</script>
@endsection


