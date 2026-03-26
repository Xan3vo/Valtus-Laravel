@extends('layouts.app')
@section('title', 'Admin • Referral Codes')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Referral Codes Management</span>
            </h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Manage referral codes, buyer discount, and referrer reward</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.referral-codes.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create Referral Code
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="rounded-lg border border-white/20 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/10 border-b border-white/20">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Code</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Buyer Discount</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Reward</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Range</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Owner Dashboard</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($referralCodes as $referralCode)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <code class="text-emerald-300 font-mono font-semibold text-sm sm:text-base bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/30">{{ $referralCode->code }}</code>
                                <button onclick="copyToClipboard('{{ url('/r/' . $referralCode->code) }}')" class="text-white/50 hover:text-white transition" title="Copy public link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            @if($referralCode->name)
                                <div class="text-white/50 text-xs mt-1">{{ $referralCode->name }}</div>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="text-white text-sm">
                                @if($referralCode->buyer_discount_method === 'percentage')
                                    {{ number_format((float) $referralCode->buyer_discount_value, 2, ',', '.') }}%
                                @else
                                    Rp {{ number_format((float) $referralCode->buyer_discount_value, 0, ',', '.') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="text-white text-sm">
                                @if($referralCode->reward_method === 'percentage')
                                    {{ number_format((float) $referralCode->reward_value, 2, ',', '.') }}%
                                @else
                                    Rp {{ number_format((float) $referralCode->reward_value, 0, ',', '.') }}
                                @endif
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="text-white/70 text-xs">
                                Min: {{ $referralCode->min_order_amount !== null ? 'Rp ' . number_format((float) $referralCode->min_order_amount, 0, ',', '.') : '-' }}
                            </div>
                            <div class="text-white/70 text-xs">
                                Max: {{ $referralCode->max_order_amount !== null ? 'Rp ' . number_format((float) $referralCode->max_order_amount, 0, ',', '.') : '-' }}
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            @if($referralCode->is_active)
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-500/20 text-gray-300 border border-gray-500/30">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ url('/ref/' . $referralCode->code . '/dashboard/' . $referralCode->secret_token) }}" target="_blank" class="text-blue-300 hover:text-blue-200 text-xs underline">Open</a>
                                <button onclick="copyToClipboard('{{ url('/ref/' . $referralCode->code . '/dashboard/' . $referralCode->secret_token) }}')" class="text-white/50 hover:text-white transition" title="Copy owner dashboard link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.referral-codes.show', $referralCode) }}" class="px-3 py-1.5 rounded-md bg-white/10 hover:bg-white/15 text-white/80 hover:text-white transition-colors border border-white/15" title="Detail">
                                    <span class="text-xs font-medium">Detail</span>
                                </a>
                                <a href="{{ route('admin.referral-codes.edit', $referralCode) }}" class="px-3 py-1.5 rounded-md bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 hover:text-blue-200 transition-colors border border-blue-500/30" title="Edit">
                                    <span class="text-xs font-medium">Edit</span>
                                </a>
                                <form method="POST" action="{{ route('admin.referral-codes.toggle-status', $referralCode) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-md {{ $referralCode->is_active ? 'bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 hover:text-yellow-200 border-yellow-500/30' : 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 hover:text-emerald-200 border-emerald-500/30' }} transition-colors border" title="{{ $referralCode->is_active ? 'Deactivate' : 'Activate' }}">
                                        <span class="text-xs font-medium">{{ $referralCode->is_active ? 'Nonaktif' : 'Aktifkan' }}</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.referral-codes.destroy', $referralCode) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus referral code ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-md bg-red-500/20 hover:bg-red-500/30 text-red-300 hover:text-red-200 transition-colors border border-red-500/30" title="Hapus">
                                        <span class="text-xs font-medium">Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                            <div class="text-white/60 text-base sm:text-lg mb-4">No referral codes found</div>
                            <a href="{{ route('admin.referral-codes.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create First Referral Code
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($referralCodes->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $referralCodes->links() }}
    </div>
    @endif
</main>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-emerald-600 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        toast.textContent = 'Copied!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}
</script>
@endsection
