@extends('layouts.app')
@section('title', 'Admin • Promo Codes')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Promo Codes Management</span>
            </h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Manage discount promo codes for customers</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.promo-codes.create') }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Promo Code
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Promo Codes Table -->
    <div class="rounded-lg border border-white/20 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/10 border-b border-white/20">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Code</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Discount</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Usage</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Created</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($promoCodes as $promoCode)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <code class="text-yellow-300 font-mono font-semibold text-sm sm:text-base bg-yellow-500/10 px-2 py-1 rounded border border-yellow-500/30">{{ $promoCode->code }}</code>
                                <button onclick="copyToClipboard('{{ $promoCode->code }}')" class="text-white/50 hover:text-white transition" title="Copy code">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex flex-col gap-1">
                                @if($promoCode->discount_method === 'percentage')
                                    <span class="text-white font-medium text-sm">
                                        {{ number_format($promoCode->discount_value_min, 0, ',', '.') }}% - {{ number_format($promoCode->discount_value_max, 0, ',', '.') }}%
                                    </span>
                                    <span class="text-white/50 text-xs">Random Percentage</span>
                                @else
                                    <span class="text-white font-medium text-sm">
                                        Rp {{ number_format($promoCode->discount_value_min, 0, ',', '.') }} - Rp {{ number_format($promoCode->discount_value_max, 0, ',', '.') }}
                                    </span>
                                    <span class="text-white/50 text-xs">Random Nominal</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-white font-medium text-sm">
                                    {{ $promoCode->current_uses }} / {{ $promoCode->max_uses }}
                                </span>
                                <div class="w-full bg-white/10 rounded-full h-1.5">
                                    @php
                                        $usagePercentage = $promoCode->max_uses > 0 ? ($promoCode->current_uses / $promoCode->max_uses) * 100 : 0;
                                        $usageColor = $usagePercentage >= 100 ? 'bg-red-500' : ($usagePercentage >= 80 ? 'bg-yellow-500' : 'bg-emerald-500');
                                    @endphp
                                    <div class="{{ $usageColor }} h-1.5 rounded-full transition-all" style="width: {{ min(100, $usagePercentage) }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            @if($promoCode->is_active && $promoCode->current_uses < $promoCode->max_uses)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active
                                </span>
                            @elseif($promoCode->current_uses >= $promoCode->max_uses)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/30">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Exhausted
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-500/20 text-gray-300 border border-gray-500/30">
                                    Inactive
                                </span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-white/60 text-xs sm:text-sm">
                            {{ $promoCode->created_at->format('d M Y') }}
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <button onclick="showUsageDetails({{ $promoCode->id }})" class="px-3 py-1.5 rounded-md bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 hover:text-purple-200 transition-colors border border-purple-500/30" title="Lihat Detail Penggunaan">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Detail</span>
                                </button>
                                <a href="{{ route('admin.promo-codes.edit', $promoCode) }}" class="px-3 py-1.5 rounded-md bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 hover:text-blue-200 transition-colors border border-blue-500/30" title="Edit">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    <span class="text-xs font-medium">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.promo-codes.toggle-status', $promoCode) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-md {{ $promoCode->is_active ? 'bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 hover:text-yellow-200 border-yellow-500/30' : 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 hover:text-emerald-200 border-emerald-500/30' }} transition-colors border" title="{{ $promoCode->is_active ? 'Deactivate' : 'Activate' }}">
                                        @if($promoCode->is_active)
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                            </svg>
                                            <span class="text-xs font-medium">Nonaktif</span>
                                        @else
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-xs font-medium">Aktifkan</span>
                                        @endif
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.promo-codes.destroy', $promoCode) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus promo code ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-md bg-red-500/20 hover:bg-red-500/30 text-red-300 hover:text-red-200 transition-colors border border-red-500/30" title="Hapus">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        <span class="text-xs font-medium">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                            <div class="text-white/60 text-base sm:text-lg mb-4">No promo codes found</div>
                            <a href="{{ route('admin.promo-codes.create') }}" class="inline-flex items-center gap-2 bg-yellow-500 hover:bg-yellow-600 text-white px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Promo Code
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($promoCodes->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $promoCodes->links() }}
    </div>
    @endif
