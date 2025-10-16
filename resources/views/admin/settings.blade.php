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

        <!-- Spreadsheet Integration Settings -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Spreadsheet Integration</h3>
            
            <div class="space-y-6">
                <label class="block">
                    <span class="text-white/70">Google Spreadsheet Link</span>
                    <input 
                        name="spreadsheet_url" 
                        type="url" 
                        value="{{ old('spreadsheet_url', $settings['spreadsheet_url'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://docs.google.com/spreadsheets/d/your-sheet-id/edit"
                    />
                    <p class="mt-1 text-white/50 text-sm">Link Google Spreadsheet untuk tracking pesanan</p>
        </label>

        <label class="block">
                    <span class="text-white/70">Google Apps Script URL</span>
                    <input 
                        name="spreadsheet_script_url" 
                        type="url" 
                        value="{{ old('spreadsheet_script_url', $settings['spreadsheet_script_url'] ?? '') }}"
                        class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                        placeholder="https://script.google.com/macros/s/YOUR_SCRIPT_ID/exec"
                    />
                    <p class="mt-1 text-white/50 text-sm">URL deployment dari Google Apps Script (untuk otomatisasi)</p>
                </label>


                <div class="p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                    <h4 class="text-blue-300 font-medium mb-2">Cara Setup Spreadsheet Otomatis:</h4>
                    <ol class="text-white/80 text-sm space-y-1 list-decimal list-inside">
                        <li>Buat Google Spreadsheet baru</li>
                        <li>Buat header: Order ID | Username | GamePass Link | Amount | Status | Date</li>
                        <li>Extensions > Apps Script (akan buka tab baru)</li>
                        <li>Di Apps Script, hapus semua kode di file <code>Code.gs</code></li>
                        <li>Copy kode dari tombol "View Script" di bawah ini dan paste ke <code>Code.gs</code></li>
                        <li>Save (Ctrl+S) di Apps Script</li>
                        <li>Deploy > New deployment > Web app > Execute as: Me, Access: Anyone</li>
                        <li><strong>Klik "Authorize access"</strong> dan pilih akun Google Anda</li>
                        <li><strong>Klik "Advanced" > "Go to [Project Name] (unsafe)"</strong> jika muncul peringatan</li>
                        <li><strong>Klik "Allow"</strong> untuk memberikan izin akses</li>
                        <li>Setelah deployment berhasil, <strong>copy URL deployment</strong> ke field "Google Apps Script URL"</li>
                        <li>Copy link spreadsheet ke field "Google Spreadsheet Link"</li>
                        <li>Aktifkan integrasi spreadsheet</li>
                    </ol>
                    <p class="text-white/60 text-xs mt-2">Setiap pesanan yang disetujui akan otomatis masuk ke spreadsheet!</p>
                    
                    <div class="mt-3 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                        <p class="text-yellow-200 text-xs font-medium mb-1">💡 Tips:</p>
                        <ul class="text-yellow-100 text-xs space-y-1 list-disc list-inside">
                            <li>File <code>Code.gs</code> adalah file default di Google Apps Script</li>
                            <li>Hapus semua kode yang ada di <code>Code.gs</code> dan ganti dengan kode dari tombol "View Script"</li>
                            <li>Jangan upload file .js, cukup copy-paste kodenya saja</li>
                            <li>Pastikan spreadsheet di-share dengan "Anyone with the link can edit"</li>
                        </ul>
                    </div>
                    
                    <div class="mt-3 p-3 bg-orange-500/10 border border-orange-500/20 rounded-lg">
                        <p class="text-orange-200 text-xs font-medium mb-1">⚠️ Proses Authorization:</p>
                        <ul class="text-orange-100 text-xs space-y-1 list-disc list-inside">
                            <li>Setelah klik "Deploy", Google akan minta izin akses ke data</li>
                            <li>Klik "Authorize access" dan pilih akun Google yang sama dengan spreadsheet</li>
                            <li>Jika muncul peringatan "This app isn't verified", klik "Advanced" > "Go to [Project Name] (unsafe)"</li>
                            <li>Klik "Allow" untuk memberikan izin akses ke Google Sheets</li>
                            <li>Setelah berhasil, Anda akan mendapat URL deployment yang panjang</li>
                        </ul>
                    </div>
                    
                    <!-- Apps Script File Actions -->
                    <div class="mt-4 pt-4 border-t border-blue-500/20">
                        <h5 class="text-blue-300 font-medium mb-3">Google Apps Script File:</h5>
                        <div class="flex gap-3">
                            <a href="{{ route('admin.settings.download-script') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download Script
                            </a>
                            <button type="button" onclick="showScriptModal()" 
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Script
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <input 
                        name="spreadsheet_enabled" 
                        type="checkbox" 
                        value="1"
                        {{ old('spreadsheet_enabled', $settings['spreadsheet_enabled'] ?? false) ? 'checked' : '' }}
                        class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                    />
                    <span class="text-white/70">Aktifkan integrasi spreadsheet</span>
                </div>
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

        <!-- Spreadsheet Integration Status -->
        <div class="mb-6">
            <h4 class="text-lg font-medium text-white/80 mb-4">Spreadsheet Integration</h4>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Spreadsheet Status</div>
                    <div class="text-white text-lg font-bold">
                        <span class="px-2 py-1 rounded text-xs {{ ($settings['spreadsheet_enabled'] ?? false) ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                            {{ ($settings['spreadsheet_enabled'] ?? false) ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <div class="text-white/60 text-sm">Spreadsheet Link</div>
                    <div class="text-white text-lg font-bold">
                        @if($settings['spreadsheet_url'] ?? false)
                            <a href="{{ $settings['spreadsheet_url'] }}" target="_blank" class="text-blue-400 hover:text-blue-300 text-sm">
                                View Spreadsheet
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

