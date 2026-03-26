@extends('layouts.app')
@section('title', 'Admin • Blacklist')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90 flex items-center gap-2 sm:gap-3">
                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <span>Blacklist Management</span>
            </h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Blokir Roblox username (permanen atau sampai waktu tertentu)</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.blacklists.create') }}" class="bg-red-500 hover:bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Blacklist
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-600 text-white rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.blacklists') }}" class="mb-6">
        <div class="flex flex-col sm:flex-row gap-3">
            <input name="q" value="{{ $q ?? '' }}" class="flex-1 px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-red-400 focus:ring-1 focus:ring-red-400 text-sm" placeholder="Cari username / alasan..." />
            <button type="submit" class="px-4 py-3 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm">Cari</button>
            @if(!empty($q))
                <a href="{{ route('admin.blacklists') }}" class="px-4 py-3 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm text-center">Reset</a>
            @endif
        </div>
    </form>

    <div class="rounded-lg border border-white/20 bg-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/10 border-b border-white/20">
                    <tr>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Username</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Status</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Sampai</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Alasan</th>
                        <th class="px-4 sm:px-6 py-3 sm:py-4 text-right text-xs sm:text-sm font-medium text-white/70 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/10">
                    @forelse($blacklists as $item)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                <div class="text-white font-medium">{{ $item->username }}</div>
                                <div class="text-white/40 text-xs">{{ $item->created_at?->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4">
                                @if($item->isBlocked())
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-red-500/20 text-red-300 border border-red-500/30">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-gray-500/20 text-gray-300 border border-gray-500/30">
                                        Nonaktif / Expired
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-white/70 text-xs sm:text-sm">
                                @if($item->banned_until)
                                    {{ $item->banned_until->format('d M Y H:i') }}
                                @else
                                    Permanen
                                @endif
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 text-white/70 text-xs sm:text-sm">
                                {{ $item->reason ?? '-' }}
                            </td>
                            <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.blacklists.edit', $item) }}" class="px-3 py-1.5 rounded-md bg-blue-500/20 hover:bg-blue-500/30 text-blue-300 hover:text-blue-200 transition-colors border border-blue-500/30" title="Edit">
                                        <span class="text-xs font-medium">Edit</span>
                                    </a>
                                    <form method="POST" action="{{ route('admin.blacklists.toggle-status', $item) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 rounded-md {{ $item->is_active ? 'bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-300 hover:text-yellow-200 border-yellow-500/30' : 'bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 hover:text-emerald-200 border-emerald-500/30' }} transition-colors border" title="Toggle">
                                            <span class="text-xs font-medium">{{ $item->is_active ? 'Nonaktif' : 'Aktifkan' }}</span>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.blacklists.destroy', $item) }}" class="inline" onsubmit="return confirm('Yakin ingin menghapus blacklist ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white transition-colors" title="Hapus">
                                            <span class="text-xs font-medium">Hapus</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-white/60">
                                Belum ada data blacklist.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(method_exists($blacklists, 'links'))
            <div class="p-4 border-t border-white/10">
                {{ $blacklists->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
