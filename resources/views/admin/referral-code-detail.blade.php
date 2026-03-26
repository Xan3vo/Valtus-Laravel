@extends('layouts.app')
@section('title', 'Admin • Referral Detail')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-6 sm:mb-8">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Referral Detail</h1>
                <code class="text-emerald-300 font-mono font-semibold text-sm sm:text-base bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/30">{{ $referralCode->code }}</code>
                @if($referralCode->is_active)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Active</span>
                @else
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-500/20 text-gray-300 border border-gray-500/30">Inactive</span>
                @endif
            </div>
            @if($referralCode->name)
                <div class="text-white/60 mt-2">{{ $referralCode->name }}</div>
            @endif
            <div class="text-white/50 text-sm mt-2">
                Public: <span class="text-white/70">{{ url('/r/' . $referralCode->code) }}</span>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.referral-codes') }}" class="px-3 py-2 rounded-lg border border-white/15 bg-white/5 hover:bg-white/10 text-white/80 hover:text-white text-sm transition">Kembali</a>
            <a href="{{ route('admin.referral-codes.edit', $referralCode) }}" class="px-3 py-2 rounded-lg border border-blue-500/30 bg-blue-500/15 hover:bg-blue-500/25 text-blue-200 text-sm transition">Edit</a>
            <button onclick="copyToClipboard('{{ url('/r/' . $referralCode->code) }}')" class="px-3 py-2 rounded-lg border border-white/15 bg-white/5 hover:bg-white/10 text-white/80 hover:text-white text-sm transition">Copy Link</button>
            <button onclick="copyToClipboard('{{ url('/ref/' . $referralCode->code . '/dashboard/' . $referralCode->secret_token) }}')" class="px-3 py-2 rounded-lg border border-white/15 bg-white/5 hover:bg-white/10 text-white/80 hover:text-white text-sm transition">Copy Owner</button>
        </div>
    </div>

    <div class="grid lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-xl border border-white/15 bg-white/5 p-4">
            <div class="text-white/60 text-xs">Clicks</div>
            <div class="text-white text-2xl font-semibold mt-1">{{ number_format((int) ($stats['clicks_count'] ?? 0), 0, ',', '.') }}</div>
        </div>
        <div class="rounded-xl border border-white/15 bg-white/5 p-4">
            <div class="text-white/60 text-xs">Conversions (Total)</div>
            <div class="text-white text-2xl font-semibold mt-1">{{ number_format((int) ($stats['conversions_total_count'] ?? 0), 0, ',', '.') }}</div>
            <div class="text-white/50 text-xs mt-2">
                Pending: {{ number_format((int) ($stats['conversions_pending_count'] ?? 0), 0, ',', '.') }}
                | Approved: {{ number_format((int) ($stats['conversions_approved_count'] ?? 0), 0, ',', '.') }}
                | Rejected: {{ number_format((int) ($stats['conversions_rejected_count'] ?? 0), 0, ',', '.') }}
            </div>
        </div>
        <div class="rounded-xl border border-white/15 bg-white/5 p-4 lg:col-span-2">
            <div class="text-white/60 text-xs">Approved Reward</div>
            <div class="text-emerald-200 text-2xl font-semibold mt-1">Rp {{ number_format((float) ($stats['approved_reward_sum'] ?? 0), 0, ',', '.') }}</div>
            <div class="text-white/50 text-xs mt-2">Komisi dihitung dari conversion yang statusnya approved.</div>
        </div>
    </div>



        <div class="rounded-xl border border-white/15 bg-white/5 overflow-hidden">
            <div class="px-4 sm:px-6 py-4 border-b border-white/10">
                <div class="text-white/90 font-semibold">Conversions</div>
                <div class="text-white/50 text-xs mt-1">Username dan email ditampilkan (khusus admin).</div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-white/10 border-b border-white/10">
                        <tr>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Order</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Username</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Email</th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-white/60 uppercase tracking-wider">Total</th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-white/60 uppercase tracking-wider">Diskon</th>
                            <th class="px-4 sm:px-6 py-3 text-right text-xs font-medium text-white/60 uppercase tracking-wider">Komisi</th>
                            <th class="px-4 sm:px-6 py-3 text-left text-xs font-medium text-white/60 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($conversions as $conv)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-4 sm:px-6 py-3 text-sm text-white/80 whitespace-nowrap">
                                    {{ optional($conv->created_at)->format('Y-m-d') ?? '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-xs text-white/70 whitespace-nowrap">
                                    <code class="text-white/80">{{ $conv->order_id }}</code>
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-white/80 whitespace-nowrap">
                                    {{ $conv->buyer_username ?? '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-xs text-white/70 whitespace-nowrap">
                                    {{ $conv->buyer_email ?? '-' }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-white/80 text-right whitespace-nowrap">
                                    Rp {{ number_format((float) ($conv->order_amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-white/80 text-right whitespace-nowrap">
                                    Rp {{ number_format((float) ($conv->buyer_discount_amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-sm text-emerald-200 text-right whitespace-nowrap">
                                    Rp {{ number_format((float) ($conv->reward_amount ?? 0), 0, ',', '.') }}
                                </td>
                                <td class="px-4 sm:px-6 py-3 text-xs whitespace-nowrap">
                                    @php $status = strtolower((string) ($conv->status ?? 'pending')); @endphp
                                    @if($status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">approved</span>
                                    @elseif($status === 'rejected')
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/30">rejected</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 sm:px-6 py-8 text-center text-white/60">Belum ada conversion.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 sm:px-6 py-4 border-t border-white/10">
                {{ $conversions->links() }}
            </div>
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
