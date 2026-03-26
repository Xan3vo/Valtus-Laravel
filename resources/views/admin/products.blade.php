@extends('layouts.app')
@section('title', 'Admin • Products')
@section('body')
@include('admin.partials.navigation')

<main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-16">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 sm:mb-8 gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-medium text-white/90">Products Management</h1>
            <p class="text-white/60 mt-2 text-sm sm:text-base">Manage Robux packages and other game items</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-3 sm:px-4 py-2 rounded-lg flex items-center gap-2 text-sm sm:text-base">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Product
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-600 text-white rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter and Search -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3 sm:gap-4">
        <input 
            type="text" 
            placeholder="Search products..." 
            class="px-3 sm:px-4 py-2 rounded-lg bg-black/30 border border-white/20 text-white placeholder-white/50 flex-1 text-sm sm:text-base"
            id="searchInput"
        />
        <select class="px-3 sm:px-4 py-2 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" id="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}">{{ ucfirst($category) }}</option>
            @endforeach
        </select>
        <select class="px-3 sm:px-4 py-2 rounded-lg bg-black/30 border border-white/20 text-white text-sm sm:text-base" id="statusFilter">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" id="productsGrid">
        @forelse($products as $product)
        <div class="product-card rounded-lg border border-white/20 p-4 sm:p-6 bg-white/5 hover:bg-white/10 transition-all duration-200 overflow-hidden" 
             data-name="{{ strtolower($product->name) }}" 
             data-category="{{ strtolower($product->category) }}" 
             data-status="{{ $product->is_active ? 'active' : 'inactive' }}">
            
            <!-- Product Header -->
            <div class="flex items-start justify-between mb-3 sm:mb-4 gap-2">
                <div class="flex items-center gap-2 sm:gap-3 min-w-0 flex-1">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover flex-shrink-0">
                    @elseif($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg object-cover flex-shrink-0">
                    @else
                        <div class="h-10 w-10 sm:h-12 sm:w-12 rounded-lg bg-gradient-to-br from-emerald-500 to-blue-500 flex items-center justify-center flex-shrink-0">
                            <img src="/assets/images/robux.png" alt="Product" class="h-6 w-6 sm:h-8 sm:w-8">
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <h3 class="text-white font-semibold text-sm sm:text-base truncate" title="{{ $product->name }}">{{ Str::limit($product->name, 20) }}</h3>
                        <p class="text-white/60 text-xs sm:text-sm truncate">{{ ucfirst($product->category) }} • {{ $product->game_type }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="px-2 py-1 rounded text-xs {{ $product->is_active ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' : 'bg-gray-500/20 text-gray-300 border border-gray-500/30' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-2 sm:space-y-3 mb-3 sm:mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-xs sm:text-sm">Base Price:</span>
                    <span class="text-white font-medium text-xs sm:text-sm">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-white/60 text-xs sm:text-sm">Tax ({{ $product->tax_rate }}%):</span>
                    <span class="text-white font-medium text-xs sm:text-sm">Rp {{ number_format($product->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-white/10 pt-2">
                    <span class="text-emerald-300 font-medium text-xs sm:text-sm">Total Price:</span>
                    <span class="text-emerald-300 font-bold text-sm sm:text-lg">
                        @if($product->discount_active && $product->final_price < $product->total_price)
                            <span class="line-through text-white/50 text-xs mr-1">Rp {{ number_format($product->total_price, 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($product->final_price, 0, ',', '.') }}</span>
                        @else
                            Rp {{ number_format($product->total_price, 0, ',', '.') }}
                        @endif
                    </span>
                </div>
                @if($product->discount_active)
                    <div class="flex justify-between items-center">
                        <span class="text-yellow-300 text-xs sm:text-sm">🎉 Diskon:</span>
                        <span class="text-yellow-300 font-medium text-xs sm:text-sm">
                            @if($product->discount_method === 'percentage')
                                {{ number_format($product->discount_value, 0, ',', '.') }}%
                            @else
                                Rp {{ number_format($product->discount_value, 0, ',', '.') }}
                            @endif
                            ({{ number_format($product->discount_amount, 0, ',', '.') }})
                        </span>
                    </div>
                @endif
                @if($product->description)
                    <p class="text-white/50 text-xs sm:text-sm mt-2 whitespace-pre-wrap">{{ Str::limit($product->description, 60) }}</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="flex-1 px-3 py-2 rounded-lg border border-white/20 text-white hover:bg-white/10 transition text-center text-xs sm:text-sm whitespace-nowrap">
                    Edit
                </a>
                <form method="POST" action="{{ route('admin.products.toggle-status', $product) }}" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full px-3 py-2 rounded-lg {{ $product->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition text-xs sm:text-sm whitespace-nowrap">
                        {{ $product->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this product?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition text-xs sm:text-sm whitespace-nowrap">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-8 sm:py-12">
            <div class="text-white/60 text-base sm:text-lg mb-4">No products found</div>
            <a href="{{ route('admin.products.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-3 sm:px-4 py-2 rounded-lg text-sm sm:text-base">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create First Product
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
    <div class="mt-8 flex justify-center">
        {{ $products->links() }}
    </div>
    @endif
</main>

<script>
    // Filter functionality
    document.getElementById('searchInput').addEventListener('input', filterProducts);
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('statusFilter').addEventListener('change', filterProducts);

    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value.toLowerCase();
        
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            const name = card.dataset.name;
            const category = card.dataset.category;
            const status = card.dataset.status;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesCategory = !categoryFilter || category === categoryFilter;
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesCategory && matchesStatus) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
</script>
@endsection
