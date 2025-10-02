<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Feedback - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6 text-center">Leave Your Feedback</h1>
            
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">There were some problems with your input.</span>
                    <ul class="mt-3 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('feedback.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">Your Name</label>
                    <input type="text" name="customer_name" id="customer_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('customer_name') }}" required>
                </div>

                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">Your Email</label>
                    <input type="email" name="customer_email" id="customer_email" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('customer_email') }}" required>
                </div>

                <div>
                    <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name (Optional)</label>
                    <input type="text" name="product_name" id="product_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                           value="{{ old('product_name') }}">
                </div>

                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                    <div class="flex items-center space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" 
                                   class="hidden" {{ old('rating') == $i ? 'checked' : '' }}>
                            <label for="star{{ $i }}" class="text-gray-300 text-3xl cursor-pointer hover:text-yellow-400 transition-colors duration-200">
                                <i class="fas fa-star"></i>
                            </label>
                        @endfor
                    </div>
                </div>

                <div>
                    <label for="feedback_type" class="block text-sm font-medium text-gray-700 mb-2">Feedback Type</label>
                    <select name="feedback_type" id="feedback_type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Type</option>
                        <option value="product" {{ old('feedback_type') == 'product' ? 'selected' : '' }}>Product Feedback</option>
                        <option value="service" {{ old('feedback_type') == 'service' ? 'selected' : '' }}>Service Feedback</option>
                        <option value="general" {{ old('feedback_type') == 'general' ? 'selected' : '' }}>General Feedback</option>
                    </select>
                </div>

                <div>
                    <label for="feedback_text" class="block text-sm font-medium text-gray-700 mb-2">Your Message</label>
                    <textarea name="feedback_text" id="feedback_text" rows="5" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                              placeholder="Tell us what you think..." required>{{ old('feedback_text') }}</textarea>
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Home
                    </a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.fa-star');
            const ratingInputs = document.querySelectorAll('input[name="rating"]');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.previousElementSibling.value;
                    ratingInputs.forEach((input, index) => {
                        if (index < value) {
                            stars[index].classList.remove('text-gray-300');
                            stars[index].classList.add('text-yellow-400');
                        } else {
                            stars[index].classList.remove('text-yellow-400');
                            stars[index].classList.add('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseover', function() {
                    const value = this.previousElementSibling.value;
                    stars.forEach((s, index) => {
                        if (index < value) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-300');
                        } else {
                            s.classList.remove('text-yellow-300');
                            s.classList.add('text-gray-300');
                        }
                    });
                });

                star.addEventListener('mouseout', function() {
                    let checkedValue = 0;
                    ratingInputs.forEach(input => {
                        if (input.checked) {
                            checkedValue = input.value;
                        }
                    });

                    stars.forEach((s, index) => {
                        if (index < checkedValue) {
                            s.classList.remove('text-gray-300');
                            s.classList.add('text-yellow-400');
                        } else {
                            s.classList.remove('text-yellow-400');
                            s.classList.add('text-gray-300');
                        }
                    });
                });
            });

            // Set initial star state based on old('rating')
            let initialRating = document.querySelector('input[name="rating"]:checked');
            if (initialRating) {
                const value = initialRating.value;
                stars.forEach((s, index) => {
                    if (index < value) {
                        s.classList.remove('text-gray-300');
                        s.classList.add('text-yellow-400');
                    } else {
                        s.classList.remove('text-yellow-400');
                        s.classList.add('text-gray-300');
                    }
                });
            }
        });
    </script>
</body>
</html>

