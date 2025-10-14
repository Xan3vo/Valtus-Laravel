@extends('layouts.app')
@section('title', 'Pilih Jumlah Robux')
@section('body')
<main class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl md:text-4xl font-medium text-white/90">Pilih Jumlah Robux</h1>
    <p class="mt-2 text-white/60">Minimal 50. Pilih paket cepat atau masukkan custom.</p>
    <form action="{{ route('user.gamepass') }}" class="mt-8 grid gap-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach ([100,200,500,1000] as $pkg)
            <label class="block">
                <input type="radio" name="amount" value="{{ $pkg }}" class="peer hidden" />
                <div class="rounded-md border border-white/10 p-4 bg-white/[0.02] peer-checked:border-white/30">
                    <div class="text-white/80 text-lg">{{ $pkg }} R$</div>
                    <div class="text-white/50 text-sm">Rp {{ number_format($pkg*50,0,',','.') }}</div>
                </div>
            </label>
            @endforeach
        </div>
        <div>
            <label class="block">
                <span class="text-white/70">Custom</span>
                <input type="number" min="50" step="50" name="custom" class="mt-2 w-full px-4 py-3 rounded-sm bg-black/30 border border-white/10 text-white placeholder-white/30" placeholder="Minimal 50" />
            </label>
        </div>
        <button class="px-5 py-3 rounded-sm bg-white text-black hover:bg-[#e5e5e5] w-fit">Lanjut</button>
    </form>
</main>
@endsection


