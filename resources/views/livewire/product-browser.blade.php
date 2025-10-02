@php
use Illuminate\Support\Facades\Storage;
@endphp

<div>
    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" wire:model.live="search" placeholder="Search products..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <!-- Brand Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <select wire:model.live="brandFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Brands</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Min Price</label>
                <input type="number" wire:model.live="priceMin" placeholder="Min price" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Max Price</label>
                <input type="number" wire:model.live="priceMax" placeholder="Max price" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        
        <div class="flex justify-between items-center mt-4">
            <button wire:click="clearFilters" class="text-indigo-600 hover:text-indigo-800 font-medium">
                Clear Filters
            </button>
            
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Sort by:</span>
                <select wire:model.live="sortBy" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="created_at">Newest</option>
                </select>
                <button wire:click="sortBy('{{ $sortBy }}')" class="text-indigo-600 hover:text-indigo-800">
                    @if($sortDirection === 'asc')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($products as $product)
            <div class="pet-card bg-white rounded-xl shadow-lg overflow-hidden group">
                <div class="relative overflow-hidden">
                    <img src="{{ $product->image ? Storage::url($product->image) : 'https://via.placeholder.com/300x200/4F46E5/FFFFFF?text=' . urlencode($product->name) }}" 
                         alt="{{ $product->name }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    
                    @if($product->isOnSale())
                        <div class="absolute top-3 left-3 bg-gradient-to-r from-red-500 to-pink-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            <i class="fas fa-tag mr-1"></i>Sale
                        </div>
                    @endif
                    
                    @if($product->is_featured)
                        <div class="absolute top-3 right-3 bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold shadow-lg">
                            <i class="fas fa-star mr-1"></i>Featured
                        </div>
                    @endif
                    
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                        <button class="opacity-0 group-hover:opacity-100 bg-white text-indigo-600 px-4 py-2 rounded-full font-medium transition-all duration-300 transform translate-y-4 group-hover:translate-y-0">
                            <i class="fas fa-eye mr-2"></i>Quick View
                        </button>
                    </div>
                </div>
                
                <div class="p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <i class="fas fa-tag mr-1"></i>{{ $product->brand->name }}
                        </span>
                        <div class="flex items-center text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-xs"></i>
                            @endfor
                            <span class="text-gray-500 text-xs ml-1">(4.8)</span>
                        </div>
                    </div>
                    
                    <h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors duration-200">{{ $product->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($product->description, 80) }}</p>
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            @if($product->isOnSale())
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="text-lg text-gray-500 line-through ml-2">${{ number_format($product->price, 2) }}</span>
                                </div>
                                <div class="text-sm text-red-600 font-medium">
                                    Save ${{ number_format($product->price - $product->sale_price, 2) }}
                                </div>
                            @else
                                <span class="text-2xl font-bold text-indigo-600">${{ number_format($product->current_price, 2) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-box mr-1"></i>
                            <span class="{{ $product->stock_quantity > 10 ? 'text-green-600' : ($product->stock_quantity > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Out of stock' }}
                            </span>
                        </div>
                        <button wire:click="addToCart({{ $product->id }})" 
                                class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-lg font-medium hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center group/btn"
                                @if(!$product->isInStock()) disabled @endif>
                            @if($product->isInStock())
                                <i class="fas fa-shopping-cart mr-2 group-hover/btn:scale-110 transition-transform"></i>
                                Add to Cart
                            @else
                                <i class="fas fa-times mr-2"></i>
                                Out of Stock
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @endif
</div>