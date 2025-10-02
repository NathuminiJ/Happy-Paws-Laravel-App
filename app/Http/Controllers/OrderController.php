<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders
     */
    public function index()
    {
        $orders = Order::with(['orderDetails.product', 'payments', 'shipment'])
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        // Ensure the order belongs to the authenticated user
        if ($order->customer_id !== Auth::id()) {
            abort(403, 'Unauthorized access to order.');
        }

        $order->load(['orderDetails.product', 'payments', 'shipment']);

        return view('orders.show', compact('order'));
    }
}