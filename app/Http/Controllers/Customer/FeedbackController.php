<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /**
     * Display a listing of the customer's feedbacks.
     */
    public function index(Request $request)
    {
        $customerId = Auth::id();
        $page = $request->get('page', 1);
        $perPage = 10;

        // Get search query
        $search = $request->get('search');
        
        if ($search) {
            $feedbacks = Feedback::search($search, $perPage, $page);
        } else {
            $feedbacks = Feedback::forCustomer($customerId, $perPage, $page);
        }

        return view('customer.feedback.index', compact('feedbacks', 'search'));
    }

    /**
     * Show the form for creating a new feedback.
     */
    public function create()
    {
        // Get customer's recent orders for product selection
        $recentOrders = Order::where('customer_id', Auth::id())
            ->with('orderDetails.product')
            ->latest()
            ->limit(10)
            ->get();

        // Get all products for general feedback
        $products = Product::where('is_active', true)->get(['id', 'name']);

        return view('customer.feedback.create', compact('recentOrders', 'products'));
    }

    /**
     * Store a newly created feedback.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'required|string|max:1000',
            'feedback_type' => 'required|in:product,service,delivery,general',
            'is_public' => 'boolean'
        ]);

        $customer = Auth::user();
        
        $data = [
            'customer_id' => (string) $customer->id,
            'customer_name' => $customer->name,
            'customer_email' => $customer->email,
            'product_id' => $request->product_id,
            'product_name' => $request->product_id ? Product::find($request->product_id)->name : null,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'feedback_text' => $request->feedback_text,
            'feedback_type' => $request->feedback_type,
            'status' => 'pending',
            'is_public' => $request->boolean('is_public', true)
        ];

        $feedback = Feedback::create($data);

        return redirect()->route('customer.feedback.index')
            ->with('success', 'Thank you for your feedback! Your review has been submitted.');
    }

    /**
     * Display the specified feedback.
     */
    public function show($feedback)
    {
        $customerId = Auth::id();
        $feedback = Feedback::find($feedback);
        
        if (!$feedback || $feedback->customer_id != $customerId) {
            abort(404, 'Feedback not found.');
        }

        return view('customer.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for editing the specified feedback.
     */
    public function edit($feedback)
    {
        $customerId = Auth::id();
        $feedback = Feedback::find($feedback);
        
        if (!$feedback || $feedback->customer_id != $customerId) {
            abort(404, 'Feedback not found.');
        }

        // Get products for selection
        $products = Product::where('is_active', true)->get(['id', 'name']);

        return view('customer.feedback.edit', compact('feedback', 'products'));
    }

    /**
     * Update the specified feedback.
     */
    public function update(Request $request, $feedback)
    {
        $customerId = Auth::id();
        $feedback = Feedback::find($feedback);
        
        if (!$feedback || $feedback->customer_id != $customerId) {
            abort(404, 'Feedback not found.');
        }

        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'required|string|max:1000',
            'feedback_type' => 'required|in:product,service,delivery,general',
            'is_public' => 'boolean'
        ]);

        $data = [
            'product_id' => $request->product_id,
            'product_name' => $request->product_id ? Product::find($request->product_id)->name : null,
            'rating' => $request->rating,
            'feedback_text' => $request->feedback_text,
            'feedback_type' => $request->feedback_type,
            'is_public' => $request->boolean('is_public', true)
        ];

        $feedback->update($data);

        return redirect()->route('customer.feedback.index')
            ->with('success', 'Your feedback has been updated successfully.');
    }

    /**
     * Remove the specified feedback.
     */
    public function destroy($feedback)
    {
        $customerId = Auth::id();
        $feedback = Feedback::find($feedback);
        
        if (!$feedback || $feedback->customer_id != $customerId) {
            abort(404, 'Feedback not found.');
        }

        $feedback->delete();

        return redirect()->route('customer.feedback.index')
            ->with('success', 'Your feedback has been deleted successfully.');
    }

    /**
     * Get feedback statistics for the customer
     */
    public function stats()
    {
        $customerId = Auth::id();
        $mongoService = new \App\Services\MongoDBService();
        $collection = $mongoService->getCollection('feedbacks');
        
        $total = $collection->countDocuments(['customer_id' => (string) $customerId]);
        $positive = $collection->countDocuments(['customer_id' => (string) $customerId, 'rating' => ['$gte' => 4]]);
        $negative = $collection->countDocuments(['customer_id' => (string) $customerId, 'rating' => ['$lt' => 3]]);
        
        return response()->json([
            'total' => $total,
            'positive' => $positive,
            'negative' => $negative
        ]);
    }
}