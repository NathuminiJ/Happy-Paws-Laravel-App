<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-amber-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-paw text-2xl mr-2"></i>
                        <span class="text-xl font-bold">Happy Paws</span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('customer.home') }}" class="text-amber-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <a href="{{ route('customer.feedback.index') }}" class="bg-amber-700 text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-star mr-1"></i>My Reviews
                    </a>
                    <a href="{{ route('customer.feedback.create') }}" class="text-amber-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i>Write Review
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-amber-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">My Reviews</h1>
                <p class="mt-2 text-gray-600">Manage your product reviews and feedback</p>
            </div>

            <!-- Success Messages -->
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <form method="GET" action="{{ route('customer.feedback.index') }}" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-1">
                        <input type="text" name="search" value="{{ $search }}" 
                               placeholder="Search your reviews..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <button type="submit" class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                    @if($search)
                        <a href="{{ route('customer.feedback.index') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Reviews List -->
            <div class="bg-white rounded-lg shadow-md">
                @if($feedbacks->data->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($feedbacks->data as $feedback)
                            <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <!-- Rating Stars -->
                                        <div class="flex items-center mb-2">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                            @endfor
                                            <span class="ml-2 text-sm text-gray-600">{{ $feedback->rating }}/5</span>
                                        </div>

                                        <!-- Product Name -->
                                        @if($feedback->product_name)
                                            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                                                <i class="fas fa-box mr-2 text-amber-600"></i>
                                                {{ $feedback->product_name }}
                                            </h3>
                                        @endif

                                        <!-- Feedback Text -->
                                        <p class="text-gray-700 mb-3">{{ $feedback->feedback_text }}</p>

                                        <!-- Feedback Type and Status -->
                                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ $feedback->feedback_type == 'product' ? 'bg-blue-100 text-blue-800' : 
                                                   ($feedback->feedback_type == 'service' ? 'bg-green-100 text-green-800' : 
                                                   ($feedback->feedback_type == 'delivery' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                                {{ ucfirst($feedback->feedback_type) }}
                                            </span>
                                            
                                            @if($feedback->is_public)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-eye mr-1"></i>Public
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-eye-slash mr-1"></i>Private
                                                </span>
                                            @endif

                                            <span class="text-gray-500">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ \Carbon\Carbon::parse($feedback->created_at)->format('M d, Y') }}
                                            </span>
                                        </div>

                                        <!-- Admin Response -->
                                        @if(isset($feedback->admin_response) && $feedback->admin_response)
                                            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                                <h4 class="text-sm font-medium text-blue-900 mb-2">
                                                    <i class="fas fa-reply mr-1"></i>Admin Response
                                                </h4>
                                                <p class="text-sm text-blue-800">{{ $feedback->admin_response }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        <a href="{{ route('customer.feedback.show', $feedback->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                        <a href="{{ route('customer.feedback.edit', $feedback->id) }}" 
                                           class="text-amber-600 hover:text-amber-800 text-sm">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <form method="POST" action="{{ route('customer.feedback.destroy', $feedback->id) }}" 
                                              class="inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($feedbacks->last_page > 1)
                        <div class="px-6 py-4 border-t border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    Showing {{ ($feedbacks->current_page - 1) * $feedbacks->per_page + 1 }} to 
                                    {{ min($feedbacks->current_page * $feedbacks->per_page, $feedbacks->total) }} of 
                                    {{ $feedbacks->total }} results
                                </div>
                                <div class="flex space-x-2">
                                    @if($feedbacks->current_page > 1)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page - 1]) }}" 
                                           class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Previous
                                        </a>
                                    @endif
                                    
                                    @if($feedbacks->current_page < $feedbacks->last_page)
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $feedbacks->current_page + 1]) }}" 
                                           class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Next
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Empty State -->
                    <div class="text-center py-12">
                        <i class="fas fa-star text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No reviews found</h3>
                        <p class="text-gray-500 mb-6">
                            @if($search)
                                No reviews match your search criteria.
                            @else
                                You haven't written any reviews yet.
                            @endif
                        </p>
                        <a href="{{ route('customer.feedback.create') }}" 
                           class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                            <i class="fas fa-plus mr-2"></i>Write Your First Review
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>