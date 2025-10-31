<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Management - Camellia Boutique99</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library untuk ekspor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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

        .transition-all {
            transition: all 0.3s ease-in-out;
        }

        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        .hover\:-translate-y-1:hover {
            transform: translateY(-4px);
        }

        /* Elegant animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Status badges */
        .status-badge {
            transition: all 0.2s ease-in-out;
        }

        .order-item {
            transition: all 0.2s ease-in-out;
        }

        .order-item:hover {
            transform: translateX(4px);
        }

        /* Modal styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            width: 90%;
            max-width: 500px;
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        /* Loading skeleton */
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        /* Mobile responsive adjustments */
        @media (max-width: 1024px) {
            .content-container {
                padding-bottom: 80px;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Sidebar Navigation -->
    <nav id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-white shadow-xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 border-r border-gray-200">
        <div class="p-6 h-full flex flex-col">
            <!-- Logo Section -->
            <div class="mb-8">
                <h1 class="text-xl font-bold text-amber-800">Camellia Boutique99</h1>
                <p class="text-xs text-gray-600 mt-1">ERP Management System</p>
            </div>
            
            <!-- Navigation Menu -->
            <ul class="space-y-2 flex-1">
                <li>
                    <a href="{{ route('dashboard.index') }}" class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300 active">
                        <i class='bx bx-home text-lg mr-3'></i>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products_menu') }}" class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300">
                        <i class='bx bx-package text-lg mr-3'></i>
                        <span class="font-medium">Products</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('orders_menu') }}" class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300">
                        <i class='bx bx-cart-alt text-lg mr-3'></i>
                        <span class="font-medium">Orders</span>
                    </a>
                </li>
            </ul>
            
            <!-- User Section -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-amber-800 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        BS
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">Butik Solo</p>
                        <p class="text-xs text-gray-500 truncate">Administrator</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Button -->
    <div class="lg:hidden fixed top-4 left-4 z-50">
        <button id="mobileMenuButton" class="p-2 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow">
            <i class='bx bx-menu text-xl text-amber-800'></i>
        </button>
    </div>

    <!-- Modal Ekspor Laporan -->
    <div id="exportModal" class="modal-overlay">
        <div class="modal-content">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">Ekspor Laporan</h3>
                    <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Periode</label>
                        <div class="grid grid-cols-2 gap-3">
                            <button class="export-period-btn border border-gray-300 rounded-lg py-2 px-3 text-sm hover:bg-gray-50 transition-colors active bg-amber-100 border-amber-400" data-period="7">
                                7 Hari Terakhir
                            </button>
                            <button class="export-period-btn border border-gray-300 rounded-lg py-2 px-3 text-sm hover:bg-gray-50 transition-colors" data-period="30">
                                30 Hari Terakhir
                            </button>
                            <button class="export-period-btn border border-gray-300 rounded-lg py-2 px-3 text-sm hover:bg-gray-50 transition-colors" data-period="90">
                                90 Hari Terakhir
                            </button>
                            <button class="export-period-btn border border-gray-300 rounded-lg py-2 px-3 text-sm hover:bg-gray-50 transition-colors" data-period="custom">
                                Kustom
                            </button>
                        </div>
                    </div>
                    
                    <div id="customDateRange" class="hidden space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                            <input type="date" id="startDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                            <input type="date" id="endDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Format</label>
                        <div class="flex space-x-3">
                            <button id="exportPdf" class="flex-1 flex items-center justify-center space-x-2 bg-red-50 border border-red-200 text-red-700 rounded-lg py-3 hover:bg-red-100 transition-colors">
                                <i class='bx bxs-file-pdf text-xl'></i>
                                <span>PDF</span>
                            </button>
                            <button id="exportExcel" class="flex-1 flex items-center justify-center space-x-2 bg-green-50 border border-green-200 text-green-700 rounded-lg py-3 hover:bg-green-100 transition-colors">
                                <i class='bx bxs-file-excel text-xl'></i>
                                <span>Excel</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <main class="lg:ml-64 min-h-screen">
        <div class="content-container">
            <div class="space-y-6 p-6">
                <!-- Welcome Section dengan Quick Actions -->
                <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500 rounded-full -translate-y-32 translate-x-32 opacity-20"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400 rounded-full translate-y-24 -translate-x-24 opacity-20"></div>
                    
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between relative z-10">
                        <div class="mb-6 lg:mb-0">
                            <h1 class="text-3xl font-bold mb-2">Selamat Datang di Camellia Boutique99</h1>
                            <p class="text-amber-100 text-lg mb-1">Dashboard Management System</p>
                            <p class="text-amber-200 text-sm" id="currentDateTime">Memuat...</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-3 min-w-20">
                                <div class="text-2xl font-bold" id="currentDay">--</div>
                                <div class="text-xs text-amber-200" id="currentDate">--</div>
                            </div>
                            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                                <i class='bx bx-calendar text-xl'></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats Bar -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="welcomeTotalProducts">{{ number_format($total_products) }}</div>
                            <div class="text-amber-200 text-xs">Total Produk</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="welcomeActiveOrders">{{ number_format($active_orders) }}</div>
                            <div class="text-amber-200 text-xs">Pesanan Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold" id="welcomeMonthlyRevenue">Rp {{ number_format($monthly_revenue, 0, ',', '.') }}</div>
                            <div class="text-amber-200 text-xs">Pendapatan Bulanan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold" id="welcomeInventoryValue">Rp {{ number_format($inventory_value, 0, ',', '.') }}</div>
                            <div class="text-amber-200 text-xs">Nilai Inventori</div>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Total Products -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1 cursor-pointer" onclick="refreshMetrics('products')">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Produk</p>
                                <p class="text-2xl font-bold text-gray-900 mb-1" id="totalProducts">{{ number_format($total_products) }}</p>
                                <p class="text-xs text-emerald-600 flex items-center">
                                    <i class='bx bx-up-arrow-alt mr-1'></i>
                                    <span id="activeProductsCount">{{ number_format($active_products) }}</span> aktif
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-package text-blue-500 text-xl'></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                            <span class="text-xs text-gray-500">Sumber Data</span>
                            <span class="text-xs text-gray-400" id="dataSource">{{ $data_source ?? 'database' }}</span>
                        </div>
                    </div>

                    <!-- Inventory Value -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Nilai Inventori</p>
                                <p class="text-xl font-bold text-gray-900 mb-1">Rp <span id="inventoryValue">{{ number_format($inventory_value, 0, ',', '.') }}</span></p>
                                <p class="text-xs text-purple-600 flex items-center">
                                    <i class='bx bx-package mr-1'></i>
                                    <span id="totalStock">{{ number_format($total_stock) }}</span> item
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-archive text-purple-500 text-xl'></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Rata-rata: Rp {{ number_format($total_products > 0 ? $inventory_value / $total_products : 0, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Active Orders -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1 cursor-pointer" onclick="refreshMetrics('orders')">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Pesanan Aktif</p>
                                <p class="text-2xl font-bold text-gray-900 mb-1" id="activeOrders">{{ number_format($active_orders) }}</p>
                                <p class="text-xs text-blue-600 flex items-center">
                                    <i class='bx bx-time mr-1'></i>
                                    <span id="pendingShipment">{{ number_format($pending_shipment) }}</span> tertunda
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-cart-alt text-green-500 text-xl'></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Selesai: {{ number_format($active_orders - $pending_shipment) }}</span>
                                <span class="text-emerald-600">Live Data</span>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Revenue -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Pendapatan Bulanan</p>
                                <p class="text-xl font-bold text-gray-900 mb-1">Rp <span id="monthlyRevenue">{{ number_format($monthly_revenue, 0, ',', '.') }}</span></p>
                                <p class="text-xs text-amber-600 flex items-center">
                                    <i class='bx bx-trending-up mr-1'></i>
                                    <span id="revenueGrowth">Estimasi</span> real-time
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-dollar-circle text-amber-500 text-xl'></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="text-xs text-gray-500 text-center">
                                Data estimasi dari pesanan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Orders & Performance -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Recent Orders -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Pesanan Terbaru</h3>
                                    <p class="text-sm text-gray-500 mt-1">Pesanan pelanggan terbaru</p>
                                </div>
                                <div class="flex items-center space-x-3">
                                    <button id="refreshOrders" class="p-2 text-gray-400 hover:text-amber-600 transition-colors duration-200" title="Segarkan Pesanan">
                                        <i class='bx bx-refresh text-lg'></i>
                                    </button>
                                    <a href="{{ route('orders_menu') }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center">
                                        Lihat Semua
                                        <i class='bx bx-chevron-right ml-1'></i>
                                    </a>
                                </div>
                            </div>
                            
                            <div class="space-y-3" id="ordersContainer">
                                @if(count($recent_orders) > 0)
                                    @foreach($recent_orders as $order)
                                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-200 order-item">
                                        <div class="flex items-center space-x-4 flex-1">
                                            <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center shadow-sm">
                                                <i class='bx bx-receipt text-amber-500'></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="font-medium text-gray-800 text-sm">{{ $order['id'] }}</h4>
                                                <p class="text-sm text-gray-600 truncate">{{ $order['recipient_address']['name'] ?? 'Pelanggan' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-semibold text-gray-900 block text-sm">Rp {{ number_format($order['payment']['total_amount'] ?? 0, 0, ',', '.') }}</span>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium status-badge 
                                                @if(($order['status'] ?? '') === 'completed') bg-emerald-100 text-emerald-800 border border-emerald-200
                                                @elseif(($order['status'] ?? '') === 'processing') bg-blue-100 text-blue-800 border border-blue-200
                                                @elseif(($order['status'] ?? '') === 'pending') bg-amber-100 text-amber-800 border border-amber-200
                                                @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                                                @if(($order['status'] ?? '') === 'completed') Selesai
                                                @elseif(($order['status'] ?? '') === 'processing') Diproses
                                                @elseif(($order['status'] ?? '') === 'pending') Tertunda
                                                @else Tidak Diketahui @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-8" id="noOrders">
                                        <i class='bx bx-receipt text-4xl text-gray-300 mb-3'></i>
                                        <p class="text-gray-500">Tidak ada pesanan terbaru</p>
                                        <p class="text-sm text-gray-400 mt-1">Pesanan baru akan muncul di sini</p>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Orders Loading State -->
                            <div id="ordersLoading" class="hidden text-center py-8">
                                <div class="inline-flex items-center">
                                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-amber-600 mr-3"></div>
                                    <span class="text-gray-600">Memuat pesanan...</span>
                                </div>
                            </div>
                        </div>

                        <!-- Platform Performance -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Kinerja Platform</h3>
                                    <p class="text-sm text-gray-500 mt-1">Distribusi pesanan di berbagai platform</p>
                                </div>
                                <select id="platformPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition">
                                    <option value="7">7 hari terakhir</option>
                                    <option value="30">30 hari terakhir</option>
                                    <option value="90">90 hari terakhir</option>
                                </select>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4" id="platformStats">
                                @foreach([
                                    ['platform' => 'TikTok Shop', 'orders' => 156, 'growth' => '+18%', 'color' => 'bg-black', 'icon' => 'bx-music'],
                                    ['platform' => 'Shopee', 'orders' => 98, 'growth' => '+12%', 'color' => 'bg-orange-500', 'icon' => 'bx-store'],
                                    ['platform' => 'Website', 'orders' => 45, 'growth' => '+22%', 'color' => 'bg-blue-500', 'icon' => 'bx-globe']
                                ] as $platform)
                                <div class="text-center p-4 bg-gray-50 rounded-lg hover:shadow-sm transition-all duration-300 hover:-translate-y-1 platform-card">
                                    <div class="w-12 h-12 {{ $platform['color'] }} rounded-lg flex items-center justify-center mx-auto mb-3 shadow-sm">
                                        <i class='bx {{ $platform['icon'] }} text-white text-lg'></i>
                                    </div>
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $platform['platform'] }}</h4>
                                    <p class="text-lg font-bold text-amber-600 mb-1">{{ number_format($platform['orders']) }}</p>
                                    <p class="text-xs text-emerald-600 font-medium">{{ $platform['growth'] }}</p>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-1">
                                        <div class="bg-amber-500 h-1 rounded-full" style="width: {{ ($platform['orders'] / 300) * 100 }}%"></div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar - Quick Actions & Stats -->
                    <div class="space-y-6">
                        <!-- Quick Actions -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                            <div class="space-y-3">
                                <a href="{{ route('products_menu') }}" class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-200 group">
                                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class='bx bx-package text-amber-600 text-lg'></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900 text-sm">Kelola Produk</p>
                                        <p class="text-xs text-gray-600">{{ number_format($total_products) }} produk</p>
                                    </div>
                                    <i class='bx bx-chevron-right text-gray-400 group-hover:text-amber-600 transition-colors'></i>
                                </a>
                                
                                <button onclick="refreshAllData()" class="w-full flex items-center p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors duration-200 group">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class='bx bx-refresh text-blue-600 text-lg'></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="font-medium text-gray-900 text-sm">Segarkan Data</p>
                                        <p class="text-xs text-gray-600">Perbarui semua metrik</p>
                                    </div>
                                    <i class='bx bx-chevron-right text-gray-400 group-hover:text-blue-600 transition-colors'></i>
                                </button>
                                
                                <button onclick="showExportModal()" class="w-full flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform">
                                        <i class='bx bx-download text-green-600 text-lg'></i>
                                    </div>
                                    <div class="flex-1 text-left">
                                        <p class="font-medium text-gray-900 text-sm">Ekspor Laporan</p>
                                        <p class="text-xs text-gray-600">PDF & Excel</p>
                                    </div>
                                    <i class='bx bx-chevron-right text-gray-400 group-hover:text-green-600 transition-colors'></i>
                                </button>
                            </div>
                        </div>

                        <!-- Inventory Health -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Kesehatan Inventori</h3>
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Tersedia</span>
                                        <span>{{ number_format($total_stock) }} item</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $total_stock > 0 ? 85 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Stok Menipis</span>
                                        <span>{{ number_format($low_stock_products) }} item</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $total_products > 0 ? ($low_stock_products / $total_products) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Habis</span>
                                        <span>{{ number_format($out_of_stock_products) }} item</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: {{ $total_products > 0 ? ($out_of_stock_products / $total_products) * 100 : 0 }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Sistem</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Database Produk</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class='bx bx-check-circle mr-1'></i>Aktif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Total Data</span>
                                    <span class="text-sm text-gray-900">{{ number_format($total_products) }} produk</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Update Terakhir</span>
                                    <span class="text-sm text-gray-900" id="lastSyncTime">{{ $last_updated }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Global state
        let refreshInterval;
        let selectedPeriod = '7';
        let exportData = {};

        // Data dari PHP/Laravel - dengan fallback yang aman
        const dashboardData = {
            total_products: {{ $total_products }},
            active_products: {{ $active_products }},
            total_stock: {{ $total_stock }},
            inventory_value: {{ $inventory_value }},
            active_orders: {{ $active_orders }},
            pending_shipment: {{ $pending_shipment }},
            monthly_revenue: {{ $monthly_revenue }},
            total_revenue: {{ $total_revenue ?? 0 }},
            recent_orders: @json($recent_orders ?? []),
            // Data tambahan dari controller baru
            low_stock_products: {{ $low_stock_products ?? 0 }},
            out_of_stock_products: {{ $out_of_stock_products ?? 0 }},
            data_source: "{{ $data_source ?? 'database' }}",
            last_updated: "{{ $last_updated ?? now()->toDateTimeString() }}"
        };

        console.log('Dashboard Data Loaded:', dashboardData);

        document.addEventListener('DOMContentLoaded', function() {
            initializeDashboard();
            startAutoRefresh();
            setupEventListeners();
            setupExportEventListeners();
            setupSidebar();
        });

        function setupSidebar() {
            const mobileMenuButton = document.getElementById('mobileMenuButton');
            const sidebar = document.getElementById('sidebar');
            const navItems = document.querySelectorAll('.nav-item');
            
            // Mobile menu toggle
            if (mobileMenuButton && sidebar) {
                mobileMenuButton.addEventListener('click', () => {
                    sidebar.classList.toggle('-translate-x-full');
                });
            }

            // Close mobile menu when clicking outside (mobile devices)
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 1024) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnMenuButton = mobileMenuButton.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnMenuButton) {
                        sidebar.classList.add('-translate-x-full');
                    }
                }
            });

            // Handle active state based on current URL
            function setActiveNav() {
                const currentPath = window.location.pathname;
                
                // Reset semua active state
                navItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Logic untuk menentukan menu aktif
                if (currentPath.includes('/products')) {
                    document.querySelector('[href="{{ route('products_menu') }}"]').classList.add('active');
                } else if (currentPath.includes('/orders')) {
                    document.querySelector('[href="{{ route('orders_menu') }}"]').classList.add('active');
                } else {
                    document.querySelector('[href="{{ route('dashboard.index') }}"]').classList.add('active');
                }
            }

            // Panggil function saat load
            setActiveNav();
        }

        function initializeDashboard() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
        }

        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            
            document.getElementById('currentDateTime').textContent = now.toLocaleDateString('id-ID', options);
            document.getElementById('currentDay').textContent = now.getDate();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { month: 'short' });
        }

        function setupEventListeners() {
            // Refresh orders button
            const refreshOrdersBtn = document.getElementById('refreshOrders');
            if (refreshOrdersBtn) {
                refreshOrdersBtn.addEventListener('click', refreshOrders);
            }
            
            // Platform period filter
            const platformPeriod = document.getElementById('platformPeriod');
            if (platformPeriod) {
                platformPeriod.addEventListener('change', updatePlatformStats);
            }
            
            // Auto-refresh toggle
            document.addEventListener('visibilitychange', handleVisibilityChange);
        }

        function setupExportEventListeners() {
            // Modal events
            const closeModalBtn = document.getElementById('closeModal');
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', hideExportModal);
            }

            const exportModal = document.getElementById('exportModal');
            if (exportModal) {
                exportModal.addEventListener('click', function(e) {
                    if (e.target === this) hideExportModal();
                });
            }

            // Period buttons
            const periodButtons = document.querySelectorAll('.export-period-btn');
            periodButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    periodButtons.forEach(b => b.classList.remove('active', 'bg-amber-100', 'border-amber-400'));
                    this.classList.add('active', 'bg-amber-100', 'border-amber-400');
                    selectedPeriod = this.dataset.period;
                    
                    if (selectedPeriod === 'custom') {
                        document.getElementById('customDateRange').classList.remove('hidden');
                    } else {
                        document.getElementById('customDateRange').classList.add('hidden');
                    }
                });
            });

            // Export buttons
            const exportPdfBtn = document.getElementById('exportPdf');
            if (exportPdfBtn) {
                exportPdfBtn.addEventListener('click', exportToPdf);
            }

            const exportExcelBtn = document.getElementById('exportExcel');
            if (exportExcelBtn) {
                exportExcelBtn.addEventListener('click', exportToExcel);
            }
        }

        function handleVisibilityChange() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            } else {
                startAutoRefresh();
            }
        }

        function startAutoRefresh() {
            // Refresh setiap 2 menit
            refreshInterval = setInterval(() => {
                refreshAllData();
            }, 120000);
        }

        // Main refresh function
        async function refreshAllData() {
            showNotification('Menyegarkan semua data...', 'info');
            
            try {
                const response = await fetch('/api/dashboard/data');
                const result = await response.json();
                
                if (result.success) {
                    updateDashboardUI(result.data);
                    showNotification('Data berhasil diperbarui', 'success');
                } else {
                    showNotification('Gagal memperbarui data: ' + result.error, 'error');
                }
            } catch (error) {
                console.error('Refresh error:', error);
                showNotification('Error jaringan saat memperbarui data', 'error');
            }
        }

        // Refresh specific metrics
        async function refreshMetrics(type = 'all') {
            const metrics = type === 'all' ? ['products', 'orders'] : [type];
            
            showNotification(`Memperbarui data ${type}...`, 'info');
            
            try {
                const response = await fetch('/api/dashboard/refresh', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ metrics })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    updateDashboardUI(result.data);
                    showNotification(`Data ${type} berhasil diperbarui`, 'success');
                } else {
                    showNotification(`Gagal memperbarui ${type}: ` + result.error, 'error');
                }
            } catch (error) {
                console.error('Refresh metrics error:', error);
                showNotification('Error jaringan saat memperbarui data', 'error');
            }
        }

        // Update UI dengan data baru
        function updateDashboardUI(data) {
            // Update welcome section
            if (data.total_products !== undefined) {
                document.getElementById('welcomeTotalProducts').textContent = data.total_products.toLocaleString();
                document.getElementById('totalProducts').textContent = data.total_products.toLocaleString();
                document.getElementById('activeProductsCount').textContent = data.active_products.toLocaleString();
            }
            
            if (data.active_orders !== undefined) {
                document.getElementById('welcomeActiveOrders').textContent = data.active_orders.toLocaleString();
                document.getElementById('activeOrders').textContent = data.active_orders.toLocaleString();
                document.getElementById('pendingShipment').textContent = data.pending_shipment.toLocaleString();
            }
            
            if (data.monthly_revenue !== undefined) {
                document.getElementById('welcomeMonthlyRevenue').textContent = 'Rp ' + data.monthly_revenue.toLocaleString('id-ID');
                document.getElementById('monthlyRevenue').textContent = data.monthly_revenue.toLocaleString('id-ID');
            }
            
            if (data.inventory_value !== undefined) {
                document.getElementById('welcomeInventoryValue').textContent = 'Rp ' + data.inventory_value.toLocaleString('id-ID');
                document.getElementById('inventoryValue').textContent = data.inventory_value.toLocaleString('id-ID');
                document.getElementById('totalStock').textContent = data.total_stock.toLocaleString();
            }
            
            // Update recent orders
            if (data.recent_orders && data.recent_orders.length > 0) {
                updateOrdersList(data.recent_orders);
            }
            
            // Update timestamp
            document.getElementById('lastSyncTime').textContent = new Date().toLocaleString('id-ID');
        }

        function updateOrdersList(orders) {
            const container = document.getElementById('ordersContainer');
            const noOrders = document.getElementById('noOrders');
            
            if (orders.length === 0) {
                if (noOrders) {
                    noOrders.classList.remove('hidden');
                }
                container.innerHTML = '';
                return;
            }
            
            if (noOrders) {
                noOrders.classList.add('hidden');
            }
            
            let ordersHTML = '';
            orders.forEach(order => {
                const statusClass = {
                    'completed': 'bg-emerald-100 text-emerald-800 border border-emerald-200',
                    'processing': 'bg-blue-100 text-blue-800 border border-blue-200',
                    'pending': 'bg-amber-100 text-amber-800 border border-amber-200'
                }[order.status] || 'bg-gray-100 text-gray-800 border border-gray-200';
                
                const statusText = {
                    'completed': 'Selesai',
                    'processing': 'Diproses',
                    'pending': 'Tertunda'
                }[order.status] || 'Tidak Diketahui';
                
                ordersHTML += `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-200 order-item fade-in">
                        <div class="flex items-center space-x-4 flex-1">
                            <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center shadow-sm">
                                <i class='bx bx-receipt text-amber-500'></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-800 text-sm">${order.id}</h4>
                                <p class="text-sm text-gray-600 truncate">${order.recipient_address?.name || 'Pelanggan'}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="font-semibold text-gray-900 block text-sm">Rp ${(order.payment?.total_amount || 0).toLocaleString('id-ID')}</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium status-badge ${statusClass}">
                                ${statusText}
                            </span>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = ordersHTML;
        }

        async function refreshOrders() {
            const container = document.getElementById('ordersContainer');
            const loading = document.getElementById('ordersLoading');
            const noOrders = document.getElementById('noOrders');
            
            // Show loading
            container.classList.add('hidden');
            loading.classList.remove('hidden');
            if (noOrders) noOrders.classList.add('hidden');
            
            try {
                const response = await fetch('/api/dashboard/refresh', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ metrics: ['orders'] })
                });
                
                const result = await response.json();
                
                loading.classList.add('hidden');
                container.classList.remove('hidden');
                
                if (result.success && result.data.recent_orders) {
                    updateOrdersList(result.data.recent_orders);
                    showNotification('Pesanan berhasil disegarkan', 'success');
                } else {
                    showNotification('Gagal memuat pesanan', 'error');
                }
            } catch (error) {
                console.error('Refresh orders error:', error);
                loading.classList.add('hidden');
                container.classList.remove('hidden');
                showNotification('Error jaringan saat memuat pesanan', 'error');
            }
        }

        function updatePlatformStats() {
            const period = document.getElementById('platformPeriod').value;
            const platformStats = document.getElementById('platformStats');
            
            platformStats.classList.add('opacity-50');
            
            // Simulate API call with different data based on period
            setTimeout(() => {
                platformStats.classList.remove('opacity-50');
                
                // Add animation to platform cards
                const platformCards = document.querySelectorAll('.platform-card');
                platformCards.forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.1}s`;
                    card.classList.add('fade-in');
                });
                
                showNotification(`Statistik platform diperbarui untuk ${period} hari`, 'info');
            }, 800);
        }

        function showExportModal() {
            document.getElementById('exportModal').classList.add('active');
            prepareExportData();
        }

        function hideExportModal() {
            document.getElementById('exportModal').classList.remove('active');
        }

        function prepareExportData() {
            const currentDate = new Date();
            const periodDays = parseInt(selectedPeriod);
            const startDate = new Date();
            startDate.setDate(currentDate.getDate() - periodDays);

            exportData = {
                company: {
                    name: "Camellia Boutique99",
                    address: "Jl. Solo Jala Buana No. 78",
                    phone: "+62 812-3456-7890"
                },
                report: {
                    title: "Laporan Kinerja Bisnis",
                    period: `${startDate.toLocaleDateString('id-ID')} - ${currentDate.toLocaleDateString('id-ID')}`,
                    generated: currentDate.toLocaleString('id-ID')
                },
                metrics: {
                    totalProducts: dashboardData.total_products.toLocaleString(),
                    activeProducts: dashboardData.active_products.toLocaleString(),
                    activeOrders: dashboardData.active_orders.toLocaleString(),
                    pendingShipment: dashboardData.pending_shipment.toLocaleString(),
                    monthlyRevenue: 'Rp ' + dashboardData.monthly_revenue.toLocaleString('id-ID'),
                    inventoryValue: 'Rp ' + dashboardData.inventory_value.toLocaleString('id-ID'),
                    totalStock: dashboardData.total_stock.toLocaleString(),
                    lowStockProducts: dashboardData.low_stock_products.toLocaleString(),
                    outOfStockProducts: dashboardData.out_of_stock_products.toLocaleString()
                },
                platformPerformance: [
                    { platform: 'TikTok Shop', orders: 156, growth: '+18%', percentage: 52 },
                    { platform: 'Shopee', orders: 98, growth: '+12%', percentage: 33 },
                    { platform: 'Website', orders: 45, growth: '+22%', percentage: 15 }
                ],
                recentOrders: dashboardData.recent_orders.map(order => ({
                    id: order.id,
                    customer: order.recipient_address?.name || 'Pelanggan',
                    amount: order.payment?.total_amount?.toLocaleString('id-ID') || '0',
                    status: order.status
                })),
                inventoryHealth: [
                    { category: 'Tersedia', items: dashboardData.total_stock.toLocaleString(), percentage: 85 },
                    { category: 'Stok Menipis', items: dashboardData.low_stock_products.toLocaleString(), percentage: Math.round((dashboardData.low_stock_products / dashboardData.total_products) * 100) || 10 },
                    { category: 'Habis', items: dashboardData.out_of_stock_products.toLocaleString(), percentage: Math.round((dashboardData.out_of_stock_products / dashboardData.total_products) * 100) || 5 }
                ]
            };
        }

        function exportToPdf() {
            showNotification('Mempersiapkan laporan PDF...', 'info');
            
            setTimeout(() => {
                try {
                    const { jsPDF } = window.jspdf;
                    const doc = new jsPDF();
                    
                    // Header
                    doc.setFontSize(20);
                    doc.setTextColor(40, 40, 40);
                    doc.text(exportData.company.name, 20, 30);
                    
                    doc.setFontSize(12);
                    doc.setTextColor(100, 100, 100);
                    doc.text(exportData.company.address, 20, 40);
                    doc.text(exportData.company.phone, 20, 47);
                    
                    // Judul Laporan
                    doc.setFontSize(16);
                    doc.setTextColor(139, 69, 19); // Brown color
                    doc.text(exportData.report.title, 20, 65);
                    
                    doc.setFontSize(10);
                    doc.setTextColor(100, 100, 100);
                    doc.text(`Periode: ${exportData.report.period}`, 20, 75);
                    doc.text(`Dibuat: ${exportData.report.generated}`, 20, 82);
                    doc.text(`Sumber Data: ${dashboardData.data_source}`, 20, 89);
                    
                    // Garis pemisah
                    doc.setDrawColor(139, 69, 19);
                    doc.setLineWidth(0.5);
                    doc.line(20, 95, 190, 95);
                    
                    let yPosition = 110;
                    
                    // Metrik Utama
                    doc.setFontSize(14);
                    doc.setTextColor(40, 40, 40);
                    doc.text('METRIK UTAMA', 20, yPosition);
                    yPosition += 10;
                    
                    const metrics = [
                        ['Total Produk', exportData.metrics.totalProducts],
                        ['Produk Aktif', exportData.metrics.activeProducts],
                        ['Pesanan Aktif', exportData.metrics.activeOrders],
                        ['Pengiriman Tertunda', exportData.metrics.pendingShipment],
                        ['Pendapatan Bulanan', exportData.metrics.monthlyRevenue],
                        ['Nilai Inventori', exportData.metrics.inventoryValue],
                        ['Stok Menipis', exportData.metrics.lowStockProducts],
                        ['Stok Habis', exportData.metrics.outOfStockProducts]
                    ];
                    
                    doc.autoTable({
                        startY: yPosition,
                        head: [['Metrik', 'Nilai']],
                        body: metrics,
                        theme: 'grid',
                        headStyles: { fillColor: [139, 69, 19] },
                        styles: { fontSize: 10 },
                        margin: { left: 20, right: 20 }
                    });
                    
                    yPosition = doc.lastAutoTable.finalY + 15;
                    
                    // Kinerja Platform
                    doc.setFontSize(14);
                    doc.text('KINERJA PLATFORM', 20, yPosition);
                    yPosition += 10;
                    
                    const platformData = exportData.platformPerformance.map(p => [
                        p.platform, 
                        p.orders.toString(), 
                        p.growth,
                        `${p.percentage}%`
                    ]);
                    
                    doc.autoTable({
                        startY: yPosition,
                        head: [['Platform', 'Pesanan', 'Pertumbuhan', 'Persentase']],
                        body: platformData,
                        theme: 'grid',
                        headStyles: { fillColor: [139, 69, 19] },
                        styles: { fontSize: 10 },
                        margin: { left: 20, right: 20 }
                    });
                    
                    yPosition = doc.lastAutoTable.finalY + 15;
                    
                    // Pesanan Terbaru
                    if (exportData.recentOrders.length > 0) {
                        doc.setFontSize(14);
                        doc.text('PESANAN TERBARU', 20, yPosition);
                        yPosition += 10;
                        
                        const ordersData = exportData.recentOrders.map(o => [
                            o.id, 
                            o.customer, 
                            `Rp ${o.amount}`,
                            o.status
                        ]);
                        
                        doc.autoTable({
                            startY: yPosition,
                            head: [['ID Pesanan', 'Pelanggan', 'Jumlah', 'Status']],
                            body: ordersData,
                            theme: 'grid',
                            headStyles: { fillColor: [139, 69, 19] },
                            styles: { fontSize: 10 },
                            margin: { left: 20, right: 20 }
                        });
                        
                        yPosition = doc.lastAutoTable.finalY + 15;
                    }
                    
                    // Kesehatan Inventori
                    doc.setFontSize(14);
                    doc.text('KESEHATAN INVENTORI', 20, yPosition);
                    yPosition += 10;
                    
                    const inventoryData = exportData.inventoryHealth.map(i => [
                        i.category, 
                        i.items, 
                        `${i.percentage}%`
                    ]);
                    
                    doc.autoTable({
                        startY: yPosition,
                        head: [['Kategori', 'Jumlah Item', 'Persentase']],
                        body: inventoryData,
                        theme: 'grid',
                        headStyles: { fillColor: [139, 69, 19] },
                        styles: { fontSize: 10 },
                        margin: { left: 20, right: 20 }
                    });
                    
                    // Footer
                    const pageCount = doc.internal.getNumberOfPages();
                    for (let i = 1; i <= pageCount; i++) {
                        doc.setPage(i);
                        doc.setFontSize(8);
                        doc.setTextColor(150, 150, 150);
                        doc.text(`Halaman ${i} dari ${pageCount}`, 105, 285, { align: 'center' });
                        doc.text(`Dibuat oleh Sistem Camellia Boutique99`, 105, 290, { align: 'center' });
                    }
                    
                    // Simpan PDF
                    const fileName = `Laporan_Bisnis_Camellia_${new Date().toISOString().split('T')[0]}.pdf`;
                    doc.save(fileName);
                    
                    hideExportModal();
                    showNotification('Laporan PDF berhasil diekspor!', 'success');
                } catch (error) {
                    console.error('Error generating PDF:', error);
                    showNotification('Terjadi kesalahan saat mengekspor PDF', 'error');
                }
            }, 1000);
        }

        function exportToExcel() {
            showNotification('Mempersiapkan laporan Excel...', 'info');
            
            setTimeout(() => {
                try {
                    // Membuat workbook baru
                    const wb = XLSX.utils.book_new();
                    
                    // Sheet 1: Ringkasan Metrik
                    const summaryData = [
                        ['LAPORAN KINERJA BISNIS - CAMELLIA BOUTIQUE99'],
                        [''],
                        ['Periode', exportData.report.period],
                        ['Dibuat', exportData.report.generated],
                        ['Sumber Data', dashboardData.data_source],
                        [''],
                        ['METRIK UTAMA'],
                        ['Total Produk', exportData.metrics.totalProducts],
                        ['Produk Aktif', exportData.metrics.activeProducts],
                        ['Pesanan Aktif', exportData.metrics.activeOrders],
                        ['Pengiriman Tertunda', exportData.metrics.pendingShipment],
                        ['Pendapatan Bulanan', exportData.metrics.monthlyRevenue],
                        ['Nilai Inventori', exportData.metrics.inventoryValue],
                        ['Stok Menipis', exportData.metrics.lowStockProducts],
                        ['Stok Habis', exportData.metrics.outOfStockProducts],
                        [''],
                        ['KINERJA PLATFORM'],
                        ['Platform', 'Pesanan', 'Pertumbuhan', 'Persentase']
                    ];
                    
                    // Tambahkan data platform
                    exportData.platformPerformance.forEach(p => {
                        summaryData.push([p.platform, p.orders, p.growth, `${p.percentage}%`]);
                    });
                    
                    const wsSummary = XLSX.utils.aoa_to_sheet(summaryData);
                    
                    // Sheet 2: Detail Pesanan
                    const ordersData = [
                        ['PESANAN TERBARU'],
                        ['ID Pesanan', 'Pelanggan', 'Jumlah', 'Status']
                    ];
                    
                    exportData.recentOrders.forEach(o => {
                        ordersData.push([o.id, o.customer, `Rp ${o.amount}`, o.status]);
                    });
                    
                    const wsOrders = XLSX.utils.aoa_to_sheet(ordersData);
                    
                    // Sheet 3: Kesehatan Inventori
                    const inventoryData = [
                        ['KESEHATAN INVENTORI'],
                        ['Kategori', 'Jumlah Item', 'Persentase']
                    ];
                    
                    exportData.inventoryHealth.forEach(i => {
                        inventoryData.push([i.category, i.items, `${i.percentage}%`]);
                    });
                    
                    const wsInventory = XLSX.utils.aoa_to_sheet(inventoryData);
                    
                    // Tambahkan sheets ke workbook
                    XLSX.utils.book_append_sheet(wb, wsSummary, 'Ringkasan');
                    XLSX.utils.book_append_sheet(wb, wsOrders, 'Pesanan');
                    XLSX.utils.book_append_sheet(wb, wsInventory, 'Inventori');
                    
                    // Simpan file Excel
                    const fileName = `Laporan_Bisnis_Camellia_${new Date().toISOString().split('T')[0]}.xlsx`;
                    XLSX.writeFile(wb, fileName);
                    
                    hideExportModal();
                    showNotification('Laporan Excel berhasil diekspor!', 'success');
                } catch (error) {
                    console.error('Error generating Excel:', error);
                    showNotification('Terjadi kesalahan saat mengekspor Excel', 'error');
                }
            }, 1000);
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notificationContainer');
            if (!container) return;
            
            const notification = document.createElement('div');
            
            const typeStyles = {
                success: 'bg-emerald-500 text-white border border-emerald-600',
                error: 'bg-red-500 text-white border border-red-600',
                warning: 'bg-amber-500 text-white border border-amber-600',
                info: 'bg-blue-500 text-white border border-blue-600'
            };
            
            const icons = {
                success: 'bx-check-circle',
                error: 'bx-error',
                warning: 'bx-error-alt',
                info: 'bx-info-circle'
            };
            
            notification.className = `px-4 py-3 rounded-lg shadow-lg transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class='bx ${icons[type]} mr-2'></i>
                    <span class="text-sm font-medium">${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full', 'opacity-0');
                notification.classList.add('translate-x-0', 'opacity-100');
            }, 10);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-0', 'opacity-100');
                notification.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + R to refresh all data
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                refreshAllData();
            }
            
            // Escape to close modal
            if (e.key === 'Escape') {
                hideExportModal();
            }
        });

        // Performance monitoring
        let performanceMetrics = {
            loadTime: Date.now(),
            interactions: 0
        };

        // Track user interactions
        document.addEventListener('click', function() {
            performanceMetrics.interactions++;
        });

        // Export performance data (for debugging)
        window.getPerformanceMetrics = function() {
            return {
                ...performanceMetrics,
                currentTime: Date.now(),
                uptime: Date.now() - performanceMetrics.loadTime
            };
        };
    </script>
</body>
</html>