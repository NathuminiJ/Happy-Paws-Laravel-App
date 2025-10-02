<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Happy Paws</title>
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

    <!-- Checkout Content -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-amber-900 mb-4">Checkout</h1>
            <p class="text-amber-700">Complete your order to get your pet supplies delivered</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Checkout Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-amber-900 mb-6">Shipping Information</h2>
                <form id="checkoutForm" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-amber-700 mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" required 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="last_name" class="block text-sm font-medium text-amber-700 mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" required 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-amber-700 mb-2">Email</label>
                        <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" required 
                               class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-amber-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required 
                               class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-amber-700 mb-2">Address</label>
                        <textarea id="address" name="address" rows="3" required 
                                  class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="city" class="block text-sm font-medium text-amber-700 mb-2">City</label>
                            <input type="text" id="city" name="city" required 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="state" class="block text-sm font-medium text-amber-700 mb-2">State</label>
                            <input type="text" id="state" name="state" required 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                        <div>
                            <label for="zip" class="block text-sm font-medium text-amber-700 mb-2">ZIP Code</label>
                            <input type="text" id="zip" name="zip" required 
                                   class="w-full px-3 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-amber-900 mb-4">Payment Method</h3>
                    <div class="space-y-3">
                        <label class="flex items-center p-3 border border-amber-300 rounded-lg cursor-pointer hover:bg-amber-50">
                            <input type="radio" name="payment_method" value="card" checked class="text-amber-600">
                            <i class="fas fa-credit-card text-amber-600 ml-3 mr-3"></i>
                            <span class="text-amber-700">Credit/Debit Card</span>
                        </label>
                        <label class="flex items-center p-3 border border-amber-300 rounded-lg cursor-pointer hover:bg-amber-50">
                            <input type="radio" name="payment_method" value="paypal" class="text-amber-600">
                            <i class="fab fa-paypal text-amber-600 ml-3 mr-3"></i>
                            <span class="text-amber-700">PayPal</span>
                        </label>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                        <i class="fas fa-lock mr-2"></i>
                        Place Order
                    </button>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-amber-900 mb-6">Order Summary</h2>
                
                <!-- Cart Items -->
                <div class="space-y-4 mb-6">
                    @foreach($cartItems as $item)
                        <div class="flex items-center space-x-4 p-3 bg-amber-50 rounded-lg">
                            <img src="{{ $item->product->image ?: 'https://via.placeholder.com/60x60/D2691E/FFFFFF?text=' . urlencode($item->product->name) }}" 
                                 alt="{{ $item->product->name }}" class="w-15 h-15 object-cover rounded">
                            <div class="flex-1">
                                <h3 class="font-medium text-amber-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-amber-600">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <span class="font-semibold text-amber-900">${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>

                <!-- Order Totals -->
                <div class="space-y-3 border-t border-amber-200 pt-4">
                    <div class="flex justify-between text-amber-700">
                        <span>Subtotal</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-amber-700">
                        <span>Shipping</span>
                        <span class="{{ $shipping > 0 ? 'text-amber-700' : 'text-green-600' }}">
                            {{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}
                        </span>
                    </div>
                    <div class="flex justify-between text-amber-700">
                        <span>Tax</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold text-amber-900 border-t border-amber-200 pt-3">
                        <span>Total</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                <!-- Security Notice -->
                <div class="mt-6 p-4 bg-green-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>
                        <span class="text-sm text-green-700">Your payment information is secure and encrypted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-amber-900 mb-4">Order Placed Successfully!</h2>
            <p class="text-amber-700 mb-6">
                Thank you for your order! You'll be receiving your order within 7+ days from the date of order.
            </p>
            <div class="space-y-3">
                <a href="{{ route('orders.index') }}" 
                   class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 block">
                    <i class="fas fa-box mr-2"></i>
                    View My Orders
                </a>
                <a href="{{ route('customer.home') }}" 
                   class="w-full bg-white text-amber-600 py-3 px-6 rounded-lg font-semibold border-2 border-amber-200 hover:bg-amber-50 transition-all duration-200 block">
                    <i class="fas fa-home mr-2"></i>
                    Continue Shopping
                </a>
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

    <script>
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            submitBtn.disabled = true;
            
            //   Send AJAX request
            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    document.getElementById('successModal').classList.remove('hidden');
                    document.getElementById('successModal').classList.add('flex');
                } else {
                    alert('Error: ' + (data.error || 'Failed to process order'));
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    </script>
</body>
</html>
