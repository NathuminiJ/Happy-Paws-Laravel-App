<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty!');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->price * $item->quantity;
        }

        $tax = $subtotal * 0.08; // 8% tax
        $shipping = $subtotal >= 50 ? 0 : 10; // Free shipping over $50
        $total = $subtotal + $tax + $shipping;

        return view('checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    public function processOrder(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Please login to place an order'], 401);
        }

        $cartItems = Auth::user()->cartItems()->with('product')->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Your cart is empty!'], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($cartItems as $item) {
                $subtotal += $item->price * $item->quantity;
            }

            $tax = $subtotal * 0.08;
            $shipping = $subtotal >= 50 ? 0 : 10;
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = Order::create([
                'customer_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            // Create order details
            foreach ($cartItems as $item) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => 'pending',
                'payment_details' => [
                    'card_last_four' => '****',
                    'card_name' => $request->first_name . ' ' . $request->last_name,
                ],
            ]);

            // Create shipment
            Shipment::create([
                'order_id' => $order->id,
                'address_line1' => $request->address,
                'address_line2' => '',
                'city' => $request->city,
                'state' => $request->state,
                'zip_code' => $request->zip,
                'country' => 'US',
                'mobile_number' => $request->phone,
                'status' => 'pending',
            ]);

            // Simulate payment processing
            $payment->update([
                'status' => 'completed',
                'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                'processed_at' => now(),
            ]);

            $order->update(['status' => 'processing']);

            // Clear cart
            Auth::user()->cartItems()->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully! You\'ll be receiving your order within 7+ days from the date of order.',
                'order_id' => $order->id,
                'order_number' => $order->order_number ?? 'ORD-' . $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to place order. Please try again.'], 500);
        }
    }
}
