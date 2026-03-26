@extends('layouts.app')
@section('title', 'Admin • Detail Admin')
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
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Detail Admin</h1>
        </div>
        <p class="text-white/60 text-sm sm:text-base">Informasi lengkap admin: {{ $admin->name }}</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        <!-- Admin Info -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Basic Info -->
            <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-4">Informasi Dasar</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Nama Admin</label>
                        <div class="text-white text-lg font-medium">{{ $admin->name }}</div>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Email</label>
                        <div class="text-white text-lg">{{ $admin->email }}</div>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Status</label>
                        @if($admin->id === auth('admin')->id())
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                <div class="h-2 w-2 bg-emerald-400 rounded-full"></div>
                                Aktif (Anda)
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-gray-500/20 text-gray-300 border border-gray-500/30">
                                <div class="h-2 w-2 bg-gray-400 rounded-full"></div>
                                Admin
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Account Info -->
            <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-4">Informasi Akun</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">ID Admin</label>
                        <div class="text-white font-mono text-sm">{{ $admin->id }}</div>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Dibuat</label>
                        <div class="text-white">{{ $admin->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div>
                        <label class="block text-white/60 text-sm font-medium mb-1">Terakhir Diperbarui</label>
                        <div class="text-white">{{ $admin->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Action Buttons -->
            <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-4">Aksi</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.admins.edit', $admin) }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-yellow-600 to-yellow-700 hover:from-yellow-700 hover:to-yellow-800 text-white font-medium transition-all duration-200 shadow-lg hover:shadow-yellow-500/25">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Admin
                    </a>
                    
                    @if($admin->id !== auth('admin')->id())
                    <form method="POST" action="{{ route('admin.admins.destroy', $admin) }}" 
                          onsubmit="return confirm('Yakin ingin menghapus admin {{ $admin->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium transition-all duration-200 shadow-lg hover:shadow-red-500/25">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Admin
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('admin.admins.index') }}" 
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl border border-white/20 hover:bg-white/5 hover:border-white/40 text-white font-medium transition-all duration-200">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Kembali ke Daftar
                    </a>
                </div>
            </div>

            <!-- Security Info -->
            <div class="rounded-xl border border-white/10 p-4 sm:p-6 bg-gradient-to-br from-white/5 to-white/0">
                <h3 class="text-lg sm:text-xl font-semibold text-white mb-4">Keamanan</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-emerald-500/20">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-white text-sm font-medium">Password Terenkripsi</div>
                            <div class="text-white/60 text-xs">Password disimpan dengan hash yang aman</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-blue-500/20">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-white text-sm font-medium">Akses Terbatas</div>
                            <div class="text-white/60 text-xs">Hanya admin yang dapat mengakses panel</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

