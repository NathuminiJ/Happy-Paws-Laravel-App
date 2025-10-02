<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Customer\FeedbackController as CustomerFeedbackController;

// Guest routes
Route::get('/', function () {
    $featured_products = \App\Models\Product::with('brand')->inRandomOrder()->limit(8)->get();
    return view('landing', compact('featured_products'));
})->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/product/{id}', function($id) {
    $product = \App\Models\Product::with('brand')->findOrFail($id);
    return view('product-details-minimal', compact('product'));
})->name('product.show');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

// Cart and Wishlist routes
Route::post('/cart/add', function(\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json(['error' => 'Please login to add items to cart'], 401);
    }
    
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'integer|min:1'
    ]);
    
    $product = \App\Models\Product::findOrFail($request->product_id);
    $quantity = $request->quantity ?? 1;
    
    $cartItem = \App\Models\Cart::where('user_id', auth()->id())
                               ->where('product_id', $product->id)
                               ->first();
    
    if ($cartItem) {
        $cartItem->increment('quantity', $quantity);
    } else {
        \App\Models\Cart::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price,
        ]);
    }
    
    return response()->json(['success' => 'Product added to cart!']);
})->name('cart.add');

Route::post('/wishlist/add', function(\Illuminate\Http\Request $request) {
    if (!auth()->check()) {
        return response()->json(['error' => 'Please login to add items to wishlist'], 401);
    }
    
    $request->validate([
        'product_id' => 'required|exists:products,id'
    ]);
    
    $wishlistItem = \App\Models\Wishlist::where('user_id', auth()->id())
                                       ->where('product_id', $request->product_id)
                                       ->first();
    
    if ($wishlistItem) {
        return response()->json(['info' => 'Product is already in your wishlist!']);
    }
    
    \App\Models\Wishlist::create([
        'user_id' => auth()->id(),
        'product_id' => $request->product_id,
    ]);
    
    return response()->json(['success' => 'Product added to wishlist!']);
})->name('wishlist.add');

// Custom registration routes
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

// Cart route (public - handles auth internally)
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

// Customer routes (authenticated)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Customer home
    Route::get('/customer-home', function () {
        $featured_products = \App\Models\Product::with('brand')->inRandomOrder()->limit(8)->get();
        return view('customer-home', compact('featured_products'));
    })->name('customer.home');
    
    // Redirect dashboard to customer home
    Route::get('/dashboard', function () {
        return redirect()->route('customer.home');
    })->name('dashboard');
    
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [\App\Http\Controllers\CheckoutController::class, 'processOrder'])->name('checkout.process');
    
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    
    // Wishlist routes
    Route::get('/wishlist', [\App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/remove', [\App\Http\Controllers\WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/clear', [\App\Http\Controllers\WishlistController::class, 'clear'])->name('wishlist.clear');
    
    // Customer Feedback routes (MongoDB)
    Route::get('/feedback', [CustomerFeedbackController::class, 'index'])->name('customer.feedback.index');
    Route::get('/feedback/create', [CustomerFeedbackController::class, 'create'])->name('customer.feedback.create');
    Route::post('/feedback', [CustomerFeedbackController::class, 'store'])->name('customer.feedback.store');
    Route::get('/feedback/{feedback}', [CustomerFeedbackController::class, 'show'])->name('customer.feedback.show');
    Route::get('/feedback/{feedback}/edit', [CustomerFeedbackController::class, 'edit'])->name('customer.feedback.edit');
    Route::put('/feedback/{feedback}', [CustomerFeedbackController::class, 'update'])->name('customer.feedback.update');
    Route::delete('/feedback/{feedback}', [CustomerFeedbackController::class, 'destroy'])->name('customer.feedback.destroy');
});

// Admin routes (admin only, no registration)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin'
])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/customers', [AdminController::class, 'customers'])->name('customers');
    Route::get('/messages', [AdminController::class, 'messages'])->name('messages');
    
    // Admin CRUD operations
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::resource('customers', \App\Http\Controllers\Admin\CustomerController::class);
    Route::resource('feedback', \App\Http\Controllers\Admin\FeedbackController::class);
});

// Admin authentication routes
Route::get('/admin-login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin-login', [AdminAuthController::class, 'login']);
Route::post('/admin-logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

// MongoDB Test routes
Route::get('/test-mongodb', [FeedbackController::class, 'testConnection'])->name('test.mongodb');
Route::get('/mongodb-test', function() {
    return view('mongodb-test');
})->name('mongodb.test');

