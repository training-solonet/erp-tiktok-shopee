<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Butik Solo Jala Buana')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .nav-item.active {
            background-color: #FEF7E6;
            color: #8B4513;
            border-left: 4px solid #D4AF37;
            font-weight: 600;
        }
        .nav-item.active i {
            color: #8B4513;
        }
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom colors untuk konsistensi */
        .bg-primary { background-color: #8B4513; }
        .text-primary { color: #8B4513; }
        .border-primary { border-color: #8B4513; }
        .hover\:bg-primary:hover { background-color: #654321; }
        .from-primary { --tw-gradient-from: #8B4513; }
        .to-primary-600 { --tw-gradient-to: #654321; }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar Navigation -->
    <nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-white shadow-xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 border-r border-gray-200">
        <div class="p-6 h-full flex flex-col">
            <!-- Logo Section -->
            <div class="mb-8">
                <h1 class="text-xl font-display font-bold text-primary">Butik Solo Jala Buana</h1>
                <p class="text-xs text-gray-600 mt-1">ERP Management System</p>
            </div>
            
            <!-- Navigation Menu -->
            <ul class="space-y-2 flex-1">
                <li>
                    <a href="{{ route('dashboard_menu') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300 {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                       <i class='bx bx-home text-lg mr-3'></i>
                       <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products_menu') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300 {{ request()->routeIs('products.*') ? 'active' : '' }}">
                       <i class='bx bx-package text-lg mr-3'></i>
                       <span class="font-medium">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders_menu') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300 {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                       <i class='bx bx-cart-alt text-lg mr-3'></i>
                       <span class="font-medium">Orders</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300">
                       <i class='bx bx-bar-chart text-lg mr-3'></i>
                       <span class="font-medium">Analytics</span>
                    </a>
                </li>
                <li>
                    <a href="#" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300">
                       <i class='bx bx-cog text-lg mr-3'></i>
                       <span class="font-medium">Settings</span>
                    </a>
                </li> --}}
            </ul>
            
            <!-- User Section -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        BS
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">Butik Solo</p>
                        <p class="text-xs text-gray-500 truncate">Administrator</p>
                    </div>
                    <button class="p-1 text-gray-400 hover:text-gray-600 transition-colors">
                        <i class='bx bx-log-out text-lg'></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobileMenuButton" class="p-2 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
            <i class='bx bx-menu text-xl text-primary'></i>
        </button>
    </div>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        @yield('content')
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('sidebar');
            const navItems = document.querySelectorAll('.nav-item');
            
            // Mobile menu toggle
            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // Close mobile menu when clicking on nav items (mobile devices)
            navItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth < 1024) {
                        sidebar.classList.add('-translate-x-full');
                    }
                });
            });

            // Handle active state based on current URL
            function setActiveNav() {
                const currentPath = window.location.pathname;
                navItems.forEach(item => {
                    const href = item.getAttribute('href');
                    if (href && currentPath.includes(href.replace('{{ url('') }}', ''))) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                });
            }

            setActiveNav();
        });
    </script>
    
    @stack('scripts')
</body>
</html>