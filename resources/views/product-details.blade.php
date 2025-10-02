@php
use Illuminate\Support\Facades\Storage;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $product->name }} - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #FDF8F3; /* Light beige background */
        }
        .navbar {
            background-color: #FFFFFF; /* White navbar */
            border-bottom: 1px solid #F0EAD6; /* Light beige border */
        }
        .nav-link {
            color: #8B4513; /* SaddleBrown */
        }
        .nav-link:hover {
            color: #A0522D; /* Sienna */
        }
        .btn-primary {
            background-color: #A0522D; /* Sienna */
            color: white;
        }
        .btn-primary:hover {
            background-color: #8B4513; /* SaddleBrown */
        }
        .text-theme-primary {
            color: #A0522D; /* Sienna */
        }
        .bg-theme-light {
            background-color: #FDF8F3; /* Light beige */
        }
        .product-image {
            transition: transform 0.3s ease;
        }
        .product-image:hover {
            transform: scale(1.05);
        }
        .login-popup {
            background-color: rgba(0, 0, 0, 0.5);
        }
        .login-popup-content {
            background-color: #FFFFFF;
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="bg-theme-light min-h-screen">
    <!-- Navigation -->
    <nav class="navbar shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <i class="fas fa-paw text-amber-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Happy Paws</span>
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="nav-link font-medium">Home</a>
                    <a href="#" class="nav-link font-medium">About Us</a>
                    <a href="{{ route('products') }}" class="nav-link font-medium">Products</a>
                    @auth
                        <a href="{{ route('contact') }}" class="nav-link font-medium">Contact</a>
                        <a href="{{ route('dashboard') }}" class="nav-link font-medium">My Orders</a>
                        <a href="{{ route('cart') }}" class="nav-link font-medium"><i class="fas fa-shopping-cart"></i></a>
                        <a href="#" class="nav-link font-medium"><i class="fas fa-heart"></i></a>
                        <div class="relative">
                            <button class="nav-link font-medium flex items-center" onclick="document.getElementById('profile-dropdown').classList.toggle('hidden')">
                                <i class="fas fa-user-circle mr-1"></i> Profile
                            </button>
                            <div id="profile-dropdown" class="absolute hidden right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-20">
                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn-primary px-4 py-2 rounded-md">Login</a>
                        <a href="{{ route('register') }}" class="btn-primary px-4 py-2 rounded-md">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb -->
    <div class="bg-white border-b border-amber-200">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-theme-primary">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="{{ route('products') }}" class="text-gray-500 hover:text-theme-primary">Products</a>
                    </li>
                    <li>
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Product Details -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Image -->
            <div class="space-y-4">
                <div class="aspect-w-1 aspect-h-1">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/600x600/A0522D/FFFFFF?text=' . urlencode($product->name) }}" 
                         alt="{{ $product->name }}" 
                         class="product-image w-full h-96 object-cover rounded-lg shadow-lg">
                </div>
                
                <!-- Additional Images (placeholder) -->
                <div class="grid grid-cols-4 gap-4">
                    <img src="https://via.placeholder.com/150x150/A0522D/FFFFFF?text=1" 
                         alt="Product view 1" 
                         class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-200">
                    <img src="https://via.placeholder.com/150x150/A0522D/FFFFFF?text=2" 
                         alt="Product view 2" 
                         class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-200">
                    <img src="https://via.placeholder.com/150x150/A0522D/FFFFFF?text=3" 
                         alt="Product view 3" 
                         class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-200">
                    <img src="https://via.placeholder.com/150x150/A0522D/FFFFFF?text=4" 
                         alt="Product view 4" 
                         class="w-full h-24 object-cover rounded-lg cursor-pointer hover:opacity-75 transition duration-200">
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <p class="text-lg text-gray-600 mb-4">{{ $product->brand->name ?? 'No Brand' }}</p>
                    
                    <!-- Rating -->
                    <div class="flex items-center mb-4">
                        <div class="flex items-center">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-yellow-400"></i>
                            @endfor
                        </div>
                        <span class="ml-2 text-sm text-gray-600">(4.8) • 124 reviews</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="border-t border-b border-amber-200 py-6">
                    <div class="flex items-center space-x-4">
                        <span class="text-4xl font-bold text-theme-primary">${{ number_format($product->price, 2) }}</span>
                        <span class="text-lg text-gray-500 line-through">${{ number_format($product->price * 1.2, 2) }}</span>
                        <span class="bg-red-100 text-red-800 text-sm font-medium px-2.5 py-0.5 rounded-full">Save 20%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Free shipping on orders over $50</p>
                </div>

                <!-- Description -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $product->description }}</p>
                </div>

                <!-- Features -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Key Features</h3>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Premium quality materials
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Safe for all pet sizes
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Easy to clean and maintain
                        </li>
                        <li class="flex items-center text-gray-600">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            30-day money-back guarantee
                        </li>
                    </ul>
                </div>

                <!-- Quantity and Add to Cart -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <div class="flex items-center space-x-2">
                            <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                <i class="fas fa-minus text-gray-600"></i>
                            </button>
                            <input type="number" value="1" min="1" max="10" 
                                   class="w-16 text-center border border-gray-300 rounded-lg py-2">
                            <button class="w-10 h-10 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50">
                                <i class="fas fa-plus text-gray-600"></i>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-3">
                        @auth
                            <button onclick="addToCart({{ $product->id }})" 
                                    class="w-full btn-primary py-3 px-6 rounded-lg font-semibold hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Add to Cart
                            </button>
                            <button onclick="addToWishlist({{ $product->id }})" 
                                    class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-heart mr-2"></i>
                                Add to Wishlist
                            </button>
                        @else
                            <button onclick="showLoginModal()" 
                                    class="w-full btn-primary py-3 px-6 rounded-lg font-semibold hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-lock mr-2"></i>
                                Login to Add to Cart
                            </button>
                            <button onclick="showLoginModal()" 
                                    class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-lg font-semibold hover:bg-gray-200 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-lock mr-2"></i>
                                Login to Add to Wishlist
                            </button>
                        @endauth
                    </div>
                </div>

                <!-- Product Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="font-medium text-gray-900">SKU:</span>
                            <span class="text-gray-600">HP-{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Brand:</span>
                            <span class="text-gray-600">{{ $product->brand->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Availability:</span>
                            <span class="text-green-600 font-medium">In Stock</span>
                        </div>
                        <div>
                            <span class="font-medium text-gray-900">Shipping:</span>
                            <span class="text-gray-600">Free on orders $50+</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Products</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($related_products as $related)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                        <img src="https://via.placeholder.com/300x200/4F46E5/FFFFFF?text={{ urlencode($related->name) }}" 
                             alt="{{ $related->name }}" class="w-full h-48 object-cover">
                        
                        <div class="p-4">
                            <h3 class="font-semibold text-lg text-gray-900 mb-2">{{ $related->name }}</h3>
                            <p class="text-gray-600 text-sm mb-3">{{ \Illuminate\Support\Str::limit($related->description, 60) }}</p>
                            
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xl font-bold text-theme-primary">${{ number_format($related->price, 2) }}</span>
                                <span class="text-sm text-gray-500">{{ $related->brand->name ?? 'No Brand' }}</span>
                            </div>
                            
                            <a href="{{ route('product.show', $related->id) }}" 
                               class="w-full btn-primary py-2 rounded hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <p class="text-gray-500">No related products found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div id="loginModal" class="login-popup fixed inset-0 flex items-center justify-center hidden z-50">
        <div class="login-popup-content p-8 max-w-sm mx-auto text-center relative">
            <button class="absolute top-4 right-4 text-gray-500 hover:text-gray-700" onclick="document.getElementById('loginModal').classList.add('hidden')">
                <i class="fas fa-times text-xl"></i>
            </button>
            <i class="fas fa-lock text-5xl text-theme-primary mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-4">Please Login to Proceed</h3>
            <p class="text-gray-600 mb-6">You need to be logged in to add items to your cart or wishlist.</p>
            <div class="flex flex-col space-y-4">
                <a href="{{ route('login') }}" class="btn-primary px-6 py-3 rounded-md text-lg font-semibold">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </a>
                <a href="{{ route('register') }}" class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md text-lg font-semibold hover:bg-gray-300 transition duration-300">
                    <i class="fas fa-user-plus mr-2"></i> Register
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-white shadow-inner mt-12 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-paw text-amber-600 text-2xl mr-2"></i>
                <span class="text-xl font-bold">Happy Paws</span>
            </div>
            <p class="text-gray-400">&copy; 2024 Happy Paws. All rights reserved.</p>
        </div>
    </footer>

    <script>
        function addToCart(productId) {
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
            
            if (!isLoggedIn) {
                showLoginModal();
                return;
            }

            // Get quantity from input
            const quantityInput = document.querySelector('input[type="number"]');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
            button.disabled = true;

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showNotification('Product added to cart!', 'success');
                    
                    // Update cart count if element exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count || 0;
                    }
                } else {
                    showNotification(data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function addToWishlist(productId) {
            const isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};
            
            if (!isLoggedIn) {
                showLoginModal();
                return;
            }

            // Show loading state
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Adding...';
            button.disabled = true;

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
                    showNotification('Product added to wishlist!', 'success');
                } else {
                    showNotification(data.message || 'Failed to add product to wishlist', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred. Please try again.', 'error');
            })
            .finally(() => {
                // Restore button state
                button.innerHTML = originalText;
                button.disabled = false;
            });
        }

        function showLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
            document.getElementById('loginModal').classList.add('flex');
        }

        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' : 
                type === 'error' ? 'bg-red-500 text-white' : 
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
                    <span>${message}</span>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Quantity controls
        document.addEventListener('DOMContentLoaded', function() {
            const quantityInput = document.querySelector('input[type="number"]');
            const minusBtn = document.querySelector('button:has(.fa-minus)');
            const plusBtn = document.querySelector('button:has(.fa-plus)');

            if (minusBtn) {
                minusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                });
            }

            if (plusBtn) {
                plusBtn.addEventListener('click', function() {
                    const currentValue = parseInt(quantityInput.value);
                    const maxValue = parseInt(quantityInput.max);
                    if (currentValue < maxValue) {
                        quantityInput.value = currentValue + 1;
                    }
                });
            }
        });
    </script>
</body>
</html>
