@extends('layouts.app')
@section('title', 'Detail Pesanan')
@section('body')

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
            <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center">3</span>
            <span class="text-white/70">Pembayaran</span>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold">Detail Pesanan</h2>
            <!-- Robux Product Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                        <img src="/assets/images/robux.png" class="h-6 w-6" alt="Robux">
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium" id="p_name">{{ session('selected_amount', 0) }} Robux</div>
                        <div class="text-white/60 text-sm">Roblox Digital Currency</div>
                    </div>
                    <div class="text-right">
                        <div class="text-white/60 text-sm">Harga</div>
                        <div class="text-white font-semibold" id="p_price">Rp 0</div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center overflow-hidden">
                        <img id="p_avatar" src="/assets/images/robux.png" class="h-12 w-12 object-cover" alt="Avatar">
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium">Pembeli</div>
                        <div class="text-white/60 text-sm">Username: <span id="p_username">{{ session('selected_username') }}</span></div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30">
                        <div class="h-2 w-2 bg-emerald-400 rounded-full"></div>
                        <span class="text-emerald-300 text-xs font-medium">Valid</span>
                    </div>
                </div>
            </div>

            @if(session('gamepass_link'))
            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">GamePass Anda</div>
                <div class="text-white/60 text-sm mb-3">GamePass yang akan digunakan admin untuk top-up Robux ke akun Anda:</div>
                <div class="text-white/80 text-sm font-mono bg-black/30 px-3 py-2 rounded border border-white/10 break-all">
                    {{ session('gamepass_link') }}
                </div>
                <div class="mt-2 text-white/50 text-xs">Admin akan menggunakan GamePass ini untuk mengirim Robux ke akun Anda</div>
            </div>
            @endif

            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">Notifikasi Pesanan (Email) <span class="text-red-400">*</span></div>
                <input id="email-input" type="email" required class="w-full px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50" placeholder="Masukkan email Anda">
                <div id="email-error" class="mt-2 text-red-400 text-sm hidden">Email wajib diisi untuk notifikasi pesanan</div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-medium">Detail Harga</div>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between"><dt class="text-white/60">Total Pesanan</dt><dd id="s_price" class="text-white">Rp 0</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-white/60">Biaya Admin</dt><dd class="text-white">Rp0</dd></div>
                    <div class="flex items-center justify-between"><dt class="text-white/60">Garansi</dt><dd class="text-emerald-300">Gratis!</dd></div>
                    @if(session('gamepass_link'))
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">GamePass</dt>
                        <dd class="text-emerald-300 text-xs">✓ Tersedia</dd>
                    </div>
                    @endif
                </dl>
                <div class="mt-4 h-px bg-white/10"></div>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                    <div class="text-white/90">Total Pembayaran</div>
                    <div id="s_total" class="text-white">Rp 0</div>
                </div>
                <button id="payment-btn" class="mt-4 inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 text-white font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Pilih Pembayaran
                </button>
            </div>
        </aside>
    </div>

    <script>
        (function(){
            const amount = {{ (int) (session('selected_amount', 0)) }};
            const username = '{{ session('selected_username', '') }}';
            const pricePer100 = {{ (int) (\App\Models\Setting::getValue('robux_price_per_100', '10000')) }};
            const price = pricePer100 * (amount/100);
            
            function fmt(n){return new Intl.NumberFormat('id-ID').format(n)}
            document.getElementById('p_price').textContent = 'Rp ' + fmt(price);
            document.getElementById('s_price').textContent = 'Rp ' + fmt(price);
            document.getElementById('s_total').textContent = 'Rp ' + fmt(price);
            
            // Email validation and payment button
            const emailInput = document.getElementById('email-input');
            const paymentBtn = document.getElementById('payment-btn');
            const emailError = document.getElementById('email-error');
            
            function validateEmail() {
                const email = emailInput.value.trim();
                const isValid = email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                
                if (!isValid) {
                    emailError.classList.remove('hidden');
                    paymentBtn.disabled = true;
                } else {
                    emailError.classList.add('hidden');
                    paymentBtn.disabled = false;
                }
            }
            
            emailInput.addEventListener('input', validateEmail);
            emailInput.addEventListener('blur', validateEmail);
            
            // Payment button click handler
            paymentBtn.addEventListener('click', function() {
                const email = emailInput.value.trim();
                if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    emailError.classList.remove('hidden');
                    emailInput.focus();
                    return;
                }
                
                // Show payment method selection popup first
                showPaymentMethodPopup(email);
            });
            
            function showPaymentMethodPopup(email) {
                // Create payment method selection popup
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                popup.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,600px)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xl font-semibold text-white">Pilih Metode Pembayaran</div>
                            <button class="text-white/60 hover:text-white" id="closeMethodPopup">✕</button>
                        </div>
                        <div class="text-white/70 text-sm mb-6">Pilih metode pembayaran yang Anda inginkan:</div>
                        <div id="paymentMethodsContainer" class="space-y-3">
                            <div class="text-center text-white/50 py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-emerald-500 mx-auto mb-2"></div>
                                <div>Memuat metode pembayaran...</div>
                            </div>
                        </div>
                    </div>`;
                
                document.body.appendChild(popup);
                
                // Close popup handlers
                popup.querySelector('#closeMethodPopup').addEventListener('click', () => popup.remove());
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) popup.remove();
                });
                
                // Load payment methods based on admin settings
                loadPaymentMethods(email, popup);
            }
            
            function loadPaymentMethods(email, popup) {
                fetch('/api/payment-mode')
                    .then(response => response.json())
                    .then(data => {
                        const container = popup.querySelector('#paymentMethodsContainer');
                        
                        if (data.payment_mode === 'manual') {
                            // Manual payment - only QRIS
                            container.innerHTML = `
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="qris">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">QRIS Transfer</div>
                                            <div class="text-white/60 text-sm">Scan QR Code untuk pembayaran instan</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Manual</div>
                                    </div>
                                </div>
                            `;
                        } else if (data.payment_mode === 'gateway') {
                            // Gateway payment - multiple options
                            container.innerHTML = `
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="bca">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-orange-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">BCA</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">BCA Virtual Account</div>
                                            <div class="text-white/60 text-sm">Transfer ke rekening BCA</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="mandiri">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-yellow-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">MDR</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">Mandiri Virtual Account</div>
                                            <div class="text-white/60 text-sm">Transfer ke rekening Mandiri</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="bni">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500/30 to-orange-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">BNI</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">BNI Virtual Account</div>
                                            <div class="text-white/60 text-sm">Transfer ke rekening BNI</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="dana">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">DANA</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">DANA E-Wallet</div>
                                            <div class="text-white/60 text-sm">Pembayaran via DANA</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="gopay">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">GOPAY</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">GoPay E-Wallet</div>
                                            <div class="text-white/60 text-sm">Pembayaran via GoPay</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                                <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="ovo">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">OVO</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="text-white font-medium">OVO E-Wallet</div>
                                            <div class="text-white/60 text-sm">Pembayaran via OVO</div>
                                        </div>
                                        <div class="text-emerald-400 text-sm font-medium">Gateway</div>
                                    </div>
                                </div>
                            `;
                        } else {
                            // Default to manual if no mode set
                            container.innerHTML = `
                                <div class="text-center text-red-400 py-4">
                                    <div class="text-sm">Error: Mode pembayaran tidak dikonfigurasi</div>
                                </div>
                            `;
                        }
                        
                        // Add click handlers for payment methods
                        popup.querySelectorAll('.payment-method').forEach(method => {
                            method.addEventListener('click', () => {
                                const methodType = method.dataset.method;
                                popup.remove();
                                
                                if (data.payment_mode === 'manual') {
                                    // Manual payment - show confirmation
                                    showPaymentConfirmationPopup(email, 'manual', methodType);
                                } else if (data.payment_mode === 'gateway') {
                                    // Gateway payment - show confirmation
                                    showPaymentConfirmationPopup(email, 'gateway', methodType);
                                }
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading payment methods:', error);
                        const container = popup.querySelector('#paymentMethodsContainer');
                        container.innerHTML = `
                            <div class="text-center text-red-400 py-4">
                                <div class="text-sm">Error memuat metode pembayaran</div>
                            </div>
                        `;
                    });
            }
            
            function showPaymentConfirmationPopup(email, paymentMode, selectedMethod) {
                // Get method display name
                const methodNames = {
                    'qris': 'QRIS Transfer',
                    'bca': 'BCA Virtual Account',
                    'mandiri': 'Mandiri Virtual Account',
                    'bni': 'BNI Virtual Account',
                    'dana': 'DANA E-Wallet',
                    'gopay': 'GoPay E-Wallet',
                    'ovo': 'OVO E-Wallet'
                };
                
                const methodName = methodNames[selectedMethod] || selectedMethod;
                
                // Create confirmation popup
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                popup.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,500px)]">
                        <div class="flex items-center justify-between mb-4">
                            <div class="text-xl font-semibold text-white">Konfirmasi Pembayaran</div>
                            <button class="text-white/60 hover:text-white" id="closeConfirmPopup">✕</button>
                        </div>
                        <div class="space-y-4">
                            <div class="text-white/70 text-sm">
                                Anda akan melanjutkan ke halaman pembayaran dengan detail:
                            </div>
                            <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Produk:</span>
                                        <span class="text-white">${amount} Robux</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Username:</span>
                                        <span class="text-white">${username}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Email:</span>
                                        <span class="text-white">${email}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-white/60">Metode Pembayaran:</span>
                                        <span class="text-emerald-300">${methodName}</span>
                                    </div>
                                    <div class="flex justify-between font-semibold">
                                        <span class="text-white/60">Total:</span>
                                        <span class="text-white">Rp ${new Intl.NumberFormat('id-ID').format(price)}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-white/60 text-xs">
                                ⏰ Waktu pembayaran: 10 menit dari sekarang
                            </div>
                            <div class="flex gap-3">
                                <button id="cancelPayment" class="flex-1 px-4 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                    Batal
                                </button>
                                <button id="confirmPayment" class="flex-1 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                    Lanjutkan
                                </button>
    </div>
    </div>
                    </div>`;
                
                document.body.appendChild(popup);
                
                // Close popup handlers
                popup.querySelector('#closeConfirmPopup').addEventListener('click', () => popup.remove());
                popup.querySelector('#cancelPayment').addEventListener('click', () => popup.remove());
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) popup.remove();
                });
                
                // Confirm payment handler
                popup.querySelector('#confirmPayment').addEventListener('click', () => {
                    popup.remove();
                    
                    // Create order with selected payment method
                    createOrderAndRedirect(email, paymentMode, selectedMethod);
                });
            }
            
            function createOrderAndRedirect(email, paymentMode, selectedMethod) {
                // Debug logging
                console.log('Creating order with data:', {
                    amount, username, email, price, paymentMode, selectedMethod,
                    gamepass_link: '{{ session("gamepass_link") }}'
                });
                
                // Create form and submit to create order securely
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/user/create-order';
                form.style.display = 'none';
                
                const fields = {
                    'amount': amount,
                    'username': username,
                    'email': email,
                    'price': price,
                    'payment_mode': paymentMode,
                    'selected_method': selectedMethod,
                    'gamepass_link': '{{ session("gamepass_link") }}',
                    '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                };
                
                Object.entries(fields).forEach(([key, value]) => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                });
                
                document.body.appendChild(form);
                form.submit();
            }
            
            
            // Fetch avatar for username display (secure)
            if (username && /^[a-zA-Z0-9_-]+$/.test(username)) {
                fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`)
                    .then(r=>r.json()).then(j=>{
                        if(j && j.ok && j.avatar){
                            const av = document.getElementById('p_avatar');
                            av.src = j.avatar;
                        }
                    }).catch(()=>{});
            }
            
            // Initial validation
            validateEmail();
        })();
    </script>
</main>
@endsection


