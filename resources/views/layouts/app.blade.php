<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Camellia Boutique99')</title>

    <!-- Boxicons & Tailwind -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Jetstream & Livewire Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        /* Enhanced Desktop Sidebar */
        .nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .nav-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 0;
            background: linear-gradient(90deg, rgba(139, 69, 19, 0.08) 0%, transparent 100%);
            transition: width 0.3s ease;
        }
        .nav-item:hover::before {
            width: 100%;
        }
        .nav-item.active {
            background: linear-gradient(90deg, rgba(139, 69, 19, 0.1) 0%, rgba(139, 69, 19, 0.05) 100%);
            color: #8B4513;
            border-left: 4px solid #8B4513;
            font-weight: 600;
        }
        .nav-item.active i {
            color: #8B4513;
            transform: scale(1.1);
        }
        .nav-item.active::after {
            content: '';
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            width: 6px;
            height: 6px;
            background: #8B4513;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        /* Enhanced Mobile Bottom Navigation */
        .bottom-nav-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .bottom-nav-item::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 3px;
            background: #8B4513;
            transition: width 0.3s ease;
            border-radius: 2px 2px 0 0;
        }
        .bottom-nav-item.active {
            color: #8B4513;
            font-weight: 600;
        }
        .bottom-nav-item.active i {
            color: #8B4513;
            transform: scale(1.15);
        }
        .bottom-nav-item.active::before {
            width: 70%;
        }
        .bottom-nav-item.active::after {
            content: '';
            position: absolute;
            top: 4px;
            right: 4px;
            width: 6px;
            height: 6px;
            background: #8B4513;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        /* Animations */
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(100%);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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

        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Enhanced logo styling */
        .logo-gradient {
            background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* User avatar styling */
        .user-avatar {
            background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
        }

        /* Safe area for notched devices */
        @supports(padding: max(0px)) {
            .bottom-nav {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }

        /* Enhanced hover effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        /* Loading animation for navigation */
        .nav-loading {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Elegant sidebar styling */
        .sidebar-container {
            background: linear-gradient(180deg, rgba(255, 253, 250, 0.95) 0%, rgba(255, 251, 245, 0.9) 100%);
            box-shadow: 0 0 40px rgba(139, 69, 19, 0.08);
        }

        .nav-icon-container {
            background: rgba(139, 69, 19, 0.08);
            transition: all 0.3s ease;
        }
        .nav-item:hover .nav-icon-container {
            background: rgba(139, 69, 19, 0.15);
            transform: translateY(-1px);
        }

        /* Elegant bottom nav styling */
        .bottom-nav-container {
            background: rgba(255, 253, 250, 0.98);
            box-shadow: 0 -4px 20px rgba(139, 69, 19, 0.08);
        }

        /* Enhanced Account Section */
        .account-section {
            background: linear-gradient(135deg, rgba(255, 251, 245, 0.95) 0%, rgba(254, 247, 230, 0.9) 100%);
            border: 1px solid rgba(139, 69, 19, 0.12);
            box-shadow: 0 4px 20px rgba(139, 69, 19, 0.08);
        }

        .account-avatar {
            position: relative;
            border: 2px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.2);
        }

        .account-role-badge {
            background: linear-gradient(135deg, #8B4513 0%, #654321 100%);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .account-divider {
            background: linear-gradient(90deg, transparent 0%, rgba(139, 69, 19, 0.15) 50%, transparent 100%);
            height: 1px;
        }

        .account-status {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10B981;
            margin-right: 6px;
            animation: pulse 2s infinite;
            box-shadow: 0 0 0 rgba(16, 185, 129, 0.4);
        }

        .logout-btn {
            background: linear-gradient(135deg, #fef7e6 0%, #fef0d7 100%);
            border: 1px solid rgba(139, 69, 19, 0.15);
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(139, 69, 19, 0.05);
        }

        .logout-btn:hover {
            background: linear-gradient(135deg, #feecc6 0%, #fedeb5 100%);
            border-color: rgba(139, 69, 19, 0.25);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.15);
        }

        /* Mobile Profile Menu */
        .mobile-profile-menu {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(139, 69, 19, 0.1);
            animation: slideUp 0.3s ease-out;
        }

        .mobile-profile-item {
            transition: all 0.2s ease;
        }
        
        .mobile-profile-item:hover {
            background: rgba(139, 69, 19, 0.05);
        }

        /* Simple & Minimal Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 253, 250, 0.7); /* Lebih transparan */
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            /* backdrop-filter: none; */ /* Hapus blur sama sekali */
        }

        .loading-container {
            text-align: center;
            animation: fadeIn 0.3s ease-out;
            background: rgba(255, 255, 255, 0.9); /* Background container lebih solid */
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(139, 69, 19, 0.1);
            border: 1px solid rgba(139, 69, 19, 0.1);
        }

        .simple-spinner {
            position: relative;
            width: 70px;
            height: 70px;
            margin: 0 auto 1rem;
        }

        .spinner-ring {
            width: 100%;
            height: 100%;
            border: 2px solid rgba(139, 69, 19, 0.2);
            border-top: 2px solid #8B4513;
            border-radius: 50%;
            animation: spin 1.5s linear infinite;
        }

        .package-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 35px;
            height: 35px;
            background: #8B4513;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .package-icon {
            font-size: 18px;
            color: white;
        }

        .loading-text {
            font-size: 0.9rem;
            color: #8B4513;
            font-weight: 500;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Bottom Profile Dropdown */
        .bottom-profile-dropdown {
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            margin-bottom: 10px;
            width: 280px;
            max-width: calc(100vw - 40px);
            background: rgba(255, 253, 250, 0.98);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(139, 69, 19, 0.15);
            border: 1px solid rgba(139, 69, 19, 0.1);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 100;
        }

        .bottom-profile-dropdown.show {
            opacity: 1;
            visibility: visible;
        }

        .bottom-profile-dropdown::after {
            content: '';
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 8px solid transparent;
            border-top-color: rgba(255, 253, 250, 0.98);
        }

        /* Responsive adjustments for mobile profile dropdown */
        @media (max-width: 640px) {
            .bottom-profile-dropdown {
                width: calc(100vw - 40px);
                left: 50%;
                transform: translateX(-50%);
                margin-bottom: 5px;
            }
            
            .bottom-profile-dropdown .p-4 {
                padding: 1rem;
            }
            
            .bottom-profile-dropdown .account-avatar {
                width: 40px;
                height: 40px;
                font-size: 0.875rem;
            }
            
            .bottom-profile-dropdown .text-sm {
                font-size: 0.875rem;
            }
            
            .bottom-profile-dropdown .text-xs {
                font-size: 0.75rem;
            }
            
            .account-role-badge {
                font-size: 0.65rem;
                padding: 2px 6px;
            }
            
            .logout-btn {
                padding: 8px 12px;
                font-size: 0.75rem;
            }

            /* Mobile adjustments for loading */
            .simple-spinner {
                width: 50px;
                height: 50px;
            }

            .package-center {
                width: 28px;
                height: 28px;
            }

            .package-icon {
                font-size: 14px;
            }
            
            .loading-container {
                padding: 1.5rem;
            }
        }

        /* Style untuk indikator aktif di samping username */
        .username-with-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-amber-50/20 to-white/80">
    <!-- Simple & Minimal Loading Overlay -->
    <div id="loadingOverlay" class="loading-overlay">
        <div class="loading-container">
            <!-- Simple Spinner -->
            <div class="simple-spinner">
                <div class="spinner-ring"></div>
                <div class="package-center">
                    <i class='bx bx-package package-icon'></i>
                </div>
            </div>

            <!-- Loading Text -->
            <div class="loading-text">Memuat...</div>
        </div>
    </div>

    <!-- Desktop Sidebar Navigation -->
    <nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 sidebar-container z-40 transform -translate-x-full lg:translate-x-0 transition-transform duration-500 border-r border-amber-200/30">
        <div class="p-6 h-full flex flex-col">
            <div class="mb-8 pt-4">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-700 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class='bx bx-store text-white text-lg'></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold logo-gradient">Camellia Boutique99</h1>
                        <p class="text-xs text-amber-700/80 mt-1 font-medium">Sistem Manajemen ERP</p>
                    </div>
                </div>
                <div class="w-full h-px bg-gradient-to-r from-transparent via-amber-300/50 to-transparent mt-4"></div>
            </div>

            <!-- Menu -->
            <ul class="space-y-1 flex-1">
                <li><a href="{{ route('dashboard.index') }}" class="nav-item flex items-center px-4 py-3.5 text-gray-700 hover:text-amber-800 rounded-xl" data-route="dashboard">
                    <div class="nav-icon-container w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class='bx bx-home text-lg text-amber-700'></i></div>
                    <span class="font-medium">Dashboard</span>
                </a></li>
                <li><a href="{{ route('products.index') }}" class="nav-item flex items-center px-4 py-3.5 text-gray-700 hover:text-amber-800 rounded-xl" data-route="products">
                    <div class="nav-icon-container w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class='bx bx-package text-lg text-amber-700'></i></div>
                    <span class="font-medium">Produk</span>
                </a></li>
                <li><a href="{{ route('orders_menu') }}" class="nav-item flex items-center px-4 py-3.5 text-gray-700 hover:text-amber-800 rounded-xl" data-route="orders">
                    <div class="nav-icon-container w-8 h-8 rounded-lg flex items-center justify-center mr-3"><i class='bx bx-cart-alt text-lg text-amber-700'></i></div>
                    <span class="font-medium">Pesanan</span>
                </a></li>
            </ul>

            <!-- Enhanced User Section -->
            <div class="pt-6 border-t border-amber-200/30">
                @auth
                <div class="account-section rounded-xl p-4">
                    <!-- User Header dengan Indikator Aktif di Samping Username -->
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="account-avatar user-avatar w-12 h-12 rounded-full flex items-center justify-center text-white text-base font-semibold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="username-with-status">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <div class="flex items-center">
                                    <span class="account-status mr-1"></span>
                                    <span class="text-xs text-amber-700">Aktif</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 truncate mt-1">{{ Auth::user()->email }}</p>
                            <div class="flex items-center mt-2">
                                <span class="account-role-badge">Administrator</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Account Divider -->
                    <div class="account-divider my-3"></div>
                    
                    <!-- Account Actions -->
                    <div class="flex space-x-2">
                        <form method="POST" action="{{ route('logout') }}" id="logout-form" class="w-full">
                            @csrf
                            <button type="submit" class="logout-btn flex items-center justify-center w-full px-3 py-2 rounded-lg text-amber-700 text-sm font-medium transition-all duration-300">
                                <i class='bx bx-log-out text-base mr-2'></i>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>
                @else
                <div class="account-section rounded-xl p-4">
                    <div class="flex items-center space-x-3">
                        <div class="user-avatar w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-semibold shadow-md">
                            <i class='bx bx-user'></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">Pengguna Tamu</p>
                            <p class="text-xs text-gray-600">Silakan login untuk melanjutkan</p>
                        </div>
                        <a href="{{ route('login') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors shadow-sm">
                            Login
                        </a>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Bottom Nav (mobile) with Enhanced Profile -->
    <nav id="bottomNav" class="lg:hidden fixed bottom-0 left-0 right-0 bottom-nav-container z-50 shadow-2xl bottom-nav">
        <div class="flex justify-around items-center p-3">
            <a href="{{ route('dashboard.index') }}" class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 w-16 py-2" data-route="dashboard">
                <i class='bx bx-home text-xl mb-1'></i><span class="text-xs font-medium">Dashboard</span>
            </a>
            <a href="{{ route('products.index') }}" class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 w-16 py-2" data-route="products">
                <i class='bx bx-package text-xl mb-1'></i><span class="text-xs font-medium">Produk</span>
            </a>
            <a href="{{ route('orders_menu') }}" class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 w-16 py-2" data-route="orders">
                <i class='bx bx-cart-alt text-xl mb-1'></i><span class="text-xs font-medium">Pesanan</span>
            </a>
            
            <!-- Enhanced Profile Button with Dropdown -->
            @auth
            <div class="relative">
                <button class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 w-16 py-2" id="bottom-profile-button">
                    <i class='bx bx-user text-xl mb-1'></i><span class="text-xs font-medium">Profil</span>
                </button>
                
                <!-- Profile Dropdown dengan Indikator Aktif di Samping Username -->
                <div class="bottom-profile-dropdown" id="bottom-profile-dropdown">
                    <div class="p-4">
                        <!-- User Info -->
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="account-avatar user-avatar w-12 h-12 rounded-full flex items-center justify-center text-white text-base font-semibold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="username-with-status">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                    <div class="flex items-center">
                                        <span class="account-status mr-1"></span>
                                        <span class="text-xs text-amber-700">Aktif</span>
                                    </div>
                                </div>
                                <p class="text-xs text-gray-600 truncate mt-1">{{ Auth::user()->email }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="account-role-badge">Administrator</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Account Divider -->
                        <div class="account-divider my-3"></div>
                        
                        <!-- Account Actions -->
                        <div class="flex space-x-2">
                            <form method="POST" action="{{ route('logout') }}" id="mobile-logout-form" class="w-full">
                                @csrf
                                <button type="submit" class="logout-btn flex items-center justify-center w-full px-3 py-2 rounded-lg text-amber-700 text-sm font-medium transition-all duration-300">
                                    <i class='bx bx-log-out text-base mr-2'></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="bottom-nav-item flex flex-col items-center justify-center text-gray-600 w-16 py-2" data-route="login">
                <i class='bx bx-log-in text-xl mb-1'></i><span class="text-xs font-medium">Login</span>
            </a>
            @endauth
        </div>
    </nav>

    <!-- Main Content Area - EMPTY (akan diisi oleh halaman lain) -->
    <main class="min-h-screen pb-20 lg:pb-0 lg:ml-64">
        @yield('content')
    </main>

    <!-- Jetstream/Livewire Hooks -->
    @stack('modals')
    @livewireScripts
    @stack('scripts')

    <script>
        window.csrfToken = "{{ csrf_token() }}";
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        window.loginUrl = "{{ route('login') }}";
        window.dashboardUrl = "{{ route('dashboard.index') }}";

        document.addEventListener('DOMContentLoaded', function() {
            const bottomNavItems = document.querySelectorAll('.bottom-nav-item');
            const navItems = document.querySelectorAll('.nav-item');
            const loadingOverlay = document.getElementById('loadingOverlay');
            const bottomProfileButton = document.getElementById('bottom-profile-button');
            const bottomProfileDropdown = document.getElementById('bottom-profile-dropdown');
            const logoutForm = document.getElementById('logout-form');
            const mobileLogoutForm = document.getElementById('mobile-logout-form');

            // Sembunyikan loading overlay setelah halaman selesai dimuat
            function hideLoadingOverlay() {
                if (loadingOverlay) {
                    setTimeout(() => {
                        loadingOverlay.style.opacity = '0';
                        setTimeout(() => {
                            loadingOverlay.style.display = 'none';
                        }, 300);
                    }, 500);
                }
            }

            // Tampilkan loading overlay
            function showLoadingOverlay() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                    setTimeout(() => {
                        loadingOverlay.style.opacity = '1';
                    }, 10);
                }
            }

            // Sembunyikan loading saat halaman pertama kali dimuat
            if (document.readyState === 'complete') {
                hideLoadingOverlay();
            } else {
                window.addEventListener('load', hideLoadingOverlay);
            }

            // Authentication check - redirect to login if not authenticated
            const authRoutes = ['dashboard', 'products', 'orders', 'profile'];
            const currentPath = window.location.pathname;
            
            // Check if current route requires authentication
            const requiresAuth = authRoutes.some(route => 
                currentPath.includes(route) || currentPath === '/' || currentPath === ''
            );
            
            // If user is not authenticated and trying to access protected route
            if (!window.isAuthenticated && requiresAuth) {
                showLoadingOverlay();
                
                // Redirect to login page after a short delay
                setTimeout(() => {
                    window.location.href = window.loginUrl;
                }, 1000);
                return;
            }

            // If user is authenticated and on login page, redirect to dashboard
            if (window.isAuthenticated && (currentPath.includes('login') || currentPath === '/')) {
                showLoadingOverlay();
                
                setTimeout(() => {
                    window.location.href = window.dashboardUrl;
                }, 1000);
            }

            // Enhanced logout functionality
            function handleLogout(event) {
                event.preventDefault();
                
                // Show confirmation dialog
                if (confirm('Apakah Anda yakin ingin keluar?')) {
                    // Show loading
                    showLoadingOverlay();
                    
                    // Add loading state to logout buttons
                    const logoutButtons = document.querySelectorAll('.logout-btn');
                    logoutButtons.forEach(btn => {
                        const originalHtml = btn.innerHTML;
                        btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Sedang Keluar...';
                        btn.disabled = true;
                    });
                    
                    // Submit the form after a short delay to show loading
                    setTimeout(() => {
                        event.target.closest('form').submit();
                    }, 500);
                }
            }

            // Attach logout handlers
            if (logoutForm) {
                logoutForm.addEventListener('submit', handleLogout);
            }
            if (mobileLogoutForm) {
                mobileLogoutForm.addEventListener('submit', handleLogout);
            }

            // Bottom Profile Dropdown Toggle
            if (bottomProfileButton && bottomProfileDropdown) {
                bottomProfileButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    bottomProfileDropdown.classList.toggle('show');
                    
                    // Add active state to the button when dropdown is open
                    this.classList.toggle('active');
                    
                    // Cek posisi dropdown agar tidak keluar dari layar
                    setTimeout(() => {
                        const dropdownRect = bottomProfileDropdown.getBoundingClientRect();
                        const viewportWidth = window.innerWidth;
                        
                        // Jika dropdown keluar dari layar di sisi kanan
                        if (dropdownRect.right > viewportWidth) {
                            const overflow = dropdownRect.right - viewportWidth;
                            bottomProfileDropdown.style.left = `calc(50% - ${overflow}px)`;
                        }
                        
                        // Jika dropdown keluar dari layar di sisi kiri
                        if (dropdownRect.left < 0) {
                            const overflow = -dropdownRect.left;
                            bottomProfileDropdown.style.left = `calc(50% + ${overflow}px)`;
                        }
                    }, 10);
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!bottomProfileDropdown.contains(e.target) && !bottomProfileButton.contains(e.target)) {
                        bottomProfileDropdown.classList.remove('show');
                        bottomProfileButton.classList.remove('active');
                    }
                });

                // Prevent dropdown from closing when clicking inside
                bottomProfileDropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            }

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
                } else if (currentPath.includes('/profile') || currentHash === '#profile') {
                    activeRoute = 'profile';
                } else if (currentPath.includes('/dashboard') || currentPath === '/') {
                    activeRoute = 'dashboard';
                }
                
                // Apply active state to both desktop and mobile navigation
                const desktopActive = document.querySelector(`.nav-item[data-route="${activeRoute}"]`);
                const mobileActive = document.querySelector(`.bottom-nav-item[data-route="${activeRoute}"]`);
                
                if (desktopActive) {
                    desktopActive.classList.add('active');
                    // Animate icon
                    const icon = desktopActive.querySelector('i');
                    icon.style.transform = 'scale(1.1)';
                }
                if (mobileActive) {
                    mobileActive.classList.add('active');
                    // Animate icon
                    const icon = mobileActive.querySelector('i');
                    icon.style.transform = 'scale(1.15)';
                }
            }

            // Enhanced navigation with loading
            function handleNavigation(e) {
                // Don't intercept external links or links with special attributes
                if (e.target.getAttribute('target') === '_blank' || 
                    e.target.getAttribute('download') ||
                    (e.target.getAttribute('href') && 
                     e.target.getAttribute('href').startsWith('http') && 
                     !e.target.getAttribute('href').includes(window.location.host))) {
                    return;
                }
                
                e.preventDefault();
                const href = e.currentTarget.getAttribute('href');
                
                // Show loading
                showLoadingOverlay();
                
                // Navigate after a short delay untuk memastikan loading terlihat
                setTimeout(() => {
                    window.location.href = href;
                }, 400);
            }

            // Enhanced touch feedback for mobile
            bottomNavItems.forEach(item => {
                item.addEventListener('touchstart', function() {
                    this.style.transform = 'scale(0.95)';
                });
                
                item.addEventListener('touchend', function() {
                    this.style.transform = 'scale(1)';
                });
                
                item.addEventListener('click', function(e) {
                    // Skip if it's the profile button
                    if (this.id === 'bottom-profile-button') return;
                    
                    handleNavigation(e);
                    
                    // Add loading animation
                    const icon = this.querySelector('i');
                    const originalTransform = icon.style.transform;
                    icon.style.transform = 'scale(1.2)';
                    
                    setTimeout(() => {
                        icon.style.transform = originalTransform;
                    }, 300);
                });
            });

            // Enhanced hover effects for desktop
            navItems.forEach(item => {
                item.addEventListener('mouseenter', function() {
                    const icon = this.querySelector('i');
                    icon.style.transform = 'scale(1.05)';
                });
                
                item.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('active')) {
                        const icon = this.querySelector('i');
                        icon.style.transform = 'scale(1)';
                    }
                });

                item.addEventListener('click', function(e) {
                    handleNavigation(e);
                });
            });

            // Handle initial load and URL changes
            setActiveNav();
            window.addEventListener('popstate', setActiveNav);
            window.addEventListener('hashchange', setActiveNav);

            // Add smooth entrance animation for sidebar items
            const sidebarItems = document.querySelectorAll('.nav-item');
            sidebarItems.forEach((item, index) => {
                item.style.animationDelay = `${(index + 1) * 0.1}s`;
                item.classList.add('animate-slide-in');
            });
        });
    </script>
</body>
</html>