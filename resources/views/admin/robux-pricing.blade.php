@extends('layouts.app')
@section('title', 'Admin • Robux Pricing')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <img src="/assets/images/robux.png" alt="Robux" class="h-6 w-6 sm:h-8 sm:w-8">
            <span class="break-words">Robux Pricing Management</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Atur harga jual Robux ke customer dan pajak GamePass</p>
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

    <form method="POST" action="{{ route('admin.robux-pricing.update') }}" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Current Settings Display -->
    <div class="mt-8 sm:mt-12 rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
        <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6 flex items-center gap-2">
            <img src="/assets/images/robux.png" alt="Robux" class="h-5 w-5 sm:h-6 sm:w-6">
            Current Robux Settings
        </h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-xs sm:text-sm">Per 1 Robux</div>
                <div class="text-white text-sm sm:text-lg font-bold">Rp {{ number_format(($settings['robux_price_per_100'] ?? '10000') / 100, 0, ',', '.') }}</div>
                <div class="text-xs text-white/50">Otomatis dihitung</div>
            </div>
            <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-xs sm:text-sm">Per 100 Robux</div>
                <div class="text-white text-sm sm:text-lg font-bold">Rp {{ number_format($settings['robux_price_per_100'] ?? '10000', 0, ',', '.') }}</div>
                <div class="text-xs text-white/50">Harga yang diatur</div>
            </div>
            <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-xs sm:text-sm">GamePass Tax</div>
                <div class="text-white text-sm sm:text-lg font-bold">{{ $settings['gamepass_tax_rate'] ?? '30' }}%</div>
            </div>
            <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-xs sm:text-sm">Minimal Order</div>
                <div class="text-white text-sm sm:text-lg font-bold">Rp {{ number_format($settings['minimal_purchase'], 0, ',', '.') }}</div>
            </div>
            <div class="p-3 sm:p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-xs sm:text-sm">Stok Robux</div>
                <div class="text-white text-sm sm:text-lg font-bold">{{ number_format($settings['robux_stock'] ?? 0, 0, ',', '.') }}</div>
                <div class="text-xs text-white/50">
                    @php
                        $stockStatus = \App\Services\RobuxStockService::getStockStatus();
                    @endphp
                    @if($stockStatus['is_low'])
                        <span class="text-red-300">⚠️ Stok Rendah</span>
                    @elseif($stockStatus['status'] === 'high')
                        <span class="text-emerald-300">✅ Stok Tinggi</span>
                    @else
                        <span class="text-blue-300">📊 Normal</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

        <!-- Robux Pricing Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Harga Jual Robux</h3>
            
            <div class="grid md:grid-cols-1 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Harga per 100 Robux (Rp)</span>
                    <input 
                        name="robux_price_per_100" 
                        type="number" 
                        step="0.01" 
                        min="0"
                        value="{{ old('robux_price_per_100', $settings['robux_price_per_100'] ?? '10000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Harga jual per 100 Robux ke customer</p>
                </label>
            </div>

            <!-- Pricing Preview -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                <h4 class="text-emerald-300 font-medium mb-3 text-sm sm:text-base">Preview Harga Jual:</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-white/60">Per 1 Robux:</span>
                        <div class="text-white font-medium" id="preview-per-unit">Rp 100</div>
                        <div class="text-xs text-white/50">Otomatis dihitung</div>
                    </div>
                    <div>
                        <span class="text-white/60">Per 100 Robux:</span>
                        <div class="text-white font-medium" id="preview-per-100">Rp 10,000</div>
                        <div class="text-xs text-white/50">Harga yang diatur</div>
                    </div>
                    <div>
                        <span class="text-white/60">Per 1000 Robux:</span>
                        <div class="text-white font-medium" id="preview-per-1000">Rp 100,000</div>
                        <div class="text-xs text-white/50">Otomatis dihitung</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GamePass Tax Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Pajak GamePass Roblox</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Pajak GamePass (%)</span>
                    <input 
                        name="gamepass_tax_rate" 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        max="100"
                        value="{{ old('gamepass_tax_rate', $settings['gamepass_tax_rate'] ?? '30') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Roblox potong 30% dari GamePass</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Minimal Order Robux</span>
                    <input 
                        name="robux_min_order" 
                        type="number" 
                        step="1" 
                        min="1"
                        value="{{ old('robux_min_order', $settings['robux_min_order'] ?? '100') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Minimal jumlah Robux yang bisa dipesan</p>
                </label>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Stok Robux Tersedia</span>
                    <input 
                        name="robux_stock" 
                        type="number" 
                        step="1" 
                        min="0"
                        value="{{ old('robux_stock', $settings['robux_stock'] ?? '100000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Jumlah Robux yang tersedia untuk dijual</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Stok Minimum Alert</span>
                    <input 
                        name="robux_stock_minimum" 
                        type="number" 
                        step="1" 
                        min="0"
                        value="{{ old('robux_stock_minimum', $settings['robux_stock_minimum'] ?? '10000') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Alert ketika stok di bawah jumlah ini</p>
                </label>
            </div>

            <!-- GamePass Calculation Preview -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                <h4 class="text-blue-300 font-medium mb-3 text-sm sm:text-base">Contoh Perhitungan GamePass:</h4>
                <div class="space-y-2 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/60">Customer mau beli:</span>
                        <span class="text-white font-medium" id="preview-customer-robux">100 Robux</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Pajak GamePass:</span>
                        <span class="text-white font-medium" id="preview-tax-amount">30 Robux</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-2">
                        <span class="text-blue-300 font-medium">GamePass yang harus dibuat:</span>
                        <span class="text-blue-300 font-bold" id="preview-gamepass-total">130 Robux</span>
                    </div>
                    <p class="text-xs text-white/50 mt-2">Customer akan dapat 100 Robux bersih setelah Roblox potong 30%</p>
                </div>
            </div>

            <!-- Stock Status Information -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-gray-500/10 border border-gray-500/20 rounded-lg">
                <h4 class="text-gray-300 font-medium mb-3 text-sm sm:text-base">Informasi Stok:</h4>
                <div class="space-y-2 text-xs sm:text-sm">
                    @php
                        $stockStatus = \App\Services\RobuxStockService::getStockStatus();
                        $pendingReduction = \App\Services\RobuxStockService::getPendingStockReduction();
                        $totalUsage = \App\Services\RobuxStockService::getTotalStockUsage();
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-white/60">Stok Tersedia:</span>
                        <span class="text-white font-medium">{{ number_format($stockStatus['current'], 0, ',', '.') }} Robux</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Pending Orders:</span>
                        <span class="text-yellow-300 font-medium">{{ number_format($pendingReduction, 0, ',', '.') }} Robux</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Stok Efektif:</span>
                        <span class="text-blue-300 font-medium">{{ number_format($totalUsage, 0, ',', '.') }} Robux</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-2">
                        <span class="text-white/60">Status:</span>
                        @if($stockStatus['is_low'])
                            <span class="text-red-300 font-medium">⚠️ Stok Rendah</span>
                        @elseif($stockStatus['status'] === 'high')
                            <span class="text-emerald-300 font-medium">✅ Stok Tinggi</span>
                        @else
                            <span class="text-blue-300 font-medium">📊 Normal</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Auto Complete Settings -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Pengaturan Otomatis</h3>
            
            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Auto Complete Days</span>
                <input 
                    name="auto_complete_days" 
                    type="number" 
                    step="1" 
                    min="1" 
                    max="30"
                    value="{{ old('auto_complete_days', $settings['auto_complete_days'] ?? '5') }}"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                    required 
                />
                <p class="mt-1 text-white/50 text-xs sm:text-sm">Hari untuk auto-complete order (1-30)</p>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition text-sm sm:text-base">
                Update Robux Pricing
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Back to Dashboard
            </a>
        </div>
    </form>

    
