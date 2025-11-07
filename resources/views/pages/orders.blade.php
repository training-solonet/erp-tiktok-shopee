@extends('layouts.app')

@section('title', 'Manajemen Pesanan - Camellia Boutique99')
@section('subtitle', 'Kelola dan lacak pesanan pelanggan Anda')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-amber-50/20 to-white/80">
    <!-- Modern Professional Header -->
    <div class="bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 px-6 py-6 lg:py-7 text-white relative overflow-hidden shadow-lg" style="min-height: 160px;">
        <!-- Sophisticated Background Pattern -->
        <div class="absolute inset-0 opacity-[0.03]">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -translate-y-32 translate-x-32"></div>
            <div class="absolute bottom-0 left-0 w-80 h-80 bg-amber-200 rounded-full translate-y-40 -translate-x-40"></div>
            <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-amber-100 rounded-full -translate-x-1/2 -translate-y-1/2 opacity-40"></div>
        </div>
        
        <div class="max-w-7xl mx-auto relative z-10 h-full">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between h-full">
                <div class="mb-4 lg:mb-0 flex-1">
                    <div class="flex items-center space-x-3 mb-3">
                        <div class="w-1.5 h-7 bg-white rounded-full shadow-md"></div>
                        <div>
                            <h1 class="text-xl lg:text-2xl font-bold bg-gradient-to-r from-white to-amber-100 bg-clip-text text-transparent">
                                Manajemen Pesanan
                            </h1>
                            <p class="text-amber-100 text-sm lg:text-sm mt-1 font-light">Kelola dan lacak semua pesanan dari TikTok Shop</p>
                        </div>
                    </div>
                    
                    <!-- Enhanced Date Display -->
                    <div class="flex flex-wrap items-center gap-2 mt-3">
                        <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/15 hover:bg-white/15 transition-all duration-200">
                            <i class='bx bx-calendar-star text-sm'></i>
                            <div>
                                <div class="text-xs font-medium" id="currentDay">Senin, 3 November 2025</div>
                                <div class="text-xs text-amber-100" id="currentTime">12:52:45 WIB</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-2 border border-white/15 hover:bg-white/15 transition-all duration-200">
                            <i class='bx bx-trending-up text-sm'></i>
                            <div>
                                <div class="text-xs font-medium">Pembaruan Langsung</div>
                                <div class="text-xs text-amber-100">Pemantauan Real-time</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Status -->
                <div class="flex flex-col space-y-2 mt-4 lg:mt-0">
                    <div class="flex items-center space-x-2 bg-white/10 backdrop-blur-sm rounded-lg px-3 py-1.5 border border-white/15">
                        <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                        <span class="text-xs font-medium">Semua Sistem Beroperasi</span>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-amber-200">Sinkronisasi Terakhir</div>
                        <div class="text-sm font-medium" id="lastSyncHeader">{{ now()->format('d M Y, H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-5">
        <!-- Error Alert -->
        @if(isset($error) && $error)
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class='bx bx-error-circle text-red-400 text-xl'></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Gagal memuat pesanan</h3>
                    <div class="mt-1 text-sm text-red-700">
                        <p>{{ $error }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Refined Metrics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            @php
                $metrics = [
                    [
                        'title' => 'Total Pesanan',
                        'value' => $total_orders ?? 0,
                        'subvalue' => 'semua waktu',
                        'icon' => 'bx-shopping-bag',
                        'color' => 'blue',
                        'gradient' => 'from-blue-500 to-blue-600',
                    ],
                    [
                        'title' => 'Pending',
                        'value' => $pending_orders ?? 0,
                        'subvalue' => 'menunggu proses',
                        'icon' => 'bx-time',
                        'color' => 'amber',
                        'gradient' => 'from-amber-500 to-amber-600',
                    ],
                    [
                        'title' => 'Selesai',
                        'value' => $completed_orders ?? 0,
                        'subvalue' => 'pesanan selesai',
                        'icon' => 'bx-check-circle',
                        'color' => 'green',
                        'gradient' => 'from-green-500 to-green-600',
                    ],
                    [
                        'title' => 'Pendapatan',
                        'value' => 'Rp ' . number_format($total_revenue ?? 0, 0, ',', '.'),
                        'subvalue' => 'dari pesanan selesai',
                        'icon' => 'bx-dollar-circle',
                        'color' => 'purple',
                        'gradient' => 'from-purple-500 to-purple-600',
                    ]
                ];
            @endphp

            @foreach($metrics as $metric)
            <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100/80 group cursor-pointer transform hover:scale-[1.02] metric-card">
                <div class="p-4 lg:p-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">{{ $metric['title'] }}</p>
                            <p class="text-lg lg:text-xl font-bold text-gray-900 mb-1">{{ $metric['value'] }}</p>
                            <p class="text-xs text-{{ $metric['color'] }}-600 flex items-center font-medium">
                                <i class='bx bx-check-circle mr-1.5 text-xs'></i>
                                {{ $metric['subvalue'] }}
                            </p>
                        </div>
                        <div class="w-10 h-10 bg-gradient-to-br {{ $metric['gradient'] }} rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300 ml-2 shadow-sm">
                            <i class='bx {{ $metric['icon'] }} text-white text-sm'></i>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Advanced Filters & Actions -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg'></i>
                        <input type="text" id="searchOrders" placeholder="Cari pesanan, produk, atau pelanggan..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm text-sm">
                    </div>
                    
                    <!-- Status Filter -->
                    <select id="statusFilter" class="border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm text-sm min-w-[160px]">
                        <option value="">Semua Status</option>
                        <option value="completed">Selesai</option>
                        <option value="cancelled">Dibatalkan</option>
                        <option value="unpaid">Belum Dibayar</option>
                        <option value="awaiting_shipment">Menunggu Pengiriman</option>
                    </select>

                    <!-- Date Filter -->
                    <select id="dateFilter" class="border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm text-sm min-w-[140px]">
                        <option value="">Semua Waktu</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="custom">Kustom</option>
                    </select>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Export Button -->
                    <button onclick="showExportModal()" class="flex items-center space-x-2 px-4 py-2.5 border border-gray-300 rounded-xl hover:border-amber-400 hover:bg-amber-50 transition-all duration-300 text-sm font-medium text-gray-700">
                        <i class='bx bx-download text-lg'></i>
                        <span>Ekspor</span>
                    </button>

                    <!-- Sync Button -->
                    <button id="syncOrdersBtn" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md">
                        <i class='bx bx-refresh text-lg'></i>
                        <span>Segarkan</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Orders Table -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300 mb-8">
            <!-- Table Header -->
            <div class="px-4 lg:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-900">Daftar Pesanan</h3>
                        <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-xs font-medium" id="ordersCount">
                            {{ is_countable($orders ?? []) ? count($orders ?? []) : 0 }} pesanan
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class='bx bx-info-circle text-sm'></i>
                        <span>Diperbarui: <span id="lastUpdateTime">{{ now()->format('H:i') }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="divide-y divide-gray-200/60" id="ordersList">
                @if(isset($orders) && is_countable($orders) && count($orders) > 0)
                    @foreach($orders as $order)
                    @php
                        // Process order data according to TikTok API structure
                        $orderId = $order['id'] ?? 'N/A';
                        $shortOrderId = 'ORD-' . substr($orderId, -6);
                        $customerName = $order['recipient_address']['name'] ?? 'Customer';
                        $customerInitials = strtoupper(substr($customerName, 0, 1));
                        
                        // PERBAIKAN: Ambil total amount dari payment->total_amount
                        $totalAmount = isset($order['payment']['total_amount']) ? (int)$order['payment']['total_amount'] : 0;
                        $formattedAmount = 'Rp ' . number_format($totalAmount, 0, ',', '.');
                        
                        // Status handling
                        $status = strtolower($order['status'] ?? 'unknown');
                        $displayStatus = $order['display_status'] ?? $status;

                        // Status mapping untuk TikTok Shop
                        $statusConfig = [
                            'completed' => ['color' => 'green', 'icon' => 'bx-check-circle', 'text' => 'Selesai', 'bg' => 'bg-green-50', 'textColor' => 'text-green-700', 'border' => 'border-green-200', 'gradient' => 'from-green-500 to-green-600'],
                            'delivered' => ['color' => 'green', 'icon' => 'bx-check-circle', 'text' => 'Terkirim', 'bg' => 'bg-green-50', 'textColor' => 'text-green-700', 'border' => 'border-green-200', 'gradient' => 'from-green-500 to-green-600'],
                            'shipped' => ['color' => 'blue', 'icon' => 'bx-package', 'text' => 'Dikirim', 'bg' => 'bg-blue-50', 'textColor' => 'text-blue-700', 'border' => 'border-blue-200', 'gradient' => 'from-blue-500 to-blue-600'],
                            'processed' => ['color' => 'blue', 'icon' => 'bx-cog', 'text' => 'Diproses', 'bg' => 'bg-blue-50', 'textColor' => 'text-blue-700', 'border' => 'border-blue-200', 'gradient' => 'from-blue-500 to-blue-600'],
                            'awaiting_shipment' => ['color' => 'amber', 'icon' => 'bx-time', 'text' => 'Menunggu Pengiriman', 'bg' => 'bg-amber-50', 'textColor' => 'text-amber-700', 'border' => 'border-amber-200', 'gradient' => 'from-amber-500 to-amber-600'],
                            'unpaid' => ['color' => 'gray', 'icon' => 'bx-time-five', 'text' => 'Belum Dibayar', 'bg' => 'bg-gray-50', 'textColor' => 'text-gray-700', 'border' => 'border-gray-200', 'gradient' => 'from-gray-500 to-gray-600'],
                            'cancelled' => ['color' => 'red', 'icon' => 'bx-x-circle', 'text' => 'Dibatalkan', 'bg' => 'bg-red-50', 'textColor' => 'text-red-700', 'border' => 'border-red-200', 'gradient' => 'from-red-500 to-red-600'],
                            'unknown' => ['color' => 'gray', 'icon' => 'bx-question-mark', 'text' => 'Tidak Diketahui', 'bg' => 'bg-gray-50', 'textColor' => 'text-gray-700', 'border' => 'border-gray-200', 'gradient' => 'from-gray-500 to-gray-600']
                        ];
                        
                        $statusInfo = $statusConfig[$status] ?? $statusConfig['unknown'];

                        // Product information
                        $productName = 'Tidak ada produk';
                        $itemCount = 0;
                        $productImage = null;
                        
                        if (isset($order['line_items']) && is_array($order['line_items']) && count($order['line_items']) > 0) {
                            $firstItem = $order['line_items'][0];
                            $productName = $firstItem['product_name'] ?? 'Produk';
                            $itemCount = count($order['line_items']);
                            $productImage = $firstItem['sku_image'] ?? null;
                        }
                        
                        $truncatedProduct = strlen($productName) > 50 ? substr($productName, 0, 50) . '...' : $productName;
                        
                        // Date formatting
                        $orderTime = $order['create_time'] ?? time();
                        try {
                            $formattedTime = \Carbon\Carbon::createFromTimestamp($orderTime)->format('M d, Y H:i');
                            $timeAgo = \Carbon\Carbon::createFromTimestamp($orderTime)->diffForHumans();
                        } catch (Exception $e) {
                            $formattedTime = 'Tanggal tidak diketahui';
                            $timeAgo = '';
                        }

                        // Shipping info
                        $shippingProvider = $order['shipping_provider'] ?? 'TikTok Shop';
                        $trackingNumber = $order['tracking_number'] ?? null;
                    @endphp
                    
                    <div class="p-5 lg:p-6 hover:bg-gradient-to-r hover:from-amber-50/30 hover:to-white/50 transition-all duration-300 order-item group" 
                         data-status="{{ $status }}" 
                         data-search="{{ $shortOrderId }} {{ $customerName }} {{ $productName }} {{ $shippingProvider }}">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <!-- Order Info Left -->
                            <div class="flex items-start space-x-4 flex-1 min-w-0">
                                @if($productImage)
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gray-100 rounded-xl border border-gray-200 overflow-hidden shadow-xs">
                                        <img src="{{ $productImage }}" alt="{{ $productName }}" 
                                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                    </div>
                                </div>
                                @else
                                <div class="flex-shrink-0">
                                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl border border-amber-200 flex items-center justify-center shadow-xs group-hover:shadow-sm transition-all">
                                        <i class='bx bx-package text-white text-lg'></i>
                                    </div>
                                </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex items-center space-x-3">
                                            <h4 class="font-semibold text-gray-900 text-base">{{ $shortOrderId }}</h4>
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusInfo['bg'] }} {{ $statusInfo['textColor'] }} {{ $statusInfo['border'] }} border backdrop-blur-sm">
                                                <i class='bx {{ $statusInfo['icon'] }} mr-1.5'></i>
                                                {{ $statusInfo['text'] }}
                                            </span>
                                        </div>
                                        <div class="hidden lg:flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                            <button class="w-8 h-8 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                                <i class='bx bx-show text-sm'></i>
                                            </button>
                                            <button class="w-8 h-8 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                                <i class='bx bx-dots-vertical-rounded text-sm'></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-700 mb-2 text-sm flex items-center">
                                        <i class='bx bx-user mr-2 text-gray-400'></i>
                                        {{ $customerName }}
                                    </p>
                                    
                                    <p class="text-gray-600 mb-3 text-sm line-clamp-2">{{ $truncatedProduct }}</p>
                                    
                                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                        <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                            <i class='bx bx-package mr-1.5 text-xs'></i>
                                            {{ $itemCount }} item{{ $itemCount > 1 ? '' : '' }}
                                        </span>
                                        <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                            <i class='bx bx-store mr-1.5 text-xs'></i>
                                            TikTok Shop
                                        </span>
                                        <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg" title="{{ $formattedTime }}">
                                            <i class='bx bx-time mr-1.5 text-xs'></i>
                                            {{ $timeAgo }}
                                        </span>
                                        @if($shippingProvider)
                                        <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                            <i class='bx bx-truck mr-1.5 text-xs'></i>
                                            {{ $shippingProvider }}
                                        </span>
                                        @endif
                                        @if($trackingNumber)
                                        <span class="flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg border border-blue-200">
                                            <i class='bx bx-map mr-1.5 text-xs'></i>
                                            {{ substr($trackingNumber, 0, 8) }}...
                                        </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Info Right -->
                            <div class="flex items-center justify-between lg:justify-end lg:items-start lg:flex-col lg:space-y-3 lg:text-right">
                                <div>
                                    <span class="font-bold text-gray-900 text-xl block">{{ $formattedAmount }}</span>
                                    <p class="text-sm text-gray-500 mt-1">Total amount</p>
                                </div>
                                
                                <!-- Mobile Actions -->
                                <div class="flex lg:hidden items-center space-x-2">
                                    <button class="w-9 h-9 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                        <i class='bx bx-show text-sm'></i>
                                    </button>
                                    <button class="w-9 h-9 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                        <i class='bx bx-dots-vertical-rounded text-sm'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                            <i class='bx bx-receipt text-3xl text-gray-400'></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pesanan Ditemukan</h3>
                        <p class="text-gray-600 mb-6">Tidak ada pesanan untuk ditampilkan saat ini.</p>
                        <button id="syncEmptyBtn" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-300 inline-flex items-center shadow-sm hover:shadow-md">
                            <i class='bx bx-refresh mr-2'></i>
                            Segarkan Pesanan
                        </button>
                    </div>
                @endif
            </div>

            <!-- Pagination & Summary -->
            @if(isset($orders) && is_countable($orders) && count($orders) > 0)
            <div class="px-4 lg:px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50/50 to-white/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-700 mb-4 sm:mb-0">
                        Menampilkan <span class="font-medium">1-{{ count($orders ?? []) }}</span> dari <span class="font-medium">{{ $total ?? count($orders ?? []) }}</span> pesanan
                    </p>
                    <div class="flex items-center space-x-3">
                        <button class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class='bx bx-chevron-left mr-2'></i>
                            Sebelumnya
                        </button>
                        <div class="flex items-center space-x-1">
                            <button class="w-9 h-9 bg-amber-500 text-white rounded-lg text-sm font-medium">1</button>
                            <button class="w-9 h-9 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">2</button>
                            <button class="w-9 h-9 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">3</button>
                        </div>
                        <button class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center">
                            Selanjutnya
                            <i class='bx bx-chevron-right ml-2'></i>
                        </button>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modern Mobile Bottom Navigation -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-gray-200/60 p-3 z-40 shadow-lg">
    <div class="grid grid-cols-4 gap-1">
        <a href="{{ route('dashboard.index') }}" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-home text-lg mb-0.5'></i>
            <span class="text-xs">Dasbor</span>
        </a>
        <a href="{{ route('products_menu') }}" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-package text-lg mb-0.5'></i>
            <span class="text-xs">Produk</span>
        </a>
        <a href="{{ route('orders_menu') }}" class="flex flex-col items-center justify-center p-2 text-amber-600 rounded-lg bg-amber-50/80 backdrop-blur-sm">
            <i class='bx bx-cart text-lg mb-0.5'></i>
            <span class="text-xs font-medium">Pesanan</span>
        </a>
        <button onclick="refreshAllData()" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-refresh text-lg mb-0.5'></i>
            <span class="text-xs">Segarkan</span>
        </button>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl mx-4 max-w-md w-full transform transition-all duration-300 scale-95 opacity-0"
         id="exportModalContent">
        <div class="px-5 py-4 border-b border-gray-200/50 bg-gradient-to-r from-amber-600 to-amber-500 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold">Ekspor Data Pesanan</h3>
                <button onclick="hideExportModal()" class="text-amber-100 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10">
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        </div>
        
        <div class="p-5">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Format Ekspor</label>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="exportToExcel()" class="flex flex-col items-center justify-center space-y-2 bg-green-50 border border-green-200 text-green-700 rounded-xl py-4 hover:bg-green-100 hover:border-green-300 transition-all duration-300 hover:scale-105 group">
                            <i class='bx bxs-file-excel text-2xl group-hover:scale-110 transition-transform'></i>
                            <span class="font-semibold text-sm">Excel</span>
                        </button>
                        <button onclick="exportToPdf()" class="flex flex-col items-center justify-center space-y-2 bg-red-50 border border-red-200 text-red-700 rounded-xl py-4 hover:bg-red-100 hover:border-red-300 transition-all duration-300 hover:scale-105 group">
                            <i class='bx bxs-file-pdf text-2xl group-hover:scale-110 transition-transform'></i>
                            <span class="font-semibold text-sm">PDF</span>
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rentang Waktu</label>
                    <select id="exportPeriod" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition text-sm">
                        <option value="all">Semua Pesanan</option>
                        <option value="today">Hari Ini</option>
                        <option value="week">Minggu Ini</option>
                        <option value="month">Bulan Ini</option>
                        <option value="custom">Kustom</option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="px-5 py-4 border-t border-gray-200/50 bg-gray-50/50 rounded-b-2xl">
            <div class="flex space-x-3">
                <button onclick="hideExportModal()" class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </button>
                <button onclick="processExport()" class="flex-1 px-4 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-medium hover:bg-amber-600 transition-colors duration-200 shadow-sm hover:shadow-md">
                    Ekspor Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2 w-full max-w-xs"></div>

<!-- Tambahkan CDN untuk library export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<style>
    /* Modern animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .content-container > * {
        animation: fadeInUp 0.4s ease-out;
    }

    /* Line clamp utility */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 6px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #f59e0b, #d97706);
        border-radius: 6px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #d97706, #b45309);
    }

    /* Enhanced hover effects */
    .metric-card {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .metric-card:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 25px -8px rgba(0, 0, 0, 0.15);
    }

    .order-item {
        transition: all 0.2s ease-in-out;
    }

    .order-item:hover {
        transform: translateX(4px);
    }

    /* Glass morphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
</style>

<script>
    // Global state
    let refreshInterval;
    let currentOrders = @json($orders ?? []);

    document.addEventListener('DOMContentLoaded', function() {
        initializeOrdersPage();
        setupEventListeners();
        startAutoRefresh();
    });

    function initializeOrdersPage() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
    }

    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        document.getElementById('currentDay').textContent = now.toLocaleDateString('id-ID', options);
        document.getElementById('currentTime').textContent = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false 
        });
    }

    function setupEventListeners() {
        const syncOrdersBtn = document.getElementById('syncOrdersBtn');
        const syncEmptyBtn = document.getElementById('syncEmptyBtn');
        const searchInput = document.getElementById('searchOrders');
        const statusFilter = document.getElementById('statusFilter');
        const dateFilter = document.getElementById('dateFilter');

        if (syncOrdersBtn) {
            syncOrdersBtn.addEventListener('click', function() {
                syncOrders(this);
            });
        }

        if (syncEmptyBtn) {
            syncEmptyBtn.addEventListener('click', function() {
                syncOrders(this);
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', filterOrders);
        }

        if (statusFilter) {
            statusFilter.addEventListener('change', filterOrders);
        }

        if (dateFilter) {
            dateFilter.addEventListener('change', filterOrders);
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
                e.preventDefault();
                syncOrders();
            }
            if (e.key === 'Escape') {
                hideExportModal();
            }
        });
    }

    function startAutoRefresh() {
        // Refresh every 2 minutes
        refreshInterval = setInterval(() => {
            syncOrders();
        }, 120000);
    }

    function syncOrders(button = null) {
        const btn = button || document.getElementById('syncOrdersBtn');
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Menyegarkan...';
        btn.disabled = true;

        fetch('{{ route("orders.sync") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Pesanan berhasil disegarkan!', 'success');
                updateOrdersUI(data.data);
                currentOrders = data.data.orders || [];
            } else {
                throw new Error(data.message || 'Gagal menyegarkan pesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Gagal menyegarkan pesanan: ' + error.message, 'error');
        })
        .finally(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    }

    function updateOrdersUI(data) {
        // Update metrics
        if (data.metrics) {
            // Update metric cards
            const metrics = document.querySelectorAll('.metric-card');
            if (metrics[0]) metrics[0].querySelector('.text-lg').textContent = data.metrics.total_orders;
            if (metrics[1]) metrics[1].querySelector('.text-lg').textContent = data.metrics.pending_orders;
            if (metrics[2]) metrics[2].querySelector('.text-lg').textContent = data.metrics.completed_orders;
            if (metrics[3]) metrics[3].querySelector('.text-lg').textContent = 'Rp ' + formatNumber(data.metrics.total_revenue);
        }

        // Update orders count
        document.getElementById('ordersCount').textContent = (data.orders?.length || 0) + ' pesanan';
        document.getElementById('lastUpdateTime').textContent = new Date().toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit' 
        });

        // Update orders list
        if (data.orders && data.orders.length > 0) {
            renderOrdersList(data.orders);
        } else {
            document.getElementById('ordersList').innerHTML = `
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <i class='bx bx-receipt text-3xl text-gray-400'></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Ada Pesanan Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Tidak ada pesanan untuk ditampilkan saat ini.</p>
                    <button id="syncEmptyBtn" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors duration-300 inline-flex items-center shadow-sm hover:shadow-md">
                        <i class='bx bx-refresh mr-2'></i>
                        Segarkan Pesanan
                    </button>
                </div>
            `;
            // Re-attach event listener to new button
            document.getElementById('syncEmptyBtn').addEventListener('click', function() {
                syncOrders(this);
            });
        }
    }

    function renderOrdersList(orders) {
        let ordersHTML = '';
        orders.forEach(order => {
            const orderId = order.id || 'N/A';
            const shortOrderId = 'ORD-' + orderId.slice(-6);
            const customerName = order.recipient_address?.name || 'Customer';
            const customerInitials = customerName.substring(0, 1).toUpperCase();
            
            // PERBAIKAN: Ambil total amount dari payment->total_amount
            const totalAmount = order.payment?.total_amount ? parseInt(order.payment.total_amount) : 0;
            const formattedAmount = 'Rp ' + formatNumber(totalAmount);
            
            const status = order.status?.toLowerCase() || 'unknown';
            
            const statusConfig = {
                'completed': ['bg-green-50 text-green-700 border-green-200', 'bx-check-circle', 'Selesai'],
                'delivered': ['bg-green-50 text-green-700 border-green-200', 'bx-check-circle', 'Terkirim'],
                'shipped': ['bg-blue-50 text-blue-700 border-blue-200', 'bx-package', 'Dikirim'],
                'processed': ['bg-blue-50 text-blue-700 border-blue-200', 'bx-cog', 'Diproses'],
                'awaiting_shipment': ['bg-amber-50 text-amber-700 border-amber-200', 'bx-time', 'Menunggu Pengiriman'],
                'unpaid': ['bg-gray-50 text-gray-700 border-gray-200', 'bx-time-five', 'Belum Dibayar'],
                'cancelled': ['bg-red-50 text-red-700 border-red-200', 'bx-x-circle', 'Dibatalkan']
            };

            const [statusClass, statusIcon, statusText] = statusConfig[status] || 
                ['bg-gray-50 text-gray-700 border-gray-200', 'bx-question-mark', 'Tidak Diketahui'];

            // Product information
            let productName = 'Tidak ada produk';
            let itemCount = 0;
            let productImage = null;
            
            if (order.line_items && Array.isArray(order.line_items) && order.line_items.length > 0) {
                productName = order.line_items[0].product_name || 'Produk';
                itemCount = order.line_items.length;
                productImage = order.line_items[0].sku_image || null;
            }
            
            const truncatedProduct = productName.length > 50 ? productName.substring(0, 50) + '...' : productName;
            
            // Date formatting
            const orderTime = order.create_time ? new Date(order.create_time * 1000) : new Date();
            const formattedTime = order.create_time ? 
                orderTime.toLocaleDateString('id-ID', { 
                    month: 'short', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                }) : 'Tanggal tidak diketahui';
            
            const timeAgo = order.create_time ? getTimeAgo(orderTime) : '';

            const shippingProvider = order.shipping_provider || 'TikTok Shop';
            const trackingNumber = order.tracking_number || null;

            ordersHTML += `
                <div class="p-5 lg:p-6 hover:bg-gradient-to-r hover:from-amber-50/30 hover:to-white/50 transition-all duration-300 order-item group" 
                     data-status="${status}" 
                     data-search="${shortOrderId} ${customerName} ${productName} ${shippingProvider}"
                     data-date="${order.create_time}">
                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                        <div class="flex items-start space-x-4 flex-1 min-w-0">
                            ${productImage ? `
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gray-100 rounded-xl border border-gray-200 overflow-hidden shadow-xs">
                                    <img src="${productImage}" alt="${productName}" 
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                </div>
                            </div>
                            ` : `
                            <div class="flex-shrink-0">
                                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl border border-amber-200 flex items-center justify-center shadow-xs group-hover:shadow-sm transition-all">
                                    <i class='bx bx-package text-white text-lg'></i>
                                </div>
                            </div>
                            `}
                            
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center space-x-3">
                                        <h4 class="font-semibold text-gray-900 text-base">${shortOrderId}</h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${statusClass} border backdrop-blur-sm">
                                            <i class='bx ${statusIcon} mr-1.5'></i>
                                            ${statusText}
                                        </span>
                                    </div>
                                    <div class="hidden lg:flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button class="w-8 h-8 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                            <i class='bx bx-show text-sm'></i>
                                        </button>
                                        <button class="w-8 h-8 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                            <i class='bx bx-dots-vertical-rounded text-sm'></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <p class="text-gray-700 mb-2 text-sm flex items-center">
                                    <i class='bx bx-user mr-2 text-gray-400'></i>
                                    ${customerName}
                                </p>
                                
                                <p class="text-gray-600 mb-3 text-sm line-clamp-2">${truncatedProduct}</p>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                        <i class='bx bx-package mr-1.5 text-xs'></i>
                                        ${itemCount} item${itemCount > 1 ? '' : ''}
                                    </span>
                                    <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                        <i class='bx bx-store mr-1.5 text-xs'></i>
                                        TikTok Shop
                                    </span>
                                    <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg" title="${formattedTime}">
                                        <i class='bx bx-time mr-1.5 text-xs'></i>
                                        ${timeAgo}
                                    </span>
                                    ${shippingProvider ? `
                                    <span class="flex items-center bg-gray-50 px-2.5 py-1 rounded-lg">
                                        <i class='bx bx-truck mr-1.5 text-xs'></i>
                                        ${shippingProvider}
                                    </span>
                                    ` : ''}
                                    ${trackingNumber ? `
                                    <span class="flex items-center bg-blue-50 text-blue-700 px-2.5 py-1 rounded-lg border border-blue-200">
                                        <i class='bx bx-map mr-1.5 text-xs'></i>
                                        ${trackingNumber.substring(0, 8)}...
                                    </span>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between lg:justify-end lg:items-start lg:flex-col lg:space-y-3 lg:text-right">
                            <div>
                                <span class="font-bold text-gray-900 text-xl block">${formattedAmount}</span>
                                <p class="text-sm text-gray-500 mt-1">Total amount</p>
                            </div>
                            
                            <div class="flex lg:hidden items-center space-x-2">
                                <button class="w-9 h-9 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                    <i class='bx bx-show text-sm'></i>
                                </button>
                                <button class="w-9 h-9 border border-gray-300 hover:border-amber-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-amber-50 text-gray-600 hover:text-amber-600">
                                    <i class='bx bx-dots-vertical-rounded text-sm'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        document.getElementById('ordersList').innerHTML = ordersHTML;
    }

    function filterOrders() {
        const searchTerm = document.getElementById('searchOrders').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        const orderItems = document.querySelectorAll('.order-item');
        
        orderItems.forEach(item => {
            const searchText = item.getAttribute('data-search').toLowerCase();
            const status = item.getAttribute('data-status');
            const orderDate = parseInt(item.getAttribute('data-date'));
            
            const matchesSearch = searchText.includes(searchTerm);
            const matchesStatus = !statusFilter || status === statusFilter;
            const matchesDate = filterByDate(orderDate, dateFilter);
            
            if (matchesSearch && matchesStatus && matchesDate) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function filterByDate(orderTimestamp, filterType) {
        if (!filterType || filterType === '' || !orderTimestamp) return true;
        
        const orderDate = new Date(orderTimestamp * 1000);
        const today = new Date();
        
        switch (filterType) {
            case 'today':
                return orderDate.toDateString() === today.toDateString();
            case 'week':
                const weekAgo = new Date(today);
                weekAgo.setDate(today.getDate() - 7);
                return orderDate >= weekAgo;
            case 'month':
                const monthAgo = new Date(today);
                monthAgo.setMonth(today.getMonth() - 1);
                return orderDate >= monthAgo;
            default:
                return true;
        }
    }

    function getTimeAgo(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Baru saja';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' menit lalu';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' jam lalu';
        if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 86400) + ' hari lalu';
        return Math.floor(diffInSeconds / 2592000) + ' bulan lalu';
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Export Functions
    function showExportModal() {
        const modal = document.getElementById('exportModal');
        const modalContent = document.getElementById('exportModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function hideExportModal() {
        const modal = document.getElementById('exportModal');
        const modalContent = document.getElementById('exportModalContent');
        
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function processExport() {
        const format = document.querySelector('input[name="exportFormat"]:checked')?.value || 'excel';
        const period = document.getElementById('exportPeriod').value;
        
        if (format === 'excel') {
            exportToExcel(period);
        } else {
            exportToPdf(period);
        }
        
        hideExportModal();
    }

    function exportToExcel(period = 'all') {
        showNotification('Membuat laporan Excel...', 'info');
        
        try {
            const wb = XLSX.utils.book_new();
            const filteredOrders = filterOrdersByPeriod(currentOrders, period);
            
            // Prepare data
            const excelData = [
                ['LAPORAN PESANAN - CAMELIA BOUTIQUE99'],
                ['Periode', getPeriodText(period)],
                ['Dibuat', new Date().toLocaleString('id-ID')],
                [],
                ['ID Pesanan', 'Pelanggan', 'Produk', 'Jumlah Item', 'Status', 'Total Amount', 'Tanggal Pesanan', 'Provider Pengiriman']
            ];
            
            filteredOrders.forEach(order => {
                const productName = order.line_items?.[0]?.product_name || 'Tidak ada produk';
                const itemCount = order.line_items?.length || 0;
                const totalAmount = order.payment?.total_amount ? parseInt(order.payment.total_amount) : 0;
                const orderDate = order.create_time ? new Date(order.create_time * 1000).toLocaleDateString('id-ID') : 'Tanggal tidak diketahui';
                
                excelData.push([
                    order.id || 'N/A',
                    order.recipient_address?.name || 'Customer',
                    productName,
                    itemCount,
                    order.status || 'unknown',
                    'Rp ' + formatNumber(totalAmount),
                    orderDate,
                    order.shipping_provider || 'TikTok Shop'
                ]);
            });
            
            const ws = XLSX.utils.aoa_to_sheet(excelData);
            XLSX.utils.book_append_sheet(wb, ws, 'Pesanan');
            
            const fileName = `Laporan_Pesanan_Camellia_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);
            
            showNotification('Laporan Excel berhasil diunduh!', 'success');
        } catch (error) {
            console.error('Excel Export Error:', error);
            showNotification('Gagal membuat laporan Excel', 'error');
        }
    }

    function exportToPdf(period = 'all') {
        showNotification('Membuat laporan PDF...', 'info');
        
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            const filteredOrders = filterOrdersByPeriod(currentOrders, period);
            
            // Add header
            doc.setFillColor(245, 158, 11);
            doc.rect(0, 0, 210, 30, 'F');
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(16);
            doc.setFont('helvetica', 'bold');
            doc.text('LAPORAN PESANAN', 105, 15, { align: 'center' });
            doc.setFontSize(10);
            doc.text('CAMELIA BOUTIQUE99', 105, 22, { align: 'center' });
            
            // Add report info
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(10);
            let yPosition = 40;
            
            doc.text(['Periode:', getPeriodText(period)], 20, yPosition);
            yPosition += 6;
            doc.text(['Dibuat:', new Date().toLocaleString('id-ID')], 20, yPosition);
            yPosition += 10;
            
            // Add table
            const tableData = filteredOrders.map(order => {
                const productName = order.line_items?.[0]?.product_name || 'Tidak ada produk';
                const truncatedProduct = productName.length > 30 ? productName.substring(0, 30) + '...' : productName;
                const totalAmount = order.payment?.total_amount ? parseInt(order.payment.total_amount) : 0;
                const orderDate = order.create_time ? new Date(order.create_time * 1000).toLocaleDateString('id-ID') : 'Tanggal tidak diketahui';
                
                return [
                    order.id ? 'ORD-' + order.id.slice(-6) : 'N/A',
                    order.recipient_address?.name || 'Customer',
                    truncatedProduct,
                    (order.line_items?.length || 0).toString(),
                    order.status || 'unknown',
                    'Rp ' + formatNumber(totalAmount),
                    orderDate
                ];
            });
            
            doc.autoTable({
                startY: yPosition,
                head: [['ID', 'Pelanggan', 'Produk', 'Items', 'Status', 'Total', 'Tanggal']],
                body: tableData,
                styles: { fontSize: 8 },
                headStyles: { fillColor: [245, 158, 11] }
            });
            
            const fileName = `Laporan_Pesanan_Camellia_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(fileName);
            
            showNotification('Laporan PDF berhasil diunduh!', 'success');
        } catch (error) {
            console.error('PDF Export Error:', error);
            showNotification('Gagal membuat laporan PDF', 'error');
        }
    }

    function filterOrdersByPeriod(orders, period) {
        if (period === 'all') return orders;
        
        const now = new Date();
        return orders.filter(order => {
            if (!order.create_time) return false;
            
            const orderDate = new Date(order.create_time * 1000);
            
            switch (period) {
                case 'today':
                    return orderDate.toDateString() === now.toDateString();
                case 'week':
                    const weekAgo = new Date(now);
                    weekAgo.setDate(now.getDate() - 7);
                    return orderDate >= weekAgo;
                case 'month':
                    const monthAgo = new Date(now);
                    monthAgo.setMonth(now.getMonth() - 1);
                    return orderDate >= monthAgo;
                default:
                    return true;
            }
        });
    }

    function getPeriodText(period) {
        const texts = {
            'all': 'Semua Pesanan',
            'today': 'Hari Ini',
            'week': 'Minggu Ini',
            'month': 'Bulan Ini'
        };
        return texts[period] || 'Semua Pesanan';
    }

    function showNotification(message, type = 'info') {
        const container = document.getElementById('notificationContainer');
        const notification = document.createElement('div');
        
        const typeStyles = {
            success: 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md',
            error: 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md',
            info: 'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md',
            warning: 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md'
        };
        
        const icons = {
            success: 'bx-check-circle',
            error: 'bx-error',
            info: 'bx-info-circle',
            warning: 'bx-error-alt'
        };
        
        notification.className = `px-4 py-3 rounded-xl transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]} backdrop-blur-sm`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class='bx ${icons[type]} text-lg'></i>
                    <div>
                        <div class="text-sm font-medium">${message}</div>
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white transition-colors ml-4">
                    <i class='bx bx-x text-lg'></i>
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
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // Close modal when clicking outside
    document.getElementById('exportModal').addEventListener('click', function(e) {
        if (e.target === this) hideExportModal();
    });
</script>
@endsection