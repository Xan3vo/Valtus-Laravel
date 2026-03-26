@extends('layouts.app')
@section('title', 'Admin • Announcement')

@section('body')
@include('admin.partials.navigation')

<main class="max-w-3xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Announcement</h1>
        <p class="text-white/60 mt-2">Configure announcement bar and banner redirect</p>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-600 text-white rounded-md">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.announcement.update') }}" class="mt-8 grid gap-8">
        @csrf
        @method('PUT')

        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Banner (gambar hijau di Home)</h3>

            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <input 
                        name="announcement_enabled" 
                        type="checkbox" 
                        value="1"
                        {{ old('announcement_enabled', $settings['announcement_enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                    />
                    <span class="text-white/70">Aktifkan redirect saat banner di klik</span>
                </div>

                <label class="block">
                    <span class="text-white/70">Redirect Link</span>
                    <input 
                        name="announcement_link" 
                        type="url" 
                        value="{{ old('announcement_link', $settings['announcement_link'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://instagram.com/username"
                    />
                    <p class="mt-1 text-white/50 text-sm">Link tujuan saat banner (gambar di home) di klik</p>
                </label>
            </div>
        </div>

        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Announcement Bar (kotak merah di atas)</h3>

            <div class="space-y-6">
                <div class="flex items-center gap-3">
                    <input 
                        name="announcement_bar_enabled" 
                        type="checkbox" 
                        value="1"
                        {{ old('announcement_bar_enabled', $settings['announcement_bar_enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                    />
                    <span class="text-white/70">Aktifkan announcement bar</span>
                </div>

                <label class="block">
                    <span class="text-white/70">Text</span>
                    <input 
                        name="announcement_bar_text" 
                        type="text" 
                        value="{{ old('announcement_bar_text', $settings['announcement_bar_text'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="Promo hari ini! Follow IG kita @valtus.asia"
                    />
                    <p class="mt-1 text-white/50 text-sm">Teks yang tampil di bar atas</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Link (opsional)</span>
                    <input 
                        name="announcement_bar_link" 
                        type="url" 
                        value="{{ old('announcement_bar_link', $settings['announcement_bar_link'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://instagram.com/valtus.asia"
                    />
                    <p class="mt-1 text-white/50 text-sm">Kalau diisi, teks bisa diklik dan redirect</p>
                </label>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                Simpan Announcement
            </button>
        </div>
    </form>
</main>
@endsection
