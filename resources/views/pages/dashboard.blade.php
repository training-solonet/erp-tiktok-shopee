@extends('layouts.app')

@section('title', 'Dashboard - Camellia Boutique99')
@section('subtitle', 'Kelola bisnis batik eksklusif Anda')

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
                                Dashboard Menu
                            </h1>
                            <p class="text-amber-100 text-sm lg:text-sm mt-1 font-light">Ringkasan Product & Analisis Bisnis</p>
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
                        <span class="text-xs font-medium" id="dataSourceIndicator">
                            @if($data_source === 'tiktok_api')
                                Data Real-time TikTok
                            @else
                                Data Fallback
                            @endif
                        </span>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-amber-200">Sinkronisasi Terakhir</div>
                        <div class="text-sm font-medium" id="lastSyncHeader">{{ $last_updated }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4 lg:mt-5">
        <!-- Refined Metrics Grid - UKURAN LEBIH PROPORSIONAL -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            @php
                $metrics = [
                    [
                        'title' => 'Total Produk',
                        'value' => $total_products,
                        'subvalue' => $active_products . ' aktif',
                        'icon' => 'bx-package',
                        'color' => 'blue',
                        'gradient' => 'from-blue-500 to-blue-600',
                        'onclick' => "refreshMetrics('products')"
                    ],
                    [
                        'title' => 'Nilai Inventaris',
                        'value' => 'Rp ' . number_format($inventory_value, 0, ',', '.'),
                        'subvalue' => $total_stock . ' item',
                        'icon' => 'bx-archive',
                        'color' => 'purple',
                        'gradient' => 'from-purple-500 to-purple-600',
                        'onclick' => null
                    ],
                    [
                        'title' => 'Pesanan Aktif',
                        'value' => $active_orders,
                        'subvalue' => $pending_shipment . ' tertunda',
                        'icon' => 'bx-cart-alt',
                        'color' => 'green',
                        'gradient' => 'from-green-500 to-green-600',
                        'onclick' => "refreshMetrics('orders')"
                    ],
                    [
                        'title' => 'Pendapatan Bulanan',
                        'value' => 'Rp ' . number_format($monthly_revenue, 0, ',', '.'),
                        'subvalue' => 'perkiraan real-time',
                        'icon' => 'bx-dollar-circle',
                        'color' => 'amber',
                        'gradient' => 'from-amber-500 to-amber-600',
                        'onclick' => null
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

        <!-- Modern Main Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
            <!-- Left Column -->
            <div class="lg:col-span-2 space-y-4 lg:space-y-6">
                <!-- Recent Orders - DESIGN BARU DENGAN INFORMASI LEBIH DETAIL -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="px-4 lg:px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                                <h3 class="text-sm lg:text-base font-semibold text-gray-900">Pesanan Terbaru</h3>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="refreshOrders()" class="p-1.5 text-gray-400 hover:text-amber-600 transition-colors duration-200 hover:scale-110" title="Segarkan Pesanan">
                                    <i class='bx bx-refresh text-sm'></i>
                                </button>
                                <a href="{{ route('orders_menu') }}" class="text-amber-600 hover:text-amber-700 text-xs font-medium flex items-center bg-amber-50 px-2.5 py-1.5 rounded-lg hover:bg-amber-100 transition-colors border border-amber-200/50">
                                    Lihat Semua
                                    <i class='bx bx-chevron-right ml-1 text-xs'></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-3 lg:p-4">
                        <div class="space-y-3" id="ordersContainer">
                            @if(count($recent_orders) > 0)
                                @foreach($recent_orders as $order)
                                @php
                                    // Process data untuk tampilan yang lebih detail
                                    $orderId = $order['id'] ?? 'N/A';
                                    $shortOrderId = 'ORD-' . substr($orderId, -6);
                                    $customerName = $order['recipient_address']['name'] ?? 'Pelanggan';
                                    $maskedCustomer = substr($customerName, 0, 1) . '***' . substr($customerName, -1);
                                    
                                    // Ambil informasi produk
                                    $productName = 'Tidak ada produk';
                                    $productImage = null;
                                    $itemCount = 0;
                                    
                                    if (isset($order['line_items']) && is_array($order['line_items']) && count($order['line_items']) > 0) {
                                        $firstItem = $order['line_items'][0];
                                        $productName = $firstItem['product_name'] ?? 'Produk';
                                        $productImage = $firstItem['sku_image'] ?? null;
                                        $itemCount = count($order['line_items']);
                                        
                                        // Potong nama produk jika terlalu panjang
                                        if (strlen($productName) > 25) {
                                            $productName = substr($productName, 0, 25) . '...';
                                        }
                                    }
                                    
                                    // Format amount
                                    $totalAmount = $order['payment']['total_amount'] ?? 0;
                                    $formattedAmount = 'Rp ' . number_format($totalAmount, 0, ',', '.');
                                    
                                    // Status mapping
                                    $status = $order['status'] ?? 'unknown';
                                    $statusConfig = [
                                        'completed' => ['class' => 'bg-emerald-100 text-emerald-800 border-emerald-200', 'icon' => 'bx-check', 'text' => 'Selesai'],
                                        'processing' => ['class' => 'bg-blue-100 text-blue-800 border-blue-200', 'icon' => 'bx-cog', 'text' => 'Proses'],
                                        'pending' => ['class' => 'bg-amber-100 text-amber-800 border-amber-200', 'icon' => 'bx-time', 'text' => 'Tertunda'],
                                        'cancelled' => ['class' => 'bg-red-100 text-red-800 border-red-200', 'icon' => 'bx-x', 'text' => 'Dibatalkan']
                                    ];
                                    
                                    $statusInfo = $statusConfig[$status] ?? ['class' => 'bg-gray-100 text-gray-800 border-gray-200', 'icon' => 'bx-question-mark', 'text' => 'Tidak Diketahui'];
                                @endphp
                                
                                <div class="p-3 bg-gray-50/50 rounded-lg hover:bg-amber-50/50 transition-all duration-200 border border-gray-200/30 group hover:border-amber-200/50 hover:shadow-sm backdrop-blur-sm">
                                    <div class="flex items-start space-x-3">
                                        <!-- Gambar Produk -->
                                        <div class="flex-shrink-0">
                                            @if($productImage)
                                            <div class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden shadow-xs group-hover:shadow-sm transition-all">
                                                <img src="{{ $productImage }}" alt="{{ $productName }}" 
                                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                            </div>
                                            @else
                                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg border border-amber-200 flex items-center justify-center shadow-xs group-hover:shadow-sm transition-all">
                                                <i class='bx bx-package text-white text-sm'></i>
                                            </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Informasi Pesanan -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between mb-1">
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $shortOrderId }}</h4>
                                                    <p class="text-xs text-gray-600 truncate">{{ $maskedCustomer }}</p>
                                                </div>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusInfo['class'] }} border ml-2">
                                                    <i class='bx {{ $statusInfo['icon'] }} mr-0.5 text-xs'></i>
                                                    {{ $statusInfo['text'] }}
                                                </span>
                                            </div>
                                            
                                            <p class="text-xs text-gray-700 mb-1 truncate">{{ $productName }}</p>
                                            
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                                    <span class="flex items-center bg-white px-1.5 py-0.5 rounded border border-gray-200">
                                                        <i class='bx bx-package mr-0.5 text-xs'></i>
                                                        {{ $itemCount }} item
                                                    </span>
                                                    <span class="text-xs text-gray-400">
                                                        {{ \Carbon\Carbon::createFromTimestamp($order['create_time'] ?? time())->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <span class="font-bold text-gray-900 text-sm">{{ $formattedAmount }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="text-center py-6" id="noOrders">
                                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                                        <i class='bx bx-receipt text-xl text-gray-300'></i>
                                    </div>
                                    <p class="text-gray-500 font-medium text-sm">Tidak ada pesanan terbaru</p>
                                    <p class="text-xs text-gray-400 mt-1">Pesanan baru akan muncul di sini</p>
                                </div>
                            @endif
                        </div>
                        
                        <div id="ordersLoading" class="hidden text-center py-6">
                            <div class="inline-flex items-center space-x-2 bg-white rounded-lg px-4 py-3 shadow-sm border border-gray-200">
                                <div class="animate-spin rounded-full h-4 w-4 border-2 border-amber-500 border-t-transparent"></div>
                                <span class="text-gray-600 text-xs">Memuat pesanan...</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Platform Performance - MODERN DESIGN -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="px-4 lg:px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                                <h3 class="text-sm lg:text-base font-semibold text-gray-900">Kinerja Platform</h3>
                            </div>
                            <select id="platformPeriod" class="text-xs border border-gray-300 rounded-lg px-2.5 py-1.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm">
                                <option value="7">7 hari</option>
                                <option value="30">30 hari</option>
                                <option value="90">90 hari</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="p-3 lg:p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 lg:gap-4" id="platformStats">
                            @foreach([
                                ['platform' => 'TikTok Shop', 'orders' => 156, 'growth' => '+18%', 'color' => 'bg-gradient-to-br from-gray-900 to-black', 'icon' => 'bx-music'],
                                ['platform' => 'Shopee', 'orders' => 98, 'growth' => '+12%', 'color' => 'bg-gradient-to-br from-orange-500 to-orange-600', 'icon' => 'bx-store'],
                                ['platform' => 'Website', 'orders' => 45, 'growth' => '+22%', 'color' => 'bg-gradient-to-br from-blue-500 to-blue-600', 'icon' => 'bx-globe']
                            ] as $platform)
                            <div class="text-center p-3 bg-gradient-to-br from-gray-50/50 to-white/50 rounded-lg hover:shadow-sm transition-all duration-300 border border-gray-200/30 hover:border-amber-200/50 group transform hover:scale-[1.02] backdrop-blur-sm">
                                <div class="w-10 h-10 {{ $platform['color'] }} rounded-lg flex items-center justify-center mx-auto mb-2 shadow-xs group-hover:scale-105 transition-transform">
                                    <i class='bx {{ $platform['icon'] }} text-white text-sm'></i>
                                </div>
                                <h4 class="font-semibold text-gray-800 text-xs mb-1">{{ $platform['platform'] }}</h4>
                                <p class="text-sm font-bold text-gray-900 mb-1">{{ number_format($platform['orders']) }}</p>
                                <p class="text-xs text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded-full">{{ $platform['growth'] }}</p>
                                <div class="mt-2 w-full bg-gray-200 rounded-full h-1.5">
                                    <div class="bg-gradient-to-r from-amber-500 to-amber-600 h-1.5 rounded-full transition-all duration-1000 group-hover:from-amber-400 group-hover:to-amber-500" 
                                         style="width: {{ ($platform['orders'] / 300) * 100 }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4 lg:space-y-6">
                <!-- Quick Actions - MODERN DESIGN -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="px-4 lg:px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                            <h3 class="text-sm lg:text-base font-semibold text-gray-900">Tindakan Cepat</h3>
                        </div>
                    </div>
                    
                    <div class="p-3 lg:p-4">
                        <div class="space-y-3">
                            <a href="{{ route('products_menu') }}" class="flex items-center p-3 bg-gradient-to-r from-amber-50 to-amber-100/50 rounded-lg hover:from-amber-100 hover:to-amber-200/50 transition-all duration-300 border border-amber-200/50 group hover:scale-[1.02] transform backdrop-blur-sm">
                                <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class='bx bx-package text-white text-xs'></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 text-xs">Kelola Produk</p>
                                    <p class="text-xs text-gray-600">{{ number_format($total_products) }} produk tersedia</p>
                                </div>
                                <i class='bx bx-chevron-right text-amber-500 text-sm group-hover:translate-x-0.5 transition-transform'></i>
                            </a>
                            
                            <button onclick="refreshAllData()" class="w-full flex items-center p-3 bg-gradient-to-r from-blue-50 to-blue-100/50 rounded-lg hover:from-blue-100 hover:to-blue-200/50 transition-all duration-300 border border-blue-200/50 group hover:scale-[1.02] transform backdrop-blur-sm">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class='bx bx-refresh text-white text-xs'></i>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="font-semibold text-gray-900 text-xs">Segarkan Data</p>
                                    <p class="text-xs text-gray-600">Perbarui semua metrik</p>
                                </div>
                                <i class='bx bx-chevron-right text-blue-500 text-sm group-hover:translate-x-0.5 transition-transform'></i>
                            </button>
                            
                            <button onclick="showExportModal()" class="w-full flex items-center p-3 bg-gradient-to-r from-green-50 to-green-100/50 rounded-lg hover:from-green-100 hover:to-green-200/50 transition-all duration-300 border border-green-200/50 group hover:scale-[1.02] transform backdrop-blur-sm">
                                <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mr-3 group-hover:scale-105 transition-transform shadow-xs">
                                    <i class='bx bx-download text-white text-xs'></i>
                                </div>
                                <div class="flex-1 text-left">
                                    <p class="font-semibold text-gray-900 text-xs">Ekspor Laporan</p>
                                    <p class="text-xs text-gray-600">Format PDF & Excel</p>
                                </div>
                                <i class='bx bx-chevron-right text-green-500 text-sm group-hover:translate-x-0.5 transition-transform'></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Inventory Health - MODERN DESIGN -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="px-4 lg:px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                            <h3 class="text-sm lg:text-base font-semibold text-gray-900">Kesehatan Inventaris</h3>
                        </div>
                    </div>
                    
                    <div class="p-3 lg:p-4">
                        <div class="space-y-3">
                            @php
                                $inventoryItems = [
                                    ['label' => 'Stok Tersedia', 'value' => $total_stock, 'percentage' => $total_products > 0 ? min(85, ($total_stock / ($total_stock + $low_stock_products + $out_of_stock_products)) * 100) : 0, 'color' => 'emerald', 'gradient' => 'from-emerald-500 to-emerald-600'],
                                    ['label' => 'Stok Rendah', 'value' => $low_stock_products, 'percentage' => $total_products > 0 ? min(15, ($low_stock_products / $total_products) * 100) : 0, 'color' => 'amber', 'gradient' => 'from-amber-500 to-amber-600'],
                                    ['label' => 'Stok Habis', 'value' => $out_of_stock_products, 'percentage' => $total_products > 0 ? min(10, ($out_of_stock_products / $total_products) * 100) : 0, 'color' => 'red', 'gradient' => 'from-red-500 to-red-600']
                                ];
                            @endphp

                            @foreach($inventoryItems as $item)
                            <div class="group">
                                <div class="flex justify-between text-xs text-gray-700 mb-1.5">
                                    <span class="font-medium">{{ $item['label'] }}</span>
                                    <span class="font-semibold">{{ number_format($item['value']) }} item</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-gradient-to-r {{ $item['gradient'] }} h-1.5 rounded-full transition-all duration-1000 group-hover:shadow-sm inventory-bar" 
                                         style="width: {{ $item['percentage'] }}%"></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- System Status - MODERN DESIGN -->
                <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300">
                    <div class="px-4 lg:px-5 py-3 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                        <div class="flex items-center space-x-2">
                            <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                            <h3 class="text-sm lg:text-base font-semibold text-gray-900">Status Sistem</h3>
                        </div>
                    </div>
                    
                    <div class="p-3 lg:p-4">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-2.5 bg-gray-50/50 rounded-lg hover:bg-white/80 transition-colors duration-200 backdrop-blur-sm">
                                <div class="flex items-center space-x-2">
                                    <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                                    <span class="text-xs text-gray-700">Basis Data Produk</span>
                                </div>
                                <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Aktif</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 bg-gray-50/50 rounded-lg hover:bg-white/80 transition-colors duration-200 backdrop-blur-sm">
                                <span class="text-xs text-gray-700">Total Data</span>
                                <span class="text-xs font-semibold text-gray-900">{{ number_format($total_products) }} produk</span>
                            </div>
                            <div class="flex items-center justify-between p-2.5 bg-gray-50/50 rounded-lg hover:bg-white/80 transition-colors duration-200 backdrop-blur-sm">
                                <span class="text-xs text-gray-700">Pembaruan Terakhir</span>
                                <span class="text-xs text-gray-900" id="lastSyncTime">{{ $last_updated }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Mobile Bottom Navigation -->
<div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-gray-200/60 p-3 z-40 shadow-lg">
    <div class="grid grid-cols-4 gap-1">
        <a href="{{ route('dashboard.index') }}" class="flex flex-col items-center justify-center p-2 text-amber-600 rounded-lg bg-amber-50/80 backdrop-blur-sm">
            <i class='bx bx-home text-lg mb-0.5'></i>
            <span class="text-xs font-medium">Dasbor</span>
        </a>
        <a href="{{ route('products_menu') }}" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-package text-lg mb-0.5'></i>
            <span class="text-xs">Produk</span>
        </a>
        <a href="{{ route('orders_menu') }}" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-cart text-lg mb-0.5'></i>
            <span class="text-xs">Pesanan</span>
        </a>
        <button onclick="refreshAllData()" class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-amber-600 transition-colors rounded-lg hover:bg-amber-50/50">
            <i class='bx bx-refresh text-lg mb-0.5'></i>
            <span class="text-xs">Segarkan</span>
        </button>
    </div>
</div>

<!-- Modern Export Modal -->
<div id="exportModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white/90 backdrop-blur-lg rounded-2xl shadow-xl mx-4 max-w-md w-full transform transition-all duration-300 scale-95 opacity-0"
         id="exportModalContent">
        <div class="px-5 py-4 border-b border-gray-200/50 bg-gradient-to-r from-amber-600 to-amber-500 text-white rounded-t-2xl">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-semibold">Ekspor Laporan Bisnis</h3>
                <button onclick="hideExportModal()" class="text-amber-100 hover:text-white transition-colors p-1 rounded-lg hover:bg-white/10">
                    <i class='bx bx-x text-lg'></i>
                </button>
            </div>
        </div>
        
        <div class="p-5">
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Periode Laporan</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button class="export-period-btn border border-gray-300 rounded-lg py-2.5 px-3 text-xs font-medium hover:border-amber-400 hover:bg-amber-50 transition-all duration-200 active:scale-95" data-period="7">
                            7 Hari
                        </button>
                        <button class="export-period-btn border border-gray-300 rounded-lg py-2.5 px-3 text-xs font-medium hover:border-amber-400 hover:bg-amber-50 transition-all duration-200 active:scale-95" data-period="30">
                            30 Hari
                        </button>
                        <button class="export-period-btn border border-gray-300 rounded-lg py-2.5 px-3 text-xs font-medium hover:border-amber-400 hover:bg-amber-50 transition-all duration-200 active:scale-95" data-period="90">
                            90 Hari
                        </button>
                        <button class="export-period-btn border border-gray-300 rounded-lg py-2.5 px-3 text-xs font-medium hover:border-amber-400 hover:bg-amber-50 transition-all duration-200 active:scale-95" data-period="custom">
                            Kustom
                        </button>
                    </div>
                </div>
                
                <div id="customDateRange" class="hidden space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition text-xs">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1.5">Tanggal Berakhir</label>
                        <input type="date" id="endDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition text-xs">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Format Ekspor</label>
                    <div class="flex space-x-3">
                        <button onclick="exportToPdf()" class="flex-1 flex flex-col items-center justify-center space-y-1.5 bg-red-50 border border-red-200 text-red-700 rounded-lg py-3 hover:bg-red-100 hover:border-red-300 transition-all duration-300 hover:scale-105 group">
                            <i class='bx bxs-file-pdf text-xl group-hover:scale-110 transition-transform'></i>
                            <span class="font-semibold text-xs">Laporan PDF</span>
                        </button>
                        <button onclick="exportToExcel()" class="flex-1 flex flex-col items-center justify-center space-y-1.5 bg-green-50 border border-green-200 text-green-700 rounded-lg py-3 hover:bg-green-100 hover:border-green-300 transition-all duration-300 hover:scale-105 group">
                            <i class='bx bxs-file-excel text-xl group-hover:scale-110 transition-transform'></i>
                            <span class="font-semibold text-xs">Lembar Excel</span>
                        </button>
                    </div>
                </div>
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

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .content-container > * {
        animation: fadeInUp 0.4s ease-out;
    }

    /* Mobile optimizations */
    @media (max-width: 768px) {
        .min-h-screen {
            padding-bottom: 4rem;
        }
        
        .metric-card {
            animation: slideIn 0.3s ease-out;
        }
        
        /* Better touch targets */
        button, a, .cursor-pointer {
            min-height: 44px;
            min-width: 44px;
        }
    }

    /* Smooth scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 4px;
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

    /* Glass morphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Subtle gradient borders */
    .gradient-border {
        border: 1px solid;
        border-image: linear-gradient(45deg, #f59e0b, #d97706) 1;
    }
</style>

<script>
    // Global state dengan data yang diperlukan untuk export
    let refreshInterval;
    let selectedPeriod = '7';
    let exportData = {};

    const dashboardData = {
        total_products: {{ $total_products }},
        active_products: {{ $active_products }},
        total_stock: {{ $total_stock }},
        inventory_value: {{ $inventory_value }},
        active_orders: {{ $active_orders }},
        pending_shipment: {{ $pending_shipment }},
        monthly_revenue: {{ $monthly_revenue }},
        recent_orders: @json($recent_orders ?? []),
        low_stock_products: {{ $low_stock_products ?? 0 }},
        out_of_stock_products: {{ $out_of_stock_products ?? 0 }},
        data_source: "{{ $data_source ?? 'database' }}",
        last_updated: "{{ $last_updated ?? now()->toDateTimeString() }}"
    };

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', function() {
        initializeDashboard();
        startAutoRefresh();
        setupEventListeners();
        setupExportEventListeners();
        
        // Add mobile specific optimizations
        if (window.innerWidth < 1024) {
            document.querySelector('.min-h-screen').classList.add('pb-16');
        }
    });

    function initializeDashboard() {
        updateDateTime();
        setInterval(updateDateTime, 1000);
        
        // Update data source indicator
        updateDataSourceIndicator('{{ $data_source }}');
    }

    function updateDataSourceIndicator(dataSource) {
        const statusElement = document.querySelector('.bg-emerald-400');
        const indicatorText = document.getElementById('dataSourceIndicator');
        
        if (statusElement && indicatorText) {
            if (dataSource === 'tiktok_api') {
                statusElement.className = 'w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse';
                indicatorText.textContent = 'Data Real-time TikTok';
            } else {
                statusElement.className = 'w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse';
                indicatorText.textContent = 'Data Fallback';
            }
        }
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
        const platformPeriod = document.getElementById('platformPeriod');
        if (platformPeriod) {
            platformPeriod.addEventListener('change', updatePlatformStats);
        }

        document.addEventListener('visibilitychange', handleVisibilityChange);
    }

    function setupExportEventListeners() {
        const periodButtons = document.querySelectorAll('.export-period-btn');
        periodButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                periodButtons.forEach(b => {
                    b.classList.remove('border-amber-400', 'bg-amber-50', 'text-amber-700');
                    b.classList.add('border-gray-300');
                });
                this.classList.add('border-amber-400', 'bg-amber-50', 'text-amber-700');
                this.classList.remove('border-gray-300');
                selectedPeriod = this.dataset.period;
                
                const customRange = document.getElementById('customDateRange');
                if (selectedPeriod === 'custom') {
                    customRange.classList.remove('hidden');
                    customRange.style.animation = 'fadeInUp 0.3s ease-out';
                } else {
                    customRange.classList.add('hidden');
                }
            });
        });

        // Close modal when clicking outside
        document.getElementById('exportModal').addEventListener('click', function(e) {
            if (e.target === this) hideExportModal();
        });
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
            refreshAllData();
        }, 120000); // Refresh every 2 minutes
    }

    // Enhanced refresh functions dengan better error handling
    async function refreshAllData() {
        showNotification('Menyegarkan data dasbor...', 'info');
        try {
            const response = await fetch('/api/dashboard/data');
            const result = await response.json();
            
            if (result.success) {
                updateDashboardUI(result.data);
                updateDataSourceIndicator('tiktok_api');
                showNotification('Dasbor berhasil diperbarui', 'success');
            } else {
                throw new Error(result.error || 'Gagal menyegarkan data');
            }
        } catch (error) {
            console.error('Refresh error:', error);
            updateDataSourceIndicator('fallback');
            showNotification('Gagal memperbarui data', 'error');
        }
    }

    async function refreshMetrics(type) {
        const typeLabels = {
            'products': 'produk',
            'orders': 'pesanan'
        };
        
        showNotification(`Menyegarkan data ${typeLabels[type]}...`, 'info');
        try {
            const response = await fetch('/api/dashboard/refresh', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ metrics: [type] })
            });
            
            const result = await response.json();
            
            if (result.success) {
                updateDashboardUI(result.data);
                updateDataSourceIndicator('tiktok_api');
                showNotification(`Data ${typeLabels[type]} berhasil diperbarui`, 'success');
            } else {
                throw new Error(result.error || `Gagal menyegarkan ${typeLabels[type]}`);
            }
        } catch (error) {
            console.error(`Refresh ${type} error:`, error);
            updateDataSourceIndicator('fallback');
            showNotification(`Gagal memperbarui ${typeLabels[type]}`, 'error');
        }
    }

    async function refreshOrders() {
        const container = document.getElementById('ordersContainer');
        const loading = document.getElementById('ordersLoading');
        const noOrders = document.getElementById('noOrders');
        
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
                updateDataSourceIndicator('tiktok_api');
                showNotification('Pesanan berhasil disegarkan', 'success');
            } else {
                throw new Error('Tidak ada data pesanan yang diterima');
            }
        } catch (error) {
            console.error('Refresh orders error:', error);
            loading.classList.add('hidden');
            container.classList.remove('hidden');
            updateDataSourceIndicator('fallback');
            showNotification('Gagal memuat pesanan', 'error');
        }
    }

    function updateDashboardUI(data) {
        // Update metrics cards
        const metricCards = document.querySelectorAll('.metric-card');
        
        if (data.total_products !== undefined && metricCards[0]) {
            metricCards[0].querySelector('.text-lg').textContent = data.total_products.toLocaleString();
            metricCards[0].querySelector('.text-xs').textContent = data.active_products + ' aktif';
        }
        
        if (data.inventory_value !== undefined && metricCards[1]) {
            metricCards[1].querySelector('.text-lg').textContent = 'Rp ' + formatNumber(data.inventory_value);
            metricCards[1].querySelector('.text-xs').textContent = data.total_stock + ' item';
        }
        
        if (data.active_orders !== undefined && metricCards[2]) {
            metricCards[2].querySelector('.text-lg').textContent = data.active_orders.toLocaleString();
            metricCards[2].querySelector('.text-xs').textContent = data.pending_shipment + ' tertunda';
        }
        
        if (data.monthly_revenue !== undefined && metricCards[3]) {
            metricCards[3].querySelector('.text-lg').textContent = 'Rp ' + formatNumber(data.monthly_revenue);
        }
        
        if (data.recent_orders) {
            updateOrdersList(data.recent_orders);
        }
        
        // Update last sync time
        const now = new Date();
        document.getElementById('lastSyncTime').textContent = now.toLocaleString('id-ID');
        document.getElementById('lastSyncHeader').textContent = now.toLocaleString('id-ID');
        
        // Update inventory health
        updateInventoryHealth(data);
    }

    function updateInventoryHealth(data) {
        if (data.total_products && data.low_stock_products !== undefined && data.out_of_stock_products !== undefined) {
            const inventoryItems = [
                { 
                    label: 'Stok Tersedia', 
                    value: data.total_stock, 
                    percentage: Math.min(85, (data.total_stock / (data.total_stock + data.low_stock_products + data.out_of_stock_products)) * 100) 
                },
                { 
                    label: 'Stok Rendah', 
                    value: data.low_stock_products, 
                    percentage: Math.min(15, (data.low_stock_products / data.total_products) * 100) 
                },
                { 
                    label: 'Stok Habis', 
                    value: data.out_of_stock_products, 
                    percentage: Math.min(10, (data.out_of_stock_products / data.total_products) * 100) 
                }
            ];

            // Update inventory health display
            const inventoryGroups = document.querySelectorAll('.group');
            inventoryGroups.forEach((group, index) => {
                if (inventoryItems[index]) {
                    const item = inventoryItems[index];
                    const valueElement = group.querySelector('.font-semibold');
                    const barElement = group.querySelector('.inventory-bar');
                    
                    if (valueElement) {
                        valueElement.textContent = item.value.toLocaleString() + ' item';
                    }
                    if (barElement) {
                        barElement.style.width = item.percentage + '%';
                    }
                }
            });
        }
    }

    function updateOrdersList(orders) {
        const container = document.getElementById('ordersContainer');
        const noOrders = document.getElementById('noOrders');
        
        if (!orders || orders.length === 0) {
            if (noOrders) noOrders.classList.remove('hidden');
            container.innerHTML = '';
            return;
        }
        
        if (noOrders) noOrders.classList.add('hidden');
        
        let ordersHTML = '';
        orders.forEach(order => {
            // Process data untuk tampilan yang lebih detail
            const orderId = order.id || 'N/A';
            const shortOrderId = 'ORD-' + orderId.slice(-6);
            const customerName = order.recipient_address?.name || 'Pelanggan';
            const maskedCustomer = customerName.substring(0, 1) + '***' + customerName.substring(customerName.length - 1);
            
            // Ambil informasi produk
            let productName = 'Tidak ada produk';
            let productImage = null;
            let itemCount = 0;
            
            if (order.line_items && Array.isArray(order.line_items) && order.line_items.length > 0) {
                productName = order.line_items[0].product_name || 'Produk';
                productImage = order.line_items[0].sku_image || null;
                itemCount = order.line_items.length;
                
                // Potong nama produk jika terlalu panjang
                if (productName.length > 25) {
                    productName = productName.substring(0, 25) + '...';
                }
            }
            
            // Format amount
            const totalAmount = order.payment?.total_amount || 0;
            const formattedAmount = 'Rp ' + formatNumber(totalAmount);
            
            // Status mapping
            const status = order.status || 'unknown';
            const statusConfig = {
                'completed': {class: 'bg-emerald-100 text-emerald-800 border-emerald-200', icon: 'bx-check', text: 'Selesai'},
                'processing': {class: 'bg-blue-100 text-blue-800 border-blue-200', icon: 'bx-cog', text: 'Proses'},
                'pending': {class: 'bg-amber-100 text-amber-800 border-amber-200', icon: 'bx-time', text: 'Tertunda'},
                'cancelled': {class: 'bg-red-100 text-red-800 border-red-200', icon: 'bx-x', text: 'Dibatalkan'}
            };
            
            const statusInfo = statusConfig[status] || {class: 'bg-gray-100 text-gray-800 border-gray-200', icon: 'bx-question-mark', text: 'Tidak Diketahui'};
            
            // Format waktu
            const orderTime = order.create_time ? new Date(order.create_time * 1000) : new Date();
            const timeAgo = getTimeAgo(orderTime);
            
            ordersHTML += `
                <div class="p-3 bg-gray-50/50 rounded-lg hover:bg-amber-50/50 transition-all duration-200 border border-gray-200/30 group hover:border-amber-200/50 hover:shadow-sm backdrop-blur-sm">
                    <div class="flex items-start space-x-3">
                        <!-- Gambar Produk -->
                        <div class="flex-shrink-0">
                            ${productImage ? `
                            <div class="w-12 h-12 bg-gray-100 rounded-lg border border-gray-200 overflow-hidden shadow-xs group-hover:shadow-sm transition-all">
                                <img src="${productImage}" alt="${productName}" 
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                            </div>
                            ` : `
                            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-amber-600 rounded-lg border border-amber-200 flex items-center justify-center shadow-xs group-hover:shadow-sm transition-all">
                                <i class='bx bx-package text-white text-sm'></i>
                            </div>
                            `}
                        </div>
                        
                        <!-- Informasi Pesanan -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-1">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-semibold text-gray-800 text-sm truncate">${shortOrderId}</h4>
                                    <p class="text-xs text-gray-600 truncate">${maskedCustomer}</p>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${statusInfo.class} border ml-2">
                                    <i class='bx ${statusInfo.icon} mr-0.5 text-xs'></i>
                                    ${statusInfo.text}
                                </span>
                            </div>
                            
                            <p class="text-xs text-gray-700 mb-1 truncate">${productName}</p>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2 text-xs text-gray-500">
                                    <span class="flex items-center bg-white px-1.5 py-0.5 rounded border border-gray-200">
                                        <i class='bx bx-package mr-0.5 text-xs'></i>
                                        ${itemCount} item
                                    </span>
                                    <span class="text-xs text-gray-400">${timeAgo}</span>
                                </div>
                                <span class="font-bold text-gray-900 text-sm">${formattedAmount}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        container.innerHTML = ordersHTML;
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

    function updatePlatformStats() {
        const period = document.getElementById('platformPeriod').value;
        const platformStats = document.getElementById('platformStats');
        
        platformStats.style.opacity = '0.5';
        
        setTimeout(() => {
            platformStats.style.opacity = '1';
            showNotification(`Statistik platform diperbarui untuk ${period} hari`, 'info');
        }, 600);
    }

    // Enhanced Export Functions - FIXED VERSION
    function showExportModal() {
        const modal = document.getElementById('exportModal');
        const modalContent = document.getElementById('exportModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
        
        prepareExportData();
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
                { category: 'Stok Rendah', items: dashboardData.low_stock_products.toLocaleString(), percentage: Math.round((dashboardData.low_stock_products / dashboardData.total_products) * 100) || 10 },
                { category: 'Stok Habis', items: dashboardData.out_of_stock_products.toLocaleString(), percentage: Math.round((dashboardData.out_of_stock_products / dashboardData.total_products) * 100) || 5 }
            ]
        };
    }

    function exportToPdf() {
        showNotification('Membuat laporan PDF...', 'info');
        
        try {
            // Use the global jsPDF from CDN
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Set document properties
            doc.setProperties({
                title: 'Camellia Boutique99 - Laporan Bisnis',
                subject: 'Laporan Kinerja Bisnis',
                author: 'Sistem Camellia Boutique99',
                keywords: 'bisnis, laporan, kinerja, penjualan',
                creator: 'Camellia Boutique99'
            });

            // Add header
            doc.setFillColor(245, 158, 11); // Amber color
            doc.rect(0, 0, 220, 30, 'F');
            
            // Title
            doc.setTextColor(255, 255, 255);
            doc.setFontSize(20);
            doc.setFont('helvetica', 'bold');
            doc.text('CAMELIA BOUTIQUE99', 105, 18, { align: 'center' });
            
            doc.setFontSize(12);
            doc.text('LAPORAN KINERJA BISNIS', 105, 25, { align: 'center' });

            // Reset text color
            doc.setTextColor(0, 0, 0);
            doc.setFontSize(10);

            let yPosition = 45;

            // Report info
            doc.setFont('helvetica', 'bold');
            doc.text('Periode Laporan:', 20, yPosition);
            doc.setFont('helvetica', 'normal');
            doc.text(exportData.report.period, 60, yPosition);
            
            yPosition += 8;
            doc.setFont('helvetica', 'bold');
            doc.text('Dibuat:', 20, yPosition);
            doc.setFont('helvetica', 'normal');
            doc.text(exportData.report.generated, 60, yPosition);

            yPosition += 15;

            // Key Metrics Section
            doc.setFont('helvetica', 'bold');
            doc.setFontSize(14);
            doc.text('METRIK KINERJA UTAMA', 20, yPosition);
            yPosition += 10;

            doc.setFontSize(10);
            const metrics = [
                ['Total Produk', exportData.metrics.totalProducts],
                ['Produk Aktif', exportData.metrics.activeProducts],
                ['Pesanan Aktif', exportData.metrics.activeOrders],
                ['Pengiriman Tertunda', exportData.metrics.pendingShipment],
                ['Pendapatan Bulanan', exportData.metrics.monthlyRevenue],
                ['Nilai Inventaris', exportData.metrics.inventoryValue],
                ['Total Stok', exportData.metrics.totalStock],
                ['Produk Stok Rendah', exportData.metrics.lowStockProducts],
                ['Produk Stok Habis', exportData.metrics.outOfStockProducts]
            ];

            metrics.forEach(([label, value], index) => {
                if (yPosition > 270) {
                    doc.addPage();
                    yPosition = 20;
                }
                
                doc.setFont('helvetica', 'bold');
                doc.text(label + ':', 20, yPosition);
                doc.setFont('helvetica', 'normal');
                doc.text(value.toString(), 80, yPosition);
                yPosition += 6;
            });

            yPosition += 10;

            // Platform Performance
            if (yPosition > 250) {
                doc.addPage();
                yPosition = 20;
            }

            doc.setFont('helvetica', 'bold');
            doc.setFontSize(14);
            doc.text('KINERJA PLATFORM', 20, yPosition);
            yPosition += 10;

            doc.setFontSize(10);
            exportData.platformPerformance.forEach(platform => {
                if (yPosition > 270) {
                    doc.addPage();
                    yPosition = 20;
                }
                
                doc.setFont('helvetica', 'bold');
                doc.text(platform.platform + ':', 20, yPosition);
                doc.setFont('helvetica', 'normal');
                doc.text(`${platform.orders} pesanan (${platform.growth})`, 80, yPosition);
                yPosition += 6;
            });

            // Footer
            const pageCount = doc.internal.getNumberOfPages();
            for (let i = 1; i <= pageCount; i++) {
                doc.setPage(i);
                doc.setFontSize(8);
                doc.setTextColor(150, 150, 150);
                doc.text(`Halaman ${i} dari ${pageCount}`, 105, 290, { align: 'center' });
                doc.text('Dibuat oleh Sistem Manajemen Camellia Boutique99', 105, 295, { align: 'center' });
            }

            const fileName = `Laporan_Camellia_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(fileName);
            
            hideExportModal();
            showNotification('Laporan PDF berhasil diunduh!', 'success');
        } catch (error) {
            console.error('PDF Export Error:', error);
            showNotification('Gagal membuat laporan PDF. Silakan coba lagi.', 'error');
        }
    }

    function exportToExcel() {
        showNotification('Membuat laporan Excel...', 'info');

        try {
            // Create workbook
            const wb = XLSX.utils.book_new();

            // Summary Sheet
            const summaryData = [
                ['CAMELIA BOUTIQUE99 - LAPORAN KINERJA BISNIS'],
                [],
                ['Periode Laporan', exportData.report.period],
                ['Dibuat', exportData.report.generated],
                [],
                ['METRIK KINERJA UTAMA'],
                ['Total Produk', exportData.metrics.totalProducts],
                ['Produk Aktif', exportData.metrics.activeProducts],
                ['Pesanan Aktif', exportData.metrics.activeOrders],
                ['Pengiriman Tertunda', exportData.metrics.pendingShipment],
                ['Pendapatan Bulanan', exportData.metrics.monthlyRevenue],
                ['Nilai Inventaris', exportData.metrics.inventoryValue],
                ['Total Stok', exportData.metrics.totalStock],
                ['Produk Stok Rendah', exportData.metrics.lowStockProducts],
                ['Produk Stok Habis', exportData.metrics.outOfStockProducts],
                [],
                ['KINERJA PLATFORM'],
                ['Platform', 'Pesanan', 'Pertumbuhan', 'Persentase'],
                ...exportData.platformPerformance.map(p => [p.platform, p.orders, p.growth, `${p.percentage}%`])
            ];

            const wsSummary = XLSX.utils.aoa_to_sheet(summaryData);
            XLSX.utils.book_append_sheet(wb, wsSummary, 'Ringkasan');

            // Orders Sheet
            if (exportData.recentOrders.length > 0) {
                const ordersData = [
                    ['PESANAN TERBARU'],
                    ['ID Pesanan', 'Pelanggan', 'Jumlah', 'Status'],
                    ...exportData.recentOrders.map(o => [o.id, o.customer, o.amount, o.status])
                ];
                const wsOrders = XLSX.utils.aoa_to_sheet(ordersData);
                XLSX.utils.book_append_sheet(wb, wsOrders, 'Pesanan Terbaru');
            }

            // Inventory Sheet
            const inventoryData = [
                ['KESEHATAN INVENTARIS'],
                ['Kategori', 'Item', 'Persentase'],
                ...exportData.inventoryHealth.map(i => [i.category, i.items, `${i.percentage}%`])
            ];
            const wsInventory = XLSX.utils.aoa_to_sheet(inventoryData);
            XLSX.utils.book_append_sheet(wb, wsInventory, 'Kesehatan Inventaris');

            // Save file
            const fileName = `Laporan_Camellia_${new Date().toISOString().split('T')[0]}.xlsx`;
            XLSX.writeFile(wb, fileName);
            
            hideExportModal();
            showNotification('Laporan Excel berhasil diunduh!', 'success');
        } catch (error) {
            console.error('Excel Export Error:', error);
            showNotification('Gagal membuat laporan Excel. Silakan coba lagi.', 'error');
        }
    }

    // Enhanced notification system - MODERN DESIGN
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
        
        const messages = {
            success: 'Berhasil',
            error: 'Kesalahan',
            info: 'Info',
            warning: 'Peringatan'
        };
        
        notification.className = `px-3 py-2 rounded-lg transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]} backdrop-blur-sm`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <i class='bx ${icons[type]} text-sm'></i>
                    <div>
                        <div class="text-xs font-medium">${messages[type]}</div>
                        <div class="text-xs opacity-90">${message}</div>
                    </div>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="text-white/80 hover:text-white transition-colors ml-3">
                    <i class='bx bx-x text-sm'></i>
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
        }, 4000);
    }

    function formatNumber(num) {
        return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'r') {
            e.preventDefault();
            refreshAllData();
        }
        if (e.key === 'Escape') {
            hideExportModal();
        }
    });

    // Mobile orientation change handling
    window.addEventListener('orientationchange', function() {
        setTimeout(() => {
            if (window.innerWidth < 1024) {
                document.querySelector('.min-h-screen').classList.add('pb-16');
            }
        }, 300);
    });
</script>
@endsection