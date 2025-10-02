<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Details - Happy Paws</title>
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
    <div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Review Details</h1>
                        <p class="mt-2 text-gray-600">View your submitted review</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="{{ route('customer.feedback.edit', $feedback->id) }}" 
                           class="bg-amber-600 text-white px-4 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                            <i class="fas fa-edit mr-2"></i>Edit Review
                        </a>
                        <a href="{{ route('customer.feedback.index') }}" 
                           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
                        </a>
                    </div>
                </div>
            </div>

            <!-- Review Details -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="space-y-6">
                    <!-- Rating and Product -->
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <!-- Rating Stars -->
                            <div class="flex items-center mb-3">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} text-xl"></i>
                                @endfor
                                <span class="ml-3 text-lg font-semibold text-gray-900">{{ $feedback->rating }}/5</span>
                            </div>

                            <!-- Product Name -->
                            @if($feedback->product_name)
                                <h2 class="text-xl font-semibold text-gray-900 mb-2">
                                    <i class="fas fa-box mr-2 text-amber-600"></i>
                                    {{ $feedback->product_name }}
                                </h2>
                            @endif
                        </div>

                        <!-- Review Type and Status -->
                        <div class="flex flex-col items-end space-y-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $feedback->feedback_type == 'product' ? 'bg-blue-100 text-blue-800' : 
                                   ($feedback->feedback_type == 'service' ? 'bg-green-100 text-green-800' : 
                                   ($feedback->feedback_type == 'delivery' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($feedback->feedback_type) }} Review
                            </span>
                            
                            @if($feedback->is_public)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-eye mr-1"></i>Public
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                    <i class="fas fa-eye-slash mr-1"></i>Private
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Your Review</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $feedback->feedback_text }}</p>
                    </div>

                    <!-- Review Metadata -->
                    <div class="border-t pt-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Submitted:</span>
                                {{ \Carbon\Carbon::parse($feedback->created_at)->format('F d, Y \a\t g:i A') }}
                            </div>
                            <div>
                                <span class="font-medium">Last Updated:</span>
                                {{ \Carbon\Carbon::parse($feedback->updated_at)->format('F d, Y \a\t g:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Admin Response -->
                    @if(isset($feedback->admin_response) && $feedback->admin_response)
                        <div class="border-t pt-6">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <h4 class="text-lg font-medium text-blue-900 mb-2">
                                    <i class="fas fa-reply mr-2"></i>Admin Response
                                </h4>
                                <p class="text-blue-800 leading-relaxed">{{ $feedback->admin_response }}</p>
                                @if(isset($feedback->responded_at))
                                    <p class="text-sm text-blue-600 mt-2">
                                        <i class="fas fa-clock mr-1"></i>
                                        Responded on {{ \Carbon\Carbon::parse($feedback->responded_at)->format('F d, Y \a\t g:i A') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-8 flex justify-end space-x-4 border-t pt-6">
                    <a href="{{ route('customer.feedback.index') }}" 
                       class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors duration-200">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Reviews
                    </a>
                    <a href="{{ route('customer.feedback.edit', $feedback->id) }}" 
                       class="bg-amber-600 text-white px-6 py-2 rounded-lg hover:bg-amber-700 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Edit Review
                    </a>
                    <form method="POST" action="{{ route('customer.feedback.destroy', $feedback->id) }}" 
                          class="inline" onsubmit="return confirm('Are you sure you want to delete this review?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-trash mr-2"></i>Delete Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>