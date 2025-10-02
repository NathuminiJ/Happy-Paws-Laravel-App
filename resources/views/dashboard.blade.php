<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <i class="fas fa-paw text-indigo-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Happy Paws</span>
                    </a>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-indigo-600">Home</a>
                    <a href="{{ route('products') }}" class="text-gray-700 hover:text-indigo-600">Products</a>
                    <a href="{{ route('contact') }}" class="text-gray-700 hover:text-indigo-600">Contact</a>
                    <a href="{{ route('dashboard') }}" class="text-indigo-600 font-semibold">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-700 hover:text-indigo-600">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Dashboard Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Here's what's happening with your account.</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-shopping-cart text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Orders</p>
                        <p class="text-2xl font-bold text-gray-900">{{ auth()->user()->orders->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-heart text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Favorites</p>
                        <p class="text-2xl font-bold text-gray-900">12</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-gift text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Loyalty Points</p>
                        <p class="text-2xl font-bold text-gray-900">1,250</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Recent Orders</h2>
            <div class="space-y-4">
                @forelse(auth()->user()->orders->take(3) as $order)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-box text-indigo-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">${{ number_format($order->total_amount ?? 0, 2) }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $order->status ?? 'Pending' }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-shopping-cart text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No orders yet</p>
                        <a href="{{ route('products') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Start shopping</a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <a href="{{ route('products') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="text-center">
                    <i class="fas fa-shopping-bag text-4xl text-indigo-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Browse Products</h3>
                    <p class="text-gray-600 text-sm">Discover our amazing pet supplies</p>
                </div>
            </a>
            
            <a href="{{ route('cart') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="text-center">
                    <i class="fas fa-shopping-cart text-4xl text-green-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">View Cart</h3>
                    <p class="text-gray-600 text-sm">Check your shopping cart</p>
                </div>
            </a>
            
            <a href="{{ route('profile') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="text-center">
                    <i class="fas fa-user-circle text-4xl text-purple-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Update Profile</h3>
                    <p class="text-gray-600 text-sm">Manage your account details</p>
                </div>
            </a>
            
            <a href="{{ route('contact') }}" class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-300">
                <div class="text-center">
                    <i class="fas fa-envelope text-4xl text-yellow-600 mb-4"></i>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Contact Support</h3>
                    <p class="text-gray-600 text-sm">Get help from our team</p>
                </div>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-8 mt-16">
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