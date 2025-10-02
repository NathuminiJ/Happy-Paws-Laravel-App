@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Products - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    <style>
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(139, 69, 19, 0.1), 0 10px 10px -5px rgba(139, 69, 19, 0.04);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-xl border-b border-amber-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center group">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-paw text-white text-xl"></i>
                            </div>
                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-heart text-red-500 text-xs"></i>
                            </div>
                        </div>
                        <div class="ml-3">
                            <span class="text-2xl font-bold bg-gradient-to-r from-amber-700 to-orange-700 bg-clip-text text-transparent">Happy Paws</span>
                            <p class="text-xs text-amber-600 -mt-1">Premium Pet Supplies</p>
                        </div>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('home') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-home mr-2 group-hover:scale-110 transition-transform"></i>
                        Home
                    </a>
                    <a href="#about" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-info-circle mr-2 group-hover:scale-110 transition-transform"></i>
                        About Us
                    </a>
                    <a href="{{ route('products') }}" class="flex items-center text-amber-600 font-semibold px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-shopping-bag mr-2 group-hover:scale-110 transition-transform"></i>
                        Products
                    </a>
                    
                    
                    @auth
                        <a href="{{ route('contact') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                            <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform"></i>
                            Contact
                        </a>
                        <a href="{{ route('cart') }}" class="relative flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                            <i class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform"></i>
                            Cart
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ auth()->user()->cartItems->count() }}</span>
                        </a>
                        
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center bg-gradient-to-r from-red-500 to-pink-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-red-600 hover:to-pink-600 transition-all duration-200 group shadow-lg">
                                <i class="fas fa-crown mr-2 group-hover:scale-110 transition-transform"></i>
                                Admin Panel
                            </a>
                        @else
                            <a href="{{ route('customer.home') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                                <i class="fas fa-user-circle mr-2 group-hover:scale-110 transition-transform"></i>
                                Dashboard
                            </a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-amber-700 hover:to-orange-700 transition-colors duration-200">
                            Register
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif

    @if (session()->has('info'))
        <div class="fixed top-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-info-circle mr-2"></i>
            {{ session('info') }}
        </div>
    @endif

    <!-- Products Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-amber-900 mb-4">Our Products</h1>
            <p class="text-amber-700">Discover our amazing collection of pet supplies</p>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-md p-6 mb-8 border border-amber-100">
            <form method="GET" action="{{ route('products') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-search mr-2"></i>Search
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products..." class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Brand
                        </label>
                        <select name="brand" class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">All Brands</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ request('brand') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-sort mr-2"></i>Sort By
                        </label>
                        <select name="sort" class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-between items-center">
                    <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-md hover:bg-amber-700 transition duration-200">
                        <i class="fas fa-filter mr-2"></i>Apply Filters
                    </button>
                    @if(request()->hasAny(['search', 'brand', 'sort']))
                        <a href="{{ route('products') }}" class="text-amber-600 hover:text-amber-800 text-sm">
                            <i class="fas fa-times mr-1"></i>Clear Filters
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Results Info -->
        <div class="mb-6 flex justify-between items-center">
            <p class="text-amber-700">
                @if(request()->hasAny(['search', 'brand', 'sort']))
                    Showing {{ $products->count() }} of {{ $products->total() }} products
                    @if(request('search'))
                        for "{{ request('search') }}"
                    @endif
                @else
                    Showing {{ $products->count() }} of {{ $products->total() }} products
                @endif
            </p>
            @if($products->count() > 0)
                <div class="text-sm text-amber-600">
                    Page {{ $products->currentPage() }} of {{ $products->lastPage() }}
                </div>
            @endif
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/300x200/D2691E/FFFFFF?text=' . urlencode($product->name) }}" 
                         alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    
                    <div class="p-6">
                        <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ $product->name }}</h3>
                        <p class="text-amber-700 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
                            <span class="text-sm text-amber-600">{{ $product->brand->name ?? 'No Brand' }}</span>
                        </div>
                        
                        <div class="space-y-2">
                            <a href="{{ route('product.show', $product->id) }}" 
                               class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                            
                            @auth
                                <div class="space-y-2">
                                    <button onclick="addToCart({{ $product->id }})" 
                                            class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700 transition duration-300">
                                        <i class="fas fa-shopping-cart mr-2"></i>
                                        Add to Cart
                                    </button>
                                    <button onclick="addToWishlist({{ $product->id }})" 
                                            class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700 transition duration-300">
                                        <i class="fas fa-heart mr-2"></i>
                                        Add to Wishlist
                                    </button>
                                </div>
                            @else
                                <button onclick="showLoginModal()" 
                                        class="w-full bg-gray-400 text-white py-2 rounded cursor-not-allowed flex items-center justify-center">
                                    <i class="fas fa-lock mr-2"></i>
                                    Login to Add to Cart
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-box text-4xl text-amber-300 mb-4"></i>
                    <p class="text-amber-600 text-lg">No products found</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($products->hasPages())
            <div class="mt-8">
                {{ $products->links() }}
            </div>
        @endif
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-amber-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-amber-900 mb-4">Login Required</h2>
            <p class="text-amber-700 mb-6">Please login to add items to your cart and view product details.</p>
            <div class="space-y-3">
                <a href="{{ route('login') }}" 
                   class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="w-full bg-white text-amber-600 py-3 px-6 rounded-lg font-semibold border-2 border-amber-200 hover:bg-amber-50 transition-all duration-200 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-amber-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-paw text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold">Happy Paws</span>
                </div>
                <p class="text-amber-200 mb-4">Premium Pet Supplies for Your Beloved Companions</p>
                <p class="text-sm text-amber-300">&copy; 2024 Happy Paws. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function addToCart(productId) {
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.success, 'success');
                } else if (data.error) {
                    showMessage(data.error, 'error');
                } else if (data.info) {
                    showMessage(data.info, 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
            });
        }

        function addToWishlist(productId) {
            fetch('/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.success, 'success');
                } else if (data.error) {
                    showMessage(data.error, 'error');
                } else if (data.info) {
                    showMessage(data.info, 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
            });
        }

        function showMessage(message, type) {
            // Remove existing messages
            const existingMessages = document.querySelectorAll('.flash-message');
            existingMessages.forEach(msg => msg.remove());

            // Create new message
            const messageDiv = document.createElement('div');
            messageDiv.className = `flash-message fixed top-4 right-4 px-4 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
                type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
                'bg-blue-100 border border-blue-400 text-blue-700'
            }`;
            
            messageDiv.innerHTML = `
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                ${message}
            `;
            
            document.body.appendChild(messageDiv);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        function showLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.getElementById('loginModal').classList.add('flex');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Auto-submit form when select dropdowns change
        document.addEventListener('DOMContentLoaded', function() {
            const brandSelect = document.querySelector('select[name="brand"]');
            const sortSelect = document.querySelector('select[name="sort"]');
            const searchInput = document.querySelector('input[name="search"]');
            const filterForm = document.getElementById('filterForm');

            // Auto-submit when brand or sort changes
            if (brandSelect) {
                brandSelect.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    filterForm.submit();
                });
            }

            // Debounced search
            let searchTimeout;
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        filterForm.submit();
                    }, 500); // Wait 500ms after user stops typing
                });
            }

            // Show loading state
            filterForm.addEventListener('submit', function() {
                const submitBtn = filterForm.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Filtering...';
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
    @livewireScripts
</body>
</html>