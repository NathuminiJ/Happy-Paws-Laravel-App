<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MongoDB Test - Happy Paws</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">MongoDB Connection Test</h1>
            
            <div class="space-y-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">Connection Status</h3>
                    <div id="connection-status" class="text-blue-800">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                        Testing connection...
                    </div>
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h3 class="font-semibold text-green-900 mb-2">Setup Complete</h3>
                    <ul class="text-green-800 space-y-2">
                        <li><i class="fas fa-check mr-2"></i>MongoDB PHP extension installed</li>
                        <li><i class="fas fa-check mr-2"></i>MongoDB Composer package installed</li>
                        <li><i class="fas fa-check mr-2"></i>MongoDBService created</li>
                        <li><i class="fas fa-check mr-2"></i>Feedback system implemented</li>
                        <li><i class="fas fa-spinner fa-spin mr-2"></i>Testing connection...</li>
                    </ul>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="font-semibold text-yellow-900 mb-2">Next Steps</h3>
                    <ol class="text-yellow-800 space-y-2">
                        <li>1. Update your .env file with the real MongoDB password</li>
                        <li>2. Test the connection using the button below</li>
                        <li>3. Start using the feedback system!</li>
                    </ol>
                </div>

                <div class="flex space-x-4">
                    <button onclick="testConnection()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plug mr-2"></i>
                        Test Connection
                    </button>
                    <a href="{{ route('home') }}" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function testConnection() {
            const statusDiv = document.getElementById('connection-status');
            statusDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Testing connection...';
            
            fetch('/test-mongodb')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        statusDiv.innerHTML = '<i class="fas fa-check-circle text-green-600 mr-2"></i>MongoDB connection successful!';
                        statusDiv.className = 'text-green-800';
                    } else {
                        statusDiv.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-2"></i>MongoDB connection failed: ' + data.message;
                        statusDiv.className = 'text-red-800';
                    }
                })
                .catch(error => {
                    statusDiv.innerHTML = '<i class="fas fa-times-circle text-red-600 mr-2"></i>Error: ' + error.message;
                    statusDiv.className = 'text-red-800';
                });
        }

        // Auto-test on page load
        document.addEventListener('DOMContentLoaded', function() {
            testConnection();
        });
    </script>
</body>
</html>

