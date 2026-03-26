@extends('layouts.app')
@section('title', isset($promoCode) ? 'Admin • Edit Promo Code' : 'Admin • Create Promo Code')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ isset($promoCode) ? 'Edit Promo Code' : 'Create New Promo Code' }}</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">
            {{ isset($promoCode) ? 'Update promo code information' : 'Add a new discount promo code' }}
        </p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-600 text-white rounded-lg">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($promoCode) ? route('admin.promo-codes.update', $promoCode) : route('admin.promo-codes.store') }}" class="space-y-6 sm:space-y-8">
        @csrf
        @if(isset($promoCode))
            @method('PUT')
        @endif

        <!-- Code Information -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Code Information</h3>
            
            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Promo Code *</span>
                @if(isset($promoCode))
                    <div class="mt-2 flex items-center gap-2">
                        <code class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-yellow-300 font-mono font-semibold text-sm sm:text-base">{{ $promoCode->code }}</code>
                        <button type="button" onclick="copyToClipboard('{{ $promoCode->code }}')" class="px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white transition text-sm sm:text-base" title="Copy">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </button>
                    </div>
                    <input 
                        name="code" 
                        type="text" 
                        value="{{ old('code', $promoCode->code) }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base font-mono" 
                        required 
                        placeholder="PROMO123"
                    />
                @else
                    <div class="mt-2 space-y-2">
                        <label class="flex items-center gap-2">
                            <input 
                                type="radio" 
                                name="code_type" 
                                value="auto" 
                                id="codeTypeAuto"
                                checked
                                onchange="toggleCodeInput()"
                                class="text-yellow-400"
                            />
                            <span class="text-white/70 text-sm">Generate Random Code</span>
                        </label>
                        <label class="flex items-center gap-2">
                            <input 
                                type="radio" 
                                name="code_type" 
                                value="custom" 
                                id="codeTypeCustom"
                                onchange="toggleCodeInput()"
                                class="text-yellow-400"
                            />
                            <span class="text-white/70 text-sm">Custom Code</span>
                        </label>
                        <input 
                            name="custom_code" 
                            type="text" 
                            id="customCodeInput"
                            value="{{ old('custom_code', '') }}"
                            class="hidden w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base font-mono uppercase" 
                            placeholder="PROMO123"
                            maxlength="50"
                        />
                        <p class="text-white/50 text-xs">Kode akan otomatis di-generate jika menggunakan random code</p>
                    </div>
                @endif
            </label>
        </div>

        <!-- Discount Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Discount Configuration</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Discount Method *</span>
                    <select 
                        name="discount_method" 
                        id="discountMethod"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base" 
                        required
                    >
                        <option value="">Pilih Metode</option>
                        <option value="percentage" {{ old('discount_method', $promoCode->discount_method ?? '') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed_amount" {{ old('discount_method', $promoCode->discount_method ?? '') == 'fixed_amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Maximum Uses *</span>
                    <input 
                        name="max_uses" 
                        type="number" 
                        step="1" 
                        min="1"
                        value="{{ old('max_uses', $promoCode->max_uses ?? '100') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base" 
                        required 
                        placeholder="100"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Jumlah maksimal penggunaan kode</p>
                </label>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Minimum Discount Value *</span>
                    <div class="mt-2 flex items-center gap-2">
                        <input 
                            name="discount_value_min" 
                            type="number" 
                            step="0.01" 
                            min="0"
                            id="discountValueMin"
                            value="{{ old('discount_value_min', $promoCode->discount_value_min ?? '0') }}"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base" 
                            required 
                            placeholder="0"
                        />
                        <span id="discountUnitMin" class="text-white/60 text-sm sm:text-base">%</span>
                    </div>
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Nilai diskon minimum (untuk random)</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Maximum Discount Value *</span>
                    <div class="mt-2 flex items-center gap-2">
                        <input 
                            name="discount_value_max" 
                            type="number" 
                            step="0.01" 
                            min="0"
                            id="discountValueMax"
                            value="{{ old('discount_value_max', $promoCode->discount_value_max ?? '0') }}"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400 text-sm sm:text-base" 
                            required 
                            placeholder="0"
                        />
                        <span id="discountUnitMax" class="text-white/60 text-sm sm:text-base">%</span>
                    </div>
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Nilai diskon maximum (untuk random)</p>
                </label>
            </div>

            <!-- Discount Preview -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                <h4 class="text-yellow-300 font-medium mb-3 text-sm sm:text-base">Preview Diskon Random:</h4>
                <div class="space-y-2 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/60">Range Diskon:</span>
                        <span class="text-yellow-300 font-medium" id="preview-range">0% - 0%</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Contoh Harga:</span>
                        <span class="text-white font-medium">Rp 100.000</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Diskon Min:</span>
                        <span class="text-yellow-300 font-medium" id="preview-discount-min">-Rp 0</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-2">
                        <span class="text-white/60">Diskon Max:</span>
                        <span class="text-yellow-300 font-bold" id="preview-discount-max">-Rp 0</span>
                    </div>
                    <p class="text-xs text-white/50 mt-2">Setiap penggunaan akan mendapat diskon random dalam range tersebut</p>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Status</h3>
            
            <label class="flex items-center gap-3">
                <input 
                    name="is_active" 
                    type="checkbox" 
                    value="1"
                    {{ old('is_active', $promoCode->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 text-yellow-400 bg-black/30 border-white/20 rounded focus:ring-yellow-500 focus:ring-2"
                />
                <span class="text-white/70 text-sm sm:text-base">Active (can be used by customers)</span>
            </label>
            @if(isset($promoCode))
                <p class="mt-2 text-white/50 text-xs sm:text-sm">
                    Current usage: <strong>{{ $promoCode->current_uses }}</strong> / {{ $promoCode->max_uses }}
                </p>
            @endif
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition text-sm sm:text-base">
                {{ isset($promoCode) ? 'Update Promo Code' : 'Create Promo Code' }}
            </button>
            <a href="{{ route('admin.promo-codes') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Cancel
            </a>
        </div>
    </form>
</main>

<script>
function toggleCodeInput() {
    const auto = document.getElementById('codeTypeAuto');
    const custom = document.getElementById('codeTypeCustom');
    const customInput = document.getElementById('customCodeInput');
    
    if (custom.checked) {
        customInput.classList.remove('hidden');
        customInput.removeAttribute('disabled');
        customInput.required = true;
    } else {
        customInput.classList.add('hidden');
        customInput.setAttribute('disabled', 'disabled');
        customInput.required = false;
    }
}

function updateDiscountUnits() {
    const method = document.getElementById('discountMethod').value;
    const unitMin = document.getElementById('discountUnitMin');
    const unitMax = document.getElementById('discountUnitMax');
    
    if (method === 'percentage') {
        unitMin.textContent = '%';
        unitMax.textContent = '%';
    } else if (method === 'fixed_amount') {
        unitMin.textContent = 'Rp';
        unitMax.textContent = 'Rp';
    }
    updatePreview();
}

function updatePreview() {
    const method = document.getElementById('discountMethod').value;
    const min = parseFloat(document.getElementById('discountValueMin').value) || 0;
    const max = parseFloat(document.getElementById('discountValueMax').value) || 0;
    const examplePrice = 100000;
    
    const rangeEl = document.getElementById('preview-range');
    const discountMinEl = document.getElementById('preview-discount-min');
    const discountMaxEl = document.getElementById('preview-discount-max');
    
    if (method === 'percentage') {
        rangeEl.textContent = min.toLocaleString('id-ID') + '% - ' + max.toLocaleString('id-ID') + '%';
        discountMinEl.textContent = '-Rp ' + (examplePrice * min / 100).toLocaleString('id-ID');
        discountMaxEl.textContent = '-Rp ' + (examplePrice * max / 100).toLocaleString('id-ID');
    } else if (method === 'fixed_amount') {
        rangeEl.textContent = 'Rp ' + min.toLocaleString('id-ID') + ' - Rp ' + max.toLocaleString('id-ID');
        discountMinEl.textContent = '-Rp ' + Math.min(min, examplePrice).toLocaleString('id-ID');
        discountMaxEl.textContent = '-Rp ' + Math.min(max, examplePrice).toLocaleString('id-ID');
    } else {
        rangeEl.textContent = '0% - 0%';
        discountMinEl.textContent = '-Rp 0';
        discountMaxEl.textContent = '-Rp 0';
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Code copied!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

// Event listeners
document.getElementById('discountMethod')?.addEventListener('change', updateDiscountUnits);
document.getElementById('discountValueMin')?.addEventListener('input', updatePreview);
document.getElementById('discountValueMax')?.addEventListener('input', updatePreview);

// Initial update
updateDiscountUnits();
</script>
@endsection



