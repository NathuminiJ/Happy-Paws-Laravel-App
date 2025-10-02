<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <a href="{{ route('contact') }}" class="flex items-center text-amber-600 font-semibold px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                        <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform"></i>
                        Contact
                    </a>
                    
                    @auth
                        <a href="{{ route('cart') }}" class="relative flex items-center text-amber-800 hover:text-amber-600 px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200 group">
                            <i class="fas fa-shopping-cart mr-2 group-hover:scale-110 transition-transform"></i>
                            Cart
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

    <!-- Contact Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-amber-900 mb-4">Contact Us</h1>
            <p class="text-amber-700">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Contact Form -->
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-md p-6 border border-amber-100">
                <h2 class="text-xl font-semibold text-amber-900 mb-4">Send us a message</h2>
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Name
                        </label>
                        <input type="text" id="name" name="name" required 
                               class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <input type="email" id="email" name="email" required 
                               class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Subject
                        </label>
                        <input type="text" id="subject" name="subject" required 
                               class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-amber-700 mb-2">
                            <i class="fas fa-comment mr-2"></i>Message
                        </label>
                        <textarea id="message" name="message" rows="4" required 
                                  class="w-full px-3 py-2 border border-amber-300 rounded-md focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
            </div>

            <!-- Contact Info -->
            <div class="bg-white/80 backdrop-blur-sm rounded-lg shadow-md p-6 border border-amber-100">
                <h2 class="text-xl font-semibold text-amber-900 mb-4">Get in touch</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-amber-600 text-xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold text-amber-900">Address</h3>
                            <p class="text-amber-700">123 Pet Street, Animal City, AC 12345</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-amber-600 text-xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold text-amber-900">Phone</h3>
                            <p class="text-amber-700">+1 (555) 123-4567</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-amber-600 text-xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold text-amber-900">Email</h3>
                            <p class="text-amber-700">info@happypaws.com</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock text-amber-600 text-xl mr-4"></i>
                        <div>
                            <h3 class="font-semibold text-amber-900">Hours</h3>
                            <p class="text-amber-700">Mon-Fri: 9AM-6PM<br>Sat-Sun: 10AM-4PM</p>
                        </div>
                    </div>
                </div>
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
</body>
</html>