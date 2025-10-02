@php
use Illuminate\Support\Facades\Storage;
@endphp

<div class="product-card bg-white rounded-lg shadow-md overflow-hidden">
    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/300x200/D2691E/FFFFFF?text=' . urlencode($product->name) }}" 
         alt="{{ $product->name }}" class="w-full h-48 object-cover">
    
    <div class="p-6">
        <h3 class="font-semibold text-lg text-amber-900 mb-2">{{ $product->name }}</h3>
        <p class="text-amber-700 text-sm mb-3">{{ Str::limit($product->description, 80) }}</p>
        
        <div class="flex items-center justify-between mb-3">
            <span class="text-2xl font-bold text-amber-600">${{ number_format($product->price, 2) }}</span>
            <span class="text-sm text-amber-600">{{ $product->brand->name ?? 'No Brand' }}</span>
        </div>
        
        <div class="space-y-2">
            <a href="{{ route('product.show', $product->id) }}" 
               class="w-full bg-amber-600 text-white py-2 rounded hover:bg-amber-700 transition duration-300 flex items-center justify-center">
                <i class="fas fa-eye mr-2"></i>
                View Details
            </a>
            
            @auth
                <div class="space-y-2">
                    <button wire:click="addToCart" 
                            class="w-full {{ $isInCart ? 'bg-green-600 hover:bg-green-700' : 'bg-orange-600 hover:bg-orange-700' }} text-white py-2 rounded transition duration-300">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        {{ $isInCart ? 'In Cart' : 'Add to Cart' }}
                    </button>
                    <button wire:click="addToWishlist" 
                            class="w-full {{ $isInWishlist ? 'bg-green-600 hover:bg-green-700' : 'bg-pink-600 hover:bg-pink-700' }} text-white py-2 rounded transition duration-300">
                        <i class="fas fa-heart mr-2"></i>
                        {{ $isInWishlist ? 'In Wishlist' : 'Add to Wishlist' }}
                    </button>
                </div>
            @else
                <button onclick="showLoginModal()" 
                        class="w-full bg-gray-400 text-white py-2 rounded cursor-not-allowed flex items-center justify-center">
                    <i class="fas fa-lock mr-2"></i>
                    Login to Add to Cart
                </button>
            @endauth
        </div>
    </div>
</div>
