<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Cart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CartManager extends Component
{
    public $cartItems = [];
    public $total = 0;
    public $subtotal = 0;
    public $tax = 0;
    public $shipping = 0;

    public function mount()
    {
        // Don't load cart on mount to avoid session conflicts
        $this->cartItems = collect();
        $this->total = 0;
        $this->subtotal = 0;
        $this->tax = 0;
        $this->shipping = 0;
    }

    public function loadCart()
    {
        if (Auth::check()) {
            $this->cartItems = Auth::user()->cartItems()->with('product')->get();
            $this->calculateTotals();
        } else {
            $this->cartItems = collect();
            $this->total = 0;
            $this->subtotal = 0;
            $this->tax = 0;
            $this->shipping = 0;
        }
    }

    public function addToCart($productId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please login to add items to cart!');
            return;
        }

        $product = Product::find($productId);
        if (!$product) {
            session()->flash('error', 'Product not found!');
            return;
        }

        $cartItem = Cart::where('user_id', Auth::id())
                       ->where('product_id', $productId)
                       ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }

        $this->loadCart();
        session()->flash('success', 'Product added to cart!');
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeFromCart($cartItemId);
            return;
        }

        $cartItem = Cart::find($cartItemId);
        if (!$cartItem || $cartItem->user_id !== Auth::id()) {
            session()->flash('error', 'Invalid cart item!');
            return;
        }

        if ($quantity > $cartItem->product->stock_quantity) {
            session()->flash('error', 'Insufficient stock!');
            return;
        }

        $cartItem->update(['quantity' => $quantity]);
        $this->loadCart();
        session()->flash('success', 'Cart updated!');
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = Cart::find($cartItemId);
        if ($cartItem && $cartItem->user_id === Auth::id()) {
            $cartItem->delete();
            $this->loadCart();
            session()->flash('success', 'Product removed from cart!');
        }
    }

    public function clearCart()
    {
        if (Auth::check()) {
            Auth::user()->cartItems()->delete();
            $this->loadCart();
            session()->flash('success', 'Cart cleared!');
        }
    }

    public function calculateTotals()
    {
        $this->subtotal = 0;
        
        foreach ($this->cartItems as $item) {
            $this->subtotal += $item->price * $item->quantity;
        }

        // Calculate tax (8% example)
        $this->tax = $this->subtotal * 0.08;
        
        // Calculate shipping (free over $50, otherwise $10)
        $this->shipping = $this->subtotal >= 50 ? 0 : 10;
        
        $this->total = $this->subtotal + $this->tax + $this->shipping;
    }

    public function proceedToCheckout()
    {
        if ($this->cartItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty!');
            return;
        }

        return redirect()->route('checkout');
    }

    public function render()
    {
        // Load cart data only when rendering
        $this->loadCart();
        return view('livewire.cart-manager');
    }
}