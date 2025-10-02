<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Sidebar -->
        <div class="bg-amber-900 text-white w-64 min-h-screen fixed left-0 top-0 z-40">
            <div class="p-6">
                <div class="flex items-center mb-8">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-paw text-white text-xl"></i>
                    </div>
                    <span class="text-xl font-bold">Admin Panel</span>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-box mr-3"></i>
                        <span>Products</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-white bg-amber-800 rounded-lg">
                        <i class="fas fa-shopping-cart mr-3"></i>
                        <span>Orders</span>
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-users mr-3"></i>
                        <span>Customers</span>
                    </a>
                    <a href="{{ route('admin.messages') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-envelope mr-3"></i>
                        <span>Messages</span>
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-external-link-alt mr-3"></i>
                        <span>View Store</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('admin.orders.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-amber-900">Edit Order</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-amber-700">Welcome, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-amber-600 hover:text-amber-800">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Left Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
                                    <select name="status" id="status" 
                                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">
                                        <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                                        <option value="completed" {{ old('status', $order->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="shipping_address" class="block text-sm font-medium text-gray-700">Shipping Address</label>
                                    <textarea name="shipping_address" id="shipping_address" rows="3" 
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">{{ old('shipping_address', $order->shipping_address) }}</textarea>
                                    @error('shipping_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
                                    <textarea name="billing_address" id="billing_address" rows="3" 
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">{{ old('billing_address', $order->billing_address) }}</textarea>
                                    @error('billing_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700">Order Notes</label>
                                    <textarea name="notes" id="notes" rows="4" 
                                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-amber-500 focus:border-amber-500">{{ old('notes', $order->notes) }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Order Summary</h4>
                                    <div class="space-y-2 text-sm">
                                        <div class="flex justify-between">
                                            <span>Order Number:</span>
                                            <span class="font-medium">{{ $order->order_number }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Customer:</span>
                                            <span class="font-medium">{{ $order->customer->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Total Amount:</span>
                                            <span class="font-medium">${{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Created:</span>
                                            <span class="font-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.orders.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>Update Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
