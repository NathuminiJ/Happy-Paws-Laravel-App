<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Payment;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get user's orders
     */
    public function index(): JsonResponse
    {
        $orders = Order::with(['orderDetails.product', 'payments', 'shipment'])
            ->where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);
    }

    /**
     * Get specific order
     */
    public function show($id): JsonResponse
    {
        $order = Order::with(['orderDetails.product', 'payments', 'shipment'])
            ->where('customer_id', Auth::id())
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    /**
     * Create new order
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|array',
            'shipping_address.address_line1' => 'required|string',
            'shipping_address.city' => 'required|string',
            'shipping_address.state' => 'required|string',
            'shipping_address.zip_code' => 'required|string',
            'shipping_address.country' => 'required|string',
            'shipping_address.mobile_number' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        $cart = session()->get('cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = 0;
            foreach ($request->items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                $subtotal += $product->current_price * $item['quantity'];
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
            foreach ($request->items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->current_price,
                    'total_price' => $product->current_price * $item['quantity'],
                ]);
            }

            // Create payment
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'status' => 'pending',
            ]);

            // Create shipment
            Shipment::create([
                'order_id' => $order->id,
                'address_line1' => $request->shipping_address['address_line1'],
                'address_line2' => $request->shipping_address['address_line2'] ?? null,
                'city' => $request->shipping_address['city'],
                'state' => $request->shipping_address['state'],
                'zip_code' => $request->shipping_address['zip_code'],
                'country' => $request->shipping_address['country'],
                'mobile_number' => $request->shipping_address['mobile_number'],
                'status' => 'pending',
            ]);

            DB::commit();

            // Clear cart
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load(['orderDetails.product', 'payments', 'shipment']),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Cancel order
     */
    public function cancel($id): JsonResponse
    {
        $order = Order::where('customer_id', Auth::id())->findOrFail($id);

        if (!$order->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled',
            ], 400);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order,
        ]);
    }
}