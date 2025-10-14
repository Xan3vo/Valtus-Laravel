@extends('layouts.app')
@section('title', 'Atur GamePass')
@section('body')
<main class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl md:text-4xl font-medium text-white/90">Buat GamePass</h1>
    <ol class="mt-6 space-y-3 text-white/70">
        <li>1. Buka Roblox → Create → Experiences → Pilih game Anda.</li>
        <li>2. Masuk ke Store → Game Passes → Create a Pass.</li>
        <li>3. Upload gambar, beri nama, dan <span class="text-white">set price</span> sesuai jumlah Robux.</li>
        <li>4. Pastikan GamePass <span class="text-white">di-aktifkan untuk sale</span>.</li>
        <li>5. Salin URL GamePass Anda.</li>
    </ol>
    <form action="{{ route('user.payment') }}" class="mt-8 grid gap-4">
        <label class="block">
            <span class="text-white/70">Link GamePass</span>
            <input required name="gamepass" class="mt-2 w-full px-4 py-3 rounded-sm bg-black/30 border border-white/10 text-white placeholder-white/30" placeholder="https://www.roblox.com/game-pass/123456…" />
        </label>
        <div class="rounded-md border border-white/10 p-4 bg-white/[0.02]">
            <div class="text-white/70 text-sm">Cek ketersediaan (mock)</div>
            <div class="mt-2 text-white/90">GamePass ditemukan dan aktif.</div>
        </div>
        <button class="px-5 py-3 rounded-sm bg-white text-black hover:bg-[#e5e5e5] w-fit">Lanjut ke Pembayaran</button>
    </form>
</main>
@endsection