</main>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show temporary notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Code copied to clipboard!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

function showUsageDetails(promoCodeId) {
    fetch(`/system/promo-codes/${promoCodeId}/usages`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        credentials: 'same-origin'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            // Calculate stats
            const paidUsages = data.usages.filter(u => u.is_paid).length;
            const unpaidUsages = data.usages.filter(u => !u.is_paid).length;
            const totalDiscount = data.usages.reduce((sum, u) => sum + parseFloat(u.discount_amount), 0);
            const paidTotalDiscount = data.usages.filter(u => u.is_paid).reduce((sum, u) => sum + parseFloat(u.discount_amount), 0);
            
            // Separate paid and unpaid usages
            const paidList = data.usages.filter(u => u.is_paid);
            const unpaidList = data.usages.filter(u => !u.is_paid);
            
            // Create modal
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,900px)] max-h-[85vh] overflow-y-auto">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <div class="text-xl font-semibold text-white">Detail Penggunaan Promo Code</div>
                            <div class="text-sm text-white/50 mt-1">Total penggunaan: ${data.usages.length}</div>
                        </div>
                        <button onclick="this.closest('.fixed').remove()" class="text-white/60 hover:text-white transition p-2 hover:bg-white/10 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    ${data.usages.length > 0 ? `
                        <!-- Stats Summary -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                                <div class="text-emerald-300 text-xs font-medium mb-1">Terbayar</div>
                                <div class="text-white text-lg font-semibold">${paidUsages}</div>
                            </div>
                            <div class="p-4 rounded-lg bg-yellow-500/10 border border-yellow-500/20">
                                <div class="text-yellow-300 text-xs font-medium mb-1">Belum Terbayar</div>
                                <div class="text-white text-lg font-semibold">${unpaidUsages}</div>
                            </div>
                            <div class="p-4 rounded-lg bg-blue-500/10 border border-blue-500/20">
                                <div class="text-blue-300 text-xs font-medium mb-1">Total Diskon</div>
                                <div class="text-white text-lg font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(totalDiscount)}</div>
                            </div>
                            <div class="p-4 rounded-lg bg-purple-500/10 border border-purple-500/20">
                                <div class="text-purple-300 text-xs font-medium mb-1">Diskon Terbayar</div>
                                <div class="text-white text-lg font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(paidTotalDiscount)}</div>
                            </div>
                        </div>
                        
                        ${paidList.length > 0 ? `
                        <!-- Paid Usages Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-emerald-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Terbayar (${paidList.length})
                            </h3>
                            <div class="overflow-x-auto rounded-lg border border-emerald-500/20 bg-emerald-500/5">
                                <table class="w-full text-sm">
                                    <thead class="bg-emerald-500/10 border-b border-emerald-500/20">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Order ID</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Username</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Email</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Harga Asli</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Diskon</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Harga Final</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-emerald-500/10">
                                        ${paidList.map(usage => `
                                            <tr class="hover:bg-emerald-500/10 transition-colors">
                                                <td class="px-4 py-3 text-white font-mono text-xs">${usage.order_id || '-'}</td>
                                                <td class="px-4 py-3 text-white font-medium">${usage.username || '-'}</td>
                                                <td class="px-4 py-3 text-white/80 text-xs">${usage.email || '-'}</td>
                                                <td class="px-4 py-3 text-right text-white">Rp ${new Intl.NumberFormat('id-ID').format(usage.original_price)}</td>
                                                <td class="px-4 py-3 text-right text-yellow-300 font-medium">-Rp ${new Intl.NumberFormat('id-ID').format(usage.discount_amount)}</td>
                                                <td class="px-4 py-3 text-right text-emerald-300 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(usage.final_price)}</td>
                                                <td class="px-4 py-3 text-white/60 text-xs">${new Date(usage.created_at).toLocaleString('id-ID', { 
                                                    year: 'numeric', 
                                                    month: 'short', 
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                })}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : ''}
                        
                        ${unpaidList.length > 0 ? `
                        <!-- Unpaid Usages Section -->
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-yellow-300 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Belum Terbayar (${unpaidList.length})
                            </h3>
                            <div class="overflow-x-auto rounded-lg border border-yellow-500/20 bg-yellow-500/5">
                                <table class="w-full text-sm">
                                    <thead class="bg-yellow-500/10 border-b border-yellow-500/20">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Order ID</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Username</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Email</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Harga Asli</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Diskon</th>
                                            <th class="px-4 py-3 text-right text-white/70 font-medium">Harga Final</th>
                                            <th class="px-4 py-3 text-left text-white/70 font-medium">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-yellow-500/10">
                                        ${unpaidList.map(usage => `
                                            <tr class="hover:bg-yellow-500/10 transition-colors">
                                                <td class="px-4 py-3 text-white font-mono text-xs">${usage.order_id || '-'}</td>
                                                <td class="px-4 py-3 text-white font-medium">${usage.username || '-'}</td>
                                                <td class="px-4 py-3 text-white/80 text-xs">${usage.email || '-'}</td>
                                                <td class="px-4 py-3 text-right text-white">Rp ${new Intl.NumberFormat('id-ID').format(usage.original_price)}</td>
                                                <td class="px-4 py-3 text-right text-yellow-300 font-medium">-Rp ${new Intl.NumberFormat('id-ID').format(usage.discount_amount)}</td>
                                                <td class="px-4 py-3 text-right text-emerald-300 font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(usage.final_price)}</td>
                                                <td class="px-4 py-3 text-white/60 text-xs">${new Date(usage.created_at).toLocaleString('id-ID', { 
                                                    year: 'numeric', 
                                                    month: 'short', 
                                                    day: 'numeric',
                                                    hour: '2-digit',
                                                    minute: '2-digit'
                                                })}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        ` : ''}
                    ` : `
                        <div class="text-center py-12 text-white/60">
                            <svg class="w-16 h-16 mx-auto mb-4 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <div class="text-lg font-medium text-white/80 mb-2">Belum Ada Penggunaan</div>
                            <div class="text-sm">Promo code ini belum pernah digunakan</div>
                        </div>
                    `}
                </div>
            `;
            document.body.appendChild(modal);
            
            // Close on outside click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.remove();
                }
            });
            
            // Close on Escape key
            const closeHandler = function(e) {
                if (e.key === 'Escape') {
                    modal.remove();
                    document.removeEventListener('keydown', closeHandler);
                }
            };
            document.addEventListener('keydown', closeHandler);
        })
        .catch(error => {
            console.error('Error loading usage details:', error);
            alert('Gagal memuat detail penggunaan');
        });
}

// Auto-sync promo code usages every 10 seconds
(function() {
    let isSyncing = false;
    
    function syncPromoCodeUsages() {
        // Prevent multiple simultaneous syncs
        if (isSyncing) {
            return;
        }
        
        isSyncing = true;
        
        fetch('/system/promo-codes/sync-usages', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Silently sync - no need to show notification unless there are updates
                if (data.updated > 0) {
                    console.log(`Synced ${data.updated} promo code usages`);
                }
            }
        })
        .catch(error => {
            // Silent error - don't disturb user with sync errors
            console.error('Error syncing promo code usages:', error);
        })
        .finally(() => {
            isSyncing = false;
        });
    }
    
    // Initial sync after page load
    setTimeout(syncPromoCodeUsages, 2000); // Wait 2 seconds after page load
    
    // Then sync every 10 seconds
    setInterval(syncPromoCodeUsages, 10000); // 10 seconds
})();
</script>
@endsection


