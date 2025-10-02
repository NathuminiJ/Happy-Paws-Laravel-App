<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wishlist - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('customer.home') }}" class="flex items-center">
                        <i class="fas fa-paw text-amber-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-amber-900">Happy Paws</span>
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('customer.home') }}" class="text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">Home</a>
                    <a href="{{ route('products') }}" class="text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">Products</a>
                    <a href="{{ route('orders.index') }}" class="text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">My Orders</a>
                    <a href="{{ route('cart') }}" class="relative flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform"></i>
                        Cart
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ auth()->user()->cartItems->count() }}</span>
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="relative flex items-center text-amber-600 font-semibold px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-heart mr-2 group-hover:scale-110 transition-transform"></i>
                        Wishlist
                        <span class="absolute -top-1 -right-1 bg-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">{{ auth()->user()->wishlistItems->count() }}</span>
                    </a>
                    <div class="relative">
                        <button class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                            <i class="fas fa-user mr-2"></i>
                            {{ auth()->user()->name }}
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-amber-200 hidden group-hover:block">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-amber-700 hover:bg-amber-50">Profile</a>
                            <a href="{{ route('logout') }}" class="block px-4 py-2 text-sm text-amber-700 hover:bg-amber-50">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Wishlist Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-amber-900 mb-4">My Wishlist</h1>
            <p class="text-amber-700">Your favorite pet supplies saved for later</p>
        </div>

        @if($wishlistItems->count() > 0)
            <!-- Wishlist Actions -->
            <div class="flex justify-between items-center mb-6">
                <p class="text-amber-700">{{ $wishlistItems->total() }} item(s) in your wishlist</p>
                <button onclick="clearWishlist()" 
                        class="bg-red-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-red-700 transition-all duration-200">
                    <i class="fas fa-trash mr-2"></i>
                    Clear Wishlist
                </button>
            </div>

            <!-- Wishlist Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-amber-200 product-card group">
                        <!-- Product Image -->
                        <div class="relative mb-4">
                            <img src="{{ $item->product->image ?: 'https://via.placeholder.com/200x200/D2691E/FFFFFF?text=' . urlencode($item->product->name) }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-48 object-cover rounded-lg group-hover:scale-105 transition-transform duration-300">
                            
                            <!-- Remove from Wishlist Button -->
                            <button onclick="removeFromWishlist({{ $item->id }})" 
                                    class="absolute top-2 right-2 bg-red-500 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                            
                            <!-- Add to Cart Button -->
                            <button onclick="addToCart({{ $item->product->id }})" 
                                    class="absolute bottom-2 right-2 bg-amber-600 text-white px-3 py-1 rounded-lg text-sm font-semibold hover:bg-amber-700 transition-colors opacity-0 group-hover:opacity-100">
                                <i class="fas fa-cart-plus mr-1"></i>
                                Add to Cart
                            </button>
                        </div>

                        <!-- Product Info -->
                        <div class="space-y-2">
                            <h3 class="font-semibold text-amber-900 text-lg group-hover:text-amber-700 transition-colors">
                                {{ $item->product->name }}
                            </h3>
                            
                            @if($item->product->brand)
                                <p class="text-amber-600 text-sm">{{ $item->product->brand->name }}</p>
                            @endif
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    @if($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <span class="text-lg font-bold text-green-600">${{ number_format($item->product->sale_price, 2) }}</span>
                                        <span class="text-sm text-gray-500 line-through">${{ number_format($item->product->price, 2) }}</span>
                                    @else
                                        <span class="text-lg font-bold text-amber-600">${{ number_format($item->product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                @if($item->product->is_featured)
                                    <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2 py-1 rounded-full">
                                        Featured
                                    </span>
                                @endif
                            </div>
                            
                            @if($item->product->short_description)
                                <p class="text-amber-700 text-sm line-clamp-2">{{ $item->product->short_description }}</p>
                            @endif
                            
                            <!-- Stock Status -->
                            <div class="flex items-center justify-between text-sm">
                                @if($item->product->stock_quantity > 0)
                                    <span class="text-green-600 flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        In Stock ({{ $item->product->stock_quantity }})
                                    </span>
                                @else
                                    <span class="text-red-600 flex items-center">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Out of Stock
                                    </span>
                                @endif
                                
                                <span class="text-amber-500 text-xs">
                                    Added {{ $item->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('product.show', $item->product->id) }}" 
                               class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-2 px-4 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 text-center block">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($wishlistItems->hasPages())
                <div class="mt-8">
                    {{ $wishlistItems->links() }}
                </div>
            @endif
        @else
            <!-- Empty Wishlist -->
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-pink-500 to-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-heart text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-amber-900 mb-2">Your wishlist is empty</h3>
                <p class="text-amber-700 mb-6">Start adding products to your wishlist to see them here.</p>
                <a href="{{ route('products') }}" 
                   class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Browse Products
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-amber-900 text-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-paw text-amber-400 text-2xl mr-2"></i>
                <span class="text-xl font-bold">Happy Paws</span>
            </div>
            <p class="text-amber-300">&copy; 2024 Happy Paws. All rights reserved.</p>
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

        function removeFromWishlist(wishlistId) {
            if (!confirm('Are you sure you want to remove this item from your wishlist?')) {
                return;
            }

            fetch('/wishlist/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    wishlist_id: wishlistId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.success, 'success');
                    // Reload the page to update the wishlist
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else if (data.error) {
                    showMessage(data.error, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('An error occurred. Please try again.', 'error');
            });
        }

        function clearWishlist() {
            if (!confirm('Are you sure you want to clear your entire wishlist? This action cannot be undone.')) {
                return;
            }

            fetch('/wishlist/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.success, 'success');
                    // Reload the page to update the wishlist
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else if (data.error) {
                    showMessage(data.error, 'error');
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

    <style>
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(139, 69, 19, 0.1), 0 10px 10px -5px rgba(139, 69, 19, 0.04);
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</body>
</html>
