@extends('layouts.app')
@section('title', isset($product) ? 'Admin • Edit Product' : 'Admin • Create Product')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">
            {{ isset($product) ? 'Edit Product' : 'Create New Product' }}
        </h1>
        <p class="text-white/60 mt-2 text-sm sm:text-base">
            {{ isset($product) ? 'Update product information' : 'Add a new product to the store' }}
        </p>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 bg-red-600 text-white rounded-lg">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" class="space-y-6 sm:space-y-8" enctype="multipart/form-data">
        @csrf
        @if(isset($product))
            @method('PUT')
        @endif

        <!-- Basic Information -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Basic Information</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Product Name *</span>
                    <input 
                        name="name" 
                        type="text" 
                        value="{{ old('name', $product->name ?? '') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                        placeholder="e.g., 100 Robux Package"
                    />
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Category *</span>
                    <select 
                        name="category" 
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required
                    >
                        <option value="">Select Category</option>
                        <option value="robux" {{ old('category', $product->category ?? '') == 'robux' ? 'selected' : '' }}>Robux</option>
                        <option value="gamepass" {{ old('category', $product->category ?? '') == 'gamepass' ? 'selected' : '' }}>GamePass</option>
                        <option value="item" {{ old('category', $product->category ?? '') == 'item' ? 'selected' : '' }}>Game Item</option>
                        <option value="currency" {{ old('category', $product->category ?? '') == 'currency' ? 'selected' : '' }}>In-Game Currency</option>
                        @foreach($categories as $category)
                            @if(!in_array($category, ['robux', 'gamepass', 'item', 'currency']))
                                <option value="{{ $category }}" {{ old('category', $product->category ?? '') == $category ? 'selected' : '' }}>{{ ucfirst($category) }}</option>
                            @endif
                        @endforeach
                    </select>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Game Type *</span>
                    <div class="mt-2 flex flex-col sm:flex-row gap-2">
                        <select 
                            name="game_type_select" 
                            id="gameTypeSelect"
                            class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        >
                            <option value="">Pilih Game Type</option>
                            @foreach($gameTypes as $gameType)
                                <option value="{{ $gameType }}" {{ old('game_type', $product->game_type ?? '') == $gameType ? 'selected' : '' }}>{{ $gameType }}</option>
                            @endforeach
                        </select>
                        <button 
                            type="button" 
                            id="newGameTypeBtn"
                            class="px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white transition text-sm sm:text-base"
                        >
                            Baru
                        </button>
                    </div>
                    <input 
                        name="game_type" 
                        type="text" 
                        id="gameTypeCustom"
                        value="{{ old('game_type', $product->game_type ?? '') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base hidden" 
                        placeholder="e.g., Roblox, Blox Fruits, etc."
                    />
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Sort Order</span>
                    <input 
                        name="sort_order" 
                        type="number" 
                        min="0"
                        value="{{ old('sort_order', $product->sort_order ?? '0') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        placeholder="0"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Lower numbers appear first</p>
                </label>
            </div>

            <label class="block mt-4 sm:mt-6">
                <span class="text-white/70 text-sm sm:text-base">Description</span>
                <textarea 
                    name="description" 
                    rows="6"
                    class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base font-mono whitespace-pre-wrap" 
                    placeholder="Optional product description...&#10;&#10;Contoh:&#10;❗PERHATIAN:&#10;Pastikan akun kalian sudah tergabung di komunitas Roblox Valtus Studios selama minimal 15 hari sebelum melakukan pemesanan.&#10;🔗 Link komunitas: https://www.roblox.com/communities/35148970/Valtus-Studios#!/about&#10;&#10;⛔ Jika akun kalian belum berada di komunitas selama 15 hari, Robux tidak akan bisa dikirim dan proses tidak dapat dilanjutkan."
                >{{ old('description', $product->description ?? '') }}</textarea>
                <p class="mt-1 text-white/50 text-xs sm:text-sm">Gunakan Enter untuk baris baru, spasi akan dipertahankan</p>
            </label>

            <label class="block mt-4 sm:mt-6">
                <span class="text-white/70 text-sm sm:text-base">Product Image</span>
                <div class="mt-2 space-y-3 sm:space-y-4">
                    <!-- Image Upload -->
                    <div>
                        <input 
                            name="image" 
                            type="file" 
                            id="imageInput"
                            accept="image/*"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        />
                        <p class="mt-1 text-white/50 text-xs sm:text-sm">Upload gambar produk (JPG, PNG, GIF - Max 2MB)</p>
                    </div>
                    
                    <!-- Current Image Preview -->
                    @if(isset($product) && $product->image)
                        <div class="mt-3 sm:mt-4">
                            <p class="text-white/70 text-xs sm:text-sm mb-2">Gambar Saat Ini:</p>
                            <img src="{{ asset($product->image) }}" alt="Current Image" class="h-24 w-24 sm:h-32 sm:w-32 object-cover rounded-lg border border-white/20">
                        </div>
                    @endif
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden">
                        <p class="text-white/70 text-xs sm:text-sm mb-2">Preview Gambar Baru:</p>
                        <img id="previewImg" src="" alt="Preview" class="h-24 w-24 sm:h-32 sm:w-32 object-cover rounded-lg border border-white/20">
                    </div>
                    
                    <!-- Image URL (Fallback) -->
                    <div>
                        <input 
                            name="image_url" 
                            type="url" 
                            value="{{ old('image_url', $product->image_url ?? '') }}"
                            class="w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                            placeholder="https://example.com/image.png"
                        />
                        <p class="mt-1 text-white/50 text-xs sm:text-sm">Atau masukkan URL gambar (opsional)</p>
                    </div>
                </div>
            </label>
        </div>

        <!-- Pricing -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6 flex items-center gap-2">
                <img src="/assets/images/robux.png" alt="Pricing" class="h-5 w-5 sm:h-6 sm:w-6">
                Pricing Configuration
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Base Price (Rp) *</span>
                    <input 
                        name="price" 
                        type="number" 
                        step="0.01" 
                        min="0"
                        value="{{ old('price', $product->price ?? '') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                        placeholder="10000"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Base price before tax</p>
                </label>

                <label class="block">
                    <span class="text-white/70 text-sm sm:text-base">Tax Rate (%) *</span>
                    <input 
                        name="tax_rate" 
                        type="number" 
                        step="0.01" 
                        min="0" 
                        max="100"
                        value="{{ old('tax_rate', $product->tax_rate ?? '30') }}"
                        class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        required 
                        placeholder="30"
                    />
                    <p class="mt-1 text-white/50 text-xs sm:text-sm">Tax percentage (0-100)</p>
                </label>
            </div>

            <!-- Price Preview -->
            <div class="mt-4 sm:mt-6 p-3 sm:p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-lg">
                <h4 class="text-emerald-300 font-medium mb-3 text-sm sm:text-base">Price Preview:</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                    <div>
                        <span class="text-white/60">Base Price:</span>
                        <div class="text-white font-medium" id="preview-base">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-white/60">Tax:</span>
                        <div class="text-white font-medium" id="preview-tax">Rp 0</div>
                    </div>
                    <div>
                        <span class="text-emerald-300">Total Price:</span>
                        <div class="text-emerald-300 font-bold text-sm sm:text-lg" id="preview-total">Rp 0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Discount Configuration -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Discount Configuration
            </h3>
            
            <label class="flex items-center gap-3 mb-4">
                <input 
                    name="discount_active" 
                    type="checkbox" 
                    value="1"
                    id="discountActiveCheckbox"
                    {{ old('discount_active', $product->discount_active ?? false) ? 'checked' : '' }}
                    class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                />
                <span class="text-white/70 text-sm sm:text-base">Enable Discount</span>
            </label>

            <div id="discountFields" class="space-y-4 {{ old('discount_active', $product->discount_active ?? false) ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                    <label class="block">
                        <span class="text-white/70 text-sm sm:text-base">Discount Method *</span>
                        <select 
                            name="discount_method" 
                            id="discountMethod"
                            class="mt-2 w-full px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                        >
                            <option value="">Pilih Metode</option>
                            <option value="percentage" {{ old('discount_method', $product->discount_method ?? '') == 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
                            <option value="fixed_amount" {{ old('discount_method', $product->discount_method ?? '') == 'fixed_amount' ? 'selected' : '' }}>Nominal (Rp)</option>
                        </select>
                        <p class="mt-1 text-white/50 text-xs sm:text-sm">Pilih salah satu: Persentase atau Nominal</p>
                    </label>

                    <label class="block">
                        <span class="text-white/70 text-sm sm:text-base">Discount Value *</span>
                        <div class="mt-2 flex items-center gap-2">
                            <input 
                                name="discount_value" 
                                type="number" 
                                step="0.01" 
                                min="0"
                                id="discountValue"
                                value="{{ old('discount_value', $product->discount_value ?? '') }}"
                                class="flex-1 px-3 sm:px-4 py-2 sm:py-3 rounded-lg bg-black/30 border border-white/20 text-white focus:border-emerald-400 focus:ring-1 focus:ring-emerald-400 text-sm sm:text-base" 
                                placeholder="0"
                            />
                            <span id="discountUnit" class="text-white/60 text-sm sm:text-base">%</span>
                        </div>
                        <p class="mt-1 text-white/50 text-xs sm:text-sm" id="discountHint">Masukkan nilai persentase (0-100)</p>
                    </label>
                </div>

                <!-- Discount Preview -->
                <div class="mt-4 p-3 sm:p-4 bg-yellow-500/10 border border-yellow-500/20 rounded-lg">
                    <h4 class="text-yellow-300 font-medium mb-3 text-sm sm:text-base">Discount Preview:</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 text-xs sm:text-sm">
                        <div>
                            <span class="text-white/60">Harga Setelah Pajak:</span>
                            <div class="text-white font-medium" id="discount-preview-base">Rp 0</div>
                        </div>
                        <div>
                            <span class="text-white/60">Diskon:</span>
                            <div class="text-yellow-300 font-medium" id="discount-preview-amount">-Rp 0</div>
                        </div>
                        <div>
                            <span class="text-yellow-300">Harga Final:</span>
                            <div class="text-yellow-300 font-bold text-sm sm:text-lg" id="discount-preview-final">Rp 0</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status -->
        <div class="rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5">
            <h3 class="text-lg sm:text-xl font-semibold text-white mb-4 sm:mb-6">Status</h3>
            
            <label class="flex items-center gap-3">
                <input 
                    name="is_active" 
                    type="checkbox" 
                    value="1"
                    {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}
                    class="w-4 h-4 text-emerald-600 bg-black/30 border-white/20 rounded focus:ring-emerald-500 focus:ring-2"
                />
                <span class="text-white/70 text-sm sm:text-base">Active (visible to customers)</span>
            </label>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
            <button type="submit" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-medium transition text-sm sm:text-base">
                {{ isset($product) ? 'Update Product' : 'Create Product' }}
            </button>
            <a href="{{ route('admin.products') }}" class="px-4 sm:px-6 py-2 sm:py-3 rounded-lg bg-gray-600 hover:bg-gray-700 text-white font-medium transition text-sm sm:text-base text-center">
                Cancel
            </a>
        </div>
    </form>
</main>

<script>
    // Price preview calculation
    function updatePreview() {
        const price = parseFloat(document.querySelector('input[name="price"]').value) || 0;
        const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
        
        const taxAmount = price * (taxRate / 100);
        const totalPrice = price + taxAmount;
        
        document.getElementById('preview-base').textContent = 'Rp ' + price.toLocaleString('id-ID');
        document.getElementById('preview-tax').textContent = 'Rp ' + taxAmount.toLocaleString('id-ID');
        document.getElementById('preview-total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }
    
    // Game Type handling
    document.getElementById('newGameTypeBtn').addEventListener('click', function() {
        const select = document.getElementById('gameTypeSelect');
        const custom = document.getElementById('gameTypeCustom');
        
        if (custom.classList.contains('hidden')) {
            // Show custom input
            select.classList.add('hidden');
            custom.classList.remove('hidden');
            custom.required = true;
            select.disabled = true;
            this.textContent = 'Pilih';
        } else {
            // Show select dropdown
            select.classList.remove('hidden');
            custom.classList.add('hidden');
            custom.required = false;
            select.disabled = false;
            this.textContent = 'Baru';
        }
    });
    
    // Handle form submission to ensure correct game_type value
    document.querySelector('form').addEventListener('submit', function(e) {
        const select = document.getElementById('gameTypeSelect');
        const custom = document.getElementById('gameTypeCustom');
        
        if (!custom.classList.contains('hidden') && custom.value.trim()) {
            // Custom input is active and has value
            select.disabled = true;
        } else if (!select.classList.contains('hidden') && select.value) {
            // Select is active and has value
            custom.disabled = true;
        }
    });
    
    // Image preview
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Add event listeners
    document.querySelector('input[name="price"]').addEventListener('input', updatePreview);
    document.querySelector('input[name="tax_rate"]').addEventListener('input', updatePreview);
    
    // Initial calculation
    updatePreview();
    
    // Discount toggle functionality
    const discountActiveCheckbox = document.getElementById('discountActiveCheckbox');
    const discountFields = document.getElementById('discountFields');
    const discountMethod = document.getElementById('discountMethod');
    const discountValue = document.getElementById('discountValue');
    const discountUnit = document.getElementById('discountUnit');
    const discountHint = document.getElementById('discountHint');
    
    function updateDiscountFields() {
        if (discountActiveCheckbox.checked) {
            discountFields.classList.remove('hidden');
        } else {
            discountFields.classList.add('hidden');
            discountMethod.value = '';
            discountValue.value = '';
        }
        updateDiscountPreview();
    }
    
    function updateDiscountMethod() {
        const method = discountMethod.value;
        if (method === 'percentage') {
            discountUnit.textContent = '%';
            discountHint.textContent = 'Masukkan nilai persentase (0-100)';
            discountValue.setAttribute('max', '100');
        } else if (method === 'fixed_amount') {
            discountUnit.textContent = 'Rp';
            discountHint.textContent = 'Masukkan nilai nominal dalam Rupiah';
            discountValue.removeAttribute('max');
        } else {
            discountUnit.textContent = '';
            discountHint.textContent = '';
        }
        updateDiscountPreview();
    }
    
    function updateDiscountPreview() {
        const price = parseFloat(document.querySelector('input[name="price"]').value) || 0;
        const taxRate = parseFloat(document.querySelector('input[name="tax_rate"]').value) || 0;
        const totalPrice = price + (price * taxRate / 100);
        
        const baseEl = document.getElementById('discount-preview-base');
        const amountEl = document.getElementById('discount-preview-amount');
        const finalEl = document.getElementById('discount-preview-final');
        
        baseEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        
        if (discountActiveCheckbox.checked && discountMethod.value && discountValue.value) {
            const method = discountMethod.value;
            const value = parseFloat(discountValue.value) || 0;
            
            let discountAmount = 0;
            if (method === 'percentage') {
                discountAmount = totalPrice * (value / 100);
            } else { // fixed_amount
                discountAmount = Math.min(value, totalPrice);
            }
            
            const finalPrice = Math.max(0, totalPrice - discountAmount);
            
            amountEl.textContent = '-Rp ' + discountAmount.toLocaleString('id-ID');
            finalEl.textContent = 'Rp ' + finalPrice.toLocaleString('id-ID');
        } else {
            amountEl.textContent = '-Rp 0';
            finalEl.textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
        }
    }
    
    if (discountActiveCheckbox) {
        discountActiveCheckbox.addEventListener('change', updateDiscountFields);
    }
    
    if (discountMethod) {
        discountMethod.addEventListener('change', updateDiscountMethod);
        // Update on load
        updateDiscountMethod();
    }
    
    if (discountValue) {
        discountValue.addEventListener('input', updateDiscountPreview);
    }
    
    // Also update discount preview when price or tax changes
    document.querySelector('input[name="price"]')?.addEventListener('input', updateDiscountPreview);
    document.querySelector('input[name="tax_rate"]')?.addEventListener('input', updateDiscountPreview);
    
    // Initialize discount fields state
    updateDiscountFields();
    
    // Initialize form state for editing
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('gameTypeSelect');
        const custom = document.getElementById('gameTypeCustom');
        const btn = document.getElementById('newGameTypeBtn');
        
        // If custom input has value and select doesn't, show custom input
        if (custom.value.trim() && !select.value) {
            select.classList.add('hidden');
            custom.classList.remove('hidden');
            custom.required = true;
            select.disabled = true;
            btn.textContent = 'Pilih';
        }
    });
</script>
@endsection
