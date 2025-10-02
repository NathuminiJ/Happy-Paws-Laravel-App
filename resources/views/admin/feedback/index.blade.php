<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Feedback - Admin Panel</title>
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
                        <h1 class="text-xl sm:text-2xl font-bold text-amber-900">Customer Reviews</h1>
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

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <!-- Filters -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6 mb-6">
                    <form method="GET" action="{{ route('admin.feedback.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                                <input type="text" name="search" id="search" value="{{ $search }}"
                                       placeholder="Search reviews..."
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                            </div>

                            <!-- Status Filter -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" id="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="read" {{ $status == 'read' ? 'selected' : '' }}>Read</option>
                                    <option value="responded" {{ $status == 'responded' ? 'selected' : '' }}>Responded</option>
                                    <option value="closed" {{ $status == 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                <select name="type" id="type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                    <option value="">All Types</option>
                                    <option value="product" {{ $type == 'product' ? 'selected' : '' }}>Product</option>
                                    <option value="service" {{ $type == 'service' ? 'selected' : '' }}>Service</option>
                                    <option value="delivery" {{ $type == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                    <option value="general" {{ $type == 'general' ? 'selected' : '' }}>General</option>
                                </select>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="flex items-end space-x-2">
                                <button type="submit" class="bg-amber-600 text-white px-4 py-2 rounded-md hover:bg-amber-700 transition-colors duration-200">
                                    <i class="fas fa-search mr-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.feedback.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600 transition-colors duration-200">
                                    <i class="fas fa-times mr-1"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Reviews Table -->
                <div class="bg-white rounded-lg shadow-md p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900">All Customer Reviews</h2>
                        <div class="text-sm text-gray-500">
                            Total: {{ $feedbacks->total ?? 0 }} reviews
                        </div>
                    </div>

                    @if($feedbacks->data && count($feedbacks->data) > 0)
                        <div class="overflow-x-auto -mx-4 sm:mx-0">
                            <div class="inline-block min-w-full align-middle">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th class="px-3 sm:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($feedbacks->data as $feedback)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-3 sm:px-6 py-4">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-10 w-10">
                                                            <div class="h-10 w-10 rounded-full bg-amber-100 flex items-center justify-center">
                                                                <i class="fas fa-user text-amber-600"></i>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 min-w-0 flex-1">
                                                            <div class="text-sm font-medium text-gray-900 truncate">{{ $feedback->customer_name }}</div>
                                                            <div class="text-sm text-gray-500 truncate">{{ $feedback->customer_email }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $feedback->product_name ?: 'General Review' }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                        @endfor
                                                        <span class="ml-1 text-sm text-gray-600">{{ $feedback->rating }}/5</span>
                                                    </div>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $feedback->feedback_type == 'product' ? 'bg-blue-100 text-blue-800' : 
                                                           ($feedback->feedback_type == 'service' ? 'bg-green-100 text-green-800' : 
                                                           ($feedback->feedback_type == 'delivery' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                                        {{ ucfirst($feedback->feedback_type) }}
                                                    </span>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        {{ $feedback->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                           ($feedback->status == 'read' ? 'bg-blue-100 text-blue-800' : 
                                                           ($feedback->status == 'responded' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800')) }}">
                                                        {{ ucfirst($feedback->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y') }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex flex-col sm:flex-row space-y-1 sm:space-y-0 sm:space-x-2">
                                                        <a href="{{ route('admin.feedback.show', $feedback->id) }}" class="text-blue-600 hover:text-blue-900 text-xs sm:text-sm">View</a>
                                                        <a href="{{ route('admin.feedback.edit', $feedback->id) }}" class="text-amber-600 hover:text-amber-900 text-xs sm:text-sm">Edit</a>
                                                        <form method="POST" action="{{ route('admin.feedback.destroy', $feedback->id) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 text-xs sm:text-sm">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        @if($feedbacks->last_page > 1)
                            <div class="mt-6">
                                <nav class="flex items-center justify-between">
                                    <div class="flex-1 flex justify-between sm:hidden">
                                        @if($feedbacks->current_page > 1)
                                            <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page - 1]) }}" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                Previous
                                            </a>
                                        @endif
                                        @if($feedbacks->current_page < $feedbacks->last_page)
                                            <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page + 1]) }}" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                Next
                                            </a>
                                        @endif
                                    </div>
                                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-sm text-gray-700">
                                                Showing {{ (($feedbacks->current_page - 1) * 10) + 1 }} to {{ min($feedbacks->current_page * 10, $feedbacks->total) }} of {{ $feedbacks->total }} results
                                            </p>
                                        </div>
                                        <div>
                                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                                @if($feedbacks->current_page > 1)
                                                    <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page - 1]) }}" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <i class="fas fa-chevron-left"></i>
                                                    </a>
                                                @endif
                                                
                                                @for($i = max(1, $feedbacks->current_page - 2); $i <= min($feedbacks->last_page, $feedbacks->current_page + 2); $i++)
                                                    <a href="{{ request()->fullUrlWithQuery(['page' => $i]) }}" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium {{ $i == $feedbacks->current_page ? 'z-10 bg-amber-50 border-amber-500 text-amber-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' }}">
                                                        {{ $i }}
                                                    </a>
                                                @endfor
                                                
                                                @if($feedbacks->current_page < $feedbacks->last_page)
                                                    <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page + 1]) }}" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                        <i class="fas fa-chevron-right"></i>
                                                    </a>
                                                @endif
                                            </nav>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-600 mb-2">No reviews found</h3>
                            <p class="text-gray-500 mb-6">No customer reviews match your current filters.</p>
                            <a href="{{ route('admin.feedback.index') }}" class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                                <i class="fas fa-refresh mr-2"></i>Clear Filters
                            </a>
                        </div>
                    @endif
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
