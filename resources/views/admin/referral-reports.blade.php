@extends('layouts.app')
@section('title', 'Admin • Referral Reports')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>Referral Reports</span>
            </h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Monitor clicks, conversions, and approved commissions</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.referral-codes') }}" class="bg-white/10 hover:bg-white/15 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base transition border border-white/15">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Manage Codes
            </a>
        </div>
    </div>

    <div class="rounded-lg border border-white/20 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/10 border-b border-white/20">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Code</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Clicks</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Conversions</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Approved Reward</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Links</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($referralCodes as $referralCode)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <code class="text-emerald-300 font-mono font-semibold text-sm sm:text-base bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/30">{{ $referralCode->code }}</code>
                                </div>
                                @if($referralCode->name)
                                    <div class="text-white/50 text-xs mt-1">{{ $referralCode->name }}</div>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="text-white text-sm">{{ number_format((int) ($referralCode->clicks_count ?? 0), 0, ',', '.') }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="text-white text-sm">
                                    Total: <span class="text-white/90 font-medium">{{ number_format((int) ($referralCode->conversions_total_count ?? 0), 0, ',', '.') }}</span>
                                </div>
                                <div class="text-white/60 text-xs mt-1">
                                    Pending: {{ number_format((int) ($referralCode->conversions_pending_count ?? 0), 0, ',', '.') }}
                                    | Approved: {{ number_format((int) ($referralCode->conversions_approved_count ?? 0), 0, ',', '.') }}
                                    | Rejected: {{ number_format((int) ($referralCode->conversions_rejected_count ?? 0), 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="text-emerald-200 text-sm font-medium">
                                    Rp {{ number_format((float) ($referralCode->approved_reward_sum ?? 0), 0, ',', '.') }}
                                </div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="copyToClipboard('{{ url('/r/' . $referralCode->code) }}')" class="px-3 py-1.5 rounded-md bg-white/10 hover:bg-white/15 text-white/80 hover:text-white transition-colors border border-white/15" title="Copy public link">
                                        <span class="text-xs font-medium">Copy Public</span>
                                    </button>
                                    <button onclick="copyToClipboard('{{ url('/ref/' . $referralCode->code . '/dashboard/' . $referralCode->secret_token) }}')" class="px-3 py-1.5 rounded-md bg-white/10 hover:bg-white/15 text-white/80 hover:text-white transition-colors border border-white/15" title="Copy owner dashboard link">
                                        <span class="text-xs font-medium">Copy Owner</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-white/60">Belum ada referral code.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-4 sm:px-6 py-4 border-t border-white/10">
            {{ $referralCodes->links() }}
        </div>
    </div>
</main>

<script>
function copyToClipboard(text) {
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(text);
    } else {
        const temp = document.createElement('textarea');
        temp.value = text;
        temp.style.position = 'fixed';
        temp.style.left = '-9999px';
        document.body.appendChild(temp);
        temp.focus();
        temp.select();
        try { document.execCommand('copy'); } catch (e) {}
        document.body.removeChild(temp);
    }
}
</script>
@endsection
