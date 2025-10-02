<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
                    <a href="{{ route('orders.index') }}" class="text-amber-600 font-semibold px-3 py-2 rounded-lg text-sm font-medium transition-colors duration-200">My Orders</a>
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

    <!-- Orders Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-amber-900 mb-4">My Orders</h1>
            <p class="text-amber-700">Track and manage your pet supply orders</p>
        </div>

        @if($orders->count() > 0)
            <div class="space-y-6">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-amber-200">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-amber-900">
                                    Order #{{ $order->order_number ?? 'ORD-' . $order->id }}
                                </h3>
                                <p class="text-amber-600">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-amber-600">${{ number_format($order->total_amount, 2) }}</p>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($order->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="space-y-3 mb-4">
                            @foreach($order->orderDetails as $detail)
                                <div class="flex items-center space-x-4 p-3 bg-amber-50 rounded-lg">
                                    <img src="{{ $detail->product->image ?: 'https://via.placeholder.com/60x60/D2691E/FFFFFF?text=' . urlencode($detail->product->name) }}" 
                                         alt="{{ $detail->product->name }}" class="w-15 h-15 object-cover rounded">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-amber-900">{{ $detail->product->name }}</h4>
                                        <p class="text-sm text-amber-600">Quantity: {{ $detail->quantity }}</p>
                                    </div>
                                    <span class="font-semibold text-amber-900">${{ number_format($detail->total_price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Summary -->
                        <div class="flex justify-between items-center pt-4 border-t border-amber-200">
                            <div class="text-sm text-amber-600">
                                <p>Subtotal: ${{ number_format($order->subtotal, 2) }}</p>
                                <p>Tax: ${{ number_format($order->tax_amount, 2) }}</p>
                                <p>Shipping: {{ $order->shipping_amount > 0 ? '$' . number_format($order->shipping_amount, 2) : 'Free' }}</p>
                            </div>
                            <a href="{{ route('orders.show', $order->id) }}" 
                               class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-2 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
                                <i class="fas fa-eye mr-2"></i>
                                View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="mt-8">
                    {{ $orders->links() }}
                </div>
            @endif
        @else
            <div class="bg-white rounded-2xl shadow-xl p-12 text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-box text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-amber-900 mb-2">No orders yet</h3>
                <p class="text-amber-700 mb-6">Start shopping to see your orders here.</p>
                <a href="{{ route('products') }}" 
                   class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Start Shopping
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
</body>
</html>
