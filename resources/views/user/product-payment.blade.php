@extends('layouts.app')
@section('title', 'Pembayaran Produk')
@section('body')

<header class="sticky top-0 z-50 backdrop-blur-md bg-gray-900/80 border-b border-white/10 shadow-lg">
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
        
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors" style="z-index: 9999; position: relative;">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/10 bg-gray-900/95 backdrop-blur-md">
        <div class="px-4 py-4 space-y-4">
            <a href="{{ route('home') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beranda</a>
            <a href="{{ route('products') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Produk</a>
            <a href="{{ route('user.status') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Cek Pesanan</a>
            <a href="javascript:void(0);" onclick="showHelpModal(); return false;" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
        </div>
    </div>
</header>

<main class="max-w-6xl mx-auto px-6 py-10">
    <!-- Steps header -->
    <div class="mb-6 flex items-center gap-4 text-sm">
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center">1</span>
            <span class="text-white/70">Pilih Produk</span>
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
            
            <!-- Product Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium" id="p_name">{{ session('selected_product_name', 'Produk') }}</div>
                        <div class="text-white/60 text-sm">{{ session('selected_game_type', 'Game') }} • 1 item</div>
                        <div class="text-emerald-300 text-xs font-medium mt-1">✓ Produk Terpilih</div>
                    </div>
                    <div class="text-right">
                        <div class="text-white/60 text-sm">Harga</div>
                        <div class="text-white font-semibold" id="p_price">Rp {{ number_format(session('selected_product_price', 0), 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Buyer Info Card -->
            <div class="mt-4 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center overflow-hidden">
                        <div class="h-12 w-12 bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center">
                            <span class="text-white font-bold text-lg">{{ strtoupper(substr(session('selected_username', 'U'), 0, 1)) }}</span>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium">Pembeli</div>
                        <div class="text-white/60 text-sm">Username: <span id="p_username">{{ session('selected_username', '') }}</span></div>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30">
                        <div class="h-2 w-2 bg-emerald-400 rounded-full"></div>
                        <span class="text-emerald-300 text-xs font-medium">Valid</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-lg border border-white/10 p-4 bg-white/5">
                <div class="text-white/80 font-medium mb-2">Notifikasi Pesanan (Email) <span class="text-red-400">*</span></div>
                <div class="text-white/60 text-sm">{{ session('selected_email', '') }}</div>
            </div>
        </div>

        <aside class="space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <!-- Promo Code Section -->
                <div class="mb-4 pb-4 border-b border-white/10">
                    <div class="text-white/90 font-medium mb-2 text-sm">Promo Code / Kode Unik</div>
                    <div class="flex gap-2">
                        <input type="text" id="promo-code-input" placeholder="Masukkan kode promo" 
                               class="flex-1 px-3 py-2 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/50 text-sm focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50">
                        <button id="apply-promo-btn" class="px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed">
                            Terapkan
                        </button>
                    </div>
                    <div id="promo-error" class="mt-2 text-red-400 text-xs hidden"></div>
                    <div id="promo-success" class="mt-2 text-emerald-400 text-xs hidden"></div>
                </div>
                
                <div class="text-white/90 font-medium">Detail Harga</div>
                <dl class="mt-3 space-y-2 text-sm">
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Total Pesanan</dt>
                        <dd id="s_price" class="text-white">Rp {{ number_format(session('selected_product_price', 0), 0, ',', '.') }}</dd>
                    </div>
                    <div id="product-discount-row" class="flex items-center justify-between hidden">
                        <dt class="text-white/60">Diskon Produk</dt>
                        <dd id="product-discount-amount" class="text-yellow-300">-Rp 0</dd>
                    </div>
                    <div id="promo-discount-row" class="flex items-center justify-between hidden">
                        <dt class="text-white/60">Diskon Promo</dt>
                        <dd id="promo-discount-amount" class="text-yellow-300">-Rp 0</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Biaya Admin</dt>
                        <dd class="text-white">Rp 0</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-white/60">Garansi</dt>
                        <dd class="text-emerald-300">Gratis!</dd>
                    </div>
                </dl>
                <div class="mt-4 h-px bg-white/10"></div>
                <div class="mt-4 flex items-center justify-between text-lg font-semibold">
                    <div class="text-white/90">Total Pembayaran</div>
                    <div id="s_total" class="text-white">Rp {{ number_format(session('selected_product_price', 0), 0, ',', '.') }}</div>
                </div>
                <button id="payment-btn" class="mt-4 inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 text-white font-medium transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    <span id="payment-btn-text">Pilih Pembayaran</span>
                </button>
            </div>
        </aside>
    </div>
</main>

<!-- Help Modal -->
<div id="helpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/20 rounded-xl max-w-md w-full max-h-[80vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-white">Hubungi Kami</h3>
                <button onclick="hideHelpModal()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="contactList" class="space-y-3">
                <!-- Contact items will be populated by JavaScript -->
            </div>
            
            <div id="noContactMessage" class="text-center py-8 hidden">
                <div class="text-gray-400 mb-2">
                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <p class="text-gray-300">Tidak ada kontak yang dapat dihubungi</p>
                <p class="text-gray-400 text-sm mt-1">Silakan coba lagi nanti</p>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const productName = '{{ session("selected_product_name", "") }}';
        const productPrice = {{ (int) (session('selected_product_price', 0)) }};
        const productOriginalPrice = {{ (int) (session('product_original_price', session('selected_product_price', 0))) }};
        const productDiscountAmount = {{ (int) (session('product_discount_amount', 0)) }};
        const productHasDiscount = {{ session('product_has_discount', false) ? 'true' : 'false' }};
        const username = '{{ session("selected_username", "") }}';
        const email = '{{ session("selected_email", "") }}';
        
        let promoCodeData = null;
        
        function fmt(n){return new Intl.NumberFormat('id-ID').format(n)}
        
        // Update price display
        function updatePriceDisplay() {
            let basePrice = productOriginalPrice || productPrice;
            let finalPrice = productPrice;
            
            // Apply product discount
            if (productHasDiscount && productDiscountAmount > 0) {
                basePrice = productOriginalPrice;
                finalPrice = productPrice; // Already includes product discount
                document.getElementById('product-discount-row').classList.remove('hidden');
                document.getElementById('product-discount-amount').textContent = '-Rp ' + fmt(productDiscountAmount);
            } else {
                document.getElementById('product-discount-row').classList.add('hidden');
            }
            
            // Apply promo discount
            if (promoCodeData && promoCodeData.success) {
                finalPrice = promoCodeData.final_price;
                document.getElementById('promo-discount-row').classList.remove('hidden');
                document.getElementById('promo-discount-amount').textContent = '-Rp ' + fmt(promoCodeData.discount_amount);
            } else {
                document.getElementById('promo-discount-row').classList.add('hidden');
            }
            
            document.getElementById('s_price').textContent = 'Rp ' + fmt(basePrice);
            document.getElementById('s_total').textContent = 'Rp ' + fmt(finalPrice);
        }
        
        // Initialize price display
        updatePriceDisplay();
        
        // Check payment mode and set button behavior
        let paymentMode = 'manual'; // Default to manual
        
        // Fetch payment mode from API
        fetch('/api/payment-methods')
            .then(response => response.json())
            .then(data => {
                if (data.payment_mode === 'gateway') {
                    paymentMode = 'gateway';
                    // Change button text to "Lanjut Pembayaran"
                    document.getElementById('payment-btn-text').textContent = 'Lanjut Pembayaran';
                } else {
                    paymentMode = 'manual';
                    // Keep button text as "Pilih Pembayaran"
                    document.getElementById('payment-btn-text').textContent = 'Pilih Pembayaran';
                }
            })
            .catch(() => {
                // Default to manual if API fails
                paymentMode = 'manual';
            });
        
        // Payment button click handler
        document.getElementById('payment-btn').addEventListener('click', function() {
            if (paymentMode === 'gateway') {
                // Gateway mode: show confirmation popup directly, then proceed to Midtrans
                showGatewayConfirmationPopup(email);
            } else {
                // Manual mode: show payment method selection popup first
                showPaymentMethodPopup(email);
            }
        });
        
        function showGatewayConfirmationPopup(email) {
            // Create confirmation popup for gateway mode
            const popup = document.createElement('div');
            popup.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
            
            popup.innerHTML = `
                <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,500px)]">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-xl font-semibold text-white">Konfirmasi Pembayaran</div>
                        <button class="text-white/60 hover:text-white" id="closeGatewayConfirmPopup">✕</button>
                    </div>
                    <div class="space-y-4">
                        <div class="text-white/70 text-sm">
                            Anda akan melanjutkan ke halaman pembayaran Midtrans dengan detail:
                        </div>
                        <div class="p-4 rounded-lg bg-white/5 border border-white/10">
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-white/60">Produk:</span>
                                    <span class="text-white">${productName}</span>
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
                                    <span class="text-emerald-300">Via Midtrans (Pilih di halaman berikutnya)</span>
                                </div>
                                ${productHasDiscount ? `
                                <div class="flex justify-between">
                                    <span class="text-white/60">Harga Asli:</span>
                                    <span class="text-white/50 line-through">Rp ${fmt(productOriginalPrice)}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Diskon Produk:</span>
                                    <span class="text-yellow-300">-Rp ${fmt(productDiscountAmount)}</span>
                                </div>
                                ` : ''}
                                ${promoCodeData && promoCodeData.success ? `
                                <div class="flex justify-between">
                                    <span class="text-white/60">Diskon Promo:</span>
                                    <span class="text-yellow-300">-Rp ${fmt(promoCodeData.discount_amount)}</span>
                                </div>
                                ` : ''}
                                <div class="flex justify-between font-semibold">
                                    <span class="text-white/60">Total:</span>
                                    <span class="text-white">Rp ${fmt(promoCodeData && promoCodeData.success ? promoCodeData.final_price : productPrice)}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 rounded-lg bg-blue-500/10 border border-blue-500/20">
                            <div class="flex items-start gap-2">
                                <svg class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div class="text-blue-200 text-xs">
                                    Anda akan diarahkan ke halaman pembayaran Midtrans. Di sana, Anda dapat memilih metode pembayaran yang tersedia (QRIS, E-Wallet, Bank Transfer, atau Kartu Kredit).
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <button id="cancelGatewayPayment" class="flex-1 px-4 py-2 rounded-md border border-white/20 text-white hover:bg-white/5 transition-colors">
                                Batal
                            </button>
                            <button id="confirmGatewayPayment" class="flex-1 px-4 py-2 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                                Lanjutkan ke Midtrans
                            </button>
                        </div>
                    </div>
                </div>`;
            
            document.body.appendChild(popup);
            
            // Close popup handlers
            popup.querySelector('#closeGatewayConfirmPopup').addEventListener('click', () => popup.remove());
            popup.querySelector('#cancelGatewayPayment').addEventListener('click', () => popup.remove());
            popup.addEventListener('click', (e) => {
                if (e.target === popup) popup.remove();
            });
            
            // Confirm payment handler - create order with gateway mode (no selected method, will be chosen in Midtrans)
            popup.querySelector('#confirmGatewayPayment').addEventListener('click', () => {
                popup.remove();
                // Create order with gateway mode, no selected method (will choose in Midtrans)
                createOrderAndRedirect(email, 'gateway', null);
            });
        }
        
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
                    <div id="paymentMethodsContainer" class="space-y-3 max-h-[60vh] overflow-y-auto pr-2">
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
            fetch('/api/payment-methods')
                .then(response => response.json())
                .then(data => {
                    const container = popup.querySelector('#paymentMethodsContainer');
                    
                    if (data.error) {
                        container.innerHTML = `
                            <div class="text-center text-red-400 py-4">
                                <div class="text-sm">${data.message || 'Error memuat metode pembayaran'}</div>
                            </div>
                        `;
                        return;
                    }
                    
                    if (!data.methods || data.methods.length === 0) {
                        container.innerHTML = `
                            <div class="text-center text-red-400 py-4">
                                <div class="text-sm">Tidak ada metode pembayaran tersedia</div>
                            </div>
                        `;
                        return;
                    }
                    
                    // Render all payment methods dynamically
                    const methodsHtml = data.methods.map(method => {
                        const iconHtml = getPaymentMethodIcon(method.icon, method.code);
                        const typeLabel = data.payment_mode === 'gateway' ? 'Gateway' : 'Manual';
                        
                        return `
                            <div class="payment-method p-4 rounded-lg border border-white/10 bg-white/5 hover:border-emerald-500/50 cursor-pointer transition-colors" data-method="${method.code}">
                                <div class="flex items-center gap-4">
                                    ${iconHtml}
                                    <div class="flex-1">
                                        <div class="text-white font-medium">${method.name}</div>
                                        <div class="text-white/60 text-sm">${method.description}</div>
                                    </div>
                                    <div class="text-emerald-400 text-sm font-medium">${typeLabel}</div>
                                </div>
                            </div>
                        `;
                    }).join('');
                    
                    container.innerHTML = methodsHtml;
                    
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
        
        // Helper function to get payment method icon HTML
        function getPaymentMethodIcon(iconType, code) {
            const icons = {
                'qris': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-500/30 to-blue-500/30 flex items-center justify-center"><svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg></div>',
                'bca': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">BCA</span></div>',
                'mandiri': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-red-500/30 to-yellow-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">MDR</span></div>',
                'bni': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">BNI</span></div>',
                'permata': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">PMT</span></div>',
                'gopay': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">GOPAY</span></div>',
                'dana': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-purple-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">DANA</span></div>',
                'ovo': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-purple-500/30 to-pink-500/30 flex items-center justify-center"><span class="text-white font-bold text-sm">OVO</span></div>',
                'linkaja': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-yellow-500/30 to-orange-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">LINK</span></div>',
                'shopeepay': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-orange-500/30 to-red-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">SPAY</span></div>',
                'credit_card': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center"><svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg></div>',
                'alfamart': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">ALFA</span></div>',
                'indomaret': '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-blue-500/30 to-cyan-500/30 flex items-center justify-center"><span class="text-white font-bold text-xs">INDO</span></div>'
            };
            
            return icons[iconType] || icons[code] || '<div class="h-12 w-12 rounded-lg bg-gradient-to-br from-gray-500/30 to-gray-600/30 flex items-center justify-center"><span class="text-white font-bold text-xs">PAY</span></div>';
        }
        
        function showPaymentConfirmationPopup(email, paymentMode, selectedMethod) {
            // Get method display name - fetch from API to get accurate name
            fetch('/api/payment-methods')
                .then(response => response.json())
                .then(data => {
                    const method = data.methods ? data.methods.find(m => m.code === selectedMethod) : null;
                    const methodName = method ? method.name : selectedMethod;
                    showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName);
                })
                .catch(() => {
                    // Fallback if API fails
                    const methodNames = {
                        'qris': 'QRIS Transfer',
                        'bca_va': 'BCA Virtual Account', 'bca': 'BCA Virtual Account',
                        'mandiri_va': 'Mandiri Virtual Account', 'mandiri': 'Mandiri Virtual Account',
                        'bni_va': 'BNI Virtual Account', 'bni': 'BNI Virtual Account',
                        'permata_va': 'Permata Virtual Account',
                        'dana': 'DANA E-Wallet',
                        'gopay': 'GoPay E-Wallet',
                        'ovo': 'OVO E-Wallet',
                        'linkaja': 'LinkAja',
                        'shopeepay': 'ShopeePay',
                        'credit_card': 'Kartu Kredit/Debit',
                        'alfamart': 'Alfamart',
                        'indomaret': 'Indomaret'
                    };
                    const methodName = methodNames[selectedMethod] || selectedMethod;
                    showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName);
                });
        }
        
        function showConfirmationPopupWithMethodName(email, paymentMode, selectedMethod, methodName) {
            
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
                                    <span class="text-white">${productName}</span>
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
                                ${productHasDiscount ? `
                                <div class="flex justify-between">
                                    <span class="text-white/60">Harga Asli:</span>
                                    <span class="text-white/50 line-through">Rp ${fmt(productOriginalPrice)}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-white/60">Diskon Produk:</span>
                                    <span class="text-yellow-300">-Rp ${fmt(productDiscountAmount)}</span>
                                </div>
                                ` : ''}
                                ${promoCodeData && promoCodeData.success ? `
                                <div class="flex justify-between">
                                    <span class="text-white/60">Diskon Promo:</span>
                                    <span class="text-yellow-300">-Rp ${fmt(promoCodeData.discount_amount)}</span>
                                </div>
                                ` : ''}
                                <div class="flex justify-between font-semibold">
                                    <span class="text-white/60">Total:</span>
                                    <span class="text-white">Rp ${fmt(promoCodeData && promoCodeData.success ? promoCodeData.final_price : productPrice)}</span>
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
        
        // Promo Code Logic
        const promoCodeInput = document.getElementById('promo-code-input');
        const applyPromoBtn = document.getElementById('apply-promo-btn');
        const promoError = document.getElementById('promo-error');
        const promoSuccess = document.getElementById('promo-success');
        
        function resetPromoMessages() {
            promoError.classList.add('hidden');
            promoSuccess.classList.add('hidden');
        }
        
        applyPromoBtn.addEventListener('click', async () => {
            const code = promoCodeInput.value.trim().toUpperCase();
            resetPromoMessages();
            
            if (!code) {
                promoError.textContent = 'Kode promo tidak boleh kosong.';
                promoError.classList.remove('hidden');
                return;
            }
            
            applyPromoBtn.disabled = true;
            applyPromoBtn.textContent = 'Menerapkan...';
            
            try {
                const basePrice = productOriginalPrice || productPrice;
                const response = await fetch('{{ route("api.promo-code.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ code: code, amount: basePrice })
                });
                
                const contentType = response.headers.get('content-type');
                const isJson = contentType && contentType.includes('application/json');
                
                if (!isJson) {
                    throw new Error('Server mengembalikan response non-JSON.');
                }
                
                const data = await response.json();
                
                if (data.success) {
                    promoCodeData = data;
                    promoSuccess.textContent = data.message || 'Kode promo berhasil diterapkan!';
                    promoSuccess.classList.remove('hidden');
                    updatePriceDisplay();
                } else {
                    promoError.textContent = data.message || 'Kode promo tidak valid.';
                    promoError.classList.remove('hidden');
                    promoCodeData = null;
                    updatePriceDisplay();
                }
            } catch (error) {
                console.error('Error applying promo code:', error);
                promoError.textContent = 'Gagal menerapkan kode promo. Silakan coba lagi.';
                promoError.classList.remove('hidden');
                promoCodeData = null;
                updatePriceDisplay();
            } finally {
                applyPromoBtn.disabled = false;
                applyPromoBtn.textContent = 'Terapkan';
            }
        });
        
        function createOrderAndRedirect(email, paymentMode, selectedMethod) {
            // For gateway mode, if no selectedMethod, use empty string (will be chosen in Midtrans)
            const methodToSubmit = (paymentMode === 'gateway' && !selectedMethod) ? '' : selectedMethod;
            
            // Create form and submit to create order securely
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/user/create-product-order';
            form.style.display = 'none';
            
            const fields = {
                'product_id': '{{ session("selected_product_id") }}',
                'product_name': productName,
                'product_price': productPrice,
                'game_type': '{{ session("selected_game_type") }}',
                'username': username,
                'email': email,
                'payment_mode': paymentMode,
                'selected_method': methodToSubmit,
                '_token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };
            
            // Add promo code data if available
            if (promoCodeData && promoCodeData.success) {
                fields['promo_code_id'] = promoCodeData.promo_code_id;
                fields['promo_code'] = promoCodeData.code;
                fields['original_price'] = productOriginalPrice || productPrice;
                fields['discount_amount'] = promoCodeData.discount_amount;
                fields['product_discount_amount'] = productDiscountAmount || 0;
            }
            
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

        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            if (!mobileMenuBtn.contains(event.target) && !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        // Help modal functions
        function showHelpModal(event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            document.getElementById('helpModal').classList.remove('hidden');
            loadContactInfo();
            return false;
        }

        function hideHelpModal() {
            document.getElementById('helpModal').classList.add('hidden');
        }

        async function loadContactInfo() {
            try {
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                
                // Reset states
                if (contactList) {
                    contactList.innerHTML = '';
                    contactList.classList.remove('hidden');
                }
                if (noContactMessage) {
                    noContactMessage.classList.add('hidden');
                }
                
                const response = await fetch('/api/contact-info');
                const data = await response.json();
                
                if (data.contacts && data.contacts.length > 0) {
                    if (noContactMessage) {
                        noContactMessage.classList.add('hidden');
                    }
                    
                    data.contacts.forEach(contact => {
                        const contactItem = document.createElement('div');
                        contactItem.className = 'flex items-center gap-3 p-3 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 transition-colors';
                        
                        contactItem.innerHTML = `
                            <div class="flex-shrink-0">
                                ${contact.icon}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-white font-medium">${contact.name}</div>
                                <div class="text-gray-400 text-sm">${contact.description}</div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="${contact.url}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    Buka
                                </a>
                            </div>
                        `;
                        
                        if (contactList) {
                            contactList.appendChild(contactItem);
                        }
                    });
                } else {
                    if (contactList) {
                        contactList.classList.add('hidden');
                    }
                    if (noContactMessage) {
                        noContactMessage.classList.remove('hidden');
                    }
                }
            } catch (error) {
                console.error('Error loading contact info:', error);
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                if (contactList) {
                    contactList.classList.add('hidden');
                }
                if (noContactMessage) {
                    noContactMessage.classList.remove('hidden');
                }
            }
        }

        // Close modal when clicking outside
        document.getElementById('helpModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideHelpModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideHelpModal();
            }
        });
    })();
</script>
@endsection

