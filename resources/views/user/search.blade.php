@extends('layouts.app')
@section('title', 'Cari Username')
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
        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center gap-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beranda</a>
            <a href="{{ route('user.search') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Beli Robux</a>
            <a href="{{ route('user.status') }}" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Cek Pesanan</a>
            <a href="#" onclick="showHelpModal()" class="text-gray-200 hover:text-white transition-colors duration-200 font-medium">Bantuan</a>
        </nav>
        
        <!-- Desktop Actions -->
        <div class="hidden md:flex items-center gap-4">
            <a href="{{ route('user.status') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-white/20 hover:border-white/40 hover:bg-white/5 transition-all duration-200 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Cek Status
            </a>
        </div>
        
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" type="button" class="md:hidden p-2 rounded-lg hover:bg-white/10 transition-colors" onclick="toggleMobileMenu()" style="z-index: 9999; position: relative;">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="md:hidden hidden border-t border-white/10 bg-gray-900/95 backdrop-blur-md">
        <div class="px-4 py-4 space-y-4">
            <a href="{{ route('home') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beranda</a>
            <a href="{{ route('user.search') }}" class="block text-gray-200 hover:text-white transition-colors duration-200 font-medium py-2">Beli Robux</a>
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
</header>

<main class="max-w-5xl mx-auto px-6 py-12">
    <!-- Subheader -->
    <section class="rounded-xl border border-white/10 bg-gradient-to-br from-white/5 to-white/0 p-5 mb-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl md:text-3xl font-semibold text-white">Top Up Robux</h1>
            <div class="flex items-center gap-3">
                <a href="#" onclick="showCaraBeliVideo()" class="hidden sm:inline-flex items-center gap-2 rounded-md border border-white/15 px-4 py-2 text-sm text-white/90 hover:bg-white/5">
                    Cara Beli
                </a>
                <div class="text-sm text-white/70">Minimal order: <span class="text-white font-medium">{{ $robuxMinOrder }} RBX</span></div>
            </div>
        </div>
    </section>

    <div class="grid lg:grid-cols-3 gap-6">
        <!-- Left: Form -->
        <div class="lg:col-span-2 rounded-xl border border-white/15 bg-white/5 p-5">
            <div class="flex items-center gap-2 text-sm">
                <button type="button" id="tabCustom" class="px-4 py-1.5 rounded-md bg-white/10 text-white shadow-sm">Kustom</button>
                <button type="button" id="tabQuick" class="px-4 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white">Pilih Cepat</button>
            </div>

            <form method="GET" action="{{ route('user.amount') }}" class="mt-5 grid gap-4">
                <input type="hidden" name="amount" id="amountInput" value="0">
        <label class="block">
                    <span class="text-white/70">Username Roblox</span>
                    <div class="mt-2 flex items-center gap-3">
                        <input required name="username" id="usernameInput" class="flex-1 px-4 py-3 rounded-md bg-black/30 border border-white/15 text-white placeholder-white/30" placeholder="mis: builderman" />
                        <button type="button" id="checkBtn" class="px-4 py-3 rounded-md border border-white/15 hover:bg-white/5 text-sm">Cek</button>
                    </div>
                    <div id="userResult" class="mt-3 hidden items-center gap-3 p-3 rounded-md border border-white/10 bg-white/5">
                        <img id="userAvatar" src="" class="w-10 h-10 rounded-md object-cover hidden" alt="" />
                        <div>
                            <div class="text-white/90 font-medium" id="userName"></div>
                            <div class="text-white/60 text-xs hidden" id="userId"></div>
                        </div>
                        <div id="userBadge" class="ml-auto text-xs px-2 py-1 rounded border hidden"></div>
                    </div>
        </label>

                <!-- Custom amount -->
                <div id="customWrap" class="grid gap-2">
                    <span class="text-white/70 text-sm">Jumlah Robux</span>
                    <div class="flex items-center gap-2">
                        <div class="flex items-center gap-2 rounded-md border border-white/15 bg-black/30 px-3 py-2 focus-within:ring-1 focus-within:ring-emerald-400">
                            <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                            <input type="number" min="{{ $robuxMinOrder }}" step="1" id="customAmount" class="bg-transparent outline-none text-white w-32" value="0" placeholder="{{ $robuxMinOrder }}" />
                        </div>
                        <div class="text-sm text-white/70">Harga per 100: <span class="text-white">Rp {{ number_format($robuxPricePer100,0,',','.') }}</span></div>
                    </div>
                </div>

                <!-- Quick picks -->
                <div id="quickWrap" class="hidden">
                    <div class="mt-1 text-white/70 text-sm">Pilih nominal cepat:</div>
                    <div class="mt-3 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        @php $list = [100, 500, 1000, 2000, 5000, 10000, 25000, 50000]; @endphp
                        @foreach($list as $q)
                        <button type="button" class="chip rounded-lg border border-white/15 bg-white/5 hover:bg-white/10 transition p-4 text-left">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <img src="/assets/images/robux.png" class="h-4 w-4"> 
                                    <div class="font-medium">{{ $q }} RBX</div>
                                </div>
                                
                            </div>
                            <div class="mt-1 text-white/80 text-sm">Rp {{ number_format($robuxPricePer100 * ($q/100), 0, ',', '.') }}</div>
                            <input type="hidden" value="{{ $q }}" data-val="{{ $q }}">
                        </button>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-md border border-white/10 p-4 bg-white/5 flex items-center justify-between">
                    <div class="text-white/70 text-sm">Total</div>
                    <div class="text-lg font-semibold" id="totalPrice">Rp 0</div>
                </div>

                <div class="text-sm text-white/60">Minimal {{ $robuxMinOrder }} RBX</div>
    </form>
        </div>

        <!-- Right: Summary -->
        <div class="space-y-4 max-w-sm w-full lg:ml-auto">
            <div class="rounded-xl border border-white/15 bg-white/5 p-5">
                <div class="text-sm text-white/70">Total Biaya</div>
                <div class="mt-1 text-2xl font-semibold" id="summaryTotal">Rp 0</div>
                <div class="mt-4">
                    <button id="proceedBtnRight" type="button" disabled class="w-full inline-flex items-center justify-center gap-2 rounded-md bg-white text-black py-3 hover:bg-gray-100 opacity-50 cursor-not-allowed">
                        <img src="/assets/images/robux.png" class="h-4 w-4" alt="Robux">
                        Lanjutkan Pembelian
                    </button>
                </div>
            </div>
            <div class="rounded-xl border border-white/15 bg-emerald-500/10 p-5">
                <div class="text-sm text-emerald-300 font-medium flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M9 12.75L11.25 15 15 9.75"/><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM3.75 12a8.25 8.25 0 1116.5 0 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd"/></svg>
                    Garansi Robux Masuk & Aman
                </div>
                <div class="mt-2 text-white/80 text-sm">Pembelian diproses otomatis. Bila ada kendala, dana aman.</div>
            </div>
            <a href="#" onclick="showHelpModal()" class="block rounded-xl border border-white/15 bg-white/5 p-4 hover:border-white/30 hover:bg-white/10 transition">
                <div class="flex items-center gap-3">
                    <img src="/assets/images/cs.png" alt="Customer Service" class="w-12 h-12 rounded-md object-cover">
                <div>
                        <div class="text-sm text-white/80 font-medium">Punya pertanyaan?</div>
                        <div class="mt-1 text-white/60 text-sm">Customer Service siap membantu kapan saja.</div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        (function(){
            const pricePer100 = {{ (int) $robuxPricePer100 }};
            const minOrder = {{ (int) $robuxMinOrder }};
            const checkBtn = document.getElementById('checkBtn');
            const usernameInput = document.getElementById('usernameInput');
            const userResult = document.getElementById('userResult');
            const userAvatar = document.getElementById('userAvatar');
            const userName = document.getElementById('userName');
            const userId = document.getElementById('userId');
            const userBadge = document.getElementById('userBadge');
            const proceedBtnRight = document.getElementById('proceedBtnRight');
            const tabCustom = document.getElementById('tabCustom');
            const tabQuick = document.getElementById('tabQuick');
            const customWrap = document.getElementById('customWrap');
            const quickWrap = document.getElementById('quickWrap');
            const amtInput = document.getElementById('amountInput');
            const customAmount = document.getElementById('customAmount');
            const totalPrice = document.getElementById('totalPrice');
            const summaryAmount = document.getElementById('summaryAmount');

            function setMode(mode){
                if(mode==='quick'){
                    tabCustom.className = 'px-3 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white';
                    tabQuick.className = 'px-3 py-1.5 rounded-md bg-white/10 text-white';
                    customWrap.classList.add('hidden');
                    quickWrap.classList.remove('hidden');
                } else {
                    tabCustom.className = 'px-3 py-1.5 rounded-md bg-white/10 text-white';
                    tabQuick.className = 'px-3 py-1.5 rounded-md border border-white/15 text-white/80 hover:text-white';
                    customWrap.classList.remove('hidden');
                    quickWrap.classList.add('hidden');
                }
            }

            function updateTotal(){
                const amt = Math.max(minOrder, parseInt(amtInput.value||0,10));
                const price = pricePer100 * (amt/100);
                totalPrice.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(price);
                document.getElementById('summaryTotal').textContent = totalPrice.textContent;
                summaryAmount.textContent = amt + ' RBX';
            }

            const chips = Array.from(document.querySelectorAll('.chip'));
            function highlightChip(active){
                chips.forEach(c=>{
                    c.classList.remove('ring-2','ring-emerald-400','bg-white/10');
                });
                if(active){ active.classList.add('ring-2','ring-emerald-400','bg-white/10'); }
            }
            chips.forEach(btn=>{
                btn.addEventListener('click',()=>{
                    highlightChip(btn);
                    const val = parseInt((btn.querySelector('input[type="hidden"]').getAttribute('data-val')),10);
                    amtInput.value = val;
                    updateTotal();
                    // Clear session amount when user manually changes
                    sessionStorage.setItem('amount_changed', 'true');
                });
            });

            customAmount.addEventListener('input',()=>{
                amtInput.value = customAmount.value;
                updateTotal();
                // Clear session amount when user manually changes
                sessionStorage.setItem('amount_changed', 'true');
            });

            // tab switching
            tabCustom.addEventListener('click', ()=>{
                if(!customWrap.classList.contains('hidden')) return;
                // ensure minimal value in custom input
                if(!amtInput.value || parseInt(amtInput.value,10) < minOrder){
                    amtInput.value = minOrder;
                    customAmount.value = minOrder;
                } else {
                    customAmount.value = amtInput.value;
                }
                setMode('custom');
                updateTotal();
            });
            tabQuick.addEventListener('click', ()=>{
                setMode('quick');
                // pilih chip minimal order secara default
                const defaultChip = chips.find(c => parseInt(c.getAttribute('data-val'),10) === minOrder) || chips[0];
                if(defaultChip){ defaultChip.click(); }
            });

            // proceed buttons trigger form submit
            const form = document.querySelector('form');
            document.getElementById('proceedBtnRight').addEventListener('click', async ()=> {
                // Popup loading modal
                const overlay = document.createElement('div');
                overlay.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                overlay.innerHTML = `
                    <div class="rounded-xl border border-white/15 bg-white/5 p-6 text-center w-[min(90vw,520px)]">
                        <div class="text-lg font-semibold text-white">Mencari GamePass...</div>
                        <div class="mt-2 text-white/70 text-sm">Kami sedang mengecek apakah tersedia gamepass sesuai harga.</div>
                        <div class="mt-5 inline-block h-5 w-5 animate-spin rounded-full border-2 border-white/30 border-t-white"></div>
                    </div>`;
                document.body.appendChild(overlay);

                try{
                    const amt = Math.max(minOrder, parseInt(amtInput.value||0,10));
                    const username = (usernameInput.value||'').trim();

                    // Ensure username was validated previously by checking result visible and badge not hidden
                    if(userResult.classList.contains('hidden') || userBadge.classList.contains('hidden')){
                        throw new Error('USERNAME_NOT_VERIFIED');
                    }

                    // We need userId -> recheck silently via API (cheap)
                    const check = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(username)}`);
                    const cj = await check.json();
                    if(!(cj && cj.ok && cj.found && cj.id)) throw new Error('USERNAME_NOT_FOUND');

                    const gp = await fetch(`{{ route('api.roblox.gamepass') }}?userId=${cj.id}&amount=${amt}`);
                    const gj = await gp.json();
                    overlay.remove();

                    if(gj && gj.ok && gj.found){
                        // Store gamepass_link in session first
                        if(gj.gamepass_link) {
                            await fetch('/user/store-gamepass-link', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    gamepass_link: gj.gamepass_link
                                })
                            });
                        }
                        
                        // Store username and current amount in session
                        await Promise.all([
                            fetch('/user/store-username', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ username: username })
                            }),
                            fetch('/user/store-amount', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({ amount: amt })
                            })
                        ]);
                        
                        // Continue to next step (redirect to payment without params)
                        window.location.href = `{{ route('user.payment') }}`;
                        return;
                    }

                    // Show instruction modal if not found
                    const modal = document.createElement('div');
                    modal.className = 'fixed inset-0 bg-black/60 backdrop-blur-sm z-50 flex items-center justify-center';
                    modal.innerHTML = `
                        <div class="rounded-xl border border-white/15 bg-[#111827] p-6 w-[min(92vw,680px)]">
                            <div class="flex items-center justify-between">
                                <div class="text-xl font-semibold text-white">Buat dan Atur Gamepass</div>
                                <button class="text-white/60 hover:text-white" id="modalClose">✕</button>
                            </div>
                                <div class="mt-4 text-center">
                                    <div class="text-2xl font-semibold text-white">Buat Gamepass Seharga <span class="text-emerald-400">R$${(gj.requiredPrice || Math.ceil(amt * (100 / 70)))}</span></div>
                                    <p class="mt-2 text-white/70">Buat gamepass di Roblox dengan harga di atas agar setelah Roblox potong 30%, Anda mendapat <span class="text-emerald-400">${amt} Robux</span> yang pas.</p>
                                    <p class="mt-1 text-white/60 text-sm">Harga yang Anda bayar tetap sama: <span class="text-white font-medium">Rp ${new Intl.NumberFormat('id-ID').format({{ $robuxPricePer100 }} * (amt/100))}</span></p>
                                <div class="mt-5 aspect-video rounded-lg overflow-hidden border border-white/10">
                                    @php
                                        $caraGamepassVideo = \App\Models\Setting::getValue('cara_bikin_gamepass_video', '');
                                        $caraGamepassVideoType = \App\Models\Setting::getValue('cara_bikin_gamepass_video_type', 'file');
                                        $caraGamepassVideoUrl = \App\Models\Setting::getValue('cara_bikin_gamepass_video_url', '');
                                    @endphp
                                    @if($caraGamepassVideoType === 'file' && $caraGamepassVideo)
                                        <video class="w-full h-full" controls>
                                            <source src="{{ asset($caraGamepassVideo) }}" type="video/mp4">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                    @elseif($caraGamepassVideoType === 'url' && $caraGamepassVideoUrl)
                                        @if(str_contains($caraGamepassVideoUrl, 'youtube.com') || str_contains($caraGamepassVideoUrl, 'youtu.be'))
                                            @php
                                                $videoId = '';
                                                if (str_contains($caraGamepassVideoUrl, 'youtube.com/watch?v=')) {
                                                    $videoId = explode('v=', $caraGamepassVideoUrl)[1];
                                                    $videoId = explode('&', $videoId)[0];
                                                } elseif (str_contains($caraGamepassVideoUrl, 'youtu.be/')) {
                                                    $videoId = explode('youtu.be/', $caraGamepassVideoUrl)[1];
                                                    $videoId = explode('?', $videoId)[0];
                                                }
                                            @endphp
                                            <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                                        @else
                                            <video class="w-full h-full" controls>
                                                <source src="{{ $caraGamepassVideoUrl }}" type="video/mp4">
                                                Browser Anda tidak mendukung video.
                                            </video>
                                        @endif
                                    @else
                                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Cara buat gamepass" allowfullscreen></iframe>
                                    @endif
                                </div>
                                <div class="mt-5 grid gap-3">
                                    <a target="_blank" href="https://www.roblox.com/develop" class="inline-flex items-center justify-center gap-2 rounded-md border border-white/20 px-4 py-2 hover:bg-white/5 text-white">Atur Gamepass ↗</a>
                                    <button id="modalDone" class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-white">Sudah Mengatur Gamepass</button>
                                </div>
                            </div>
                        </div>`;
                    document.body.appendChild(modal);
                    
                    // Close modal when clicking outside
                    modal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            // Stop all videos in gamepass modal
                            const videos = modal.querySelectorAll('video, iframe');
                            videos.forEach(video => {
                                if (video.tagName === 'VIDEO') {
                                    video.pause();
                                    video.currentTime = 0;
                                } else if (video.tagName === 'IFRAME') {
                                    video.src = video.src; // Reload iframe to stop video
                                }
                            });
                            modal.remove();
                        }
                    });
                    
                    modal.querySelector('#modalClose').addEventListener('click', ()=> {
                        // Stop all videos in gamepass modal
                        const videos = modal.querySelectorAll('video, iframe');
                        videos.forEach(video => {
                            if (video.tagName === 'VIDEO') {
                                video.pause();
                                video.currentTime = 0;
                            } else if (video.tagName === 'IFRAME') {
                                video.src = video.src; // Reload iframe to stop video
                            }
                        });
                        modal.remove();
                    });
                    modal.querySelector('#modalDone').addEventListener('click', ()=> { 
                        // Stop all videos in gamepass modal
                        const videos = modal.querySelectorAll('video, iframe');
                        videos.forEach(video => {
                            if (video.tagName === 'VIDEO') {
                                video.pause();
                                video.currentTime = 0;
                            } else if (video.tagName === 'IFRAME') {
                                video.src = video.src; // Reload iframe to stop video
                            }
                        });
                        modal.remove(); 
                    });

                }catch(err){
                    overlay.remove();
                    // Simple fallback toast
                    alert('Gagal memeriksa gamepass. Coba lagi.');
                }
            });

            // username check via our backend proxy to Roblox
            async function checkUsername(){
                const u = (usernameInput.value || '').trim();
                if(!u){ usernameInput.focus(); return; }
                checkBtn.disabled = true;
                checkBtn.textContent = 'Mengecek...';
                try{
                    const res = await fetch(`{{ route('api.roblox.username') }}?username=${encodeURIComponent(u)}`);
                    const j = await res.json();
                    if(j && j.ok && j.found){
                        if (j.avatar) {
                            userAvatar.src = j.avatar;
                            userAvatar.classList.remove('hidden');
                        } else {
                            userAvatar.classList.add('hidden');
                        }
                        userName.textContent = j.displayName || j.name || u;
                        userId.textContent = '';
                        userId.classList.add('hidden');
                        userBadge.textContent = 'Valid';
                        userBadge.classList.remove('hidden');
                        userBadge.classList.remove('bg-red-500/15','text-red-300','border-red-500/30');
                        userBadge.classList.add('bg-emerald-500/15','text-emerald-300','border-emerald-500/30');
                        // enable proceed
                        proceedBtnRight.disabled = false;
                        proceedBtnRight.classList.remove('opacity-50','cursor-not-allowed');
                    }else{
                        userAvatar.classList.add('hidden');
                        userName.textContent = 'Username tidak ditemukan';
                        userId.textContent = '';
                        userBadge.classList.add('hidden');
                        // disable proceed
                        proceedBtnRight.disabled = true;
                        proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                    }
                    userResult.classList.remove('hidden');
                }catch(e){
                    userName.textContent = 'Gagal mengecek';
                    userId.textContent = '';
                    userResult.classList.remove('hidden');
                    userBadge.classList.add('hidden');
                    proceedBtnRight.disabled = true;
                    proceedBtnRight.classList.add('opacity-50','cursor-not-allowed');
                }finally{
                    checkBtn.disabled = false;
                    checkBtn.textContent = 'Cek';
                }
            }
            checkBtn.addEventListener('click', checkUsername);
            usernameInput.addEventListener('keydown', (e)=>{ if(e.key==='Enter'){ e.preventDefault(); checkUsername(); }});

            // Set initial amount from session if available and user hasn't changed it
            const sessionAmount = {{ (int) (session('selected_amount') ?? 0) }};
            const amountChanged = sessionStorage.getItem('amount_changed') === 'true';
            
            if(sessionAmount > 0 && !amountChanged) {
                amtInput.value = sessionAmount;
                customAmount.value = sessionAmount;
                setMode('custom');
            } else {
                // Default to minimal order if no session amount or user changed it
                const defaultAmount = Math.max(minOrder, 100);
                amtInput.value = defaultAmount;
                customAmount.value = defaultAmount;
                setMode('custom');
            }
            updateTotal();
        })();

        // Simple mobile menu toggle function
        function toggleMobileMenu() {
            console.log('Mobile menu button clicked!');
            const mobileMenu = document.getElementById('mobile-menu');
            if (mobileMenu) {
                mobileMenu.classList.toggle('hidden');
                console.log('Mobile menu toggled, hidden:', mobileMenu.classList.contains('hidden'));
            } else {
                console.error('Mobile menu not found!');
            }
        }

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            
            if (mobileMenu && mobileMenuBtn && 
                !mobileMenuBtn.contains(event.target) && 
                !mobileMenu.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });

        function showCaraBeliVideo() {
            document.getElementById('caraBeliModal').classList.remove('hidden');
        }

        function hideCaraBeliVideo() {
            // Stop all videos in cara beli modal
            const videos = document.querySelectorAll('#caraBeliModal video, #caraBeliModal iframe');
            videos.forEach(video => {
                if (video.tagName === 'VIDEO') {
                    video.pause();
                    video.currentTime = 0;
                } else if (video.tagName === 'IFRAME') {
                    // For YouTube iframes, we can't directly control them, but we can hide them
                    video.src = video.src; // This will reload the iframe and stop the video
                }
            });
            document.getElementById('caraBeliModal').classList.add('hidden');
        }

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
                
                // Clear previous content
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

        // Close cara beli modal when clicking outside
        document.getElementById('caraBeliModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideCaraBeliVideo();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                hideHelpModal();
                hideCaraBeliVideo();
                // Close any open gamepass modal
                const gamepassModal = document.querySelector('.fixed.inset-0.bg-black\\/60');
                if (gamepassModal) {
                    // Stop all videos in gamepass modal
                    const videos = gamepassModal.querySelectorAll('video, iframe');
                    videos.forEach(video => {
                        if (video.tagName === 'VIDEO') {
                            video.pause();
                            video.currentTime = 0;
                        } else if (video.tagName === 'IFRAME') {
                            video.src = video.src; // Reload iframe to stop video
                        }
                    });
                    gamepassModal.remove();
                }
            }
        });
    </script>
</main>

<!-- Cara Beli Video Modal -->
<div id="caraBeliModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
    <div class="bg-gray-900 border border-white/20 rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-white">Cara Beli Robux</h3>
                <button onclick="hideCaraBeliVideo()" class="text-gray-400 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="aspect-video rounded-lg overflow-hidden border border-white/10">
                @php
                    $caraBeliVideo = \App\Models\Setting::getValue('cara_beli_video', '');
                    $caraBeliVideoType = \App\Models\Setting::getValue('cara_beli_video_type', 'file');
                    $caraBeliVideoUrl = \App\Models\Setting::getValue('cara_beli_video_url', '');
                @endphp
                @if($caraBeliVideoType === 'file' && $caraBeliVideo)
                    <video class="w-full h-full" controls>
                        <source src="{{ asset($caraBeliVideo) }}" type="video/mp4">
                        Browser Anda tidak mendukung video.
                    </video>
                @elseif($caraBeliVideoType === 'url' && $caraBeliVideoUrl)
                    @if(str_contains($caraBeliVideoUrl, 'youtube.com') || str_contains($caraBeliVideoUrl, 'youtu.be'))
                        @php
                            $videoId = '';
                            if (str_contains($caraBeliVideoUrl, 'youtube.com/watch?v=')) {
                                $videoId = explode('v=', $caraBeliVideoUrl)[1];
                                $videoId = explode('&', $videoId)[0];
                            } elseif (str_contains($caraBeliVideoUrl, 'youtu.be/')) {
                                $videoId = explode('youtu.be/', $caraBeliVideoUrl)[1];
                                $videoId = explode('?', $videoId)[0];
                            }
                        @endphp
                        <iframe src="https://www.youtube.com/embed/{{ $videoId }}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>
                    @else
                        <video class="w-full h-full" controls>
                            <source src="{{ $caraBeliVideoUrl }}" type="video/mp4">
                            Browser Anda tidak mendukung video.
                        </video>
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-800 text-gray-400">
                        <div class="text-center">
                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                            <p>Video cara beli belum tersedia</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

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

<style>
/* Simple mobile menu styles */
#mobile-menu-btn {
    cursor: pointer !important;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    pointer-events: auto !important;
    z-index: 9999 !important;
    position: relative !important;
}

#mobile-menu-btn:active {
    transform: scale(0.95);
}

#mobile-menu-btn:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Ensure mobile menu is properly positioned */
#mobile-menu {
    z-index: 40;
}
</style>

@endsection


