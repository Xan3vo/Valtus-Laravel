@extends('layouts.app')
@section('title', isset($blacklist) ? 'Admin • Edit Blacklist' : 'Admin • Tambah Blacklist')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
            </svg>
            <span>{{ isset($blacklist) ? 'Edit Blacklist' : 'Tambah Blacklist' }}</span>
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">Blacklist berbasis Roblox username (permanen atau sampai waktu tertentu)</p>
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

    <form method="POST" action="{{ isset($blacklist) ? route('admin.blacklists.update', $blacklist) : route('admin.blacklists.store') }}" class="space-y-6 sm:space-y-8">
        @csrf
        @if(isset($blacklist))
            @method('PUT')
        @endif

        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5 space-y-5">
            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Username Roblox *</span>
                <input
                    name="username"
                    type="text"
                    value="{{ old('username', $blacklist->username ?? '') }}"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-red-400 focus:ring-1 focus:ring-red-400 text-sm sm:text-base"
                    required
                    maxlength="100"
                    placeholder="mis: builderman"
                />
                <p class="mt-1 text-white/50 text-xs">Case-insensitive (disimpan juga versi lowercase).</p>
            </label>

            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Alasan (opsional)</span>
                <textarea
                    name="reason"
                    rows="3"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-red-400 focus:ring-1 focus:ring-red-400 text-sm sm:text-base"
                    placeholder="Catatan internal admin..."
                >{{ old('reason', $blacklist->reason ?? '') }}</textarea>
            </label>

            <label class="block">
                <span class="text-white/70 text-sm sm:text-base">Banned Until (kosong = permanen)</span>
                <input
                    name="banned_until"
                    type="datetime-local"
                    value="{{ old('banned_until', isset($blacklist) && $blacklist->banned_until ? $blacklist->banned_until->format('Y-m-d\\TH:i') : '') }}"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-red-400 focus:ring-1 focus:ring-red-400 text-sm sm:text-base"
                />
                <p class="mt-1 text-white/50 text-xs">Jika waktu sudah lewat, blacklist dianggap expired (tidak memblokir).</p>
            </label>

            <label class="flex items-center gap-3">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', isset($blacklist) ? ($blacklist->is_active ? '1' : '0') : '1') == '1' ? 'checked' : '' }} class="w-4 h-4 text-red-400 bg-black/30 border-white/20 rounded">
                <span class="text-white/70 text-sm">Aktif</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-red-500 hover:bg-red-600 text-white font-medium transition">
                Simpan
            </button>
            <a href="{{ route('admin.blacklists') }}" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-center">
                Kembali
            </a>
        </div>
    </form>
</main>
@endsection
