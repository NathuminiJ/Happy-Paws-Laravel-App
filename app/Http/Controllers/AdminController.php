<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_products' => Product::count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'new_messages' => ContactMessage::where('status', 'new')->count(),
        ];

        $recent_orders = Order::with(['customer', 'orderDetails.product'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_messages = ContactMessage::with(['customer'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_orders', 'recent_messages'));
    }

    /**
     * Products management
     */
    public function products()
    {
        $products = Product::with(['brand', 'admin'])
            ->latest()
            ->paginate(20);

        return view('admin.products', compact('products'));
    }

    /**
     * Orders management
     */
    public function orders()
    {
        $orders = Order::with(['customer', 'orderDetails.product', 'payments'])
            ->latest()
            ->paginate(20);

        return view('admin.orders', compact('orders'));
    }

    /**
     * Customers management
     */
    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount(['orders', 'subscriptions'])
            ->latest()
            ->paginate(20);

        return view('admin.customers', compact('customers'));
    }

    /**
     * Contact messages management
     */
    public function messages()
    {
        $messages = ContactMessage::with(['customer', 'admin'])
            ->latest()
            ->paginate(20);

        return view('admin.messages', compact('messages'));
    }
}