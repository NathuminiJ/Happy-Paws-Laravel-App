<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review - Happy Paws</title>
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
                    <a href="{{ route('customer.feedback.index') }}" class="text-amber-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
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
                <h1 class="text-3xl font-bold text-gray-900">Edit Review</h1>
                <p class="mt-2 text-gray-600">Update your review details</p>
            </div>

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('customer.feedback.update', $feedback->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-6">
                        <!-- Product Selection -->
                        <div>
                            <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Product (Optional)
                            </label>
                            <select name="product_id" id="product_id" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Select a product (optional)</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $feedback->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Rating <span class="text-red-500">*</span>
                            </label>
                            <div class="flex items-center space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" name="rating" value="{{ $i }}" id="rating_{{ $i }}" 
                                           {{ old('rating', $feedback->rating) == $i ? 'checked' : '' }}
                                           class="sr-only">
                                    <label for="rating_{{ $i }}" class="cursor-pointer">
                                        <i class="fas fa-star text-2xl {{ old('rating', $feedback->rating) >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors"></i>
                                    </label>
                                @endfor
                                <span class="ml-2 text-sm text-gray-600" id="rating-text">
                                    {{ old('rating', $feedback->rating) ? ['', 'Poor', 'Fair', 'Good', 'Very Good', 'Excellent'][old('rating', $feedback->rating)] : 'Select a rating' }}
                                </span>
                            </div>
                            @error('rating')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Feedback Type -->
                        <div>
                            <label for="feedback_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Review Type <span class="text-red-500">*</span>
                            </label>
                            <select name="feedback_type" id="feedback_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="">Select review type</option>
                                <option value="product" {{ old('feedback_type', $feedback->feedback_type) == 'product' ? 'selected' : '' }}>Product Review</option>
                                <option value="service" {{ old('feedback_type', $feedback->feedback_type) == 'service' ? 'selected' : '' }}>Customer Service</option>
                                <option value="delivery" {{ old('feedback_type', $feedback->feedback_type) == 'delivery' ? 'selected' : '' }}>Delivery Experience</option>
                                <option value="general" {{ old('feedback_type', $feedback->feedback_type) == 'general' ? 'selected' : '' }}>General Feedback</option>
                            </select>
                            @error('feedback_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Feedback Text -->
                        <div>
                            <label for="feedback_text" class="block text-sm font-medium text-gray-700 mb-2">
                                Your Review <span class="text-red-500">*</span>
                            </label>
                            <textarea name="feedback_text" id="feedback_text" rows="6" required
                                      placeholder="Tell us about your experience..."
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-amber-500 focus:border-amber-500">{{ old('feedback_text', $feedback->feedback_text) }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                            @error('feedback_text')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Privacy Settings -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" 
                                       {{ old('is_public', $feedback->is_public) ? 'checked' : '' }}
                                       class="h-4 w-4 text-amber-600 focus:ring-amber-500 border-gray-300 rounded">
                                <label for="is_public" class="ml-2 block text-sm text-gray-700">
                                    Make this review public (visible to other customers)
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">
                                Public reviews help other customers make informed decisions
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <a href="{{ route('customer.feedback.show', $feedback->id) }}" 
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

    <script>
        // Rating interaction
        document.addEventListener('DOMContentLoaded', function() {
            const ratingInputs = document.querySelectorAll('input[name="rating"]');
            const ratingText = document.getElementById('rating-text');
            
            const ratingTexts = {
                1: 'Poor',
                2: 'Fair', 
                3: 'Good',
                4: 'Very Good',
                5: 'Excellent'
            };

            ratingInputs.forEach(input => {
                input.addEventListener('change', function() {
                    // Update star colors
                    ratingInputs.forEach((star, index) => {
                        const label = star.nextElementSibling;
                        const icon = label.querySelector('i');
                        if (index < this.value) {
                            icon.classList.remove('text-gray-300');
                            icon.classList.add('text-yellow-400');
                        } else {
                            icon.classList.remove('text-yellow-400');
                            icon.classList.add('text-gray-300');
                        }
                    });
                    
                    // Update rating text
                    ratingText.textContent = ratingTexts[this.value];
                });
            });

            // Hover effect for stars
            ratingInputs.forEach((input, index) => {
                const label = input.nextElementSibling;
                label.addEventListener('mouseenter', function() {
                    ratingInputs.forEach((star, starIndex) => {
                        const starLabel = star.nextElementSibling;
                        const icon = starLabel.querySelector('i');
                        if (starIndex <= index) {
                            icon.classList.add('text-yellow-400');
                        }
                    });
                });
            });

            document.querySelector('.flex.items-center.space-x-2').addEventListener('mouseleave', function() {
                const checkedInput = document.querySelector('input[name="rating"]:checked');
                if (checkedInput) {
                    ratingInputs.forEach((star, index) => {
                        const label = star.nextElementSibling;
                        const icon = label.querySelector('i');
                        if (index < checkedInput.value) {
                            icon.classList.remove('text-gray-300');
                            icon.classList.add('text-yellow-400');
                        } else {
                            icon.classList.remove('text-yellow-400');
                            icon.classList.add('text-gray-300');
                        }
                    });
                } else {
                    ratingInputs.forEach(star => {
                        const label = star.nextElementSibling;
                        const icon = label.querySelector('i');
                        icon.classList.remove('text-yellow-400');
                        icon.classList.add('text-gray-300');
                    });
                }
            });
        });
    </script>
</body>
</html>