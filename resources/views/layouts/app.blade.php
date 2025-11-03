<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Butik Solo Jala Buana')</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Desktop Sidebar Active State */
        .nav-item.active {
            background-color: #FEF7E6;
            color: #8B4513;
            border-left: 4px solid #D4AF37;
            font-weight: 600;
        }
        .nav-item.active i {
            color: #8B4513;
        }

        /* Mobile Bottom Navigation Active State */
        .bottom-nav-item.active {
            color: #8B4513;
            font-weight: 600;
            position: relative;
        }
        .bottom-nav-item.active i {
            color: #8B4513;
            transform: scale(1.1);
        }
        .bottom-nav-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background-color: #8B4513;
            border-radius: 50%;
        }

        /* Smooth transitions */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom colors untuk konsistensi */
        .bg-primary { background-color: #8B4513; }
        .text-primary { color: #8B4513; }
        .border-primary { border-color: #8B4513; }
        .hover\:bg-primary:hover { background-color: #654321; }

        /* Optimized for mobile interaction */
        .bottom-nav-item {
            transition: all 0.2s ease-in-out;
            padding: 8px 12px;
            border-radius: 8px;
        }
        .bottom-nav-item:active {
            background-color: #FEF7E6;
            transform: scale(0.95);
        }

        /* Safe area for notched devices */
        @supports(padding: max(0px)) {
            .bottom-nav {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Desktop Sidebar Navigation -->
    <nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-white shadow-xl z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 border-r border-gray-200">
        <div class="p-6 h-full flex flex-col">
            <!-- Logo Section -->
            <div class="mb-8">
                <h1 class="text-xl font-display font-bold text-primary">Camellia Boutique99</h1>
                <p class="text-xs text-gray-600 mt-1">ERP Management System</p>
            </div>

            <input type="hidden" id="csrf-token-input" value="{{ csrf_token() }}">

            <!-- Navigation Menu -->
            <ul class="space-y-2 flex-1">
                <li>
                    <a href="{{ route('dashboard.index') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                       data-route="dashboard">
                       <i class='bx bx-home text-lg mr-3'></i>
                       <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products_menu') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                       data-route="products">
                       <i class='bx bx-package text-lg mr-3'></i>
                       <span class="font-medium">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders_menu') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                       data-route="orders">
                       <i class='bx bx-cart-alt text-lg mr-3'></i>
                       <span class="font-medium">Orders</span>
                    </a>
                </li>
                {{-- <li>
                    <a href="/" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300"
                       data-route="account">
                       <i class='bx bx-user text-lg mr-3'></i>
                       <span class="font-medium">Account</span>
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

    <!-- Mobile Bottom Navigation -->
    <nav id="bottomNav" class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50 shadow-lg">
        <div class="flex justify-around items-center p-3">
            <a href="{{ route('dashboard.index') }}" 
               class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 transition-all duration-300 w-16"
               data-route="dashboard">
               <i class='bx bx-home text-xl mb-1'></i>
               <span class="text-xs font-medium">Dashboard</span>
            </a>
            <a href="{{ route('products_menu') }}" 
               class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 transition-all duration-300 w-16"
               data-route="products">
               <i class='bx bx-package text-xl mb-1'></i>
               <span class="text-xs font-medium">Products</span>
            </a>
            <a href="{{ route('orders_menu') }}" 
               class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 transition-all duration-300 w-16"
               data-route="orders">
               <i class='bx bx-cart-alt text-xl mb-1'></i>
               <span class="text-xs font-medium">Orders</span>
            </a>
            <a href="#" 
               class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 transition-all duration-300 w-16"
               data-route="account">
               <i class='bx bx-user text-xl mb-1'></i>
               <span class="text-xs font-medium">Account</span>
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="min-h-screen pb-20 lg:pb-0 lg:ml-64">
        @yield('content')
    </main>

    <script>
        // Set CSRF token untuk Axios
        window.csrfToken = "{{ csrf_token() }}";
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
            const navItems = document.querySelectorAll('.nav-item');

            // Enhanced active state handler
            function setActiveNav() {
                const currentPath = window.location.pathname;
                const currentHash = window.location.hash;
                
                // Reset semua active state
                navItems.forEach(item => item.classList.remove('active'));
                bottomNavItems.forEach(item => item.classList.remove('active'));
                
                // Determine active route based on current URL
                let activeRoute = 'dashboard'; // default
                
                if (currentPath.includes('/products') || currentHash === '#products') {
                    activeRoute = 'products';
                } else if (currentPath.includes('/orders') || currentHash === '#orders') {
                    activeRoute = 'orders';
                } else if (currentPath.includes('/account') || currentHash === '#account') {
                    activeRoute = 'account';
                } else if (currentPath.includes('/dashboard') || currentPath === '/') {
                    activeRoute = 'dashboard';
                }
                
                // Apply active state to both desktop and mobile navigation
                const desktopActive = document.querySelector(`.nav-item[data-route="${activeRoute}"]`);
                const mobileActive = document.querySelector(`.bottom-nav-item[data-route="${activeRoute}"]`);
                
                if (desktopActive) desktopActive.classList.add('active');
                if (mobileActive) mobileActive.classList.add('active');
            }

            // Enhanced touch feedback for mobile
            bottomNavItems.forEach(item => {
                item.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                item.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
            });

            // Handle initial load and URL changes
            setActiveNav();
            window.addEventListener('popstate', setActiveNav);
            window.addEventListener('hashchange', setActiveNav);

            // Optional: Add loading states for better UX
            bottomNavItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    if (this.getAttribute('href') === '#') {
                        e.preventDefault();
                    }
                    
                    // Add subtle loading feedback
                    const icon = this.querySelector('i');
                    const originalClass = icon.className;
                    icon.className = 'bx bx-loader-alt text-xl mb-1 animate-spin';
                    
                    setTimeout(() => {
                        icon.className = originalClass;
                    }, 800);
                });
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>