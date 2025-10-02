<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Happy Paws</title>
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

    <!-- Order Details Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <a href="{{ route('orders.index') }}" class="inline-flex items-center text-amber-600 hover:text-amber-800 mb-4">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
            <h1 class="text-3xl font-bold text-amber-900 mb-4">
                Order #{{ $order->order_number ?? 'ORD-' . $order->id }}
            </h1>
            <p class="text-amber-700">Placed on {{ $order->created_at->format('M d, Y \a\t g:i A') }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-amber-200 mb-6">
                    <h2 class="text-xl font-semibold text-amber-900 mb-6">Order Items</h2>
                    <div class="space-y-4">
                        @foreach($order->orderDetails as $detail)
                            <div class="flex items-center space-x-4 p-4 bg-amber-50 rounded-lg">
                                <img src="{{ $detail->product->image ?: 'https://via.placeholder.com/80x80/D2691E/FFFFFF?text=' . urlencode($detail->product->name) }}" 
                                     alt="{{ $detail->product->name }}" class="w-20 h-20 object-cover rounded">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-amber-900 text-lg">{{ $detail->product->name }}</h3>
                                    <p class="text-amber-600">{{ $detail->product->brand->name ?? 'No Brand' }}</p>
                                    <p class="text-sm text-amber-500">SKU: {{ $detail->product->sku ?? 'N/A' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-amber-600">${{ number_format($detail->unit_price, 2) }} each</p>
                                    <p class="text-amber-600">Qty: {{ $detail->quantity }}</p>
                                    <p class="font-bold text-amber-900 text-lg">${{ number_format($detail->total_price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Shipping Information -->
                @if($order->shipment)
                    <div class="bg-white rounded-2xl shadow-xl p-6 border border-amber-200">
                        <h2 class="text-xl font-semibold text-amber-900 mb-6">Shipping Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-semibold text-amber-800 mb-2">Shipping Address</h3>
                                <p class="text-amber-700">{{ $order->shipment->address_line1 }}</p>
                                @if($order->shipment->address_line2)
                                    <p class="text-amber-700">{{ $order->shipment->address_line2 }}</p>
                                @endif
                                <p class="text-amber-700">
                                    {{ $order->shipment->city }}, {{ $order->shipment->state }} {{ $order->shipment->zip_code }}
                                </p>
                                <p class="text-amber-700">{{ $order->shipment->country }}</p>
                            </div>
                            <div>
                                <h3 class="font-semibold text-amber-800 mb-2">Contact</h3>
                                <p class="text-amber-700">{{ $order->shipment->mobile_number }}</p>
                                <p class="text-amber-700">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl p-6 border border-amber-200 sticky top-8">
                    <h2 class="text-xl font-semibold text-amber-900 mb-6">Order Summary</h2>
                    
                    <!-- Status -->
                    <div class="mb-6">
                        <h3 class="font-semibold text-amber-800 mb-2">Order Status</h3>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($order->status === 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                            @elseif($order->status === 'delivered') bg-green-100 text-green-800
                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800
                            @endif">
                            <i class="fas fa-circle mr-2 text-xs"></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>

                    <!-- Payment Information -->
                    @if($order->payments->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold text-amber-800 mb-2">Payment Information</h3>
                            <div class="space-y-2">
                                <p class="text-amber-700">
                                    <span class="font-medium">Method:</span> 
                                    {{ ucfirst(str_replace('_', ' ', $order->payments->first()->payment_method)) }}
                                </p>
                                <p class="text-amber-700">
                                    <span class="font-medium">Status:</span> 
                                    <span class="text-green-600 font-semibold">{{ ucfirst($order->payments->first()->status) }}</span>
                                </p>
                                @if($order->payments->first()->transaction_id)
                                    <p class="text-amber-700">
                                        <span class="font-medium">Transaction ID:</span> 
                                        {{ $order->payments->first()->transaction_id }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Order Totals -->
                    <div class="space-y-3 border-t border-amber-200 pt-4">
                        <div class="flex justify-between text-amber-700">
                            <span>Subtotal</span>
                            <span>${{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-amber-700">
                            <span>Tax</span>
                            <span>${{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-amber-700">
                            <span>Shipping</span>
                            <span>{{ $order->shipping_amount > 0 ? '$' . number_format($order->shipping_amount, 2) : 'Free' }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-amber-900 border-t border-amber-200 pt-3">
                            <span>Total</span>
                            <span>${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-6 space-y-3">
                        <a href="{{ route('products') }}" 
                           class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 text-center block">
                            <i class="fas fa-shopping-bag mr-2"></i>
                            Continue Shopping
                        </a>
                        <a href="{{ route('orders.index') }}" 
                           class="w-full bg-white text-amber-600 py-3 px-6 rounded-lg font-semibold border-2 border-amber-200 hover:bg-amber-50 transition-all duration-200 text-center block">
                            <i class="fas fa-list mr-2"></i>
                            All Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
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
