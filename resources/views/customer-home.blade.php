@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Happy Paws - Premium Pet Supplies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .pet-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23f5f5dc' fill-opacity='0.1'%3E%3Cpath d='M30 30c0-11.046-8.954-20-20-20s-20 8.954-20 20 8.954 20 20 20 20-8.954 20-20zm0 0c0-11.046 8.954-20 20-20s20 8.954 20 20-8.954 20-20 20-20-8.954-20-20z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(139, 69, 19, 0.1), 0 10px 10px -5px rgba(139, 69, 19, 0.04);
        }
        .dropdown {
            position: relative;
            display: inline-block;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1000;
            border-radius: 8px;
            border: 1px solid #f3f4f6;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-sm shadow-xl border-b border-amber-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <a href="{{ route('customer.home') }}" class="flex items-center group">
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
                    <a href="{{ route('customer.home') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-home mr-2 group-hover:scale-110 transition-transform"></i>
                        Home
                    </a>
                    <a href="#about" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-info-circle mr-2 group-hover:scale-110 transition-transform"></i>
                        About Us
                    </a>
                    <a href="{{ route('products') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-shopping-bag mr-2 group-hover:scale-110 transition-transform"></i>
                        Products
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group bg-amber-100 hover:bg-amber-200" style="pointer-events: auto; z-index: 10;">
                        <i class="fas fa-box mr-2 group-hover:scale-110 transition-transform"></i>
                        My Orders
                    </a>
                    <a href="{{ route('cart') }}" class="relative flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform"></i>
                        Cart
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ auth()->user()->cartItems->count() }}</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="relative flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-heart mr-2 group-hover:scale-110 transition-transform"></i>
                        Wishlist
                        <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ auth()->user()->wishlistItems->count() }}</span>
                    </a>
                    <a href="{{ route('customer.feedback.index') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-star mr-2 group-hover:scale-110 transition-transform"></i>
                        My Reviews
                    </a>
                    <div class="dropdown">
                        <button class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                            <i class="fas fa-user-circle mr-2 group-hover:scale-110 transition-transform"></i>
                            Profile
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-amber-700 hover:bg-amber-50">My Profile</a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-amber-700 hover:bg-amber-50">Dashboard</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative overflow-hidden">
        <div class="pet-pattern absolute inset-0"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <div class="floating-animation inline-block mb-8">
                    <i class="fas fa-paw text-8xl text-amber-600"></i>
                </div>
                <h1 class="text-5xl md:text-6xl font-bold text-amber-900 mb-6">
                    Welcome back, <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">{{ auth()->user()->name }}!</span>
                </h1>
                <p class="text-xl text-amber-700 mb-8 max-w-3xl mx-auto">
                    Ready to find the perfect supplies for your beloved pets? Browse our premium collection!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products') }}" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Shop Now
                    </a>
                    <a href="{{ route('orders.index') }}" class="bg-white text-amber-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-amber-50 transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-amber-200">
                        <i class="fas fa-box mr-2"></i>
                        View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shopping-cart text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-900">{{ auth()->user()->orders->count() }}</h3>
                    <p class="text-amber-700">Total Orders</p>
                </div>
                
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-orange-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-900">{{ auth()->user()->wishlistItems->count() }}</h3>
                    <p class="text-amber-700">Wishlist Items</p>
                </div>
                
                <div class="bg-gradient-to-r from-yellow-50 to-amber-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gift text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-900">1,250</h3>
                    <p class="text-amber-700">Loyalty Points</p>
                </div>
                
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-6 text-center">
                    <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-amber-600 text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-amber-900">4.8</h3>
                    <p class="text-amber-700">Average Rating</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="py-20 bg-gradient-to-r from-amber-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">Featured Products</h2>
                <p class="text-lg text-amber-700">Discover our most popular pet supplies</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($featured_products as $product)
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/300x200/D2691E/FFFFFF?text=' . urlencode($product->name) }}" 
                             alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-amber-700 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-amber-600">{{ $product->brand->name ?? 'No Brand' }}</span>
                            </div>
                            
                            <div class="space-y-2">
                                <a href="{{ route('product.show', $product->id) }}" 
                                   class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </a>
                                
                                <button onclick="addToCart({{ $product->id }})" 
                                        class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700 transition duration-300">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
                                </button>
                                
                                <button onclick="addToWishlist({{ $product->id }})" 
                                        class="w-full bg-pink-100 text-pink-600 py-2 rounded hover:bg-pink-200 transition duration-300">
                                    <i class="fas fa-heart mr-2"></i>
                                    Add to Wishlist
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-box text-4xl text-amber-300 mb-4"></i>
                        <p class="text-amber-600 text-lg">No featured products available</p>
                    </div>
                @endforelse
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('products') }}" 
                   class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    View All Products
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">Recent Orders</h2>
                <p class="text-lg text-amber-700">Track your latest purchases</p>
            </div>
            
            <div class="space-y-4">
                @forelse(auth()->user()->orders->take(3) as $order)
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-lg p-6 flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-box text-amber-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-amber-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-amber-600">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-amber-900">${{ number_format($order->total_amount ?? 0, 2) }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $order->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-4xl text-amber-300 mb-4"></i>
                        <p class="text-amber-600">No orders yet</p>
                        <a href="{{ route('products') }}" class="text-amber-600 hover:text-amber-800 font-medium">Start shopping</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-amber-900 text-white py-12">
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
    </script>
</body>
</html>
