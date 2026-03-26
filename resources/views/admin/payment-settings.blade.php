@extends('layouts.app')

@section('title', 'Admin • Payment Settings')

@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-6 py-16">
    <div class="mb-8">
        <h1 class="text-3xl md:text-4xl font-medium text-white/90">Payment Settings</h1>
        <p class="text-white/60 mt-2">Kelola metode pembayaran dan konfigurasi gateway</p>
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

    <!-- Current Settings Display -->
    <div class="rounded-lg border border-white/20 p-6 bg-white/5 mb-8">
        <h3 class="text-xl font-semibold text-white mb-6">Current Payment Settings</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-sm">Payment Mode</div>
                <div class="text-white text-lg font-bold">
                    {{ \App\Models\Setting::getValue('payment_mode', 'manual') === 'manual' ? 'QRIS Manual' : 'Gateway Otomatis' }}
                </div>
            </div>
            <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-sm">Active Gateway</div>
                <div class="text-white text-lg font-bold">
                    {{ \App\Models\Setting::getValue('payment_gateway', 'none') === 'none' ? 'None' : ucfirst(\App\Models\Setting::getValue('payment_gateway', 'none')) }}
                </div>
            </div>
            <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                <div class="text-white/60 text-sm">Payment Status</div>
                <div class="text-white text-lg font-bold text-emerald-400">
                    Active
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Settings Form -->
    <form method="POST" action="{{ route('admin.payment-settings.update') }}" enctype="multipart/form-data" class="space-y-8">
            @csrf
            @method('PUT')

        <!-- Payment Mode -->
        <div class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">Payment Mode</h3>
            
            <div class="grid md:grid-cols-2 gap-6">
                <label class="cursor-pointer">
                    <div class="p-4 rounded-lg border-2 transition-all duration-200 {{ \App\Models\Setting::getValue('payment_mode', 'manual') === 'manual' ? 'border-emerald-500 bg-emerald-500/10' : 'border-white/20 hover:border-white/40' }}">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="radio" name="payment_mode" value="manual" 
                                   {{ \App\Models\Setting::getValue('payment_mode', 'manual') === 'manual' ? 'checked' : '' }}
                                   class="payment-mode-radio w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2">
                            <span class="text-white/90 font-medium">QRIS Manual</span>
                        </div>
                        <p class="text-white/60 text-sm">Customer scan QRIS dan upload bukti transfer</p>
                    </div>
                </label>
                <label class="cursor-pointer">
                    <div class="p-4 rounded-lg border-2 transition-all duration-200 {{ \App\Models\Setting::getValue('payment_mode', 'manual') === 'gateway' ? 'border-emerald-500 bg-emerald-500/10' : 'border-white/20 hover:border-white/40' }}">
                        <div class="flex items-center gap-3 mb-2">
                            <input type="radio" name="payment_mode" value="gateway" 
                                   {{ \App\Models\Setting::getValue('payment_mode', 'manual') === 'gateway' ? 'checked' : '' }}
                                   class="payment-mode-radio w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2">
                            <span class="text-white/90 font-medium">Gateway Otomatis</span>
                        </div>
                        <p class="text-white/60 text-sm">Integrasi dengan payment gateway Midtrans</p>
                    </div>
                </label>
            </div>
        </div>

        <!-- Payment Gateway Selection - Midtrans Only -->
        <div id="gateway-section" class="rounded-lg border border-white/20 p-6 bg-white/5" style="display: none;">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-white mb-2 flex items-center gap-2">
                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Midtrans Payment Gateway
                    </h3>
                    <p class="text-white/60 text-sm">Konfigurasi gateway pembayaran otomatis dengan Midtrans</p>
                </div>
                <div class="px-3 py-1 bg-emerald-500/20 border border-emerald-500/30 rounded-full">
                    <span class="text-emerald-400 text-xs font-medium">AUTOMATIC</span>
                </div>
            </div>
            
            <input type="hidden" name="payment_gateway" value="midtrans">

            <!-- Midtrans Settings -->
            <div class="space-y-6">
                <div class="p-4 bg-white/10 rounded-lg border border-white/20">
                    <h4 class="font-medium text-white/90 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Konfigurasi Midtrans
                    </h4>
                    <div class="grid md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-white/70 mb-2">Server Key *</label>
                            <input type="text" name="midtrans_server_key" 
                                   value="{{ \App\Models\Setting::getValue('midtrans_server_key', '') }}"
                                   class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                                   placeholder="Masukkan Server Key"
                                   required>
                            <p class="mt-1 text-white/50 text-xs">Digunakan untuk backend API calls</p>
                        </div>
                        <div>
                            <label class="block text-white/70 mb-2">Client Key *</label>
                            <input type="text" name="midtrans_client_key" 
                                   value="{{ \App\Models\Setting::getValue('midtrans_client_key', '') }}"
                                   class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                                   placeholder="Masukkan Client Key"
                                   required>
                            <p class="mt-1 text-white/50 text-xs">Digunakan untuk frontend Snap.js</p>
                        </div>
                        <div>
                            <label class="block text-white/70 mb-2">Merchant ID</label>
                            <input type="text" name="midtrans_merchant_id" 
                                   value="{{ \App\Models\Setting::getValue('midtrans_merchant_id', '') }}"
                                   class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                                   placeholder="Masukkan Merchant ID (opsional)">
                            <p class="mt-1 text-white/50 text-xs">Opsional, untuk identifikasi merchant</p>
                        </div>
                        <div>
                            <label class="block text-white/70 mb-2">Environment *</label>
                            <select name="midtrans_environment" class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400" required>
                                <option value="sandbox" {{ \App\Models\Setting::getValue('midtrans_environment', 'sandbox') === 'sandbox' ? 'selected' : '' }}>Sandbox (Testing)</option>
                                <option value="production" {{ \App\Models\Setting::getValue('midtrans_environment', 'sandbox') === 'production' ? 'selected' : '' }}>Production (Live)</option>
                            </select>
                            <p class="mt-1 text-white/50 text-xs">Pilih mode: Sandbox untuk testing, Production untuk live</p>
                        </div>
                    </div>
                    
                    <!-- Panduan Cara Mendapatkan Credentials -->
                    <div class="mt-6 p-4 bg-blue-500/10 border border-blue-500/20 rounded-lg">
                        <p class="text-blue-300 text-sm font-medium mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Cara Mendapatkan Credentials Midtrans
                        </p>
                        <ol class="text-white/70 text-xs space-y-2 list-decimal pl-5">
                            <li>Daftar akun di <a href="https://dashboard.midtrans.com/register" target="_blank" class="text-blue-400 hover:underline font-medium">dashboard.midtrans.com</a> (gratis)</li>
                            <li>Login ke dashboard Midtrans</li>
                            <li>Klik <strong>Settings</strong> → <strong>Access Keys</strong></li>
                            <li>Salin <strong>Server Key</strong> dan paste ke field "Server Key" di atas</li>
                            <li>Salin <strong>Client Key</strong> dan paste ke field "Client Key" di atas</li>
                            <li>Untuk <strong>Merchant ID</strong>, bisa ditemukan di Settings → Account → Merchant ID (opsional)</li>
                            <li>Pilih <strong>Environment</strong>:
                                <ul class="list-disc pl-5 mt-1 space-y-1">
                                    <li><strong>Sandbox:</strong> Untuk testing (tidak ada uang real yang ditransfer)</li>
                                    <li><strong>Production:</strong> Untuk live (transaksi real dengan uang real)</li>
                                </ul>
                            </li>
                        </ol>
                        <div class="mt-4 p-3 bg-yellow-500/10 border border-yellow-500/20 rounded">
                            <p class="text-yellow-300 text-xs font-medium mb-1">💡 Tips:</p>
                            <ul class="text-white/60 text-xs space-y-1 list-disc pl-4">
                                <li>Gunakan <strong>Sandbox</strong> untuk testing terlebih dahulu sebelum go live</li>
                                <li>Untuk testing, gunakan kartu kredit testing: <code class="bg-black/30 px-1 rounded">4111 1111 1111 1111</code> (CVV: 123)</li>
                                <li>Pastikan Server Key dan Client Key sesuai dengan Environment yang dipilih</li>
                                <li>Setelah go live, pastikan sudah ganti ke <strong>Production</strong> dan gunakan credentials Production</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QRIS Settings -->
        <div id="manual-section" class="rounded-lg border border-white/20 p-6 bg-white/5">
            <h3 class="text-xl font-semibold text-white mb-6">QRIS Settings</h3>
            
            <div class="grid md:grid-cols-2 gap-8">
                <!-- Left Column: Upload & Info -->
                <div class="space-y-6">
                    <!-- Upload QR Code -->
                    <div>
                        <label class="block text-white/70 mb-3">Upload QR Code</label>
                        
                        <!-- Current QR Code Display -->
                        @if(\App\Models\Setting::getValue('manual_qris_image'))
                        <div class="mb-4 p-4 bg-white/10 rounded-lg border border-white/20" id="current-image-container">
                            <p class="text-white/60 text-sm mb-3">QR Code saat ini:</p>
                            <div class="flex items-center justify-center">
                                <div class="relative">
                                    <img src="{{ asset(\App\Models\Setting::getValue('manual_qris_image')) }}" alt="QR Code" class="h-32 w-32 object-contain bg-white border border-white/20 rounded-lg shadow-sm">
                                    <div class="absolute -top-2 -right-2">
                                        <button type="button" onclick="removeCurrentImage()" class="bg-red-500 hover:bg-red-600 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">
                                            ×
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2 justify-center mt-3">
                                <button type="button" onclick="showUploadArea()" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                    Upload Ulang
                                </button>
                                <button type="button" onclick="removeCurrentImage()" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                    Hapus
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Upload Area -->
                        <div class="border-2 border-dashed border-white/20 rounded-lg p-6 text-center hover:border-emerald-400/50 transition-colors" id="upload-area">
                            <div class="space-y-3" id="upload-placeholder">
                                <svg class="mx-auto h-10 w-10 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div class="text-white/60">
                                    <label for="qris_upload" class="cursor-pointer text-emerald-400 hover:text-emerald-300 font-medium">
                                        Upload QR Code
                                    </label>
                                    <input id="qris_upload" name="qris_image" type="file" class="hidden" accept="image/*">
                                    <span class="text-white/40"> atau drag and drop</span>
                                </div>
                                <p class="text-xs text-white/40">PNG, JPG, GIF hingga 10MB</p>
                            </div>
                            
                            <!-- Preview Area (hidden by default) -->
                            <div id="image-preview" class="hidden">
                                <div class="space-y-3">
                                    <img id="preview-img" src="" alt="Preview" class="h-32 w-32 object-contain bg-white border border-white/20 rounded-lg mx-auto">
                                    <div class="text-white/60 text-sm">Preview:</div>
                                    <div class="flex gap-2 justify-center">
                                        <button type="button" onclick="removePreview()" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded">
                                            Hapus
                                        </button>
                                        <button type="button" onclick="confirmUpload()" class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs rounded">
                                            Gunakan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- QRIS Information -->
                    <div>
                        <label class="block text-white/70 mb-2">Nama Pemilik QRIS</label>
                        <input type="text" name="manual_qris_name" 
                               value="{{ \App\Models\Setting::getValue('manual_qris_name', '') }}"
                               class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400"
                               placeholder="Nama Pemilik QRIS">
                    </div>
                </div>

                <!-- Right Column: Instructions -->
                <div>
                    <label class="block text-white/70 mb-2">Instruksi untuk Customer</label>
                    <textarea name="manual_qris_instructions" rows="8"
                              class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 resize-none"
                              placeholder="Contoh: Scan QR Code dengan aplikasi pembayaran favorit Anda, kirim bukti transfernya">{{ \App\Models\Setting::getValue('manual_qris_instructions', '') }}</textarea>
                    <p class="text-white/40 text-xs mt-2">Instruksi ini akan ditampilkan kepada customer saat melakukan pembayaran</p>
                </div>
            </div>
        </div>


        <!-- Submit Button -->
        <div class="flex gap-4">
            <button type="submit" class="px-5 py-3 rounded-sm bg-white text-black w-fit hover:bg-gray-100">
                Save Settings
            </button>
            <a href="{{ route('admin.payment-settings') }}" class="px-5 py-3 rounded-sm bg-gray-600 text-white w-fit hover:bg-gray-700">
                Reset
            </a>
        </div>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentModeRadios = document.querySelectorAll('.payment-mode-radio');
    const gatewaySection = document.getElementById('gateway-section');
    const manualSection = document.getElementById('manual-section');
    

    function toggleSections() {
        const selectedMode = document.querySelector('.payment-mode-radio:checked');
        
        if (selectedMode && selectedMode.value === 'gateway') {
            if (gatewaySection) gatewaySection.style.display = 'block';
            if (manualSection) manualSection.style.display = 'none';
            // Show Midtrans settings automatically
            setTimeout(showMidtransSettings, 100);
        } else {
            if (gatewaySection) gatewaySection.style.display = 'none';
            if (manualSection) manualSection.style.display = 'block';
        }
    }

    // Show Midtrans settings automatically when gateway mode is selected
    function showMidtransSettings() {
        const gatewaySection = document.getElementById('gateway-section');
        const midtransSettings = document.querySelector('#gateway-section .space-y-6');
        
        if (gatewaySection && gatewaySection.style.display !== 'none' && midtransSettings) {
            midtransSettings.style.display = 'block';
        }
    }

    // Initial setup - only if elements exist
    if (paymentModeRadios.length > 0) {
        toggleSections();
    }

    // Event listeners
    paymentModeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            toggleSections();
            updateRadioStyling();
            // Show Midtrans settings when gateway mode is selected
            if (radio.value === 'gateway') {
                setTimeout(showMidtransSettings, 100);
            }
        });
    });

    function updateRadioStyling() {
        const labels = document.querySelectorAll('label[class*="cursor-pointer"]');
        labels.forEach(label => {
            const radio = label.querySelector('input[type="radio"]');
            const container = label.querySelector('div');
            
            if (radio && container) {
                if (radio.checked) {
                    container.className = container.className.replace('border-white/20', 'border-emerald-500').replace('border-white/40', 'border-emerald-500');
                    container.className = container.className.replace('hover:border-white/40', 'bg-emerald-500/10');
                    if (!container.className.includes('bg-emerald-500/10')) {
                        container.className += ' bg-emerald-500/10';
                    }
                } else {
                    container.className = container.className.replace('border-emerald-500', 'border-white/20').replace('bg-emerald-500/10', '');
                    container.className = container.className.replace('hover:border-white/40', 'hover:border-white/40');
                }
            }
        });
    }

    // Initial styling update
    updateRadioStyling();

    // Show Midtrans settings on initial load if gateway mode is selected
    const selectedMode = document.querySelector('.payment-mode-radio:checked');
    if (selectedMode && selectedMode.value === 'gateway') {
        setTimeout(showMidtransSettings, 100);
    }

    // Image upload functionality
    const fileInput = document.getElementById('qris_upload');
    const uploadPlaceholder = document.getElementById('upload-placeholder');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');

    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
        
        // Drag and drop functionality
        const uploadArea = document.getElementById('upload-area');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('drop', handleDrop);
            uploadArea.addEventListener('dragleave', handleDragLeave);
        }
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            showImagePreview(file);
        }
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('border-emerald-400', 'bg-emerald-500/10');
    }

    function handleDragLeave(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('border-emerald-400', 'bg-emerald-500/10');
    }

    function handleDrop(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('border-emerald-400', 'bg-emerald-500/10');
        
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                showImagePreview(file);
                fileInput.files = files;
            }
        }
    }

    function showImagePreview(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            uploadPlaceholder.classList.add('hidden');
            imagePreview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Global functions for buttons
    window.removePreview = function() {
        uploadPlaceholder.classList.remove('hidden');
        imagePreview.classList.add('hidden');
        fileInput.value = '';
        previewImg.src = '';
    };

    window.confirmUpload = function() {
        // Image is already selected, just keep the preview visible
        // The form will submit with the selected file
    };

    window.showUploadArea = function() {
        // Show upload area
        const uploadPlaceholder = document.getElementById('upload-placeholder');
        const imagePreview = document.getElementById('image-preview');
        if (uploadPlaceholder && imagePreview) {
            uploadPlaceholder.classList.remove('hidden');
            imagePreview.classList.add('hidden');
        }
        
        // Reset file input
        const fileInput = document.getElementById('qris_upload');
        if (fileInput) {
            fileInput.value = '';
        }
    };

    window.removeCurrentImage = function() {
        // Add a hidden input to indicate removal
        let removeInput = document.getElementById('remove_current_image');
        if (!removeInput) {
            removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_current_image';
            removeInput.id = 'remove_current_image';
            document.querySelector('form').appendChild(removeInput);
        }
        removeInput.value = '1';
        
        // Hide current image display
        const currentImageContainer = document.getElementById('current-image-container');
        if (currentImageContainer) {
            currentImageContainer.style.display = 'none';
        }
        
        // Show upload area
        showUploadArea();
    };
});
</script>
@endsection
