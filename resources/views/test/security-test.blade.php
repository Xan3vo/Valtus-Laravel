<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Test - Valtus</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f0f23 0%, #1a1a2e 50%, #16213e 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white/5 backdrop-blur-lg border border-white/10 rounded-xl p-6 shadow-2xl">
            <div class="text-center mb-6">
                <h1 class="text-3xl font-bold text-white mb-2">🔒 Security Test Page</h1>
                <p class="text-white/70">Testing Order Creation & Spreadsheet Integration</p>
            </div>

            <!-- Username Check Section -->
            <div class="mb-6">
                <label class="block text-white/90 font-medium mb-2">Username Roblox</label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        id="username-input" 
                        class="flex-1 px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        placeholder="Masukkan username Roblox"
                    />
                    <button 
                        id="check-username-btn" 
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed"
                    >
                        Cek Username
                    </button>
                </div>
                <div id="username-result" class="mt-3 hidden"></div>
            </div>

            <!-- Purchase Method Selection -->
            <div class="mb-6">
                <label class="block text-white/90 font-medium mb-3">Pilih Metode Pembelian</label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="block cursor-pointer">
                        <input type="radio" name="purchaseMethod" value="gamepass" id="radioGamepass" checked class="hidden peer">
                        <div class="p-4 rounded-lg border-2 border-white/15 bg-white/5 peer-checked:border-blue-400 peer-checked:bg-blue-500/10 transition-all duration-200 hover:border-blue-400/50 hover:bg-blue-500/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-sm text-white">Via Gamepass</div>
                                    <div class="text-xs text-white/70 mt-0.5">Buat gamepass sendiri</div>
                                </div>
                            </div>
                        </div>
                    </label>
                    <label class="block cursor-pointer">
                        <input type="radio" name="purchaseMethod" value="group" id="radioGroup" class="hidden peer">
                        <div class="p-4 rounded-lg border-2 border-white/15 bg-white/5 peer-checked:border-purple-400 peer-checked:bg-purple-500/10 transition-all duration-200 hover:border-purple-400/50 hover:bg-purple-500/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <div class="font-semibold text-sm text-white">Via Group</div>
                                    <div class="text-xs text-white/70 mt-0.5">Bergabung dengan group</div>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Amount Input -->
            <div class="mb-6">
                <label class="block text-white/90 font-medium mb-2">Jumlah Robux</label>
                <input 
                    type="number" 
                    id="amount-input" 
                    min="{{ $robuxMinOrder }}"
                    value="{{ $robuxMinOrder }}"
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                    placeholder="Minimal {{ $robuxMinOrder }} Robux"
                />
                <p class="text-white/60 text-sm mt-1">Harga per 100: <span id="price-per-100-display">Rp {{ number_format($robuxPricePer100, 0, ',', '.') }}</span></p>
            </div>

            <!-- Email Input -->
            <div class="mb-6">
                <label class="block text-white/90 font-medium mb-2">Email</label>
                <input 
                    type="email" 
                    id="email-input" 
                    class="w-full px-4 py-3 rounded-lg bg-black/30 border border-white/20 text-white placeholder-white/40 focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                    placeholder="test@example.com"
                />
            </div>

            <!-- Group Info Section (Hidden by default) -->
            <div id="group-info-section" class="mb-6 hidden rounded-lg border border-purple-400/30 p-4 bg-purple-500/10">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-white font-medium mb-1">Pembelian via Group</div>
                        <div class="text-purple-200/80 text-sm">Robux akan dikirim melalui group {{ $groupName ?? 'Valtus Studios' }}. Pastikan Anda sudah bergabung minimal {{ $minMembershipDays ?? 14 }} hari.</div>
                    </div>
                </div>
            </div>

            <!-- Gamepass Check Section -->
            <div id="gamepass-section" class="mb-6">
                <button 
                    id="check-gamepass-btn" 
                    class="w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed"
                    disabled
                >
                    Cek Gamepass
                </button>
                <div id="gamepass-result" class="mt-3 hidden"></div>
            </div>

            <!-- Group Membership Check Section -->
            <div id="group-section" class="mb-6 hidden">
                <button 
                    id="check-group-btn" 
                    class="w-full px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-medium transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed"
                    disabled
                >
                    Cek Keanggotaan Group
                </button>
                <div id="group-result" class="mt-3 hidden"></div>
            </div>

            <!-- Create Order Button -->
            <div class="mb-6">
                <button 
                    id="create-order-btn" 
                    class="w-full px-6 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-lg transition-colors disabled:bg-gray-600 disabled:cursor-not-allowed"
                    disabled
                >
                    Buat Order (Test Midtrans)
                </button>
                <div id="order-result" class="mt-3 hidden"></div>
            </div>

            <!-- Info Box -->
            <div class="bg-yellow-500/10 border border-yellow-500/30 rounded-lg p-4">
                <p class="text-yellow-200 text-sm">
                    <strong>⚠️ Testing Mode:</strong> Order akan langsung dibuat dengan:
                    <br>• Payment Status: <strong>Completed</strong>
                    <br>• Payment Gateway: <strong>Midtrans</strong>
                    <br>• Order Status: <strong>Pending</strong>
                    <br>• Purchase Method: <strong id="info-purchase-method">Gamepass</strong>
                    <br>• Akan masuk ke Spreadsheet otomatis
                </p>
            </div>
        </div>
    </div>

    <!-- Gamepass Confirmation Modal -->
    <div id="gamepass-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-gray-900 border border-white/20 rounded-xl max-w-md w-full p-6">
            <h3 class="text-xl font-semibold text-white mb-4">Gamepass Tersedia!</h3>
            <p class="text-white/80 mb-6">Gamepass untuk jumlah Robux ini sudah tersedia. Lanjutkan membuat order?</p>
            <div class="flex gap-3">
                <button id="cancel-gamepass-btn" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    Batal
                </button>
                <button id="continue-gamepass-btn" class="flex-1 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors">
                    Lanjutkan
                </button>
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const groupRobuxPricePer100 = {{ (int) ($groupRobuxPricePer100 ?? 10000) }};
        const robuxPricePer100 = {{ (int) $robuxPricePer100 }};
        const groupName = '{{ addslashes($groupName ?? "Valtus Studios") }}';
        const minMembershipDays = {{ (int) ($minMembershipDays ?? 14) }};
        
        let usernameValid = false;
        let gamepassChecked = false;
        let gamepassLink = null;
        let groupMembershipChecked = false;
        let selectedMethod = 'gamepass';

        // Check Username
        document.getElementById('check-username-btn').addEventListener('click', async function() {
            const username = document.getElementById('username-input').value.trim();
            const resultDiv = document.getElementById('username-result');
            const btn = this;

            if (!username) {
                resultDiv.innerHTML = '<div class="text-red-400">Username wajib diisi</div>';
                resultDiv.classList.remove('hidden');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengecek...';

            try {
                const response = await fetch(`/keamanan/valtus/2879165/check-username?username=${encodeURIComponent(username)}`);
                const data = await response.json();

                if (data.ok && data.found) {
                    usernameValid = true;
                    // Build avatar HTML (same style as product-order.blade.php)
                    let avatarHtml = '';
                    if (data.avatar) {
                        avatarHtml = `<img src="${data.avatar}" class="w-16 h-16 rounded-full border-2 border-emerald-500/50 shadow-lg object-cover" alt="Avatar" onerror="this.onerror=null; this.src=''; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-600 flex items-center justify-center hidden">
                                <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>`;
                    } else {
                        avatarHtml = `<div class="flex-shrink-0 w-16 h-16 rounded-full bg-gray-600 flex items-center justify-center">
                            <svg class="w-8 h-8 text-white/60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>`;
                    }
                    
                    resultDiv.innerHTML = `
                        <div class="flex items-center gap-4 p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg">
                            <div class="flex-shrink-0 relative">
                                ${avatarHtml}
                            </div>
                            <div class="flex-1">
                                <div class="text-emerald-300 font-bold text-base mb-1">✓ Username Valid</div>
                                <div class="text-white font-medium text-lg">${data.displayName || data.name}</div>
                                <div class="text-white/60 text-sm mt-1">@${data.name}</div>
                            </div>
                        </div>
                    `;
                    // Enable buttons based on selected method
                    if (selectedMethod === 'gamepass') {
                        document.getElementById('check-gamepass-btn').disabled = false;
                    } else {
                        document.getElementById('check-group-btn').disabled = false;
                    }
                    updateCreateOrderButton();
                } else {
                    usernameValid = false;
                    resultDiv.innerHTML = '<div class="text-red-400 p-3 bg-red-500/10 border border-red-500/30 rounded-lg">✗ Username tidak ditemukan</div>';
                    document.getElementById('check-gamepass-btn').disabled = true;
                    document.getElementById('check-group-btn').disabled = true;
                }
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultDiv.innerHTML = '<div class="text-red-400">Gagal mengecek username</div>';
                resultDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cek Username';
            }
        });

        // Check Gamepass
        document.getElementById('check-gamepass-btn').addEventListener('click', async function() {
            const username = document.getElementById('username-input').value.trim();
            const amount = parseInt(document.getElementById('amount-input').value) || 0;
            const resultDiv = document.getElementById('gamepass-result');
            const btn = this;

            if (!username || !usernameValid) {
                alert('Silakan cek username terlebih dahulu');
                return;
            }

            if (amount < {{ $robuxMinOrder }}) {
                alert(`Minimal {{ $robuxMinOrder }} Robux`);
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengecek Gamepass...';

            try {
                const response = await fetch(`/keamanan/valtus/2879165/check-gamepass?username=${encodeURIComponent(username)}&amount=${amount}`);
                const data = await response.json();

                if (data.ok) {
                    if (data.found) {
                        gamepassChecked = true;
                        gamepassLink = data.gamepass_link;
                        let gamepassInfo = `
                            <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 rounded-lg mb-3">
                                <div class="text-emerald-300 font-medium">✓ Gamepass Tersedia!</div>
                                <div class="text-white/70 text-sm mt-1">Harga: R$${data.required_price}</div>
                                ${data.gamepass_name ? `<div class="text-white/60 text-xs mt-1">Nama: ${data.gamepass_name}</div>` : ''}
                            </div>
                        `;
                        
                        // Show all gamepasses for testing
                        if (data.all_gamepasses && data.all_gamepasses.length > 0) {
                            gamepassInfo += `
                                <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                                    <div class="text-blue-300 font-medium text-sm mb-2">📋 Semua Gamepass User (${data.all_gamepasses.length}):</div>
                                    <div class="max-h-48 overflow-y-auto space-y-2">
                                        ${data.all_gamepasses.map(gp => `
                                            <div class="flex items-center justify-between p-2 bg-white/5 rounded text-xs">
                                                <div class="flex-1">
                                                    <div class="text-white font-medium">${gp.name || 'Unknown'}</div>
                                                    <div class="text-white/60">Harga: R$${gp.price}</div>
                                                </div>
                                                <div class="text-right">
                                                    ${gp.isForSale ? '<span class="text-green-400">✓ For Sale</span>' : '<span class="text-red-400">✗ Not For Sale</span>'}
                                                    ${gp.link ? `<a href="${gp.link}" target="_blank" class="text-blue-400 hover:underline ml-2">Link</a>` : ''}
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;
                        } else {
                            gamepassInfo += `
                                <div class="p-3 bg-gray-500/10 border border-gray-500/30 rounded-lg">
                                    <div class="text-gray-300 text-sm">User tidak memiliki gamepass</div>
                                </div>
                            `;
                        }
                        
                        resultDiv.innerHTML = gamepassInfo;
                        updateCreateOrderButton();
                        // Show modal
                        document.getElementById('gamepass-modal').classList.remove('hidden');
                    } else {
                        gamepassChecked = false;
                        let notFoundInfo = `
                            <div class="p-3 bg-yellow-500/10 border border-yellow-500/30 rounded-lg mb-3">
                                <div class="text-yellow-300 font-medium">⚠ Gamepass Tidak Ditemukan</div>
                                <div class="text-white/70 text-sm mt-1">Buat gamepass dengan harga R$${data.required_price}</div>
                            </div>
                        `;
                        
                        // Still show all gamepasses even if not found
                        if (data.all_gamepasses && data.all_gamepasses.length > 0) {
                            notFoundInfo += `
                                <div class="p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                                    <div class="text-blue-300 font-medium text-sm mb-2">📋 Semua Gamepass User (${data.all_gamepasses.length}):</div>
                                    <div class="max-h-48 overflow-y-auto space-y-2">
                                        ${data.all_gamepasses.map(gp => `
                                            <div class="flex items-center justify-between p-2 bg-white/5 rounded text-xs">
                                                <div class="flex-1">
                                                    <div class="text-white font-medium">${gp.name || 'Unknown'}</div>
                                                    <div class="text-white/60">Harga: R$${gp.price}</div>
                                                </div>
                                                <div class="text-right">
                                                    ${gp.isForSale ? '<span class="text-green-400">✓ For Sale</span>' : '<span class="text-red-400">✗ Not For Sale</span>'}
                                                    ${gp.link ? `<a href="${gp.link}" target="_blank" class="text-blue-400 hover:underline ml-2">Link</a>` : ''}
                                                </div>
                                            </div>
                                        `).join('')}
                                    </div>
                                </div>
                            `;
                        }
                        
                        resultDiv.innerHTML = notFoundInfo;
                        updateCreateOrderButton(); // Still allow creating order
                    }
                } else {
                    resultDiv.innerHTML = `<div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg"><div class="text-red-300">Error: ${data.error || 'Unknown error'}</div></div>`;
                }
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultDiv.innerHTML = '<div class="text-red-400">Gagal mengecek gamepass</div>';
                resultDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cek Gamepass';
            }
        });

        // Method Selection Handlers
        document.getElementById('radioGamepass').addEventListener('change', function() {
            if (this.checked) {
                selectedMethod = 'gamepass';
                document.getElementById('gamepass-section').classList.remove('hidden');
                document.getElementById('group-section').classList.add('hidden');
                document.getElementById('group-info-section').classList.add('hidden');
                updatePriceDisplay();
                updateCreateOrderButton();
                // Enable/disable buttons based on username validation
                if (usernameValid) {
                    document.getElementById('check-gamepass-btn').disabled = false;
                } else {
                    document.getElementById('check-gamepass-btn').disabled = true;
                }
                document.getElementById('check-group-btn').disabled = true;
            }
        });

        document.getElementById('radioGroup').addEventListener('change', function() {
            if (this.checked) {
                selectedMethod = 'group';
                document.getElementById('gamepass-section').classList.add('hidden');
                document.getElementById('group-section').classList.remove('hidden');
                document.getElementById('group-info-section').classList.remove('hidden');
                updatePriceDisplay();
                updateCreateOrderButton();
                // Enable/disable buttons based on username validation
                if (usernameValid) {
                    document.getElementById('check-group-btn').disabled = false;
                } else {
                    document.getElementById('check-group-btn').disabled = true;
                }
                document.getElementById('check-gamepass-btn').disabled = true;
            }
        });

        // Update price display based on method
        function updatePriceDisplay() {
            const pricePer100 = selectedMethod === 'group' ? groupRobuxPricePer100 : robuxPricePer100;
            document.getElementById('price-per-100-display').textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(pricePer100);
        }

        // Update create order button state
        function updateCreateOrderButton() {
            const btn = document.getElementById('create-order-btn');
            const username = document.getElementById('username-input').value.trim();
            const email = document.getElementById('email-input').value.trim();
            const amount = parseInt(document.getElementById('amount-input').value) || 0;
            const infoPurchaseMethod = document.getElementById('info-purchase-method');
            
            let canCreate = false;
            
            if (selectedMethod === 'gamepass') {
                // For gamepass: need username valid and gamepass checked (or at least attempted)
                canCreate = usernameValid && username && email && amount >= {{ $robuxMinOrder }};
            } else if (selectedMethod === 'group') {
                // For group: need username valid (group membership check is optional for testing)
                canCreate = usernameValid && username && email && amount >= {{ $robuxMinOrder }};
            }
            
            btn.disabled = !canCreate;
            
            // Update button text based on method
            if (selectedMethod === 'group') {
                btn.textContent = 'Buat Order (Test Group)';
                if (infoPurchaseMethod) infoPurchaseMethod.textContent = 'Group';
            } else {
                btn.textContent = 'Buat Order (Test Midtrans)';
                if (infoPurchaseMethod) infoPurchaseMethod.textContent = 'Gamepass';
            }
        }

        // Check Group Membership
        document.getElementById('check-group-btn').addEventListener('click', async function() {
            const username = document.getElementById('username-input').value.trim();
            const resultDiv = document.getElementById('group-result');
            const btn = this;

            if (!username || !usernameValid) {
                alert('Silakan cek username terlebih dahulu');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Mengecek...';

            try {
                const response = await fetch(`/keamanan/valtus/2879165/check-group-membership?username=${encodeURIComponent(username)}`);
                const data = await response.json();

                if (data.ok) {
                    if (data.is_member) {
                        groupMembershipChecked = true;
                        const membershipDays = data.membership_days || 0;
                        const minDays = data.min_membership_days || minMembershipDays;
                        const currentGroupName = data.group_name || groupName;
                        
                        const meetsRequirement = membershipDays >= minDays;
                        const statusBgClass = meetsRequirement ? 'bg-emerald-500/10' : 'bg-yellow-500/10';
                        const statusBorderClass = meetsRequirement ? 'border-emerald-500/30' : 'border-yellow-500/30';
                        const statusTextClass = meetsRequirement ? 'text-emerald-300' : 'text-yellow-300';
                        const statusText = meetsRequirement ? '✓ Memenuhi Syarat' : '⚠ Belum Memenuhi Syarat';
                        const statusMessage = meetsRequirement 
                            ? `Anda sudah bergabung selama ${membershipDays} hari. Memenuhi syarat minimal ${minDays} hari.`
                            : `Anda sudah bergabung selama ${membershipDays} hari. Belum memenuhi syarat minimal ${minDays} hari.`;
                        
                        resultDiv.innerHTML = `
                            <div class="p-3 ${statusBgClass} border ${statusBorderClass} rounded-lg">
                                <div class="${statusTextClass} font-medium">${statusText}</div>
                                <div class="text-white/70 text-sm mt-1">${statusMessage}</div>
                                <div class="text-white/60 text-xs mt-2">Group: ${currentGroupName}</div>
                            </div>
                        `;
                        updateCreateOrderButton();
                    } else {
                        groupMembershipChecked = false;
                        const currentGroupName = data.group_name || groupName;
                        resultDiv.innerHTML = `
                            <div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg">
                                <div class="text-red-300 font-medium">✗ Bukan Member Group</div>
                                <div class="text-white/70 text-sm mt-1">Anda belum bergabung dengan group ${currentGroupName}.</div>
                            </div>
                        `;
                        updateCreateOrderButton(); // Still allow creating order for testing
                    }
                } else {
                    resultDiv.innerHTML = `<div class="p-3 bg-red-500/10 border border-red-500/30 rounded-lg"><div class="text-red-300">Error: ${data.error || 'Unknown error'}</div></div>`;
                }
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultDiv.innerHTML = '<div class="text-red-400">Gagal mengecek keanggotaan group</div>';
                resultDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Cek Keanggotaan Group';
            }
        });

        // Gamepass Modal Handlers
        document.getElementById('cancel-gamepass-btn').addEventListener('click', function() {
            document.getElementById('gamepass-modal').classList.add('hidden');
        });

        document.getElementById('continue-gamepass-btn').addEventListener('click', function() {
            document.getElementById('gamepass-modal').classList.add('hidden');
            document.getElementById('create-order-btn').click();
        });

        // Add event listeners for email and amount to update button state
        document.getElementById('email-input').addEventListener('input', updateCreateOrderButton);
        document.getElementById('amount-input').addEventListener('input', updateCreateOrderButton);
        
        // Initial button state update
        updateCreateOrderButton();

        // Create Order
        document.getElementById('create-order-btn').addEventListener('click', async function() {
            const username = document.getElementById('username-input').value.trim();
            const amount = parseInt(document.getElementById('amount-input').value) || 0;
            const email = document.getElementById('email-input').value.trim();
            const resultDiv = document.getElementById('order-result');
            const btn = this;

            if (!username || !email || amount < {{ $robuxMinOrder }}) {
                alert('Lengkapi semua field dengan benar');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Membuat Order...';

            try {
                const response = await fetch('/keamanan/valtus/2879165/create-order', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        username: username,
                        email: email,
                        amount: amount,
                        gamepass_link: gamepassLink,
                        purchase_method: selectedMethod,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    resultDiv.innerHTML = `
                        <div class="p-4 bg-emerald-500/10 border border-emerald-500/30 rounded-lg">
                            <div class="text-emerald-300 font-bold text-lg mb-2">✓ Order Berhasil Dibuat!</div>
                            <div class="text-white/80 space-y-1 text-sm">
                                <div><strong>Order ID:</strong> ${data.order_id}</div>
                                <div><strong>Username:</strong> ${data.order.username}</div>
                                <div><strong>Amount:</strong> ${data.order.amount} Robux</div>
                                <div><strong>Total:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.order.total_amount)}</div>
                                <div><strong>Payment Status:</strong> ${data.order.payment_status}</div>
                                <div><strong>Order Status:</strong> ${data.order.order_status}</div>
                                <div><strong>Payment Gateway:</strong> ${data.order.payment_gateway}</div>
                                <div><strong>Purchase Method:</strong> ${data.order.purchase_method || selectedMethod}</div>
                            </div>
                            <div class="mt-3 text-emerald-200 text-xs">
                                Order akan masuk ke Spreadsheet dalam beberapa detik...
                            </div>
                        </div>
                    `;
                } else {
                    resultDiv.innerHTML = `
                        <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                            <div class="text-red-300 font-bold">✗ Gagal Membuat Order</div>
                            <div class="text-white/80 text-sm mt-1">${data.error || 'Unknown error'}</div>
                        </div>
                    `;
                }
                resultDiv.classList.remove('hidden');
            } catch (error) {
                resultDiv.innerHTML = `
                    <div class="p-4 bg-red-500/10 border border-red-500/30 rounded-lg">
                        <div class="text-red-300 font-bold">✗ Error</div>
                        <div class="text-white/80 text-sm mt-1">${error.message}</div>
                    </div>
                `;
                resultDiv.classList.remove('hidden');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Buat Order (Test Midtrans)';
            }
        });
    </script>
</body>
</html>

