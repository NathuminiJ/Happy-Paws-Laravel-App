<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Services\MongoDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    private $mongoService;

    public function __construct()
    {
        $this->mongoService = new MongoDBService();
    }

    /**
     * Display the feedback form
     */
    public function create(Request $request)
    {
        $productId = $request->get('product_id');
        $orderId = $request->get('order_id');
        
        $product = null;
        $order = null;
        
        if ($productId) {
            $product = Product::find($productId);
        }
        
        if ($orderId) {
            $order = Order::with(['orderDetails.product'])->find($orderId);
        }

        return view('feedback.create', compact('product', 'order'));
    }

    /**
     * Store a new feedback
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'product_id' => 'nullable|exists:products,id',
            'product_name' => 'required|string|max:255',
            'order_id' => 'nullable|exists:orders,id',
            'rating' => 'required|integer|min:1|max:5',
            'feedback_text' => 'required|string|min:10|max:1000',
            'feedback_type' => 'required|in:product,service,general',
            'is_anonymous' => 'boolean'
        ]);

        $data = [
            'customer_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'product_id' => $request->product_id,
            'product_name' => $request->product_name,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'feedback_text' => $request->feedback_text,
            'feedback_type' => $request->feedback_type,
            'is_anonymous' => $request->boolean('is_anonymous'),
            'status' => 'pending'
        ];

        // Store in MongoDB
        $mongoId = $this->mongoService->createFeedback($data);

        return redirect()->route('feedback.thank-you')
            ->with('success', 'Thank you for your feedback! We appreciate your input.')
            ->with('mongo_id', $mongoId);
    }

    /**
     * Display feedbacks (for admin)
     */
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $limit = 10;
        $search = $request->get('search');

        if ($search) {
            $feedbacks = $this->mongoService->searchFeedbacks($search, $page, $limit);
        } else {
            $feedbacks = $this->mongoService->getFeedbacks($page, $limit);
        }

        $stats = $this->mongoService->getFeedbackStats();

        return view('admin.feedback.index', compact('feedbacks', 'stats', 'search'));
    }

    /**
     * Show a specific feedback
     */
    public function show(string $id)
    {
        $feedback = $this->mongoService->getFeedbackById($id);
        
        if (!$feedback) {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'Feedback not found.');
        }

        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Show the form for responding to feedback
     */
    public function respond(string $id)
    {
        $feedback = $this->mongoService->getFeedbackById($id);
        
        if (!$feedback) {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'Feedback not found.');
        }

        return view('admin.feedback.respond', compact('feedback'));
    }

    /**
     * Store admin response
     */
    public function storeResponse(Request $request, string $id)
    {
        $request->validate([
            'admin_response' => 'required|string|min:10|max:500'
        ]);

        $updateData = [
            'admin_response' => $request->admin_response,
            'admin_id' => Auth::id(),
            'status' => 'responded',
            'responded_at' => now()
        ];

        $success = $this->mongoService->updateFeedback($id, $updateData);

        if ($success) {
            return redirect()->route('admin.feedback.show', $id)
                ->with('success', 'Response sent successfully!');
        } else {
            return redirect()->route('admin.feedback.respond', $id)
                ->with('error', 'Failed to send response. Please try again.');
        }
    }

    /**
     * Update feedback status
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,responded,closed'
        ]);

        $updateData = ['status' => $request->status];
        $success = $this->mongoService->updateFeedback($id, $updateData);

        if ($success) {
            return redirect()->route('admin.feedback.index')
                ->with('success', 'Feedback status updated successfully!');
        } else {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Delete feedback
     */
    public function destroy(string $id)
    {
        $success = $this->mongoService->deleteFeedback($id);

        if ($success) {
            return redirect()->route('admin.feedback.index')
                ->with('success', 'Feedback deleted successfully!');
        } else {
            return redirect()->route('admin.feedback.index')
                ->with('error', 'Failed to delete feedback. Please try again.');
        }
    }

    /**
     * Thank you page
     */
    public function thankYou()
    {
        return view('feedback.thank-you');
    }

    /**
     * Test MongoDB connection
     */
    public function testConnection()
    {
        $isConnected = $this->mongoService->testConnection();
        
        if ($isConnected) {
            return response()->json([
                'status' => 'success',
                'message' => 'MongoDB connection successful!'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'MongoDB connection failed!'
            ], 500);
        }
    }
}

