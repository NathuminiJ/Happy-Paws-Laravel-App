<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\AuthController;

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {
    // Authentication routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Product routes
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/products/search', [ProductController::class, 'search']);
    Route::get('/products/brand/{brandId}', [ProductController::class, 'byBrand']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // Payment methods
    Route::get('/payment-methods', [PaymentController::class, 'methods']);
});

// Protected API routes (authentication required)
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    
    // Cart routes
    Route::apiResource('cart', CartController::class)->only(['index', 'store', 'update', 'destroy']);
    Route::delete('/cart', [CartController::class, 'clear']);
    
    // Order routes
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'store']);
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel']);
    
    // Payment routes
    Route::post('/payments/process', [PaymentController::class, 'process']);
    Route::get('/payments/{id}/status', [PaymentController::class, 'status']);
    Route::post('/payments/{id}/refund', [PaymentController::class, 'refund']);
    
    // Subscription routes
    Route::get('/subscriptions/plans', [SubscriptionController::class, 'plans']);
    Route::get('/subscriptions/active', [SubscriptionController::class, 'active']);
    Route::apiResource('subscriptions', SubscriptionController::class)->only(['index', 'store', 'show']);
    Route::post('/subscriptions/{id}/cancel', [SubscriptionController::class, 'cancel']);
    Route::post('/subscriptions/{id}/pause', [SubscriptionController::class, 'pause']);
    Route::post('/subscriptions/{id}/resume', [SubscriptionController::class, 'resume']);
});
