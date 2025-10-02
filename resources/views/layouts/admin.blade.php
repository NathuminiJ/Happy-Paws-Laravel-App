<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Admin Panel - {{ config('app.name', 'Happy Paws') }}</title>
    <meta name="description" content="Happy Paws Admin Panel - Manage your pet supply store efficiently.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom Styles -->
    <style>
        .admin-sidebar {
            background: linear-gradient(180deg, #1e3a8a 0%, #3730a3 100%);
        }
        .admin-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid #e2e8f0;
        }
        .admin-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .stat-card-green {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }
        .stat-card-yellow {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
        .stat-card-red {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="admin-sidebar w-64 min-h-screen shadow-xl">
            <div class="p-6">
                <div class="flex items-center mb-8">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center mr-3">
                        <i class="fas fa-crown text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-white text-xl font-bold">Admin Panel</h1>
                        <p class="text-indigo-200 text-sm">Happy Paws</p>
                    </div>
                </div>
                
                <nav class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-chart-pie mr-3 group-hover:scale-110 transition-transform"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.products') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.products*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-box mr-3 group-hover:scale-110 transition-transform"></i>
                        Products
                    </a>
                    <a href="{{ route('admin.orders') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.orders*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-shopping-cart mr-3 group-hover:scale-110 transition-transform"></i>
                        Orders
                    </a>
                    <a href="{{ route('admin.customers') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.customers*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-users mr-3 group-hover:scale-110 transition-transform"></i>
                        Customers
                    </a>
                    <a href="{{ route('admin.messages') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.messages*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-envelope mr-3 group-hover:scale-110 transition-transform"></i>
                        Messages
                        @if(\App\Models\ContactMessage::where('status', 'new')->count() > 0)
                            <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
                                {{ \App\Models\ContactMessage::where('status', 'new')->count() }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('admin.brands') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.brands*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-tags mr-3 group-hover:scale-110 transition-transform"></i>
                        Brands
                    </a>
                    <a href="{{ route('admin.analytics') }}" class="flex items-center text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors duration-200 group {{ request()->routeIs('admin.analytics*') ? 'bg-white/10' : '' }}">
                        <i class="fas fa-chart-line mr-3 group-hover:scale-110 transition-transform"></i>
                        Analytics
                    </a>
                </nav>
            </div>
            
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <div class="bg-white/10 rounded-lg p-4">
                    <div class="flex items-center">
                        <img class="w-10 h-10 rounded-full" src="{{ auth()->user()->profile_photo_url }}" alt="{{ auth()->user()->name }}">
                        <div class="ml-3">
                            <p class="text-white font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-indigo-200 text-sm">Administrator</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('home') }}" class="text-indigo-200 hover:text-white text-sm flex items-center">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            View Store
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $pageTitle ?? 'Dashboard' }}</h2>
                        <p class="text-gray-600">{{ $pageDescription ?? 'Manage your pet supply store' }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-bell mr-2"></i>
                                Notifications
                            </button>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    <script>
        // Admin panel JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 5000);
        });
    </script>
</body>
</html>
