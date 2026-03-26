@extends('layouts.app')
@section('title', 'Admin • Activity History')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Histori Aktivitas</h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Audit trail tindakan admin (approve/reject payment, proses/tolak order)</p>
    </div>

    <form method="GET" action="{{ route('admin.activity-history') }}" class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">
        <input
            name="search"
            value="{{ request('search') }}"
            class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white placeholder-white/30 flex-1 text-sm sm:text-base"
            placeholder="Cari username / order id (contoh: rizki atau #9900)"
        />
        <select name="action" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base">
            <option value="">Semua Aksi</option>
            @foreach($actionOptions as $opt)
                <option value="{{ $opt }}" {{ request('action') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
        <input
            name="admin"
            value="{{ request('admin') }}"
            class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white placeholder-white/30 flex-1 text-sm sm:text-base"
            placeholder="Cari admin (nama/email)"
        />
        <div class="flex gap-2 sm:gap-3">
            <input
                name="date_from"
                type="date"
                value="{{ request('date_from') }}"
                class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base flex-1"
            />
            <input
                name="date_to"
                type="date"
                value="{{ request('date_to') }}"
                class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-black/30 border border-white/10 text-white text-sm sm:text-base flex-1"
            />
        </div>
        <div class="flex gap-2 sm:gap-3">
            <button type="submit" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-white text-black text-sm sm:text-base">Filter</button>
            <a href="{{ route('admin.activity-history') }}" class="px-3 sm:px-4 py-2 sm:py-3 rounded-sm bg-gray-600 text-white text-sm sm:text-base">Bersihkan</a>
        </div>
    </form>

    <div class="mt-6 sm:mt-8 rounded-md border border-white/10 bg-white/[0.02]">
        <div class="overflow-x-auto scrollbar-thin scrollbar-thumb-white/20 scrollbar-track-transparent">
            <table class="w-full text-left min-w-[900px]">
                <thead class="text-white/60">
                    <tr>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Waktu</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Aksi</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Admin</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Order</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">User</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Status</th>
                        <th class="px-2 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm">Catatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10 text-white/80">
                    @forelse($activities as $a)
                        @php
                            $atText = $a->created_at ? $a->created_at->format('M d, Y H:i') : '-';
                            $adminName = trim((string) ($a->admin_name ?? ''));
                            $adminEmail = trim((string) ($a->admin_email ?? ''));
                            $adminText = $adminName !== '' ? $adminName : 'Admin';
                            if ($adminEmail !== '') {
                                $adminText .= ' (' . $adminEmail . ')';
                            }
                            $statusText = (string) (($a->order_payment_status ?? '-') . ' / ' . ($a->order_order_status ?? '-'));
                        @endphp
                        <tr>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="text-xs sm:text-sm text-white">{{ $atText }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="font-mono text-xs sm:text-sm text-white">{{ $a->action ?? '-' }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="text-xs sm:text-sm">{{ $adminText }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                @if(!empty($a->order_id))
                                    <a href="{{ route('admin.orders.show', $a->order_id) }}" class="text-blue-300 hover:text-blue-200 text-xs sm:text-sm font-mono">
                                        #{{ $a->order_id ?? '-' }}
                                    </a>
                                @else
                                    <span class="text-xs sm:text-sm font-mono text-white/60">#-</span>
                                @endif
                                <div class="text-xs text-white/50 mt-1">{{ $a->order_game_type ?? '-' }} • {{ number_format((int) ($a->order_amount ?? 0), 0, ',', '.') }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="text-xs sm:text-sm text-white">{{ $a->order_username ?? '-' }}</div>
                                <div class="text-xs text-white/50 mt-1">{{ $a->order_email ?? '-' }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="text-xs sm:text-sm">{{ $statusText }}</div>
                            </td>
                            <td class="px-2 sm:px-4 py-2 sm:py-3">
                                <div class="text-xs sm:text-sm text-white/70">{{ $a->notes ?? '-' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-2 sm:px-4 py-8 sm:py-12 text-center">
                                <div class="text-white/60">Belum ada histori aktivitas.</div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($activities->hasPages())
            <div class="px-2 sm:px-4 py-3 sm:py-4 border-t border-white/10">
                <div class="flex items-center justify-between">
                    <div class="text-xs sm:text-sm text-white/60">
                        Menampilkan {{ $activities->firstItem() ?? 0 }} - {{ $activities->lastItem() ?? 0 }} dari {{ $activities->total() }} hasil
                    </div>
                    <div>
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
