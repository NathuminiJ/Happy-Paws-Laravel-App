<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Cart;
use App\Models\Wishlist;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ProductCard extends Component
{
    public $product;
    public $isInCart = false;
    public $isInWishlist = false;

    public function mount($product)
    {
        $this->product = $product;
        $this->checkCartStatus();
        $this->checkWishlistStatus();
    }

    public function addToCart()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart!');
            return;
        }

        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('product_id', $this->product->id)
                       ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
                'quantity' => 1,
                'price' => $this->product->price,
            ]);
        }

        $this->checkCartStatus();
        session()->flash('success', 'Product added to cart!');
    }

    public function addToWishlist()
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to wishlist!');
            return;
        }

        $wishlistItem = Wishlist::where('user_id', Auth::id())
                               ->where('product_id', $this->product->id)
                               ->first();

        if ($wishlistItem) {
            session()->flash('info', 'Product is already in your wishlist!');
            return;
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
        ]);

        $this->checkWishlistStatus();
        session()->flash('success', 'Product added to wishlist!');
    }

    public function checkCartStatus()
    {
        if (Auth::check()) {
            $this->isInCart = Cart::where('user_id', Auth::id())
                                 ->where('product_id', $this->product->id)
                                 ->exists();
        }
    }

    public function checkWishlistStatus()
    {
        if (Auth::check()) {
            $this->isInWishlist = Wishlist::where('user_id', Auth::id())
                                         ->where('product_id', $this->product->id)
                                         ->exists();
        }
    }

    public function render()
    {
        return view('livewire.product-card');
    }
}
