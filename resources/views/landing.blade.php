<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 0;
            border: none;
            border-radius: 20px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
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
                    <a href="{{ route('products') }}" class="flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-shopping-bag mr-2 group-hover:scale-110 transition-transform"></i>
                        Products
                    </a>
                    <a href="{{ route('login') }}" class="text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:from-amber-700 hover:to-orange-700 transition-colors duration-200">
                        Register
                    </a>
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
                    Welcome to <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">Happy Paws</span>
                </h1>
                <p class="text-xl text-amber-700 mb-8 max-w-3xl mx-auto">
                    Your trusted partner for premium pet supplies. We provide the highest quality products for dogs, cats, and all your beloved pets.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('products') }}" class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-8 py-4 rounded-lg text-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        Browse Products
                    </a>
                    <a href="#about" class="bg-white text-amber-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-amber-50 transition-all duration-200 shadow-lg hover:shadow-xl border-2 border-amber-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">About Happy Paws</h2>
                <p class="text-lg text-amber-700">We're passionate about providing the best for your furry friends</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-amber-900 mb-2">Premium Quality</h3>
                    <p class="text-amber-700">Only the highest quality products from trusted brands</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-amber-900 mb-2">Fast Shipping</h3>
                    <p class="text-amber-700">Quick and reliable delivery to your doorstep</p>
                </div>
                
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-amber-900 mb-2">Pet Lovers</h3>
                    <p class="text-amber-700">Made by pet lovers, for pet lovers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Brands Section -->
    <div class="py-20 bg-gradient-to-r from-amber-50 to-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">Our Trusted Brands</h2>
                <p class="text-lg text-amber-700">Partnering with the best in pet care</p>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-crown text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-amber-900">Royal Canin</h3>
                </div>
                <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-star text-orange-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-amber-900">Hill's Science</h3>
                </div>
                <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-gem text-yellow-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-amber-900">Purina Pro</h3>
                </div>
                <div class="bg-white rounded-lg p-6 text-center shadow-md hover:shadow-lg transition duration-300">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-amber-600 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-amber-900">Whiskas</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Products Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-amber-900 mb-4">Featured Products</h2>
                <p class="text-lg text-amber-700">Discover our most popular pet supplies</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($featured_products as $product)
                    <div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
                        <img src="https://via.placeholder.com/300x200/D2691E/FFFFFF?text={{ urlencode($product->name) }}" 
                             alt="{{ $product->name }}" class="w-full h-48 object-cover">
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ $product->name }}</h3>
                            <p class="text-amber-700 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
                            
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
                                <span class="text-sm text-amber-600">{{ $product->brand->name ?? 'No Brand' }}</span>
                            </div>
                            
                            <div class="space-y-2">
                                <button onclick="showLoginModal()" 
                                        class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                                    <i class="fas fa-eye mr-2"></i>
                                    View Details
                                </button>
                                
                                <button onclick="showLoginModal()" 
                                        class="w-full bg-orange-600 text-white py-2 rounded hover:bg-orange-700 transition duration-300">
                                    <i class="fas fa-shopping-cart mr-2"></i>
                                    Add to Cart
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

    <!-- CTA Section -->
    <div class="bg-gradient-to-r from-amber-600 to-orange-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Shop?</h2>
            <p class="text-xl text-amber-100 mb-8">Join thousands of happy pet owners who trust Happy Paws</p>
            <div class="space-x-4">
                <a href="{{ route('register') }}" class="bg-white text-amber-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-amber-50 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <i class="fas fa-user-plus mr-2"></i>
                    Create Account
                </a>
                <a href="{{ route('login') }}" class="bg-transparent text-white px-8 py-4 rounded-lg text-lg font-semibold border-2 border-white hover:bg-white hover:text-amber-600 transition-all duration-200">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign In
                </a>
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

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <div class="bg-gradient-to-r from-amber-600 to-orange-600 text-white p-6 rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-paw text-2xl mr-3"></i>
                        <h2 class="text-xl font-bold">Login Required</h2>
                    </div>
                    <span onclick="closeLoginModal()" class="text-white hover:text-amber-200 cursor-pointer text-2xl">&times;</span>
                </div>
            </div>
            <div class="p-6">
                <p class="text-amber-700 mb-6 text-center">Please login to view product details and add items to your cart.</p>
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
    </div>

    <script>
        function showLoginModal() {
            document.getElementById('loginModal').style.display = 'block';
        }

        function closeLoginModal() {
            document.getElementById('loginModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('loginModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>