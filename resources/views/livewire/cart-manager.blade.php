<div>
    @if(!auth()->check())
        <!-- Guest User - Login Required -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-amber-200">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-lock text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-amber-900 mb-4">Login Required</h2>
                <p class="text-amber-700 mb-6">You need to be logged in to view your shopping cart and make purchases.</p>
                
                <div class="space-y-4">
                    <a href="{{ route('login') }}" 
                       class="w-full bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Login to Continue
                    </a>
                    
                    <div class="text-center">
                        <span class="text-amber-600">Don't have an account? </span>
                        <a href="{{ route('register') }}" class="text-amber-700 hover:text-amber-600 font-medium">
                            Create one here
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @elseif($cartItems->isEmpty())
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-amber-200">
            <div class="text-center py-12">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-600 to-orange-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-white text-3xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-amber-900 mb-2">Your cart is empty</h3>
                <p class="text-amber-700 mb-6">Start adding some products to your cart.</p>
                <a href="{{ route('products') }}" 
                   class="bg-gradient-to-r from-amber-600 to-orange-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200 inline-flex items-center">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    @else
        <div class="space-y-6">
            @foreach($cartItems as $item)
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 border border-amber-200 flex items-center space-x-6">
                    <img src="{{ $item->product->image ?: 'https://via.placeholder.com/100x100/D2691E/FFFFFF?text=' . urlencode($item->product->name) }}" 
                         alt="{{ $item->product->name }}" class="w-20 h-20 object-cover rounded-lg">
                    
                    <div class="flex-1">
                        <h3 class="font-semibold text-xl text-amber-900">{{ $item->product->name }}</h3>
                        <p class="text-amber-700">${{ number_format($item->price, 2) }} each</p>
                        <p class="text-sm text-amber-600">{{ $item->product->brand->name ?? 'No Brand' }}</p>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" 
                                class="bg-amber-200 text-amber-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-amber-300 transition-colors">
                            <i class="fas fa-minus text-sm"></i>
                        </button>
                        <span class="w-12 text-center font-semibold text-amber-900">{{ $item->quantity }}</span>
                        <button wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" 
                                class="bg-amber-200 text-amber-800 w-10 h-10 rounded-full flex items-center justify-center hover:bg-amber-300 transition-colors">
                            <i class="fas fa-plus text-sm"></i>
                        </button>
                    </div>
                    
                    <div class="text-right">
                        <p class="font-bold text-xl text-amber-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                        <button wire:click="removeFromCart({{ $item->id }})" 
                                class="text-red-600 hover:text-red-800 text-sm font-medium transition-colors">
                            <i class="fas fa-trash mr-1"></i>
                            Remove
                        </button>
                    </div>
                </div>
            @endforeach
            
            <!-- Cart Summary -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-8 border border-amber-200">
                <h3 class="text-2xl font-bold text-amber-900 mb-6">Order Summary</h3>
                <div class="space-y-4">
                    <div class="flex justify-between text-lg">
                        <span class="text-amber-700">Subtotal:</span>
                        <span class="font-semibold text-amber-900">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-amber-700">Tax (8%):</span>
                        <span class="font-semibold text-amber-900">${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-lg">
                        <span class="text-amber-700">Shipping:</span>
                        <span class="font-semibold text-amber-900">{{ $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free' }}</span>
                    </div>
                    <div class="border-t-2 border-amber-200 pt-4">
                        <div class="flex justify-between text-2xl font-bold">
                            <span class="text-amber-900">Total:</span>
                            <span class="text-amber-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex space-x-4 mt-8">
                    <button wire:click="clearCart" 
                            class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-700 transition-all duration-200">
                        <i class="fas fa-trash mr-2"></i>
                        Clear Cart
                    </button>
                    <button wire:click="proceedToCheckout" 
                            class="flex-1 bg-gradient-to-r from-amber-600 to-orange-600 text-white py-3 px-6 rounded-lg font-semibold hover:from-amber-700 hover:to-orange-700 transition-all duration-200">
                        <i class="fas fa-credit-card mr-2"></i>
                        Proceed to Checkout
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50">
            <i class="fas fa-exclamation-circle mr-2"></i>
            {{ session('error') }}
        </div>
    @endif
</div>