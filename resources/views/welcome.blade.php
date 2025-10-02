<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Happy Paws - Premium Pet Supplies</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <i class="fas fa-paw text-indigo-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900">Happy Paws</span>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="{{ route('products') }}" class="text-gray-700 hover:text-indigo-600">Products</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-indigo-600">Contact</a>
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="bg-red-500 text-white px-4 py-2 rounded">Admin</a>
                        @else
                            <a href="{{ route('dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Dashboard</a>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-indigo-600">Login</a>
                        <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-4 py-2 rounded">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="text-center py-20">
        <div class="max-w-4xl mx-auto px-4">
            <i class="fas fa-paw text-6xl text-indigo-500 mb-8"></i>
            <h1 class="text-5xl font-bold text-gray-900 mb-6">
                Welcome to <span class="text-indigo-600">Happy Paws</span>
            </h1>
            <p class="text-xl text-gray-600 mb-8">
                Your trusted partner for premium pet supplies. Quality products for dogs, cats, and all your beloved pets.
            </p>
            <div class="space-x-4">
                <a href="{{ route('products') }}" class="bg-indigo-600 text-white px-8 py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700">
                    Shop Now
                </a>
                <a href="{{ route('contact') }}" class="bg-white text-indigo-600 px-8 py-3 rounded-lg text-lg font-semibold border-2 border-indigo-200 hover:bg-gray-50">
                    Contact Us
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="bg-white py-16">
        <div class="max-w-6xl mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Choose Happy Paws?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <i class="fas fa-shield-alt text-4xl text-indigo-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Premium Quality</h3>
                    <p class="text-gray-600">Only the highest quality products from trusted brands</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-shipping-fast text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Fast Shipping</h3>
                    <p class="text-gray-600">Quick and reliable delivery to your doorstep</p>
                </div>
                <div class="text-center">
                    <i class="fas fa-heart text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Pet Lovers</h3>
                    <p class="text-gray-600">Made by pet lovers, for pet lovers</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="flex items-center justify-center mb-4">
                <i class="fas fa-paw text-indigo-400 text-2xl mr-2"></i>
                <span class="text-xl font-bold">Happy Paws</span>
            </div>
            <p class="text-gray-400">&copy; 2024 Happy Paws. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
