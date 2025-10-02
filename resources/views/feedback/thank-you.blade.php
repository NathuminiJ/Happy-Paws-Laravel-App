<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-2xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="text-green-500 text-6xl mb-4">
                <i class="fas fa-check-circle"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You for Your Feedback!</h1>
            <p class="text-gray-600 mb-6">We appreciate you taking the time to share your thoughts with us. Your feedback helps us improve our products and services!</p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <p class="text-blue-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Your feedback has been successfully submitted and will be reviewed by our team.
                </p>
            </div>

            <div class="flex justify-center space-x-4">
                <a href="{{ route('home') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-home mr-2"></i>Return to Home
                </a>
                <a href="{{ route('feedback.create') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-colors duration-200">
                    <i class="fas fa-plus mr-2"></i>Submit Another Feedback
                </a>
            </div>
        </div>
    </div>
</body>
</html>

