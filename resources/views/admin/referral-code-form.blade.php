@extends('layouts.app')
@section('title', isset($referralCode) ? 'Admin • Edit Referral Code' : 'Admin • Create Referral Code')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            <span>{{ isset($referralCode) ? 'Edit Referral Code' : 'Create New Referral Code' }}</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">
            {{ isset($referralCode) ? 'Update referral code information' : 'Add a new referral code' }}
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

    <form method="POST" action="{{ isset($referralCode) ? route('admin.referral-codes.update', $referralCode) : route('admin.referral-codes.store') }}" class="space-y-6 sm:space-y-8">
        @csrf
        @if(isset($referralCode))
            @method('PUT')
        @endif

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Code Information</h3>

            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Name (optional)</span>
                <input 
                    name="name" 
                    type="text" 
                    value="{{ old('name', $referralCode->name ?? '') }}"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                    placeholder="Nama pemilik referral"
                />
            </label>

            <div class="mt-4">
                <span class="text-white/70 text-sm sm:text-base">Referral Code *</span>
                @if(isset($referralCode))
                    <input 
                        name="code" 
                        type="text" 
                        value="{{ old('code', $referralCode->code) }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base font-mono uppercase" 
                        required 
                        maxlength="50"
                    />
                    <div class="mt-2 text-white/50 text-xs">
                        Public link: {{ url('/r/' . ($referralCode->code ?? '')) }}
                    </div>
                    <div class="mt-1 text-white/50 text-xs">
                        Owner dashboard link: {{ url('/ref/' . ($referralCode->code ?? '') . '/dashboard/' . ($referralCode->secret_token ?? '')) }}
                    </div>
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
                                class="text-emerald-400"
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
                                class="text-emerald-400"
                            />
                            <span class="text-white/70 text-sm">Custom Code</span>
                        </label>
                        <input 
                            name="custom_code" 
                            type="text" 
                            id="customCodeInput"
                            value="{{ old('custom_code', '') }}"
                            class="hidden w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base font-mono uppercase" 
                            placeholder="REF12345"
                            maxlength="50"
                        />
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Buyer Discount</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Discount Method *</span>
                    <select 
                        name="buyer_discount_method" 
                        id="buyerDiscountMethod"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                        required
                    >
                        <option value="percentage" {{ old('buyer_discount_method', $referralCode->buyer_discount_method ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed_amount" {{ old('buyer_discount_method', $referralCode->buyer_discount_method ?? '') == 'fixed_amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Discount Value *</span>
                    <div class="mt-2 flex items-center gap-2">
                        <input 
                            name="buyer_discount_value" 
                            type="number" 
                            step="0.01" 
                            min="0"
                            value="{{ old('buyer_discount_value', $referralCode->buyer_discount_value ?? 0) }}"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                            required
                        />
                        <span id="buyerDiscountUnit" class="text-white/60 text-sm sm:text-base">%</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Referrer Reward</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Reward Method *</span>
                    <select 
                        name="reward_method" 
                        id="rewardMethod"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                        required
                    >
                        <option value="percentage" {{ old('reward_method', $referralCode->reward_method ?? 'percentage') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                        <option value="fixed_amount" {{ old('reward_method', $referralCode->reward_method ?? '') == 'fixed_amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Reward Value *</span>
                    <div class="mt-2 flex items-center gap-2">
                        <input 
                            name="reward_value" 
                            type="number" 
                            step="0.01" 
                            min="0"
                            value="{{ old('reward_value', $referralCode->reward_value ?? 0) }}"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                            required
                        />
                        <span id="rewardUnit" class="text-white/60 text-sm sm:text-base">%</span>
                    </div>
                </label>
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Order Amount Range (optional)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Min Order Amount</span>
                    <input 
                        name="min_order_amount" 
                        type="number" 
                        step="0.01" 
                        min="0"
                        value="{{ old('min_order_amount', $referralCode->min_order_amount ?? '') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                        placeholder="0"
                    />
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Max Order Amount</span>
                    <input 
                        name="max_order_amount" 
                        type="number" 
                        step="0.01" 
                        min="0"
                        value="{{ old('max_order_amount', $referralCode->max_order_amount ?? '') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" 
                        placeholder=""
                    />
                </label>
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Status</h3>

            <label class="flex items-center gap-3">
                <input 
                    name="is_active" 
                    type="checkbox" 
                    value="1"
                    {{ old('is_active', $referralCode->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 text-emerald-400 bg-black/30 border-white/20 rounded"
                />
                <span class="text-white/70 text-sm sm:text-base">Active</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition text-sm sm:text-base">
                {{ isset($referralCode) ? 'Update Referral Code' : 'Create Referral Code' }}
            </button>
            <a href="{{ route('admin.referral-codes') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Cancel
            </a>
        </div>
    </form>
</main>

<script>
function toggleCodeInput() {
    const custom = document.getElementById('codeTypeCustom');
    const customInput = document.getElementById('customCodeInput');

    if (custom && custom.checked) {
        customInput.classList.remove('hidden');
        customInput.removeAttribute('disabled');
        customInput.required = true;
    } else {
        customInput.classList.add('hidden');
        customInput.setAttribute('disabled', 'disabled');
        customInput.required = false;
    }
}

function updateUnits() {
    const buyerMethod = document.getElementById('buyerDiscountMethod').value;
    const rewardMethod = document.getElementById('rewardMethod').value;

    document.getElementById('buyerDiscountUnit').textContent = buyerMethod === 'percentage' ? '%' : 'Rp';
    document.getElementById('rewardUnit').textContent = rewardMethod === 'percentage' ? '%' : 'Rp';
}

document.getElementById('buyerDiscountMethod').addEventListener('change', updateUnits);
document.getElementById('rewardMethod').addEventListener('change', updateUnits);
updateUnits();
toggleCodeInput();
</script>
@endsection
