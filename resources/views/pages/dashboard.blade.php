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
        .from-primary { --tw-gradient-from: #8B4513; }
        .to-primary-600 { --tw-gradient-to: #654321; }

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
                <h1 class="text-xl font-display font-bold text-primary">Camellia Boutique99</h1>
                <p class="text-xs text-gray-600 mt-1">ERP Management System</p>
            </div>
            
            <!-- Navigation Menu -->
            <ul class="space-y-2 flex-1">
                <li>
                    <a href="{{ route('dashboard') }}" 
                       class="nav-item flex items-center px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-all duration-300 active"
                       data-route="dashboard">
                       <i class='bx bx-home text-lg mr-3'></i>
                       <span class="font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('products.index') }}" 
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
                            <button class="export-period-btn border border-gray-300 rounded-lg py-2 px-3 text-sm hover:bg-gray-50 transition-colors active bg-amber-100" data-period="7">
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
                            <p class="text-amber-100 text-lg mb-1">Sistem Manajemen</p>
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
                            <div class="text-2xl font-bold">{{ number_format($total_products) }}</div>
                            <div class="text-amber-200 text-xs">Total Produk</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ number_format($active_orders) }}</div>
                            <div class="text-amber-200 text-xs">Pesanan Aktif</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold">Rp {{ number_format($monthly_revenue, 0, ',', '.') }}</div>
                            <div class="text-amber-200 text-xs">Pendapatan Bulanan</div>
                        </div>
                        <div class="text-center">
                            <div class="text-lg font-bold">Rp {{ number_format($inventory_value, 0, ',', '.') }}</div>
                            <div class="text-amber-200 text-xs">Nilai Inventori</div>
                        </div>
                    </div>
                </div>

                <!-- Key Metrics Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Total Products -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1 cursor-pointer" onclick="refreshMetrics()">
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
                            <span class="text-xs text-gray-500">Terakhir diperbarui</span>
                            <span class="text-xs text-gray-400" id="productsUpdateTime">Baru saja</span>
                        </div>
                    </div>

                    <!-- Active Orders -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
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
                                <span class="text-emerald-600">+12%</span>
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
                                    <span id="revenueGrowth">18%</span> pertumbuhan
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <i class='bx bx-dollar-circle text-amber-500 text-xl'></i>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-100">
                            <div class="w-full bg-gray-200 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: 75%"></div>
                            </div>
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
                                <span>Rata-rata nilai: Rp {{ number_format($total_products > 0 ? $inventory_value / $total_products : 0, 0, ',', '.') }}</span>
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
                                @if(isset($recent_orders) && count($recent_orders) > 0)
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
                                            <span class="font-semibold text-gray-900 block text-sm">Rp {{ number_format($order['payment']['total_amount'] ?? 0) }}</span>
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
                                <a href="{{ route('products.index') }}" class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-200 group">
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
                                        <div class="bg-emerald-500 h-2 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Stok Menipis</span>
                                        <span>{{ number_format(max(0, $total_stock * 0.1)) }} item</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-amber-500 h-2 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                                        <span>Habis</span>
                                        <span>{{ number_format(max(0, $total_stock * 0.05)) }} item</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-500 h-2 rounded-full" style="width: 5%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Status -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Status Sistem</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">TikTok API</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class='bx bx-check-circle mr-1'></i>Terkoneksi
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Database</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                        <i class='bx bx-check-circle mr-1'></i>Online
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Sinkronisasi Terakhir</span>
                                    <span class="text-sm text-gray-900" id="lastSyncTime">Baru saja</span>
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

        // Data dari PHP/Laravel
        const dashboardData = {
            total_products: {{ $total_products }},
            active_products: {{ $active_products }},
            total_stock: {{ $total_stock }},
            inventory_value: {{ $inventory_value }},
            active_orders: {{ $active_orders }},
            pending_shipment: {{ $pending_shipment }},
            monthly_revenue: {{ $monthly_revenue }},
            total_revenue: {{ $total_revenue ?? 0 }},
            recent_orders: @json($recent_orders ?? [])
        };

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
                const currentHash = window.location.hash;
                
                console.log('Current Path:', currentPath);
                console.log('Current Hash:', currentHash);
                
                // Reset semua active state
                navItems.forEach(item => {
                    item.classList.remove('active');
                });
                
                // Logic untuk menentukan menu aktif
                if (currentPath.includes('/products') || currentHash === '#products') {
                    // Products page
                    document.querySelector('[data-route="products"]').classList.add('active');
                    console.log('Setting active: Products');
                } else if (currentPath.includes('/orders') || currentHash === '#orders') {
                    // Orders page  
                    document.querySelector('[data-route="orders"]').classList.add('active');
                    console.log('Setting active: Orders');
                } else if (currentPath.includes('/dashboard') || currentPath === '/' || currentHash === '#dashboard' || currentHash === '') {
                    // Dashboard page (default)
                    document.querySelector('[data-route="dashboard"]').classList.add('active');
                    console.log('Setting active: Dashboard');
                }
                
                // Fallback: Jika tidak ada yang match, set dashboard sebagai default
                const activeItems = document.querySelectorAll('.nav-item.active');
                if (activeItems.length === 0) {
                    document.querySelector('[data-route="dashboard"]').classList.add('active');
                    console.log('Fallback: Setting active: Dashboard');
                }
            }

            // Panggil function saat load
            setActiveNav();
            
            // Juga panggil saat URL berubah (untuk single page application behavior)
            window.addEventListener('popstate', setActiveNav);
            
            // Untuk handle hash changes
            window.addEventListener('hashchange', setActiveNav);
        }

        function initializeDashboard() {
            updateDateTime();
            setInterval(updateDateTime, 1000);
            // Update UI dengan data dari controller
            updateDashboardUI();
        }

        function updateDashboardUI() {
            // Data sudah ditampilkan langsung dari PHP Blade
            // Fungsi ini untuk update real-time jika diperlukan
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
            document.getElementById('refreshOrders').addEventListener('click', refreshOrders);
            
            // Platform period filter
            document.getElementById('platformPeriod').addEventListener('change', updatePlatformStats);
            
            // Auto-refresh toggle
            document.addEventListener('visibilitychange', handleVisibilityChange);
        }

        function setupExportEventListeners() {
            // Modal events
            document.getElementById('closeModal').addEventListener('click', hideExportModal);
            document.getElementById('exportModal').addEventListener('click', function(e) {
                if (e.target === this) hideExportModal();
            });

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
            document.getElementById('exportPdf').addEventListener('click', exportToPdf);
            document.getElementById('exportExcel').addEventListener('click', exportToExcel);
        }

        function handleVisibilityChange() {
            if (document.hidden) {
                clearInterval(refreshInterval);
            } else {
                startAutoRefresh();
            }
        }

        function startAutoRefresh() {
            refreshInterval = setInterval(() => {
                refreshMetrics();
            }, 30000); // 30 seconds
        }

        // Main refresh function
        function refreshAllData() {
            showNotification('Menyegarkan semua data...', 'info');
            refreshMetrics();
            refreshOrders();
            updatePlatformStats();
        }

        function refreshMetrics() {
            const metrics = document.querySelectorAll('.bg-white.rounded-xl');
            metrics.forEach(metric => {
                metric.classList.add('pulse');
            });
            
            // Simulate API call
            setTimeout(() => {
                metrics.forEach(metric => {
                    metric.classList.remove('pulse');
                });
                
                // Update timestamps
                const now = new Date();
                document.getElementById('productsUpdateTime').textContent = 'Baru saja';
                document.getElementById('lastSyncTime').textContent = now.toLocaleTimeString('id-ID');
                
                showNotification('Metrik berhasil diperbarui', 'success');
            }, 1000);
        }

        function refreshOrders() {
            const container = document.getElementById('ordersContainer');
            const loading = document.getElementById('ordersLoading');
            const noOrders = document.getElementById('noOrders');
            
            // Show loading
            container.classList.add('hidden');
            loading.classList.remove('hidden');
            if (noOrders) noOrders.classList.add('hidden');
            
            // Simulate API call
            setTimeout(() => {
                loading.classList.add('hidden');
                container.classList.remove('hidden');
                
                // Add fade-in animation to orders
                const orderItems = document.querySelectorAll('.order-item');
                orderItems.forEach((item, index) => {
                    item.style.animationDelay = `${index * 0.1}s`;
                    item.classList.add('fade-in');
                });
                
                showNotification('Pesanan berhasil disegarkan', 'success');
            }, 1500);
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
            prepareExportData(); // Refresh data sebelum ekspor
        }

        function hideExportModal() {
            document.getElementById('exportModal').classList.remove('active');
        }

        function prepareExportData() {
            // Mengumpulkan data real-time dari dashboard controller
            const currentDate = new Date();
            const periodDays = parseInt(selectedPeriod);
            const startDate = new Date();
            startDate.setDate(currentDate.getDate() - periodDays);

            exportData = {
                company: {
                    name: "Camellia Boutique99",
                    address: "Jl. Contoh No. 123, Jakarta",
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
                    totalStock: dashboardData.total_stock.toLocaleString()
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
                    { category: 'Tersedia', items: Math.round(dashboardData.total_stock * 0.85).toLocaleString(), percentage: 85 },
                    { category: 'Stok Menipis', items: Math.round(dashboardData.total_stock * 0.1).toLocaleString(), percentage: 10 },
                    { category: 'Habis', items: Math.round(dashboardData.total_stock * 0.05).toLocaleString(), percentage: 5 }
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
                    doc.setTextColor(217, 119, 6); // Amber color
                    doc.text(exportData.report.title, 20, 65);
                    
                    doc.setFontSize(10);
                    doc.setTextColor(100, 100, 100);
                    doc.text(`Periode: ${exportData.report.period}`, 20, 75);
                    doc.text(`Dibuat: ${exportData.report.generated}`, 20, 82);
                    
                    // Garis pemisah
                    doc.setDrawColor(217, 119, 6);
                    doc.setLineWidth(0.5);
                    doc.line(20, 85, 190, 85);
                    
                    let yPosition = 100;
                    
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
                        ['Nilai Inventori', exportData.metrics.inventoryValue]
                    ];
                    
                    doc.autoTable({
                        startY: yPosition,
                        head: [['Metrik', 'Nilai']],
                        body: metrics,
                        theme: 'grid',
                        headStyles: { fillColor: [217, 119, 6] },
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
                        headStyles: { fillColor: [217, 119, 6] },
                        styles: { fontSize: 10 },
                        margin: { left: 20, right: 20 }
                    });
                    
                    yPosition = doc.lastAutoTable.finalY + 15;
                    
                    // Pesanan Terbaru
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
                        headStyles: { fillColor: [217, 119, 6] },
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
                        [''],
                        ['METRIK UTAMA'],
                        ['Total Produk', exportData.metrics.totalProducts],
                        ['Produk Aktif', exportData.metrics.activeProducts],
                        ['Pesanan Aktif', exportData.metrics.activeOrders],
                        ['Pengiriman Tertunda', exportData.metrics.pendingShipment],
                        ['Pendapatan Bulanan', exportData.metrics.monthlyRevenue],
                        ['Nilai Inventori', exportData.metrics.inventoryValue],
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
            const notification = document.createElement('div');
            
            const typeStyles = {
                success: 'bg-emerald-500 text-white',
                error: 'bg-red-500 text-white',
                warning: 'bg-amber-500 text-white',
                info: 'bg-blue-500 text-white'
            };
            
            notification.className = `px-4 py-3 rounded-lg shadow-lg transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class='bx ${getNotificationIcon(type)} mr-2'></i>
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

        function getNotificationIcon(type) {
            const icons = {
                success: 'bx-check-circle',
                error: 'bx-error',
                warning: 'bx-error-alt',
                info: 'bx-info-circle'
            };
            return icons[type] || 'bx-info-circle';
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + R to refresh all data
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                refreshAllData();
            }
            
            // F5 to refresh
            if (e.key === 'F5') {
                e.preventDefault();
                refreshAllData();
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