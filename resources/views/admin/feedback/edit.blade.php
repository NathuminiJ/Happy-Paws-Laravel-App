<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="flex">
        <!-- Mobile sidebar overlay -->
        <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="bg-amber-900 text-white w-64 min-h-screen fixed left-0 top-0 z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300">
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-paw text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold">Admin Panel</span>
                    </div>
                    <button id="sidebar-close" class="lg:hidden text-amber-200 hover:text-white">
                        <i class="fas fa-times text-xl"></i>
                    </button>
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
                    <a href="{{ route('admin.orders.index') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
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
                    <a href="{{ route('admin.feedback.index') }}" class="flex items-center px-4 py-3 text-white bg-amber-800 rounded-lg">
                        <i class="fas fa-star mr-3"></i>
                        <span>Customer Reviews</span>
                    </a>
                    <a href="{{ route('home') }}" class="flex items-center px-4 py-3 text-amber-200 hover:bg-amber-800 rounded-lg transition duration-200">
                        <i class="fas fa-external-link-alt mr-3"></i>
                        <span>View Store</span>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 lg:ml-64 min-h-screen">
            <!-- Top Navigation -->
            <div class="bg-white shadow-sm border-b border-gray-200 px-4 sm:px-6 py-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center mb-2 sm:mb-0">
                        <button id="sidebar-toggle" class="text-gray-600 hover:text-gray-900 mr-4 lg:hidden">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-xl sm:text-2xl font-bold text-amber-900">Edit Review</h1>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <span class="text-amber-700 text-sm sm:text-base">Welcome, {{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-amber-600 hover:text-amber-800 text-sm sm:text-base">
                                <i class="fas fa-sign-out-alt mr-1"></i>Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-4 sm:p-6">
                <!-- Success Messages -->
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                <!-- Review Information -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Customer</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $feedback->customer_name }} ({{ $feedback->customer_email }})</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $feedback->product_name ?: 'General Review' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rating</label>
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Type</label>
                            <p class="mt-1 text-sm text-gray-900">{{ ucfirst($feedback->feedback_type) }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700">Review Text</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg">
                            <p class="text-sm text-gray-900">{{ $feedback->feedback_text }}</p>
                        </div>
                    </div>
                </div>

                <!-- Admin Response Form -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Admin Response</h2>
                    
                    <form method="POST" action="{{ route('admin.feedback.update', $feedback->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                    Review Status <span class="text-red-500">*</span>
                                </label>
                                <select name="status" id="status" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="pending" {{ old('status', $feedback->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="read" {{ old('status', $feedback->status) == 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="responded" {{ old('status', $feedback->status) == 'responded' ? 'selected' : '' }}>Responded</option>
                                    <option value="closed" {{ old('status', $feedback->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Admin Response -->
                            <div>
                                <label for="admin_response" class="block text-sm font-medium text-gray-700 mb-2">
                                    Admin Response
                                </label>
                                <textarea name="admin_response" id="admin_response" rows="6"
                                          placeholder="Write your response to the customer..."
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">{{ old('admin_response', $feedback->admin_response) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">This response will be visible to the customer if the review is public.</p>
                                @error('admin_response')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 flex justify-end space-x-4">
                            <a href="{{ route('admin.feedback.show', $feedback->id) }}" 
                               class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                <i class="fas fa-save mr-2"></i>Update Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebar-overlay');
            const sidebarClose = document.getElementById('sidebar-close');

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                sidebarOverlay.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                sidebarOverlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            sidebarToggle.addEventListener('click', openSidebar);
            sidebarClose.addEventListener('click', closeSidebar);
            sidebarOverlay.addEventListener('click', closeSidebar);

            // Close sidebar on window resize if screen becomes large
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>
</body>
</html>
