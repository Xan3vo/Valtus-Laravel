@extends('layouts.app')
@section('title', 'Referral Dashboard')
@section('body')

<main class="max-w-6xl mx-auto px-6 py-10">
    <div class="rounded-xl border border-white/10 bg-white/5 p-6">
        <div class="text-white text-xl font-semibold">Referral Dashboard</div>
        <div class="mt-2 text-white/70 text-sm">Kode: <span class="text-white font-medium">{{ $referralCode->code }}</span></div>
        <div class="mt-1 text-white/70 text-sm">Pemilik: <span class="text-white font-medium">{{ $referralCode->name ?? '-' }}</span></div>
        <div class="mt-1 text-white/70 text-sm">Link Sebar: <span class="text-white font-medium">{{ $publicLink }}</span></div>
        <div class="mt-4 text-white/90 font-medium">Total Komisi (Approved)</div>
        <div class="text-white text-2xl font-semibold">Rp {{ number_format($approvedTotal, 0, ',', '.') }}</div>
    </div>

    <div class="mt-6 rounded-xl border border-white/10 bg-white/5 p-6">
        <div class="text-white/90 font-medium">Riwayat Pembelian (maks 100 terbaru)</div>
        <div class="mt-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-white/60">
                        <th class="text-left py-2 pr-4">Tanggal</th>
                        <th class="text-left py-2 pr-4">Order</th>
                        <th class="text-left py-2 pr-4">Pembeli</th>
                        <th class="text-left py-2 pr-4">Email</th>
                        <th class="text-right py-2 pl-4">Total</th>
                        <th class="text-right py-2 pl-4">Diskon</th>
                        <th class="text-right py-2 pl-4">Komisi</th>
                        <th class="text-left py-2 pl-4">Status</th>
                    </tr>
                </thead>
                <tbody class="text-white/80">
                    @forelse($conversions as $c)
                        <tr class="border-t border-white/10">
                            <td class="py-2 pr-4">{{ optional($c['created_at'])->format('Y-m-d') }}</td>
                            <td class="py-2 pr-4">{{ $c['order_id'] }}</td>
                            <td class="py-2 pr-4">{{ $c['buyer_username'] ?? '-' }}</td>
                            @php
                                $rawEmail = (string) ($c['buyer_email'] ?? '');
                                $maskedEmail = '-';
                                if ($rawEmail !== '' && str_contains($rawEmail, '@')) {
                                    [$local, $domain] = array_pad(explode('@', $rawEmail, 2), 2, '');
                                    $localMask = $local === '' ? '***' : (mb_substr($local, 0, 1) . str_repeat('*', max(3, mb_strlen($local) - 1)));
                                    $domainMask = $domain === '' ? '***' : (str_repeat('*', max(3, mb_strlen($domain))));
                                    $maskedEmail = $localMask . '@' . $domainMask;
                                }
                            @endphp
                            <td class="py-2 pr-4">{{ $maskedEmail }}</td>
                            <td class="py-2 pl-4 text-right">Rp {{ number_format($c['order_amount'], 0, ',', '.') }}</td>
                            <td class="py-2 pl-4 text-right">Rp {{ number_format($c['buyer_discount_amount'], 0, ',', '.') }}</td>
                            <td class="py-2 pl-4 text-right">Rp {{ number_format($c['reward_amount'], 0, ',', '.') }}</td>
                            <td class="py-2 pl-4">{{ $c['status'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 text-white/60" colspan="8">Belum ada data.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

@endsection
