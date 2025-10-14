@extends('layouts.app')
@section('title', 'Admin • Settings')

@section('body')
@include('admin.partials.navigation')

<main class="max-w-3xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Settings</h1>
        <p class="text-white/60 mt-2">Configure system settings and pricing</p>
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

    <form method="POST" action="{{ route('admin.settings.update') }}" class="mt-8 grid gap-8">
        @csrf
        @method('PUT')
        
        <!-- General Settings -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">General System Settings</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <label class="block">
                    <span class="text-white/70">Site Name</span>
                    <input 
                        name="site_name" 
                        type="text" 
                        value="{{ old('site_name', $settings['site_name'] ?? 'Valtus') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-sm">Nama website yang ditampilkan</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Site Description</span>
                    <input 
                        name="site_description" 
                        type="text" 
                        value="{{ old('site_description', $settings['site_description'] ?? 'Top Up Robux Terpercaya') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        required 
                    />
                    <p class="mt-1 text-white/50 text-sm">Deskripsi singkat website</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Contact Email</span>
                    <input 
                        name="contact_email" 
                        type="email" 
                        value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                    />
                    <p class="mt-1 text-white/50 text-sm">Email untuk customer support</p>
                </label>

                <label class="block">
                    <span class="text-white/70">WhatsApp Number</span>
                    <input 
                        name="whatsapp_number" 
                        type="text" 
                        value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="6281234567890"
                    />
                    <p class="mt-1 text-white/50 text-sm">Nomor WhatsApp (dengan kode negara)</p>
                </label>
            </div>
        </div>

        <!-- Social Media & Contact Settings -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Social Media & Contact</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <label class="block">
                    <span class="text-white/70">Instagram Username</span>
                    <input 
                        name="instagram_username" 
                        type="text" 
                        value="{{ old('instagram_username', $settings['instagram_username'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="@username"
                    />
                    <p class="mt-1 text-white/50 text-sm">Username Instagram (tanpa @)</p>
                </label>

                <label class="block">
                    <span class="text-white/70">TikTok Username</span>
                    <input 
                        name="tiktok_username" 
                        type="text" 
                        value="{{ old('tiktok_username', $settings['tiktok_username'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="@username"
                    />
                    <p class="mt-1 text-white/50 text-sm">Username TikTok (tanpa @)</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Facebook Page</span>
                    <input 
                        name="facebook_page" 
                        type="text" 
                        value="{{ old('facebook_page', $settings['facebook_page'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://facebook.com/yourpage"
                    />
                    <p class="mt-1 text-white/50 text-sm">Link halaman Facebook</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Discord Server</span>
                    <input 
                        name="discord_server" 
                        type="text" 
                        value="{{ old('discord_server', $settings['discord_server'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://discord.gg/invitecode"
                    />
                    <p class="mt-1 text-white/50 text-sm">Link server Discord</p>
                </label>

                <label class="block">
                    <span class="text-white/70">Telegram Username</span>
                    <input 
                        name="telegram_username" 
                        type="text" 
                        value="{{ old('telegram_username', $settings['telegram_username'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="@username"
                    />
                    <p class="mt-1 text-white/50 text-sm">Username Telegram (tanpa @)</p>
                </label>

                <label class="block">
                    <span class="text-white/70">YouTube Channel</span>
                    <input 
                        name="youtube_channel" 
                        type="text" 
                        value="{{ old('youtube_channel', $settings['youtube_channel'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://youtube.com/@channel"
                    />
                    <p class="mt-1 text-white/50 text-sm">Link channel YouTube</p>
                </label>
            </div>
        </div>

        <!-- Additional Contact Settings -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Additional Contact Information</h3>
            
            <div class="grid md:grid-cols-1 gap-6">
                <label class="block">
                    <span class="text-white/70">Address</span>
                    <textarea 
                        name="address" 
                        rows="3"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="Alamat kantor atau lokasi"
                    >{{ old('address', $settings['address'] ?? '') }}</textarea>
                    <p class="mt-1 text-white/50 text-sm">Alamat kantor atau lokasi</p>
                </label>
            </div>
        </div>

        <!-- Maintenance Settings -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Maintenance & Status</h3>
            
            <div class="space-y-4">
                <label class="flex items-center gap-3">
                    <input 
                        name="maintenance_mode" 
                        type="checkbox" 
                        value="1"
                        {{ old('maintenance_mode', $settings['maintenance_mode'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                    />
                    <span class="text-white/70">Maintenance Mode (tutup sementara untuk customer)</span>
                </label>

                <label class="block">
                    <span class="text-white/70">Maintenance Message</span>
                    <textarea 
                        name="maintenance_message" 
                        rows="3"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="Website sedang dalam maintenance..."
                    >{{ old('maintenance_message', $settings['maintenance_message'] ?? '') }}</textarea>
                </label>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-5 py-3 rounded-sm bg-white text-black w-fit hover:bg-gray-100">
                Save Settings
            </button>
            <a href="{{ route('admin.settings') }}" class="px-5 py-3 rounded-sm bg-gray-600 text-white w-fit hover:bg-gray-700">
                Reset
            </a>
        </div>
    </form>

    <div class="mt-12 rounded-lg border border-white/20 p-6 bg-white/5">
        <h3 class="text-xl font-semibold text-white mb-6">Current System Settings</h3>
        
        <!-- General Settings -->
        <div class="mb-6">
            <h4 class="text-lg font-medium text-white/80 mb-4">General Information</h4>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Site Name</div>
                    <div class="text-white text-lg font-bold">{{ $settings['site_name'] ?? 'Valtus' }}</div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Contact Email</div>
                    <div class="text-white text-lg font-bold">{{ $settings['contact_email'] ?? 'Not set' }}</div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">WhatsApp</div>
                    <div class="text-white text-lg font-bold">{{ $settings['whatsapp_number'] ?? 'Not set' }}</div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div class="mb-6">
            <h4 class="text-lg font-medium text-white/80 mb-4">Social Media</h4>
            <div class="grid md:grid-cols-3 gap-4">
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Instagram</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['instagram_username'] ?? false)
                            <a href="https://instagram.com/{{ $settings['instagram_username'] }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                @{{ $settings['instagram_username'] }}
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">TikTok</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['tiktok_username'] ?? false)
                            <a href="https://tiktok.com/@{{ $settings['tiktok_username'] }}" target="_blank" class="text-pink-400 hover:text-pink-300">
                                @{{ $settings['tiktok_username'] }}
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Facebook</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['facebook_page'] ?? false)
                            <a href="{{ $settings['facebook_page'] }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                View Page
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Discord</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['discord_server'] ?? false)
                            <a href="{{ $settings['discord_server'] }}" target="_blank" class="text-indigo-400 hover:text-indigo-300">
                                Join Server
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Telegram</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['telegram_username'] ?? false)
                            <a href="https://t.me/{{ $settings['telegram_username'] }}" target="_blank" class="text-blue-400 hover:text-blue-300">
                                @{{ $settings['telegram_username'] }}
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">YouTube</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['youtube_channel'] ?? false)
                            <a href="{{ $settings['youtube_channel'] }}" target="_blank" class="text-red-400 hover:text-red-300">
                                View Channel
                            </a>
                        @else
                            Not set
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div>
            <h4 class="text-lg font-medium text-white/80 mb-4">System Status</h4>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Maintenance Mode</div>
                    <div class="text-white text-lg font-bold">
                        <span class="px-2 py-1 rounded text-xs {{ ($settings['maintenance_mode'] ?? false) ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ ($settings['maintenance_mode'] ?? false) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Address</div>
                    <div class="text-white text-lg font-bold">{{ $settings['address'] ?? 'Not set' }}</div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection