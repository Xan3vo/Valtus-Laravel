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
            <a href="javascript:void(0);" onclick="showHelpModal(); return false;" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Bantuan</a>
        </div>
    </div>
</header>

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-6 sm:py-10">
    <!-- Steps header -->
    <div class="mb-4 sm:mb-6">
        <div class="flex items-center justify-center gap-1 sm:gap-4 text-xs sm:text-sm">
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-white text-black flex items-center justify-center font-medium text-xs sm:text-sm">1</span>
                <span class="text-white hidden sm:inline">Pilih Produk</span>
                <span class="text-white sm:hidden">Pilih</span>
            </div>
            <div class="text-white/30 text-xs sm:text-sm">/</div>
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-white text-black flex items-center justify-center font-medium text-xs sm:text-sm">2</span>
                <span class="text-white hidden sm:inline">Detail Pesanan</span>
                <span class="text-white sm:hidden">Detail</span>
            </div>
            <div class="text-white/30 text-xs sm:text-sm">/</div>
            <div class="flex items-center gap-1 sm:gap-2">
                <span class="h-5 w-5 sm:h-6 sm:w-6 rounded-full bg-white/10 text-white flex items-center justify-center text-xs sm:text-sm">3</span>
                <span class="text-white/70 hidden sm:inline">Pembayaran</span>
                <span class="text-white/70 sm:hidden">Bayar</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 rounded-xl border border-white/10 bg-white/5 p-3 sm:p-6">
            <h2 class="text-white text-lg sm:text-xl font-semibold mb-4 sm:mb-6">Produk Terpilih</h2>
            
            @if(isset($selectedProduct))
                <div class="rounded-lg border border-emerald-500/30 p-3 sm:p-5 bg-emerald-500/10">
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex flex-col gap-2">
                            <div class="text-white font-semibold text-base sm:text-lg leading-tight">{{ $selectedProduct->name }}</div>
                            <span class="px-2 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-medium border border-emerald-500/30 self-start w-fit">
                                Terpilih
                            </span>
                        </div>
                        @if($selectedProduct->game_type)
                            <div class="text-emerald-300 text-xs sm:text-sm font-medium">{{ $selectedProduct->game_type }}</div>
                        @endif
                        @if($selectedProduct->description)
                            <div class="text-white/70 text-xs sm:text-sm whitespace-pre-wrap leading-relaxed">{{ $selectedProduct->description }}</div>
                        @endif
                        <div class="pt-2 border-t border-emerald-500/20">
                            @if($selectedProduct->discount_active && $selectedProduct->final_price < $selectedProduct->total_price)
                                <div class="text-white/50 line-through text-xs sm:text-sm">Rp {{ number_format($selectedProduct->total_price, 0, ',', '.') }}</div>
                                <div class="text-emerald-300 font-bold text-base sm:text-lg">Rp {{ number_format($selectedProduct->final_price, 0, ',', '.') }}</div>
                                @if($selectedProduct->discount_method === 'percentage')
                                    <div class="text-yellow-300 text-xs font-medium mt-0.5">{{ number_format($selectedProduct->discount_value, 0, ',', '.') }}% off</div>
                                @else
                                    <div class="text-yellow-300 text-xs font-medium mt-0.5">Diskon Rp {{ number_format($selectedProduct->discount_value, 0, ',', '.') }}</div>
                                @endif
                            @else
                                <div class="text-emerald-300 font-bold text-base sm:text-lg">Rp {{ number_format($selectedProduct->total_price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="h-16 w-16 rounded-full bg-white/10 flex items-center justify-center mx-auto mb-4">
                        <svg class="h-8 w-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                    <div class="text-white/60 text-lg mb-2">Produk tidak ditemukan</div>
                    <div class="text-white/40 text-sm">Silakan pilih produk dari halaman sebelumnya</div>
                </div>
            @endif
        </div>

        <aside class="lg:col-span-2 space-y-4">
            <div class="rounded-xl border border-white/10 bg-white/5 p-3 sm:p-6">
                <div class="text-white/90 font-semibold text-base sm:text-lg mb-4 sm:mb-6">Detail Pesanan</div>
                
                <!-- Selected Product Summary -->
                <div id="selectedProduct" class="mb-4 sm:mb-6 p-3 sm:p-5 rounded-lg border border-white/10 bg-white/5">
                    <div class="text-white/80 font-medium mb-2 sm:mb-3 text-sm sm:text-base">Ringkasan Produk</div>
                    <div class="space-y-1.5 sm:space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs sm:text-sm">Produk:</span>
                            <span id="productName" class="text-white font-medium text-xs sm:text-sm text-right">Pilih produk di sebelah kiri</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white/60 text-xs sm:text-sm">Kategori:</span>
                            <span id="productGameType" class="text-white/60 text-xs sm:text-sm text-right">-</span>
                        </div>
                        <div class="flex justify-between items-center border-t border-white/10 pt-1.5 sm:pt-2">
                            <span class="text-emerald-300 font-medium text-xs sm:text-sm">Harga:</span>
                            <span id="productPrice" class="text-emerald-300 font-bold text-sm sm:text-lg">-</span>
                        </div>
                    </div>
                </div>

                <!-- Username Input -->
                <div class="mb-4 sm:mb-6">
                    <label class="text-white/80 font-medium text-xs sm:text-sm mb-2 sm:mb-3 block">Username Game <span class="text-red-400">*</span></label>
                    <div class="space-y-2 sm:space-y-0 sm:flex sm:gap-3">
                        <input id="username-input" type="text" required 
                               class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-md bg-black/30 border border-white/15 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 text-sm" 
                               placeholder="Masukkan username game">
                        <button id="check-username" class="w-full sm:w-auto sm:px-4 px-3 py-2.5 sm:py-3 rounded-md bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed whitespace-nowrap text-sm">
                            Cek
                        </button>
                    </div>
                    <div id="userResult" class="mt-2 sm:mt-3 hidden items-center gap-3 p-3 rounded-md border border-white/10 bg-white/5">
                        <img id="userAvatar" src="" class="w-10 h-10 rounded-md object-cover hidden" alt="" />
                        <div class="flex-1">
                            <div class="text-white/90 font-medium text-sm" id="userName"></div>
                            <div class="text-white/60 text-xs hidden" id="userId"></div>
                        </div>
                        <div id="userBadge" class="ml-auto text-xs px-2 py-1 rounded border hidden"></div>
                    </div>
                    <div id="username-error" class="mt-1 sm:mt-2 text-red-400 text-xs sm:text-sm hidden">Username wajib diisi</div>
                </div>

                <!-- Email Input -->
                <div class="mb-4 sm:mb-6">
                    <label class="text-white/80 font-medium text-xs sm:text-sm mb-2 sm:mb-3 block">Email Notifikasi <span class="text-red-400">*</span></label>
                    <input id="email-input" type="email" required 
                           class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-md bg-black/30 border border-white/15 text-white focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500/50 text-sm" 
                           placeholder="Masukkan email Anda">
                    <div id="email-error" class="mt-1 sm:mt-2 text-red-400 text-xs sm:text-sm hidden">Email wajib diisi</div>
                </div>

                <!-- Order Summary -->
                <div id="orderSummary" class="hidden">
                    <div class="h-px bg-white/10 mb-4 sm:mb-6"></div>
                    <dl class="space-y-2 sm:space-y-3 text-xs sm:text-sm">
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
                    <div class="h-px bg-white/10 my-4 sm:my-6"></div>
                    <div class="flex items-center justify-between text-lg sm:text-xl font-bold">
                        <div class="text-white/90">Total Pembayaran</div>
                        <div id="finalTotal" class="text-white">Rp 0</div>
                    </div>
                </div>

                <button id="payment-btn" class="mt-4 sm:mt-6 inline-flex items-center justify-center w-full rounded-md bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-600 disabled:cursor-not-allowed py-3 sm:py-4 text-white font-semibold text-base sm:text-lg transition-colors" disabled>
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        function showBlacklistedPopup() {
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center p-4';
            overlay.innerHTML = `
                <div class="rounded-xl border border-red-500/30 bg-gray-900 p-5 sm:p-6 w-full max-w-sm sm:max-w-md mx-4">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center flex-shrink-0 shadow-lg">
                            <svg class="w-8 h-8 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-white">Akun diblokir</h3>
                            <p class="text-white/70 text-sm mt-1">Akun anda diblokir. Silakan hubungi admin.</p>
                        </div>
                    </div>
                    <div class="flex justify-center">
                        <button class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition-colors shadow-lg">
                            Tutup
                        </button>
                    </div>
                </div>
            `;
            const btn = overlay.querySelector('button');
            btn.addEventListener('click', () => overlay.remove());
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) overlay.remove();
            });
            document.body.appendChild(overlay);
        }

        // Auto-select product on page load
        function autoSelectProduct() {
            // If there's a selectedProduct from the server, use it
            @if(isset($selectedProduct))
                selectedProduct = {
                    id: '{{ $selectedProduct->id }}',
                    name: '{{ $selectedProduct->name }}',
                    price: {{ $selectedProduct->discount_active && $selectedProduct->final_price < $selectedProduct->total_price ? $selectedProduct->final_price : $selectedProduct->total_price }},
                    originalPrice: {{ $selectedProduct->total_price }},
                    discountAmount: {{ $selectedProduct->discount_active && $selectedProduct->final_price < $selectedProduct->total_price ? ($selectedProduct->total_price - $selectedProduct->final_price) : 0 }},
                    hasDiscount: {{ $selectedProduct->discount_active && $selectedProduct->final_price < $selectedProduct->total_price ? 'true' : 'false' }},
                    gameType: '{{ $selectedProduct->game_type }}'
                };
                
                // Update sidebar
                document.getElementById('productName').textContent = selectedProduct.name;
                document.getElementById('productGameType').textContent = selectedProduct.gameType;
                
                // Display price with discount if available
                if (selectedProduct.hasDiscount) {
                    const priceElement = document.getElementById('productPrice');
                    priceElement.innerHTML = `
                        <div class="text-white/50 line-through text-xs">Rp ${new Intl.NumberFormat('id-ID').format(selectedProduct.originalPrice)}</div>
                        <div class="text-emerald-300 font-bold text-sm sm:text-lg">Rp ${new Intl.NumberFormat('id-ID').format(selectedProduct.price)}</div>
                    `;
                } else {
                    document.getElementById('productPrice').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
                }
                
                // Update order summary
                updateOrderSummary();
                validateForm();
            @endif
        }

        // Auto-select product when page loads
        autoSelectProduct();

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

        checkUsernameBtn.addEventListener('click', async function() {
            if (!validateUsername()) return;
            
            const username = usernameInput.value.trim();
            checkUsernameBtn.disabled = true;
            checkUsernameBtn.textContent = 'Mengecek...';
            
            const userResult = document.getElementById('userResult');
            const userAvatar = document.getElementById('userAvatar');
            const userName = document.getElementById('userName');
            const userId = document.getElementById('userId');
            const userBadge = document.getElementById('userBadge');
            
            try {
                const res = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`);
                const j = await res.json();
                
                if (j && j.ok && j.found) {
                    if (j.blacklisted) {
                        userAvatar.classList.add('hidden');
                        userName.textContent = 'Akun diblokir';
                        userId.textContent = '';
                        userBadge.textContent = 'Diblokir';
                        userBadge.classList.remove('hidden');
                        userBadge.classList.remove('bg-emerald-500/15','text-emerald-300','border-emerald-500/30');
                        userBadge.classList.add('bg-red-500/15','text-red-300','border-red-500/30');
                        usernameError.classList.add('hidden');
                        isUsernameValid = false;
                        showBlacklistedPopup();
                        userResult.classList.remove('hidden');
                        return;
                    }
                    if (j.avatar) {
                        userAvatar.src = j.avatar;
                        userAvatar.classList.remove('hidden');
                    } else {
                        userAvatar.classList.add('hidden');
                    }
                    userName.textContent = j.displayName || j.name || username;
                    userId.textContent = '';
                    userId.classList.add('hidden');
                    userBadge.textContent = 'Valid';
                    userBadge.classList.remove('hidden');
                    userBadge.classList.remove('bg-red-500/15','text-red-300','border-red-500/30');
                    userBadge.classList.add('bg-emerald-500/15','text-emerald-300','border-emerald-500/30');
                    usernameError.classList.add('hidden');
                    isUsernameValid = true;
                } else {
                    userAvatar.classList.add('hidden');
                    userName.textContent = 'Username tidak ditemukan';
                    userId.textContent = '';
                    userBadge.classList.add('hidden');
                    usernameError.textContent = 'Username tidak ditemukan di Roblox';
                    usernameError.classList.remove('hidden');
                    isUsernameValid = false;
                }
                userResult.classList.remove('hidden');
            } catch (e) {
                userName.textContent = 'Gagal mengecek';
                userId.textContent = '';
                userResult.classList.remove('hidden');
                userBadge.classList.add('hidden');
                usernameError.textContent = 'Gagal mengecek username. Silakan coba lagi.';
                usernameError.classList.remove('hidden');
                isUsernameValid = false;
            } finally {
                checkUsernameBtn.disabled = false;
                checkUsernameBtn.textContent = 'Cek';
                validateForm();
            }
        });
        
        // Allow Enter key to trigger check
        usernameInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                checkUsernameBtn.click();
            }
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
            
            // Display original price with strikethrough if discount exists
            if (selectedProduct.hasDiscount) {
                const orderTotalEl = document.getElementById('orderTotal');
                orderTotalEl.innerHTML = `
                    <span class="text-white/50 line-through text-xs mr-2">Rp ${new Intl.NumberFormat('id-ID').format(selectedProduct.originalPrice)}</span>
                    <span class="text-white font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(selectedProduct.price)}</span>
                `;
                
                // Add discount row if not exists
                let discountRow = document.getElementById('productDiscountRow');
                if (!discountRow) {
                    const orderSummary = document.getElementById('orderSummary');
                    const dl = orderSummary.querySelector('dl');
                    const firstItem = dl.querySelector('div');
                    discountRow = document.createElement('div');
                    discountRow.id = 'productDiscountRow';
                    discountRow.className = 'flex items-center justify-between';
                    discountRow.innerHTML = `
                        <dt class="text-white/60">Diskon Produk</dt>
                        <dd class="text-yellow-300">-Rp ${new Intl.NumberFormat('id-ID').format(selectedProduct.discountAmount)}</dd>
                    `;
                    dl.insertBefore(discountRow, firstItem.nextSibling);
                } else {
                    discountRow.querySelector('dd').textContent = '-Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.discountAmount);
                    discountRow.classList.remove('hidden');
                }
            } else {
                document.getElementById('orderTotal').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(selectedProduct.price);
                const discountRow = document.getElementById('productDiscountRow');
                if (discountRow) {
                    discountRow.classList.add('hidden');
                }
            }
            
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
