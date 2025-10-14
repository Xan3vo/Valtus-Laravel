@extends('layouts.app')
@section('title', 'Pesan Produk')
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
            <a href="#" onclick="showHelpModal()" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
            <div class="pt-4 border-t border-white/10">
                <a href="{{ route('admin.login') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 transition-all duration-200 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                    </svg>
                    Admin
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-6 py-10">
    <!-- Steps header -->
    <div class="mb-6 flex items-center gap-4 text-sm">
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white text-black flex items-center justify-center font-medium">1</span>
            <span class="text-white">Pilih Produk</span>
        </div>
        <div class="text-white/30">/</div>
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center">2</span>
            <span class="text-white/70">Detail Pesanan</span>
        </div>
        <div class="text-white/30">/</div>
        <div class="flex items-center gap-2">
            <span class="h-6 w-6 rounded-full bg-white/10 text-white flex items-center justify-center">3</span>
            <span class="text-white/70">Pembayaran</span>
        </div>
    </div>

    <div class="grid lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 rounded-xl border border-white/10 bg-white/5 p-6">
            <h2 class="text-white text-xl font-semibold mb-6">Pilih Produk {{ $gameType }}</h2>
            
            @if($products->count() > 0)
                <div class="grid gap-4">
                    @foreach($products as $index => $product)
                        <div class="product-item rounded-lg border border-white/10 p-5 bg-white/5 hover:bg-white/10 transition-all duration-200 cursor-pointer group {{ $index === 0 ? 'border-emerald-500 bg-emerald-500/10' : '' }}" 
                             data-product-id="{{ $product->id }}"
                             data-product-name="{{ $product->name }}"
                             data-product-price="{{ $product->total_price }}"
                             data-game-type="{{ $product->game_type }}">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="text-white font-semibold text-lg">{{ $product->name }}</div>
                                        @if($index === 0)
                                            <span class="px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-medium border border-emerald-500/30">
                                                Terpilih
                                            </span>
                                        @endif
                                    </div>
                                    @if($product->description)
                                        <div class="text-white/60 text-sm mb-2">{{ $product->description }}</div>
                                    @endif
                                    @if($product->game_type)
                                        <div class="text-emerald-300 text-xs font-medium">{{ $product->game_type }}</div>
                                    @endif
                                </div>
                                <div class="text-right">
                                    <div class="text-white/60 text-sm mb-1">Harga</div>
                                    <div class="text-white font-bold text-xl">Rp {{ number_format($product->total_price, 0, ',', '.') }}</div>
                                   
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="h-16 w-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="text-white/60 text-lg mb-2">Tidak ada produk tersedia</div>
                    <div class="text-white/40 text-sm">Produk untuk kategori ini belum ditambahkan</div>
                </div>
            @endif
        </div>

        <aside class="lg:col-span-2 space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-6">
                <div class="text-white/90 font-semibold text-lg mb-6">Detail Pesanan</div>
                
                <!-- Selected Product -->
                <div id="selectedProduct" class="mb-6 p-5 rounded-lg border border-white/10 bg-white/5">
                    <div class="text-white/80 font-medium mb-3">Produk Terpilih</div>
                    <div id="productName" class="text-white font-semibold text-lg mb-1">Pilih produk di sebelah kiri</div>
                    <div id="productGameType" class="text-white/60 text-sm mb-2">-</div>
                    <div id="productPrice" class="text-emerald-300 font-bold text-xl">-</div>
                </div>

                <!-- Username Input -->
                <div class="mb-6">
                    <label class="text-white/80 font-medium text-sm mb-3 block">Username Game <span class="text-red-400">*</span></label>
                    <div class="flex gap-3">
                        <input id="username-input" type="text" required 
                               class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50" 
                               placeholder="Masukkan username game">
                        <button id="check-username" class="px-6 py-3 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed whitespace-nowrap">
                            Cek
                        </button>
                    </div>
                    <div id="username-error" class="mt-2 text-red-400 text-sm hidden">Username wajib diisi</div>
                    <div id="username-valid" class="mt-2 text-emerald-400 text-sm hidden">✓ Username valid</div>
                </div>

                <!-- Email Input -->
                <div class="mb-6">
                    <label class="text-white/80 font-medium text-sm mb-3 block">Email Notifikasi <span class="text-red-400">*</span></label>
                    <input id="email-input" type="email" required 
                           class="w-full px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50" 
                           placeholder="Masukkan email Anda">
                    <div id="email-error" class="mt-2 text-red-400 text-sm hidden">Email wajib diisi</div>
                </div>

                <!-- Order Summary -->
                <div id="orderSummary" class="hidden">
                    <div class="h-px bg-white/10 mb-6"></div>
                    <dl class="space-y-3 text-sm">
                        <div class="flex items-center justify-between">
                            <dt class="text-white/60">Total Pesanan</dt>
                            <dd id="orderTotal" class="text-white font-semibold">Rp 0</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-white/60">Biaya Admin</dt>
                            <dd class="text-white">Rp 0</dd>
                        </div>
                        <div class="flex items-center justify-between">
                            <dt class="text-white/60">Garansi</dt>
                            <dd class="text-emerald-300 font-medium">Gratis!</dd>
                        </div>
                    </dl>
                    <div class="h-px bg-white/10 my-6"></div>
                    <div class="flex items-center justify-between text-xl font-bold">
                        <div class="text-white/90">Total Pembayaran</div>
                        <div id="finalTotal" class="text-white">Rp 0</div>
                    </div>
                </div>

                <button id="payment-btn" class="mt-6 inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-4 text-white font-semibold text-lg transition-colors" disabled>
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Pilih Pembayaran
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
        let selectedProduct = null;
        let isUsernameValid = false;
        let isEmailValid = false;

        // Auto-select first product on page load
        function autoSelectFirstProduct() {
            const firstProduct = document.querySelector('.product-item');
            if (firstProduct) {
                selectedProduct = {
                    id: firstProduct.dataset.productId,
                    name: firstProduct.dataset.productName,
                    price: parseInt(firstProduct.dataset.productPrice),
                    gameType: firstProduct.dataset.gameType
                };
                
                // Update sidebar
                document.getElementById('productName').textContent = selectedProduct.name;
                document.getElementById('productGameType').textContent = selectedProduct.gameType + ' • 1 item';
                document.getElementById('productPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
                
                // Update order summary
                updateOrderSummary();
                validateForm();
            }
        }

        // Product selection
        document.querySelectorAll('.product-item').forEach(item => {
            item.addEventListener('click', function() {
                // Remove previous selection
                document.querySelectorAll('.product-item').forEach(i => {
                    i.classList.remove('border-emerald-500', 'bg-emerald-500/10');
                    const badge = i.querySelector('.text-emerald-300');
                    if (badge && badge.textContent === 'Terpilih') {
                        badge.remove();
                    }
                });
                
                // Add selection to clicked item
                this.classList.add('border-emerald-500', 'bg-emerald-500/10');
                
                // Add selected badge
                const nameDiv = this.querySelector('.flex.items-center.gap-3');
                if (nameDiv && !nameDiv.querySelector('.text-emerald-300')) {
                    const badge = document.createElement('span');
                    badge.className = 'px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-medium border border-emerald-500/30';
                    badge.textContent = 'Terpilih';
                    nameDiv.appendChild(badge);
                }
                
                // Set selected product
                selectedProduct = {
                    id: this.dataset.productId,
                    name: this.dataset.productName,
                    price: parseInt(this.dataset.productPrice),
                    gameType: this.dataset.gameType
                };
                
                // Update sidebar
                document.getElementById('productName').textContent = selectedProduct.name;
                document.getElementById('productGameType').textContent = selectedProduct.gameType + ' • 1 item';
                document.getElementById('productPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
                
                // Update order summary
                updateOrderSummary();
                validateForm();
            });
        });

        // Auto-select first product when page loads
        autoSelectFirstProduct();

        // Username validation
        const usernameInput = document.getElementById('username-input');
        const checkUsernameBtn = document.getElementById('check-username');
        const usernameError = document.getElementById('username-error');
        const usernameValid = document.getElementById('username-valid');

        function validateUsername() {
            const username = usernameInput.value.trim();
            if (!username) {
                usernameError.textContent = 'Username wajib diisi';
                usernameError.classList.remove('hidden');
                usernameValid.classList.add('hidden');
                isUsernameValid = false;
                return false;
            }
            return true;
        }

        checkUsernameBtn.addEventListener('click', function() {
            if (!validateUsername()) return;
            
            const username = usernameInput.value.trim();
            checkUsernameBtn.disabled = true;
            checkUsernameBtn.textContent = 'Mengecek...';
            
            // Simulate username check (you can replace with actual API call)
            setTimeout(() => {
                // For now, just validate format
                if (username.length >= 3 && /^[a-zA-Z0-9_-]+$/.test(username)) {
                    usernameError.classList.add('hidden');
                    usernameValid.classList.remove('hidden');
                    isUsernameValid = true;
                } else {
                    usernameError.textContent = 'Format username tidak valid';
                    usernameError.classList.remove('hidden');
                    usernameValid.classList.add('hidden');
                    isUsernameValid = false;
                }
                
                checkUsernameBtn.disabled = false;
                checkUsernameBtn.textContent = 'Cek';
                validateForm();
            }, 1000);
        });

        // Email validation
        const emailInput = document.getElementById('email-input');
        const emailError = document.getElementById('email-error');

        function validateEmail() {
            const email = emailInput.value.trim();
            const isValid = email && /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
            
            if (!isValid) {
                emailError.textContent = 'Email wajib diisi dan format harus valid';
                emailError.classList.remove('hidden');
                isEmailValid = false;
            } else {
                emailError.classList.add('hidden');
                isEmailValid = true;
            }
            
            validateForm();
        }

        emailInput.addEventListener('input', validateEmail);
        emailInput.addEventListener('blur', validateEmail);

        // Update order summary
        function updateOrderSummary() {
            if (!selectedProduct) return;
            
            document.getElementById('orderSummary').classList.remove('hidden');
            document.getElementById('orderTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
            document.getElementById('finalTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
        }

        // Validate form
        function validateForm() {
            const paymentBtn = document.getElementById('payment-btn');
            if (selectedProduct && isUsernameValid && isEmailValid) {
                paymentBtn.disabled = false;
            } else {
                paymentBtn.disabled = true;
            }
        }

        // Payment button click
        document.getElementById('payment-btn').addEventListener('click', function() {
            if (!selectedProduct || !isUsernameValid || !isEmailValid) return;
            
            // Store data in session and redirect to payment page
            storeProductDataAndRedirect();
        });

        function storeProductDataAndRedirect() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/user/store-product-data';
            form.style.display = 'none';
            
            const fields = {
                'product_id': selectedProduct.id,
                'product_name': selectedProduct.name,
                'product_price': selectedProduct.price,
                'game_type': selectedProduct.gameType,
                'username': usernameInput.value.trim(),
                'email': emailInput.value.trim(),
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
        function showHelpModal() {
            document.getElementById('helpModal').classList.remove('hidden');
            loadContactInfo();
        }

        function hideHelpModal() {
            document.getElementById('helpModal').classList.add('hidden');
        }

        async function loadContactInfo() {
            try {
                const response = await fetch('/api/contact-info');
                const data = await response.json();
                
                const contactList = document.getElementById('contactList');
                const noContactMessage = document.getElementById('noContactMessage');
                
                contactList.innerHTML = '';
                
                if (data.contacts && data.contacts.length > 0) {
                    noContactMessage.classList.add('hidden');
                    
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
                        
                        contactList.appendChild(contactItem);
                    });
                } else {
                    contactList.classList.add('hidden');
                    noContactMessage.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error loading contact info:', error);
                document.getElementById('contactList').classList.add('hidden');
                document.getElementById('noContactMessage').classList.remove('hidden');
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
