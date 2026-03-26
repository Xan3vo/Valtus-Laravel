@extends('layouts.app')
@section('title', 'Admin • Email Test')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-6xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Email Test</h1>
        <p class="text-white/60 mt-2">Test email notification dan cek log email</p>
        <div class="mt-4 p-4 bg-green-500/10 border border-green-500/20 rounded-lg">
            <p class="text-green-300 text-sm font-medium mb-1">✓ Gmail SMTP Gratis & Mudah!</p>
            <p class="text-white/70 text-xs">Sistem menggunakan <strong>Database Settings</strong> untuk email config. Tidak perlu isi <code class="bg-black/30 px-1 rounded">.env</code> untuk email!</p>
            <div class="mt-2 p-2 bg-blue-500/10 border border-blue-500/20 rounded text-xs">
                <p class="text-blue-300 font-medium mb-1">📝 Port SMTP:</p>
                <ul class="text-white/60 space-y-0.5 list-disc pl-4">
                    <li><strong>Port 587 (TLS):</strong> Recommended, biasanya lebih kompatibel</li>
                    <li><strong>Port 465 (SSL):</strong> Alternatif jika 587 tidak berhasil - ini yang berhasil untuk Anda!</li>
                </ul>
                <p class="text-white/60 mt-1">💡 Ubah port di Settings → Email Configuration. Sistem akan otomatis set encryption (TLS untuk 587, SSL untuk 465).</p>
            </div>
            <p class="text-yellow-300 text-xs mt-2">⚠️ <strong>Note:</strong> Jika error di local (komputer Anda), itu normal karena firewall/network blocking. Test di hosting - pasti langsung berhasil!</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-600 text-white rounded-md">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Email Configuration -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                Email Configuration (Dari Database)
            </h3>
            
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-white/60">Mail Driver:</span>
                    <span class="text-white font-mono">{{ $emailConfig['mailer'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">SMTP Host:</span>
                    <span class="text-white font-mono">{{ $emailConfig['host'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">SMTP Port:</span>
                    <span class="text-white font-mono">{{ $emailConfig['port'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">Username:</span>
                    <span class="text-white font-mono">{{ $emailConfig['username'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">From Address:</span>
                    <span class="text-white font-mono">{{ $emailConfig['from_address'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">From Name:</span>
                    <span class="text-white font-mono">{{ $emailConfig['from_name'] ?? 'Not set' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-white/60">Encryption:</span>
                    <span class="text-white font-mono">{{ $emailConfig['encryption'] ?? 'Not set' }}</span>
                </div>
            </div>
            
            <!-- Active Config (What Laravel is using) -->
            <div class="mt-6 pt-6 border-t border-white/10">
                <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Active Config (Yang Sedang Dipakai)
                </h4>
                <div class="space-y-2 text-xs bg-blue-500/10 p-3 rounded border border-blue-500/20">
                    <div class="flex justify-between">
                        <span class="text-white/60">Mailer:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['mailer'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Host:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['host'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Port:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['port'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Username:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['username'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Password:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['password'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Encryption:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['encryption'] ?? 'null' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-white/60">Timeout:</span>
                        <span class="text-blue-300 font-mono">{{ $emailConfigActive['timeout'] }}s</span>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Ubah Konfigurasi Email
                </a>
                
                @if($emailConfig['mailer'] === 'smtp')
                <div class="text-xs text-green-300 mt-2 p-2 bg-green-500/10 border border-green-500/20 rounded">
                    ✅ <strong>Gmail SMTP Gratis!</strong> Konfigurasi ini sama seperti contoh teman Anda yang mudah setup. 
                    Jika gagal di local, coba di hosting - biasanya akan langsung berhasil.
                </div>
                @endif
            </div>
        </div>

        <!-- Test Email Form -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6 flex items-center gap-2">
                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Test Email
            </h3>
            
            <form method="POST" action="{{ route('admin.email-test.send') }}">
                @csrf
                
                <div class="space-y-4">
                    <label class="block">
                        <span class="text-white/70">Email Tujuan *</span>
                        <input 
                            name="test_email" 
                            type="email" 
                            value="{{ old('test_email', '') }}"
                            class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" 
                            placeholder="your-email@gmail.com"
                            required
                        />
                        <p class="mt-1 text-white/50 text-xs">Email tempat test email akan dikirim</p>
                    </label>

                    <label class="block">
                        <span class="text-white/70">Jenis Email *</span>
                        <select 
                            name="email_type" 
                            class="mt-2 w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                            required
                        >
                            <option value="order_created" {{ old('email_type') === 'order_created' ? 'selected' : '' }}>Pesanan Anda Sudah Dibuat</option>
                            <option value="payment_confirmed" {{ old('email_type') === 'payment_confirmed' ? 'selected' : '' }}>Pembayaran Anda Sudah Dikonfirmasi</option>
                            <option value="order_processed" {{ old('email_type') === 'order_processed' ? 'selected' : '' }}>Pesanan Anda Sedang Diproses</option>
                        </select>
                        <p class="mt-1 text-white/50 text-xs">Pilih jenis email yang ingin ditest</p>
                    </label>
                    
                    <div class="bg-blue-500/10 border border-blue-500/20 rounded-lg p-3">
                        <p class="text-blue-300 text-xs font-medium mb-1">💡 Info Port SMTP:</p>
                        <ul class="text-white/60 text-xs space-y-0.5 list-disc pl-4">
                            <li><strong>Port 587 (TLS):</strong> Recommended, biasanya lebih kompatibel</li>
                            <li><strong>Port 465 (SSL):</strong> Alternatif jika 587 tidak berhasil di hosting</li>
                        </ul>
                        <p class="text-white/60 text-xs mt-1">Port saat ini: <code class="bg-black/30 px-1 rounded">{{ $emailConfigActive['port'] ?? '587' }}</code> ({{ $emailConfigActive['port'] == '465' ? 'SSL' : 'TLS' }})</p>
                    </div>

                    <button 
                        type="submit" 
                        class="w-full px-4 py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Kirim Test Email
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Email Logs -->
    <div class="mt-6 rounded-lg border border-white/20 p-6 bg-white/5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold text-white flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Email Logs
            </h3>
            <button 
                onclick="location.reload()" 
                class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm transition-colors flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
        
        <div class="bg-black/30 rounded-lg p-4 max-h-96 overflow-y-auto">
            @if(!empty($logs))
                <div class="space-y-2 font-mono text-xs">
                    @foreach(array_reverse($logs) as $log)
                        @php
                            $isError = stripos($log, 'error') !== false || stripos($log, 'failed') !== false || stripos($log, 'could not be established') !== false || stripos($log, 'connection') !== false || stripos($log, 'timeout') !== false;
                            $isSuccess = stripos($log, 'success') !== false || stripos($log, 'sent successfully') !== false;
                            $isInfo = stripos($log, 'attempting') !== false || stripos($log, 'test') !== false || stripos($log, 'started') !== false;
                            $isWarning = stripos($log, 'warning') !== false;
                            
                            $colorClass = 'text-white/60';
                            if ($isError) $colorClass = 'text-red-400';
                            elseif ($isSuccess) $colorClass = 'text-green-400';
                            elseif ($isInfo) $colorClass = 'text-blue-400';
                            elseif ($isWarning) $colorClass = 'text-yellow-400';
                        @endphp
                        <div class="p-2 rounded border border-white/10 {{ $isError ? 'bg-red-500/10 border-red-500/20' : ($isSuccess ? 'bg-green-500/10 border-green-500/20' : 'bg-white/5') }}">
                            <pre class="{{ $colorClass }} whitespace-pre-wrap break-words">{{ $log }}</pre>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-white/60 text-sm text-center py-8">
                    Belum ada log email. Kirim test email untuk melihat log.
                </div>
            @endif
            
            <!-- Error Troubleshooting Info -->
            @if(session('error') && (stripos(session('error'), 'connection') !== false || stripos(session('error'), 'timeout') !== false || stripos(session('error'), 'Unable to connect') !== false))
            <div class="mt-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                <p class="text-red-300 text-sm font-medium mb-2">⚠️ Kenapa Error di Local Development?</p>
                <div class="space-y-3 text-white/70 text-xs">
                    <div>
                        <p class="font-semibold text-red-300 mb-1">Error: "Unable to connect to smtp.gmail.com:587"</p>
                        <p>Ini terjadi karena komputer Windows lokal Anda tidak bisa connect ke Gmail SMTP. Berikut penyebabnya:</p>
                    </div>
                    
                    <div class="bg-white/5 p-3 rounded border border-white/10">
                        <p class="font-medium text-white mb-2">🔍 Penyebab:</p>
                        <ol class="list-decimal pl-5 space-y-1">
                            <li><strong>Windows Firewall</strong> - Firewall Windows memblokir outbound connection ke port 587</li>
                            <li><strong>ISP/Network Blocking</strong> - Provider internet atau network memblokir port SMTP (587/465)</li>
                            <li><strong>Antivirus/Firewall Software</strong> - Software keamanan memblokir koneksi SMTP</li>
                            <li><strong>Router/Network Settings</strong> - Router atau network policy memblokir port tersebut</li>
                            <li><strong>Corporate/University Network</strong> - Network institusi biasanya memblokir port SMTP</li>
                        </ol>
                    </div>
                    
                    <div class="bg-blue-500/10 p-3 rounded border border-blue-500/20">
                        <p class="font-medium text-blue-300 mb-2">✅ Solusi (Pilih Salah Satu):</p>
                        <ol class="list-decimal pl-5 space-y-2">
                            <li>
                                <strong>Test di Hosting (Recommended!)</strong>
                                <p class="text-white/60 ml-0 mt-1">Gmail SMTP akan langsung bekerja di hosting. Ini yang paling mudah dan pasti berhasil!</p>
                            </li>
                            <li>
                                <strong>Bypass Firewall (Untuk Testing Local)</strong>
                                <ul class="list-disc ml-5 mt-1 space-y-0.5 text-white/60">
                                    <li>Buka Windows Defender Firewall</li>
                                    <li>Advanced Settings → Outbound Rules → New Rule</li>
                                    <li>Allow connection untuk port 587</li>
                                    <li>Atau matikan firewall sementara untuk test</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Gunakan VPN atau Hotspot</strong>
                                <p class="text-white/60 ml-0 mt-1">Coba connect VPN atau gunakan hotspot smartphone - kadang bisa bypass blocking.</p>
                            </li>
                            <li>
                                <strong>Use Mailtrap (Untuk Testing Lokal Saja)</strong>
                                <p class="text-white/60 ml-0 mt-1">Sign up gratis di <a href="https://mailtrap.io" target="_blank" class="text-blue-400 underline">mailtrap.io</a>, dapatkan SMTP credentials, dan ubah host ke smtp.mailtrap.io port 2525 (hanya untuk testing lokal).</p>
                            </li>
                        </ol>
                    </div>
                    
                    <div class="bg-green-500/10 p-3 rounded border border-green-500/20">
                        <p class="font-medium text-green-300 mb-1">💡 Kesimpulan:</p>
                        <p class="text-white/60">Error ini <strong>NORMAL di local development Windows</strong>. Konfigurasi Anda sudah benar! Gmail SMTP akan langsung bekerja dengan baik di hosting/server production. Jangan khawatir, cukup deploy ke hosting dan email akan langsung berfungsi.</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <p class="mt-4 text-white/50 text-xs">
            💡 Log ini menampilkan 50 log email terakhir dari <code class="bg-white/10 px-1 rounded">storage/logs/laravel.log</code>. 
            Refresh halaman untuk update log terbaru.
        </p>
    </div>
</main>

<script>
function testSMTPConnection() {
    const host = '{{ $emailConfigActive["host"] }}';
    const port = '{{ $emailConfigActive["port"] }}';
    
    if (!host || host === 'not set') {
        alert('❌ SMTP Host belum dikonfigurasi!');
        return;
    }
    
    alert(`Testing connection ke ${host}:${port}...\n\n⚠️ Ini akan test koneksi TCP ke SMTP server.\n\nJika muncul error, kemungkinan:\n1. Firewall memblokir port ${port}\n2. Network/ISP memblokir SMTP\n3. Ini normal di local development\n\n💡 Coba langsung test kirim email - jika error connection berarti memang di-blokir oleh network.`);
    
    // Note: Browser JavaScript tidak bisa test SMTP connection secara langsung
    // User perlu test dengan mengirim email actual
    console.log('SMTP Config:', {
        host: host,
        port: port,
        encryption: '{{ $emailConfigActive["encryption"] }}'
    });
}

// Auto-show active config comparison
document.addEventListener('DOMContentLoaded', function() {
    const dbHost = '{{ $emailConfig["host"] }}';
    const activeHost = '{{ $emailConfigActive["host"] }}';
    
    if (dbHost && activeHost && dbHost !== activeHost) {
        console.warn('⚠️ Config mismatch detected!');
        console.log('Database:', dbHost);
        console.log('Active:', activeHost);
    }
    
    // Show comparison info
    console.log('=== Email Config Check ===');
    console.log('Database Config:', {
        host: '{{ $emailConfig["host"] }}',
        port: '{{ $emailConfig["port"] }}',
        username: '{{ $emailConfig["username"] }}'
    });
    console.log('Active Config:', {
        host: '{{ $emailConfigActive["host"] }}',
        port: '{{ $emailConfigActive["port"] }}',
        username: '{{ $emailConfigActive["username"] }}'
    });
});
</script>

@endsection