<!-- Script Modal -->
<div id="scriptModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-gray-900 rounded-xl border border-white/20 max-w-4xl w-full max-h-[80vh] overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-white/10">
            <h3 class="text-xl font-semibold text-white">Google Apps Script Code</h3>
            <button type="button" onclick="hideScriptModal()" class="text-white/60 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="p-6 overflow-auto max-h-[60vh]">
            <div class="bg-black/50 rounded-lg p-4 border border-white/10">
                <pre class="text-green-400 text-sm overflow-x-auto"><code id="scriptCode">Loading...</code></pre>
            </div>
            <div class="mt-4 flex gap-3">
                <button type="button" onclick="copyScript()" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy Code
                </button>
                <a href="{{ route('admin.settings.download-script') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download File
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function showScriptModal() {
    document.getElementById('scriptModal').classList.remove('hidden');
    loadScriptCode();
}

function hideScriptModal() {
    document.getElementById('scriptModal').classList.add('hidden');
}

function loadScriptCode() {
    fetch('{{ route("admin.settings.view-script") }}')
        .then(response => response.text())
        .then(data => {
            document.getElementById('scriptCode').textContent = data;
        })
        .catch(error => {
            document.getElementById('scriptCode').textContent = 'Error loading script: ' + error.message;
        });
}

function copyScript() {
    const code = document.getElementById('scriptCode').textContent;
    navigator.clipboard.writeText(code).then(() => {
        // Show success message
        const button = event.target.closest('button');
        const originalText = button.innerHTML;
        button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Copied!';
        button.classList.add('bg-green-600', 'hover:bg-green-700');
        button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        
        setTimeout(() => {
            button.innerHTML = originalText;
            button.classList.remove('bg-green-600', 'hover:bg-green-700');
            button.classList.add('bg-blue-600', 'hover:bg-blue-700');
        }, 2000);
    });
}

// Close modal when clicking outside
document.getElementById('scriptModal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideScriptModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        hideScriptModal();
    }
});

// Dynamic buttons for spreadsheet URLs
document.addEventListener('DOMContentLoaded', function() {
    const spreadsheetUrlInput = document.querySelector('input[name="spreadsheet_url"]');
    const scriptUrlInput = document.querySelector('input[name="spreadsheet_script_url"]');
    
    // Function to create/open spreadsheet button
    function toggleSpreadsheetButton(input, buttonClass, buttonText, iconClass) {
        const existingButton = input.parentNode.querySelector('.dynamic-button');
        if (existingButton) {
            existingButton.remove();
        }
        
        if (input.value.trim()) {
            const buttonDiv = document.createElement('div');
            buttonDiv.className = 'mt-2 dynamic-button';
            buttonDiv.innerHTML = `
                <a href="${input.value}" target="_blank" 
                   class="inline-flex items-center gap-2 px-3 py-2 ${buttonClass} text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    ${buttonText}
                </a>
            `;
            input.parentNode.appendChild(buttonDiv);
        }
    }
    
    // Add event listeners
    spreadsheetUrlInput.addEventListener('input', function() {
        toggleSpreadsheetButton(this, 'bg-blue-600 hover:bg-blue-700', 'Buka Spreadsheet', 'external-link');
    });
    
    scriptUrlInput.addEventListener('input', function() {
        toggleSpreadsheetButton(this, 'bg-green-600 hover:bg-green-700', 'Test Apps Script', 'external-link');
    });
    
    // Initialize buttons if URLs are already filled
    if (spreadsheetUrlInput.value.trim()) {
        toggleSpreadsheetButton(spreadsheetUrlInput, 'bg-blue-600 hover:bg-blue-700', 'Buka Spreadsheet', 'external-link');
    }
    
    if (scriptUrlInput.value.trim()) {
        toggleSpreadsheetButton(scriptUrlInput, 'bg-green-600 hover:bg-green-700', 'Test Apps Script', 'external-link');
    }
});
</script>
@endsection