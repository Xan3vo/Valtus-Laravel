@extends('layouts.app')
@section('title', 'Pilih Pembayaran')
@section('body')

@php
$order = null;
$orderPaymentMode = 'manual';
$currentOrderId = session('current_order_id');

if($currentOrderId) {
    $order = \App\Models\Order::where('order_id', $currentOrderId)->first();
    
    if($order && $order->notes) {
        $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes, true);
        $orderPaymentMode = $notes['payment_mode'] ?? 'manual';
    }
}
@endphp

<header class="sticky top-0 z-50 backdrop-blur-md bg-gray-900/80 border-b border-white/10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="relative">
                <img src="/assets/images/iconv.jpg" alt="Valtus" class="h-10 w-10 rounded-lg object-cover ring-2 ring-white/10">
                <div class="absolute -top-1 -right-1 h-4 w-4 bg-emerald-500 rounded-full border-2 border-gray-900"></div>
            </div>
            <div>
                <span class="text-xl tracking-wide font-bold text-white">Valtus</span>
                <div class="text-xs text-emerald-400 font-medium">Verified Store</div>
            </div>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    <!-- Steps header -->
    <div class="mb-6 flex items-center gap-4 text-sm">
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center">1</span>
            <span class="text-white/70">Memilih Produk</span>
        </div>
        <div class="text-white/30">/</div>
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white text-black flex items-center justify-center font-medium">2</span>
            <span class="text-white">Detail Pesanan</span>
        </div>
        <div class="text-white/30">/</div>
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-emerald-500 text-white flex items-center justify-center font-medium">3</span>
            <span class="text-white">Pembayaran</span>
        </div>
    </div>

    <!-- Countdown Timer -->
    @if($order && $order->expires_at)
    <div class="mb-6 rounded-xl border border-white/10 bg-white/5 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-8 w-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-white font-medium">Waktu Pembayaran</div>
                    <div class="text-white/60 text-sm">Selesaikan pembayaran sebelum waktu habis</div>
                </div>
            </div>
            <div class="text-right">
                <div id="countdown-timer" class="text-2xl font-bold text-emerald-400 font-mono">10:00</div>
                <div class="text-white/60 text-xs">menit tersisa</div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold mb-4">Pembayaran</h2>
            
            @php
            $paymentEnabled = \App\Models\Setting::getValue('payment_enabled', '0') === '1';
            @endphp

            @if(!$paymentEnabled)
            <div class="text-center py-8">
                <div class="text-red-400 text-lg mb-2">Sistem Pembayaran Sedang Dinonaktifkan</div>
                <div class="text-white/60">Silakan hubungi admin untuk informasi lebih lanjut</div>
            </div>
            @elseif($orderPaymentMode === 'manual')
            <!-- QRIS Payment -->
            <div class="space-y-4">
                @if(\App\Models\Setting::getValue('manual_qris_enabled', '0') === '1' && \App\Models\Setting::getValue('manual_qris_image'))
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">QRIS Payment</h3>
                    <div class="text-center">
                        <!-- Nominal Transfer -->
                        <div class="mb-4 p-4 rounded-lg bg-emerald-500/10 border border-emerald-500/20">
                            <div class="text-emerald-300 text-sm font-medium mb-1">Nominal yang harus ditransfer:</div>
                            <div class="text-white text-2xl font-bold">{{ $order ? 'Rp ' . number_format($order->price, 0, ',', '.') : '-' }}</div>
                        </div>
                        
                        <div class="inline-block p-4 bg-white rounded-lg">
                            <img src="/{{ \App\Models\Setting::getValue('manual_qris_image') }}" alt="QR Code" class="h-48 w-48 object-contain">
                        </div>
                        @if(\App\Models\Setting::getValue('manual_qris_name'))
                        <div class="mt-2 text-white/60 text-sm">Pemilik: {{ \App\Models\Setting::getValue('manual_qris_name') }}</div>
                        @endif
                        @if(\App\Models\Setting::getValue('manual_qris_instructions'))
                        <div class="mt-3 p-3 bg-white/10 rounded-lg">
                            <div class="text-white/80 text-sm">{{ \App\Models\Setting::getValue('manual_qris_instructions') }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Upload Bukti Transfer -->
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Upload Bukti Transfer</h3>
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-white/20 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <div class="mt-2">
                                <label for="proof-upload" class="cursor-pointer">
                                    <span class="text-white font-medium">Klik untuk upload bukti transfer</span>
                                    <input id="proof-upload" type="file" accept="image/*" class="hidden">
                                </label>
                            </div>
                            <p class="text-white/60 text-sm mt-1">PNG, JPG, GIF hingga 10MB</p>
                        </div>
                        
                        <!-- File Preview Area -->
                        <div id="file-preview" class="hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Gateway Payment - Virtual Account -->
            @php
            $selectedBank = null;
            if($order && $order->notes) {
                $notes = is_array($order->notes) ? $order->notes : json_decode($order->notes, true);
                $selectedBank = $notes['selected_bank'] ?? null;
            }
            @endphp
            
            @if($selectedBank)
            <div class="space-y-4">
                <!-- Virtual Account Info -->
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Virtual Account {{ strtoupper($selectedBank) }}</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3 p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="h-12 w-12 rounded-lg bg-gradient-to-br 
                                @if($selectedBank === 'bca') from-red-500/30 to-orange-500/30
                                @elseif($selectedBank === 'mandiri') from-red-500/30 to-yellow-500/30
                                @elseif($selectedBank === 'bni') from-yellow-500/30 to-orange-500/30
                                @else from-gray-500/30 to-gray-600/30 @endif
                                flex items-center justify-center">
                                <span class="text-white font-bold text-sm">
                                    @if($selectedBank === 'bca') BCA
                                    @elseif($selectedBank === 'mandiri') MDR
                                    @elseif($selectedBank === 'bni') BNI
                                    @else {{ strtoupper($selectedBank) }} @endif
                                </span>
                            </div>
                            <div class="flex-1">
                                <div class="text-white font-medium">
                                    @if($selectedBank === 'bca') BCA Virtual Account
                                    @elseif($selectedBank === 'mandiri') Mandiri Virtual Account
                                    @elseif($selectedBank === 'bni') BNI Virtual Account
                                    @else {{ ucfirst($selectedBank) }} Virtual Account @endif
                                </div>
                                <div class="text-white/60 text-sm">Transfer ke rekening {{ strtoupper($selectedBank) }}</div>
                            </div>
                        </div>
                        
                        <!-- Virtual Account Number (Mock) -->
                        <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="text-white/60 text-sm mb-2">Nomor Virtual Account:</div>
                            <div class="text-white font-mono text-lg font-semibold">1234567890123456</div>
                            <div class="text-white/60 text-xs mt-1">*Nomor ini akan digenerate otomatis saat integrasi gateway</div>
                        </div>
                        
                        <!-- Payment Instructions -->
                        <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="text-white/60 text-sm mb-2">Cara Pembayaran:</div>
                            <ol class="text-white/80 text-sm space-y-1 list-decimal list-inside">
                                <li>Salin nomor Virtual Account di atas</li>
                                <li>Buka aplikasi mobile banking {{ strtoupper($selectedBank) }}</li>
                                <li>Pilih menu "Transfer" → "Virtual Account"</li>
                                <li>Masukkan nomor VA dan nominal pembayaran</li>
                                <li>Konfirmasi dan selesaikan pembayaran</li>
                            </ol>
                        </div>
                    </div>
                </div>
                
                <!-- Upload Bukti Transfer -->
                <div class="rounded-lg border border-white/10 p-6 bg-white/5">
                    <h3 class="text-white font-medium mb-4">Upload Bukti Transfer</h3>
                    <div class="space-y-4">
                        <div class="border-2 border-dashed border-white/20 rounded-lg p-6 text-center">
                            <svg class="mx-auto h-12 w-12 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <div class="mt-2">
                                <label for="proof-upload" class="cursor-pointer">
                                    <span class="text-white font-medium">Klik untuk upload bukti transfer</span>
                                    <input id="proof-upload" type="file" accept="image/*" class="hidden">
                                </label>
                            </div>
                            <p class="text-white/60 text-sm mt-1">PNG, JPG, GIF hingga 10MB</p>
                        </div>
                        
                        <!-- File Preview Area -->
                        <div id="file-preview" class="hidden">
                            <!-- Preview will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-red-400 text-lg mb-2">Bank tidak dipilih</div>
                <div class="text-white/60">Silakan kembali ke halaman sebelumnya</div>
            </div>
            @endif
            @endif

            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/70 text-sm font-medium mb-2">Instruksi Pembayaran</div>
                <ul class="text-white/60 text-sm list-disc pl-5 space-y-1">
                    <li>Lakukan pembayaran sebelum 10 menit untuk menghindari pembatalan otomatis</li>
                    <li>Upload bukti transfer setelah melakukan pembayaran</li>
                    <li>Proses konfirmasi: 3-5 jam (di luar jam kerja bisa lebih lama)</li>
                    <li>Robux akan otomatis masuk ke akun Anda dalam 5 hari setelah konfirmasi</li>
                    <li>Notifikasi akan dikirim ke email yang telah Anda berikan</li>
                </ul>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-medium mb-3">Ringkasan Pesanan</div>
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Order ID</span>
                        <span class="text-white font-mono text-xs">{{ $order ? $order->order_id : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Produk</span>
                        <span class="text-white">
                            @if($order)
                                @if($order->game_type === 'Robux')
                                    {{ number_format($order->amount, 0, ',', '.') }} Robux
                                @else
                                    {{ $order->product_name ?? $order->game_type }}
                                @endif
                            @else
                                -
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Username</span>
                        <span class="text-white">{{ $order ? $order->username : '-' }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">Email</span>
                        <span class="text-white text-xs">{{ $order ? $order->email : '-' }}</span>
                    </div>
                    @if($order && $order->gamepass_link)
                    <div class="flex items-center justify-between">
                        <span class="text-white/60">GamePass Anda</span>
                        <span class="text-emerald-300 text-xs">✓ Tersedia</span>
                    </div>
                    @endif
                </div>
                <div class="mt-4 h-px bg-white/10"></div>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                    <div class="text-white/90">Total Pembayaran</div>
                    <div class="text-white">{{ $order ? 'Rp ' . number_format($order->price, 0, ',', '.') : '-' }}</div>
                </div>
                <button id="submit-proof-btn" class="mt-4 w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 text-white font-medium transition-colors" disabled>
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Kirim Bukti Transfer
                </button>
            </div>
        </aside>
    </div>
</main>

@if($order)
<script>
// Countdown Timer
@if($order->expires_at)
(function(){
    const expiresAt = new Date('{{ $order->expires_at }}').getTime();
    const timerElement = document.getElementById('countdown-timer');
    
    function updateTimer() {
        const now = new Date().getTime();
        const distance = expiresAt - now;
        
        if (distance < 0) {
            // Time expired
            timerElement.textContent = '00:00';
            timerElement.className = 'text-2xl font-bold text-red-400 font-mono';
            
            // Show expired message and redirect
            const expiredModal = document.createElement('div');
            expiredModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
            expiredModal.innerHTML = `
                <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                    <div class="text-red-400 text-2xl mb-4">⏰</div>
                    <div class="text-white text-xl font-semibold mb-2">Waktu Pembayaran Habis</div>
                    <div class="text-white/70 text-sm mb-4">Pesanan Anda telah expired. Silakan buat pesanan baru.</div>
                    <button onclick="window.location.href='/'" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                        Kembali ke Beranda
                    </button>
                </div>`;
            document.body.appendChild(expiredModal);
            
            return;
        }
        
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        const timeString = minutes.toString().padStart(2, '0') + ':' + seconds.toString().padStart(2, '0');
        timerElement.textContent = timeString;
        
        // Change color when less than 2 minutes
        if (minutes < 2) {
            timerElement.className = 'text-2xl font-bold text-red-400 font-mono';
        } else if (minutes < 5) {
            timerElement.className = 'text-2xl font-bold text-yellow-400 font-mono';
        } else {
            timerElement.className = 'text-2xl font-bold text-emerald-400 font-mono';
        }
    }
    
    // Update timer immediately and then every second
    // Add small delay to prevent race condition
    setTimeout(() => {
        updateTimer();
        setInterval(updateTimer, 1000);
    }, 100);
})();
@endif

(function(){
    const orderId = '{{ $order->order_id }}';
    
    // No payment method selection needed - order already has payment method set
    
    // Upload proof functionality
    const proofUpload = document.getElementById('proof-upload');
    const submitProofBtn = document.getElementById('submit-proof-btn');
    let selectedFile = null;
    
    if (proofUpload && submitProofBtn) {
        proofUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diperbolehkan');
                    return;
                }
                
                // Validate file size (10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('Ukuran file maksimal 10MB');
                    return;
                }
                
                selectedFile = file;
                submitProofBtn.disabled = false;
                
                // Show file preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('file-preview');
                    previewContainer.innerHTML = `
                        <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="flex items-center gap-3">
                                <img src="${e.target.result}" alt="Preview" class="h-16 w-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <div class="text-white font-medium">${file.name}</div>
                                    <div class="text-white/60 text-sm">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                                </div>
                                <button onclick="removePreview()" class="text-red-400 hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    previewContainer.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
        
        submitProofBtn.addEventListener('click', function() {
            if (!selectedFile) return;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<div class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-white/30 border-t-white mr-2"></div>Mengupload...';
            this.disabled = true;
            
            // Create form data
            const formData = new FormData();
            formData.append('proof_file', selectedFile);
            formData.append('order_id', orderId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            // Submit proof
            fetch('/user/upload-proof', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If not JSON, it's probably an HTML error page
                    throw new Error('Server error: ' + response.status);
                }
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successModal = document.createElement('div');
                    successModal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                    successModal.innerHTML = `
                        <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,400px)] text-center">
                            <div class="text-emerald-400 text-2xl mb-4">✅</div>
                            <div class="text-white text-xl font-semibold mb-2">Bukti Transfer Terkirim</div>
                            <div class="text-white/70 text-sm mb-4">Bukti transfer Anda telah berhasil dikirim. Tim kami akan memproses dalam 3-5 jam.</div>
                            <button onclick="window.location.href='/user/status'" class="px-6 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
                                Lihat Status Pesanan
                            </button>
                        </div>`;
                    document.body.appendChild(successModal);
                } else {
                    throw new Error(data.message || 'Upload gagal');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                alert('Upload bukti transfer gagal: ' + error.message);
                this.innerHTML = originalText;
                this.disabled = false;
            });
        });
    }
    
    // Function to remove file preview
    window.removePreview = function() {
        selectedFile = null;
        document.getElementById('proof-upload').value = '';
        document.getElementById('submit-proof-btn').disabled = true;
        document.getElementById('file-preview').classList.add('hidden');
        document.getElementById('file-preview').innerHTML = '';
    };
})();
</script>
@else
<div class="text-center py-8">
    <div class="text-red-400 text-lg mb-2">Pesanan Tidak Ditemukan</div>
    <div class="text-white/60 mb-4">Session mungkin telah expired atau pesanan tidak valid.</div>
    <a href="/" class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors">
        Kembali ke Beranda
    </a>
</div>
<script>
// Auto redirect after 3 seconds
setTimeout(() => {
    window.location.href = '/';
}, 3000);
</script>
@endif


@endsection