</main>

    <script>
        // Price preview calculation
        function updatePreview() {
            const pricePer100 = parseFloat(document.querySelector('input[name="robux_price_per_100"]').value) || 10000;
            const gamepassTax = parseFloat(document.querySelector('input[name="gamepass_tax_rate"]').value) || 30;
            const customerRobux = 100; // Example amount
            
            // Calculate price per 1 Robux from price per 100 Robux
            const pricePerUnit = pricePer100 / 100;
            const pricePer1000 = pricePer100 * 10;
            
            // Roblox potong 30%, jadi customer dapat 70% dari GamePass
            // Rumus: GamePass = (Robux yang mau didapatkan) × 100/70
            const gamepassTotal = Math.ceil(customerRobux * (100 / 70));
            const gamepassTaxAmount = gamepassTotal - customerRobux;
            
            document.getElementById('preview-per-unit').textContent = 'Rp ' + pricePerUnit.toLocaleString('id-ID');
            document.getElementById('preview-per-100').textContent = 'Rp ' + pricePer100.toLocaleString('id-ID');
            document.getElementById('preview-per-1000').textContent = 'Rp ' + pricePer1000.toLocaleString('id-ID');
            document.getElementById('preview-customer-robux').textContent = customerRobux + ' Robux';
            document.getElementById('preview-tax-amount').textContent = gamepassTaxAmount + ' Robux';
            document.getElementById('preview-gamepass-total').textContent = gamepassTotal + ' Robux';
        }
        
        // Add event listeners
        document.querySelector('input[name="robux_price_per_100"]').addEventListener('input', updatePreview);
        document.querySelector('input[name="gamepass_tax_rate"]').addEventListener('input', updatePreview);
        
        // Initial calculation
        updatePreview();
    </script>
@endsection
