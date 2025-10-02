<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * Get user's subscriptions
     */
    public function index(): JsonResponse
    {
        $subscriptions = Subscription::where('customer_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subscriptions,
        ]);
    }

    /**
     * Get active subscription
     */
    public function active(): JsonResponse
    {
        $subscription = Subscription::where('customer_id', Auth::id())
            ->active()
            ->first();

        return response()->json([
            'success' => true,
            'data' => $subscription,
        ]);
    }

    /**
     * Get available subscription plans
     */
    public function plans(): JsonResponse
    {
        $plans = [
            [
                'id' => 'basic',
                'name' => 'Basic Plan',
                'price' => 9.99,
                'interval' => 'monthly',
                'features' => [
                    'Basic customer support',
                    'Email notifications',
                    'Access to basic products',
                    'Standard shipping',
                ],
                'popular' => false,
            ],
            [
                'id' => 'premium',
                'name' => 'Premium Plan',
                'price' => 19.99,
                'interval' => 'monthly',
                'features' => [
                    'Priority customer support',
                    'SMS and email notifications',
                    'Access to premium products',
                    'Free shipping on orders over $25',
                    'Exclusive discounts',
                ],
                'popular' => true,
            ],
            [
                'id' => 'pro',
                'name' => 'Pro Plan',
                'price' => 39.99,
                'interval' => 'monthly',
                'features' => [
                    '24/7 premium support',
                    'All notification types',
                    'Access to all products',
                    'Free shipping on all orders',
                    'Exclusive discounts up to 30%',
                    'Early access to new products',
                    'Personal shopping assistant',
                ],
                'popular' => false,
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $plans,
        ]);
    }

    /**
     * Subscribe to a plan
     */
    public function subscribe(Request $request): JsonResponse
    {
        $request->validate([
            'plan_id' => 'required|string|in:basic,premium,pro',
        ]);

        $plans = [
            'basic' => ['price' => 9.99, 'features' => ['Basic support', 'Email notifications']],
            'premium' => ['price' => 19.99, 'features' => ['Priority support', 'SMS notifications', 'Exclusive products']],
            'pro' => ['price' => 39.99, 'features' => ['24/7 support', 'All notifications', 'All products', 'Free shipping']],
        ];

        $plan = $plans[$request->plan_id];
        $user = Auth::user();

        // Cancel existing active subscription
        Subscription::where('customer_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Create new subscription
        $subscription = Subscription::create([
            'customer_id' => $user->id,
            'plan_name' => $request->plan_id,
            'price' => $plan['price'],
            'status' => 'active',
            'start_date' => now(),
            'next_billing_date' => now()->addMonth(),
            'features' => $plan['features'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed to ' . ucfirst($request->plan_id) . ' plan',
            'data' => $subscription,
        ], 201);
    }

    /**
     * Cancel subscription
     */
    public function cancel($id): JsonResponse
    {
        $subscription = Subscription::where('customer_id', Auth::id())
            ->findOrFail($id);

        if ($subscription->isCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription is already cancelled',
            ], 400);
        }

        $subscription->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully',
            'data' => $subscription,
        ]);
    }

    /**
     * Pause subscription
     */
    public function pause($id): JsonResponse
    {
        $subscription = Subscription::where('customer_id', Auth::id())
            ->findOrFail($id);

        if (!$subscription->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'Only active subscriptions can be paused',
            ], 400);
        }

        $subscription->update(['status' => 'paused']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription paused successfully',
            'data' => $subscription,
        ]);
    }

    /**
     * Resume subscription
     */
    public function resume($id): JsonResponse
    {
        $subscription = Subscription::where('customer_id', Auth::id())
            ->findOrFail($id);

        if (!$subscription->isPaused()) {
            return response()->json([
                'success' => false,
                'message' => 'Only paused subscriptions can be resumed',
            ], 400);
        }

        $subscription->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Subscription resumed successfully',
            'data' => $subscription,
        ]);
    }
}