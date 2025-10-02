<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Get payment methods
     */
    public function methods(): JsonResponse
    {
        $methods = [
            [
                'id' => 'credit_card',
                'name' => 'Credit Card',
                'description' => 'Visa, MasterCard, American Express',
                'icon' => 'credit-card',
            ],
            [
                'id' => 'paypal',
                'name' => 'PayPal',
                'description' => 'Pay with your PayPal account',
                'icon' => 'paypal',
            ],
            [
                'id' => 'stripe',
                'name' => 'Stripe',
                'description' => 'Secure payment with Stripe',
                'icon' => 'stripe',
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $methods,
        ]);
    }

    /**
     * Process payment
     */
    public function process(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'payment_details' => 'required|array',
        ]);

        $order = Order::where('customer_id', Auth::id())->findOrFail($request->order_id);

        if ($order->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Order is already paid',
            ], 400);
        }

        try {
            // Simulate payment processing
            $payment = Payment::create([
                'order_id' => $order->id,
                'payment_method' => $request->payment_method,
                'amount' => $request->amount,
                'status' => 'pending',
                'payment_details' => $request->payment_details,
            ]);

            // Simulate payment gateway response
            $isSuccessful = $this->simulatePaymentGateway($request->payment_method, $request->amount);

            if ($isSuccessful) {
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'processed_at' => now(),
                ]);

                $order->update(['status' => 'processing']);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment processed successfully',
                    'data' => [
                        'payment' => $payment,
                        'order' => $order,
                        'transaction_id' => $payment->transaction_id,
                    ],
                ]);
            } else {
                $payment->update(['status' => 'failed']);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment failed',
                    'data' => $payment,
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function status($paymentId): JsonResponse
    {
        $payment = Payment::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->findOrFail($paymentId);

        return response()->json([
            'success' => true,
            'data' => $payment,
        ]);
    }

    /**
     * Refund payment
     */
    public function refund(Request $request, $paymentId): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string',
        ]);

        $payment = Payment::whereHas('order', function ($query) {
            $query->where('customer_id', Auth::id());
        })->findOrFail($paymentId);

        if (!$payment->isSuccessful()) {
            return response()->json([
                'success' => false,
                'message' => 'Payment is not successful',
            ], 400);
        }

        // Simulate refund processing
        $refund = Payment::create([
            'order_id' => $payment->order_id,
            'payment_method' => $payment->payment_method,
            'amount' => -$request->amount, // Negative amount for refund
            'status' => 'completed',
            'transaction_id' => 'REF-' . strtoupper(uniqid()),
            'processed_at' => now(),
            'payment_details' => [
                'type' => 'refund',
                'original_transaction_id' => $payment->transaction_id,
                'reason' => $request->reason,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Refund processed successfully',
            'data' => $refund,
        ]);
    }

    /**
     * Simulate payment gateway processing
     */
    private function simulatePaymentGateway($method, $amount)
    {
        // Simulate 95% success rate
        return rand(1, 100) <= 95;
    }
}