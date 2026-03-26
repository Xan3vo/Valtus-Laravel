@extends('layouts.app')
@section('title', 'Admin • Edit Admin')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <div class="flex items-center gap-3 sm:gap-4 mb-3 sm:mb-4">
            <a href="{{ route('admin.admins.index') }}" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Edit Admin</h1>
        </div>
        <p class="text-white/60 text-sm sm:text-base">Ubah informasi admin: {{ $admin->name }}</p>
    </div>

    <div class="rounded-2xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.admins.update', $admin) }}" class="space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Name Field -->
            <div class="space-y-2">
                <label for="name" class="block text-white/80 text-sm font-medium">Nama Admin</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name', $admin->name) }}"
                    class="w-full px-4 py-3 rounded-xl bg-black/30 border border-white/15 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 @error('name') border-red-500/50 @enderror" 
                    placeholder="Masukkan nama admin"
                    required
                >
                @error('name')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div class="space-y-2">
                <label for="email" class="block text-white/80 text-sm font-medium">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $admin->email) }}"
                    class="w-full px-4 py-3 rounded-xl bg-black/30 border border-white/15 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 @error('email') border-red-500/50 @enderror" 
                    placeholder="Masukkan email admin"
                    required
                >
                @error('email')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Field -->
            <div class="space-y-2">
                <label for="password" class="block text-white/80 text-sm font-medium">Password Baru</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-3 rounded-xl bg-black/30 border border-white/15 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200 @error('password') border-red-500/50 @enderror" 
                    placeholder="Kosongkan jika tidak ingin mengubah password"
                >
                <p class="text-white/50 text-xs">Kosongkan jika tidak ingin mengubah password</p>
                @error('password')
                    <p class="text-red-400 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Confirmation Field -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-white/80 text-sm font-medium">Konfirmasi Password Baru</label>
                <input 
                    type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    class="w-full px-4 py-3 rounded-xl bg-black/30 border border-white/15 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition-all duration-200" 
                    placeholder="Ulangi password baru"
                >
            </div>

            <!-- Submit Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                <button type="submit" 
                        class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-medium transition-all duration-200 shadow-lg hover:shadow-emerald-500/25">
                    <div class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan Perubahan
                    </div>
                </button>
                <a href="{{ route('admin.admins.index') }}" 
                   class="w-full sm:w-auto px-6 sm:px-8 py-3 rounded-xl border border-white/20 hover:bg-white/5 hover:border-white/40 text-white font-medium transition-all duration-200 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</main>
@endsection

