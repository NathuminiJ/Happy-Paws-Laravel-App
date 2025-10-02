<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Wishlist;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class WishlistManager extends Component
{
    public $wishlistItems = [];

    public function mount()
    {
        $this->loadWishlist();
    }

    public function loadWishlist()
    {
        if (Auth::check()) {
            $this->wishlistItems = Auth::user()->wishlistItems()->with('product')->get();
        }
    }

    public function addToWishlist($productId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to wishlist!');
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }

        $wishlistItem = Wishlist::where('user_id', Auth::id())
                               ->where('product_id', $productId)
                               ->first();

        if ($wishlistItem) {
            session()->flash('info', 'Product is already in your wishlist!');
            return;
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
        ]);

        $this->loadWishlist();
        session()->flash('success', 'Product added to wishlist!');
    }

    public function removeFromWishlist($wishlistItemId)
    {
        $wishlistItem = Wishlist::find($wishlistItemId);
        if ($wishlistItem && $wishlistItem->user_id === Auth::id()) {
            $wishlistItem->delete();
            $this->loadWishlist();
            session()->flash('success', 'Product removed from wishlist!');
        }
    }

    public function clearWishlist()
    {
        if (Auth::check()) {
            Auth::user()->wishlistItems()->delete();
            $this->loadWishlist();
            session()->flash('success', 'Wishlist cleared!');
        }
    }

    public function render()
    {
        $this->loadWishlist();
        return view('livewire.wishlist-manager');
    }
}
