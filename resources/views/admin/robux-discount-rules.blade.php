@extends('layouts.app')
@section('title', 'Admin • Robux Discount Rules')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Robux Discount Rules - {{ ucfirst($purchaseMethod) }}</span>
            </h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Kelola aturan diskon untuk pembelian Robux via {{ $purchaseMethod === 'gamepass' ? 'Gamepass' : 'Group' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ $purchaseMethod === 'gamepass' ? route('admin.robux-pricing') : route('admin.group-robux-settings') }}" class="px-3 sm:px-4 py-2 rounded-lg bg-gray-600 hover:bg-gray-700 text-white text-sm sm:text-base transition">
                Kembali
            </a>
            <button onclick="showCreateModal()" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Rule
            </button>
        </div>
    </div>

    <!-- Method Switcher -->
    <div class="mb-6 flex gap-2">
        <a href="{{ route('admin.robux-discount-rules', ['method' => 'gamepass']) }}" class="px-4 py-2 rounded-lg transition {{ $purchaseMethod === 'gamepass' ? 'bg-emerald-600 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} text-sm sm:text-base">
            Via Gamepass
        </a>
        <a href="{{ route('admin.robux-discount-rules', ['method' => 'group']) }}" class="px-4 py-2 rounded-lg transition {{ $purchaseMethod === 'group' ? 'bg-purple-600 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20' }} text-sm sm:text-base">
            Via Group
        </a>
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

    @if(isset($conflicts) && count($conflicts) > 0)
        <div class="mb-6 p-4 bg-yellow-600 text-white rounded-lg border border-yellow-400">
            <div class="flex items-start gap-3">
                <svg class="w-6 h-6 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <div class="flex-1">
                    <h3 class="font-semibold mb-2">⚠️ Konflik Priority Terdeteksi!</h3>
                    <p class="text-sm mb-2">Ada rules dengan priority yang sama dan OVERLAP. Jika ada overlap, sistem akan memilih rule dengan <code class="bg-white/20 px-1 py-0.5 rounded">min_amount</code> lebih besar.</p>
                    <p class="text-sm font-medium">Disarankan: Ubah priority salah satu rule untuk menghindari konflik.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Rules List -->
    <div class="space-y-4">
        @forelse($rules as $rule)
        @php
            $hasConflict = false;
            if(isset($conflicts)) {
                foreach($conflicts as $conflict) {
                    if($conflict['rule1'] == $rule->id || $conflict['rule2'] == $rule->id) {
                        $hasConflict = true;
                        break;
                    }
                }
            }
        @endphp
        <div class="rounded-lg border {{ $hasConflict ? 'border-yellow-500/50 bg-yellow-500/5' : 'border-white/20 bg-white/5' }} p-4 sm:p-6 hover:bg-white/10 transition">
            @if($hasConflict)
            <div class="mb-3 p-2 bg-yellow-500/20 border border-yellow-500/30 rounded text-xs text-yellow-200">
                ⚠️ Konflik: Ada rule lain dengan priority {{ $rule->sort_order }} yang overlap
            </div>
            @endif
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2 flex-wrap">
                        <div class="px-3 py-1 rounded text-xs font-medium {{ $rule->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-gray-500/20 text-gray-300 border border-gray-500/30' }}">
                            {{ $rule->is_active ? 'Aktif' : 'Nonaktif' }}
                        </div>
                        <div class="px-3 py-1 rounded text-xs font-medium {{ $hasConflict ? 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30' : 'bg-blue-500/20 text-blue-300 border border-blue-500/30' }}" title="Priority: {{ $rule->sort_order }} (lebih kecil = lebih tinggi)">
                            🎯 Priority: {{ $rule->sort_order }}
                            @if($hasConflict)
                                <span class="ml-1">⚠️</span>
                            @endif
                        </div>
                        @if($rule->min_amount === $rule->max_amount && $rule->min_amount !== null)
                            <div class="px-3 py-1 rounded text-xs font-medium bg-purple-500/20 text-purple-300 border border-purple-500/30" title="Exact Amount">
                                Exact
                            </div>
                        @elseif($rule->max_amount === null)
                            <div class="px-3 py-1 rounded text-xs font-medium bg-orange-500/20 text-orange-300 border border-orange-500/30" title="Minimum Only">
                                Minimum
                            </div>
                        @else
                            <div class="px-3 py-1 rounded text-xs font-medium bg-cyan-500/20 text-cyan-300 border border-cyan-500/30" title="Range">
                                Range
                            </div>
                        @endif
                    </div>
                    <h3 class="text-white font-semibold text-lg mb-2">
                        {{ $rule->description }}
                    </h3>
                    <div class="flex flex-wrap gap-4 text-sm">
                        <div>
                            <span class="text-white/60">Diskon:</span>
                            <span class="text-yellow-300 font-medium ml-2">
                                @if($rule->discount_method === 'percentage')
                                    {{ number_format($rule->discount_value, 0, ',', '.') }}%
                                @else
                                    Rp {{ number_format($rule->discount_value, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                        @if($rule->min_amount !== null && $rule->max_amount !== null)
                            @if($rule->min_amount === $rule->max_amount)
                                <div>
                                    <span class="text-white/60">Tipe:</span>
                                    <span class="text-white/80 ml-2">Exact Amount ({{ number_format($rule->min_amount, 0, ',', '.') }} Robux)</span>
                                </div>
                            @else
                                <div>
                                    <span class="text-white/60">Range:</span>
                                    <span class="text-white/80 ml-2">{{ number_format($rule->min_amount, 0, ',', '.') }} - {{ number_format($rule->max_amount, 0, ',', '.') }} Robux</span>
                                </div>
                            @endif
                        @elseif($rule->min_amount !== null)
                            <div>
                                <span class="text-white/60">Tipe:</span>
                                <span class="text-white/80 ml-2">Minimum (≥ {{ number_format($rule->min_amount, 0, ',', '.') }} Robux)</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button onclick="editRule({{ $rule->id }})" class="px-3 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition text-sm" title="Edit">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </button>
                    <form method="POST" action="{{ route('admin.robux-discount-rules.toggle-status', $rule) }}" class="inline">
                        @csrf
                        <button type="submit" class="px-3 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition text-sm" title="{{ $rule->is_active ? 'Deactivate' : 'Activate' }}">
                            @if($rule->is_active)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                </svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @endif
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.robux-discount-rules.destroy', $rule) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus rule ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition text-sm" title="Delete">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="rounded-lg border border-white/20 p-8 sm:p-12 bg-white/5 text-center">
            <svg class="w-16 h-16 text-white/30 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="text-white/60 text-base sm:text-lg mb-4">Belum ada discount rules</div>
            <button onclick="showCreateModal()" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Buat Rule Pertama
            </button>
        </div>
        @endforelse
    </div>
</main>

<!-- Create/Edit Modal -->
<div id="ruleModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-gray-800 rounded-xl border border-white/20 p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-white" id="modalTitle">Tambah Discount Rule</h2>
            <button onclick="closeModal()" class="text-white/60 hover:text-white transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="ruleForm" method="POST" action="" class="space-y-4">
            @csrf
            <span id="formMethod"></span>

            <input type="hidden" name="purchase_method" value="{{ $purchaseMethod }}">

            <!-- Rule Type -->
            <div>
                <label class="block text-white/70 text-sm mb-2">Tipe Rule *</label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg border border-white/20 hover:bg-white/5 transition">
                        <input type="radio" name="rule_type" value="exact" onchange="updateRuleTypeFields()" class="text-yellow-400" checked>
                        <span class="text-white text-sm">Exact Amount</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg border border-white/20 hover:bg-white/5 transition">
                        <input type="radio" name="rule_type" value="range" onchange="updateRuleTypeFields()" class="text-yellow-400">
                        <span class="text-white text-sm">Range</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer p-3 rounded-lg border border-white/20 hover:bg-white/5 transition">
                        <input type="radio" name="rule_type" value="minimum" onchange="updateRuleTypeFields()" class="text-yellow-400">
                        <span class="text-white text-sm">Minimum</span>
                    </label>
                </div>
            </div>

            <!-- Amount Fields -->
            <div id="exactAmountFields" class="space-y-4">
                <label class="block">
                    <span class="text-white/70 text-sm">Jumlah Robux (Exact) *</span>
                    <input type="number" name="exact_amount" step="1" min="0" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="1000">
                </label>
            </div>

            <div id="rangeAmountFields" class="space-y-4 hidden">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="block">
                        <span class="text-white/70 text-sm">Minimum (Robux) *</span>
                        <input type="number" name="min_amount" step="1" min="0" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="1000">
                    </label>
                    <label class="block">
                        <span class="text-white/70 text-sm">Maximum (Robux) *</span>
                        <input type="number" name="max_amount" step="1" min="0" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="1999">
                    </label>
                </div>
            </div>

            <div id="minimumAmountFields" class="space-y-4 hidden">
                <label class="block">
                    <span class="text-white/70 text-sm">Minimum Pembelian (Robux) *</span>
                    <input type="number" name="min_amount_only" step="1" min="0" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="1000">
                    <p class="mt-1 text-white/50 text-xs">Diskon berlaku untuk pembelian ≥ jumlah ini</p>
                </label>
            </div>

            <!-- Discount Configuration -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="block">
                    <span class="text-white/70 text-sm">Metode Diskon *</span>
                    <select name="discount_method" id="discountMethod" onchange="updateDiscountUnits()" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" required>
                        <option value="">Pilih Metode</option>
                        <option value="percentage">Persentase (%)</option>
                        <option value="fixed_amount">Nominal (Rp)</option>
                    </select>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm">Nilai Diskon *</span>
                    <div class="mt-2 flex items-center gap-2">
                        <input type="number" name="discount_value" step="0.01" min="0" id="discountValue" class="flex-1 px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="0" required>
                        <span id="discountUnit" class="text-white/60 text-sm">%</span>
                    </div>
                </label>
            </div>

            <!-- Priority -->
            <label class="block">
                <span class="text-white/70 text-sm">Priority (Sort Order) *</span>
                <input type="number" name="sort_order" step="1" min="0" value="0" class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-yellow-400 focus:ring-1 focus:ring-yellow-400" placeholder="0" required>
                <div class="mt-2 p-3 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                    <p class="text-blue-300 text-xs font-medium mb-2">📌 Cara Kerja Priority:</p>
                    <ul class="text-white/70 text-xs space-y-1 ml-4 list-disc">
                        <li><strong>Angka lebih kecil = Priority lebih tinggi</strong> (dicek lebih dulu)</li>
                        <li>Jika ada beberapa rules match, yang priority lebih tinggi yang dipilih</li>
                        <li><strong>Rekomendasi:</strong> Exact Amount = 0, Range Sempit = 1-2, Range Luas = 3-5, Minimum Besar = 0-1</li>
                    </ul>
                    <details class="mt-2">
                        <summary class="text-blue-300 text-xs cursor-pointer hover:text-blue-200">💡 Contoh & Tips (Klik untuk lihat)</summary>
                        <div class="mt-2 text-white/60 text-xs space-y-1">
                            <p><strong>✅ Contoh Benar:</strong></p>
                            <ul class="ml-4 list-disc space-y-0.5">
                                <li>Priority 0: Exact 1000 Robux → Diskon 5%</li>
                                <li>Priority 1: Range 1000-1999 → Diskon 10%</li>
                                <li>Priority 2: Minimum ≥2000 → Diskon 20%</li>
                            </ul>
                            <p class="mt-2"><strong>⚠️ Hindari Overlap:</strong></p>
                            <ul class="ml-4 list-disc space-y-0.5">
                                <li>Jangan buat range yang overlap tanpa aturan priority yang jelas</li>
                                <li>Minimum yang lebih besar harus priority lebih kecil</li>
                            </ul>
                        </div>
                    </details>
                </div>
            </label>

            <!-- Status -->
            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-yellow-400 bg-black/30 border-white/20 rounded focus:ring-yellow-500 focus:ring-2">
                <span class="text-white/70 text-sm">Aktif</span>
            </label>

            <!-- Preview -->
            <div class="p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                <h4 class="text-yellow-300 font-medium mb-3 text-sm">Preview:</h4>
                <div class="space-y-2 text-xs sm:text-sm">
                    <div class="flex justify-between">
                        <span class="text-white/60">Kondisi:</span>
                        <span class="text-white font-medium" id="preview-condition">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Diskon:</span>
                        <span class="text-yellow-300 font-medium" id="preview-discount">-</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Contoh: Beli 1000 Robux</span>
                        <span class="text-white font-medium">Rp 100.000</span>
                    </div>
                    <div class="flex justify-between border-t border-white/10 pt-2">
                        <span class="text-white/60">Setelah Diskon:</span>
                        <span class="text-yellow-300 font-bold" id="preview-final">-</span>
                    </div>
                </div>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 px-4 py-3 rounded-lg bg-yellow-500 hover:bg-yellow-600 text-white font-medium transition">
                    Simpan
                </button>
                <button type="button" onclick="closeModal()" class="px-4 py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
let editingRuleId = null;
const rules = @json($rules->keyBy('id'));

function showCreateModal() {
    editingRuleId = null;
    document.getElementById('modalTitle').textContent = 'Tambah Discount Rule';
    document.getElementById('ruleForm').action = '{{ route("admin.robux-discount-rules.store") }}';
    document.getElementById('formMethod').innerHTML = '';
    document.getElementById('ruleForm').reset();
    document.querySelector('input[name="rule_type"][value="exact"]').checked = true;
    updateRuleTypeFields();
    updatePreview();
    document.getElementById('ruleModal').classList.remove('hidden');
    document.getElementById('ruleModal').classList.add('flex');
}

function editRule(id) {
    const rule = rules[id];
    if (!rule) return;
    
    editingRuleId = id;
    document.getElementById('modalTitle').textContent = 'Edit Discount Rule';
    document.getElementById('ruleForm').action = `/system/robux-discount-rules/${id}`;
    document.getElementById('formMethod').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    
    // Determine rule type
    if (rule.min_amount === rule.max_amount && rule.min_amount !== null) {
        document.querySelector('input[name="rule_type"][value="exact"]').checked = true;
        document.querySelector('input[name="exact_amount"]').value = rule.min_amount;
    } else if (rule.max_amount === null && rule.min_amount !== null) {
        document.querySelector('input[name="rule_type"][value="minimum"]').checked = true;
        document.querySelector('input[name="min_amount_only"]').value = rule.min_amount;
    } else {
        document.querySelector('input[name="rule_type"][value="range"]').checked = true;
        document.querySelector('input[name="min_amount"]').value = rule.min_amount || '';
        document.querySelector('input[name="max_amount"]').value = rule.max_amount || '';
    }
    
    document.querySelector('select[name="discount_method"]').value = rule.discount_method;
    document.querySelector('input[name="discount_value"]').value = rule.discount_value;
    document.querySelector('input[name="sort_order"]').value = rule.sort_order;
    document.querySelector('input[name="is_active"]').checked = rule.is_active;
    
    updateRuleTypeFields();
    updateDiscountUnits();
    updatePreview();
    document.getElementById('ruleModal').classList.remove('hidden');
    document.getElementById('ruleModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('ruleModal').classList.add('hidden');
    document.getElementById('ruleModal').classList.remove('flex');
}

function updateRuleTypeFields() {
    const type = document.querySelector('input[name="rule_type"]:checked').value;
    document.getElementById('exactAmountFields').classList.toggle('hidden', type !== 'exact');
    document.getElementById('rangeAmountFields').classList.toggle('hidden', type !== 'range');
    document.getElementById('minimumAmountFields').classList.toggle('hidden', type !== 'minimum');
    
    // Clear all amount inputs
    document.querySelector('input[name="exact_amount"]').value = '';
    document.querySelector('input[name="min_amount"]').value = '';
    document.querySelector('input[name="max_amount"]').value = '';
    document.querySelector('input[name="min_amount_only"]').value = '';
    
    updatePreview();
}

function updateDiscountUnits() {
    const method = document.getElementById('discountMethod').value;
    document.getElementById('discountUnit').textContent = method === 'percentage' ? '%' : 'Rp';
    updatePreview();
}

function updatePreview() {
    const type = document.querySelector('input[name="rule_type"]:checked').value;
    const method = document.getElementById('discountMethod').value;
    const value = parseFloat(document.getElementById('discountValue').value) || 0;
    const examplePrice = 100000;
    
    let condition = '-';
    let discountText = '-';
    let finalPrice = '-';
    
    if (type === 'exact') {
        const amount = document.querySelector('input[name="exact_amount"]').value;
        if (amount) {
            condition = `Pembelian tepat ${parseInt(amount).toLocaleString('id-ID')} Robux`;
        }
    } else if (type === 'range') {
        const min = document.querySelector('input[name="min_amount"]').value;
        const max = document.querySelector('input[name="max_amount"]').value;
        if (min && max) {
            condition = `Pembelian ${parseInt(min).toLocaleString('id-ID')}-${parseInt(max).toLocaleString('id-ID')} Robux`;
        }
    } else if (type === 'minimum') {
        const min = document.querySelector('input[name="min_amount_only"]').value;
        if (min) {
            condition = `Pembelian ≥ ${parseInt(min).toLocaleString('id-ID')} Robux`;
        }
    }
    
    if (method && value > 0) {
        let discountAmount = 0;
        if (method === 'percentage') {
            discountAmount = examplePrice * (value / 100);
            discountText = `${value.toLocaleString('id-ID')}% (-Rp ${discountAmount.toLocaleString('id-ID')})`;
        } else {
            discountAmount = Math.min(value, examplePrice);
            discountText = `Rp ${value.toLocaleString('id-ID')} (-Rp ${discountAmount.toLocaleString('id-ID')})`;
        }
        finalPrice = `Rp ${(examplePrice - discountAmount).toLocaleString('id-ID')}`;
    }
    
    document.getElementById('preview-condition').textContent = condition;
    document.getElementById('preview-discount').textContent = discountText;
    document.getElementById('preview-final').textContent = finalPrice;
}

// Event listeners
document.querySelectorAll('input[name="rule_type"]').forEach(radio => {
    radio.addEventListener('change', updateRuleTypeFields);
});

document.getElementById('discountMethod')?.addEventListener('change', updateDiscountUnits);
document.getElementById('discountValue')?.addEventListener('input', updatePreview);
document.querySelector('input[name="exact_amount"]')?.addEventListener('input', updatePreview);
document.querySelector('input[name="min_amount"]')?.addEventListener('input', updatePreview);
document.querySelector('input[name="max_amount"]')?.addEventListener('input', updatePreview);
document.querySelector('input[name="min_amount_only"]')?.addEventListener('input', updatePreview);

// Handle form submission - convert to proper format
document.getElementById('ruleForm')?.addEventListener('submit', function(e) {
    const type = document.querySelector('input[name="rule_type"]:checked').value;
    const form = this;
    
    if (type === 'exact') {
        const exact = document.querySelector('input[name="exact_amount"]').value;
        if (exact) {
            const hiddenMin = document.createElement('input');
            hiddenMin.type = 'hidden';
            hiddenMin.name = 'min_amount';
            hiddenMin.value = exact;
            form.appendChild(hiddenMin);
            
            const hiddenMax = document.createElement('input');
            hiddenMax.type = 'hidden';
            hiddenMax.name = 'max_amount';
            hiddenMax.value = exact;
            form.appendChild(hiddenMax);
        }
    } else if (type === 'minimum') {
        const min = document.querySelector('input[name="min_amount_only"]').value;
        if (min) {
            const hiddenMin = document.createElement('input');
            hiddenMin.type = 'hidden';
            hiddenMin.name = 'min_amount';
            hiddenMin.value = min;
            form.appendChild(hiddenMin);
        }
    }
    // For range, min_amount and max_amount already exist
});
</script>
@endsection

