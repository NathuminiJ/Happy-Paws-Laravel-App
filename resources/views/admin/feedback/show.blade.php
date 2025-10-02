<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Details - Admin Panel</title>
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
                        <h1 class="text-xl sm:text-2xl font-bold text-amber-900">Review Details</h1>
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

                <!-- Review Details -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">Customer Review</h2>
                            <p class="mt-1 text-gray-600">Review ID: {{ $feedback->id }}</p>
                        </div>
                        <div class="flex space-x-3 mt-4 sm:mt-0">
                            <a href="{{ route('admin.feedback.edit', $feedback->id) }}" 
                               class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                <i class="fas fa-edit mr-2"></i>Edit Review
                            </a>
                            <a href="{{ route('admin.feedback.index') }}" 
                               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
                            </a>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="border-b pb-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Customer Name</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $feedback->customer_name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $feedback->customer_email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Review Content -->
                    <div class="border-b pb-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Content</h3>
                        
                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} text-2xl"></i>
                                @endfor
                                <span class="ml-3 text-lg font-semibold text-gray-900">{{ $feedback->rating }}/5</span>
                            </div>
                        </div>

                        <!-- Product -->
                        @if($feedback->product_name)
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Product</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $feedback->product_name }}</p>
                            </div>
                        @endif

                        <!-- Review Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Review Type</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                {{ $feedback->feedback_type == 'product' ? 'bg-blue-100 text-blue-800' : 
                                   ($feedback->feedback_type == 'service' ? 'bg-green-100 text-green-800' : 
                                   ($feedback->feedback_type == 'delivery' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($feedback->feedback_type) }} Review
                            </span>
                        </div>

                        <!-- Review Text -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Review Text</label>
                            <div class="mt-1 p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-900 leading-relaxed">{{ $feedback->feedback_text }}</p>
                            </div>
                        </div>

                        <!-- Visibility -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Visibility</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                {{ $feedback->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                <i class="fas {{ $feedback->is_public ? 'fa-eye' : 'fa-eye-slash' }} mr-1"></i>
                                {{ $feedback->is_public ? 'Public' : 'Private' }}
                            </span>
                        </div>
                    </div>

                    <!-- Review Status -->
                    <div class="border-b pb-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Current Status</label>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                                    {{ $feedback->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($feedback->status == 'read' ? 'bg-blue-100 text-blue-800' : 
                                       ($feedback->status == 'responded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst($feedback->status) }}
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Submitted</label>
                                <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($feedback->created_at)->format('F d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Response -->
                    @if($feedback->admin_response)
                        <div class="border-b pb-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Admin Response</h3>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-blue-900 leading-relaxed">{{ $feedback->admin_response }}</p>
                                @if($feedback->responded_at)
                                    <p class="text-sm text-blue-600 mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        Responded on {{ \Carbon\Carbon::parse($feedback->responded_at)->format('F d, Y \a\t g:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Review Metadata -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Review Metadata</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Created:</span>
                                {{ \Carbon\Carbon::parse($feedback->created_at)->format('F d, Y \a\t g:i A') }}
                            </div>
                            <div>
                                <span class="font-medium">Last Updated:</span>
                                {{ \Carbon\Carbon::parse($feedback->updated_at)->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>
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
