@extends('layouts.app')

@section('title', 'Products Management - Camellia Boutique99')
@section('subtitle', 'Kelola katalog produk batik eksklusif Anda')

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
                                Products Management
                            </h1>
                            <p class="text-amber-100 text-sm lg:text-sm mt-1 font-light">Kelola dan pantau semua produk batik eksklusif dalam satu tempat</p>
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
        <!-- Refined Metrics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4 mb-6">
            @php
                $totalProducts = $products['count'] ?? 0;
                $activeProducts = 0;
                $totalStock = 0;
                $inventoryValue = 0;
                
                if (isset($products['products'])) {
                    foreach ($products['products'] as $product) {
                        if (($product['status'] ?? '') === 'ACTIVATE') {
                            $activeProducts++;
                        }
                        $totalStock += $product['stock'] ?? 0;
                        $productPrice = $product['price'] ?? 0;
                        $inventoryValue += ($product['stock'] ?? 0) * $productPrice;
                    }
                }
                
                $metrics = [
                    [
                        'title' => 'Total Produk',
                        'value' => $totalProducts,
                        'subvalue' => $activeProducts . ' aktif',
                        'icon' => 'bx-package',
                        'color' => 'blue',
                        'gradient' => 'from-blue-500 to-blue-600',
                    ],
                    [
                        'title' => 'Total Stok',
                        'value' => number_format($totalStock),
                        'subvalue' => 'stok tersedia',
                        'icon' => 'bx-check-circle',
                        'color' => 'green',
                        'gradient' => 'from-green-500 to-green-600',
                    ],
                    [
                        'title' => 'Nilai Inventori',
                        'value' => 'Rp ' . number_format($inventoryValue, 0, ',', '.'),
                        'subvalue' => 'total nilai stok',
                        'icon' => 'bx-dollar-circle',
                        'color' => 'purple',
                        'gradient' => 'from-purple-500 to-purple-600',
                    ],
                    [
                        'title' => 'Produk Aktif',
                        'value' => $activeProducts,
                        'subvalue' => 'sedang dijual',
                        'icon' => 'bx-play-circle',
                        'color' => 'amber',
                        'gradient' => 'from-amber-500 to-amber-600',
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

        <!-- Search and Filter Section -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div class="flex flex-col sm:flex-row sm:items-center gap-3 flex-1">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-md">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-lg'></i>
                        <input type="text" id="searchInput" placeholder="Cari produk, deskripsi, atau ID..." 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm text-sm">
                        <div id="searchLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-amber-500"></div>
                        </div>
                    </div>
                    
                    <!-- Status Filter -->
                    <select id="statusFilter" class="border border-gray-300 rounded-xl px-3 py-2.5 focus:ring-2 focus:ring-amber-400 focus:border-transparent transition bg-white/80 backdrop-blur-sm text-sm min-w-[160px]">
                        <option value="all">Semua Status</option>
                        <option value="ACTIVATE">Aktif</option>
                        <option value="DRAFT">Draft</option>
                        <option value="ARCHIVED">Arsip</option>
                    </select>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Sync Button -->
                    <button id="syncProductsBtn" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium transition-all duration-300 flex items-center space-x-2 shadow-sm hover:shadow-md">
                        <i class='bx bx-refresh text-lg'></i>
                        <span>Segarkan</span>
                    </button>
                </div>
            </div>

            <!-- Search Info -->
            <div id="searchInfo" class="mt-4 hidden">
                <div class="flex items-center justify-between bg-amber-50/80 backdrop-blur-sm border border-amber-200/50 rounded-lg p-3">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-amber-700">Hasil pencarian untuk:</span>
                        <span id="searchQuery" class="text-sm font-medium text-amber-800 bg-amber-100 px-3 py-1 rounded-md"></span>
                        <button id="clearSearch" class="text-amber-500 hover:text-amber-700 transition-colors">
                            <i class='bx bx-x text-lg'></i>
                        </button>
                    </div>
                    <span id="searchResultsCount" class="text-sm text-amber-700"></span>
                </div>
            </div>
        </div>

        <!-- Products Grid Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-xl shadow-sm border border-gray-100/80 overflow-hidden hover:shadow-md transition-all duration-300 mb-8">
            <!-- Table Header -->
            <div class="px-4 lg:px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-gray-50/50 to-white/50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-5 bg-gradient-to-b from-amber-500 to-amber-600 rounded-full"></div>
                        <h3 class="text-base font-semibold text-gray-900">Katalog Produk</h3>
                        <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-xs font-medium" id="productsCount">
                            {{ $products['count'] ?? 0 }} produk
                        </span>
                    </div>
                    
                    <div class="flex items-center space-x-2 text-sm text-gray-500">
                        <i class='bx bx-info-circle text-sm'></i>
                        <span>Diperbarui: <span id="lastUpdateTime">{{ now()->format('H:i') }}</span></span>
                    </div>
                </div>
            </div>

            <!-- Products Content -->
            <div class="p-4 lg:p-6">
                <div id="productsContainer">
                    @if (isset($products['products']) && count($products['products']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productsGrid">
                            @foreach ($products['products'] as $product)
                                @php
                                    $productData = $product;
                                    $productStock = $product['stock'] ?? 0;
                                    $productPrice = $product['price'] ?? 0;
                                    $productValue = $productStock * $productPrice;

                                    // Status styling
                                    $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                                    $statusText = $product['status'] ?? 'Unknown';
                                    $statusIcon = 'bx-question-mark';

                                    if (($product['status'] ?? '') === 'ACTIVATE') {
                                        $statusClass = 'bg-emerald-100 text-emerald-700 border border-emerald-200';
                                        $statusText = 'Aktif';
                                        $statusIcon = 'bx-check-circle';
                                    } elseif (($product['status'] ?? '') === 'LIMITED') {
                                        $statusClass = 'bg-amber-100 text-amber-700 border border-amber-200';
                                        $statusText = 'Terbatas';
                                        $statusIcon = 'bx-time';
                                    } elseif (($product['status'] ?? '') === 'SOLD_OUT') {
                                        $statusClass = 'bg-rose-100 text-rose-700 border border-rose-200';
                                        $statusText = 'Habis';
                                        $statusIcon = 'bx-x-circle';
                                    } elseif (($product['status'] ?? '') === 'DRAFT') {
                                        $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                                        $statusText = 'Draft';
                                        $statusIcon = 'bx-edit';
                                    } elseif (($product['status'] ?? '') === 'ARCHIVED') {
                                        $statusClass = 'bg-gray-200 text-gray-700 border border-gray-300';
                                        $statusText = 'Arsip';
                                        $statusIcon = 'bx-archive';
                                    }

                                    // Clean description
                                    $cleanDescription = strip_tags($product['description'] ?? '');
                                    $cleanDescription = str_replace(['&nbsp;', '&amp;'], [' ', '&'], $cleanDescription);
                                    $cleanDescription = trim($cleanDescription);

                                    // Product image
                                    $productImage = $product['image'] ?? '';
                                    $allImages = json_decode($product['images'] ?? '[]', true) ?: [];
                                    if (empty($productImage) && count($allImages) > 0) {
                                        $productImage = $allImages[0];
                                    }

                                    $skus = json_decode($product['skus'] ?? '[]', true) ?: [];
                                @endphp

                                <!-- Product Card -->
                                <div class="bg-white border border-gray-200/80 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 group relative product-card"
                                    data-title="{{ strtolower($product['title'] ?? '') }}"
                                    data-description="{{ strtolower($cleanDescription) }}"
                                    data-status="{{ $product['status'] ?? '' }}" 
                                    data-price="{{ $productPrice }}"
                                    data-stock="{{ $productStock }}" 
                                    data-value="{{ $productValue }}"
                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}">
                                    
<div class="h-48 relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
    @if (!empty($productImage))
        <img src="{{ $productImage }}"
            alt="{{ $product['title'] ?? 'Product Image' }}"
            class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
            style="object-position: center 10%;"
            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            loading="lazy">
        <div class="w-full h-full hidden items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
            <i class='bx bx-package text-gray-400 text-3xl'></i>
        </div>
    @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
            <i class='bx bx-package text-gray-400 text-3xl'></i>
        </div>
    @endif

    <!-- Status Badge - Dipindahkan ke luar container gambar -->
    <div class="absolute top-3 right-3">
        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }} backdrop-blur-sm">
            <i class='bx {{ $statusIcon }} mr-1'></i>
            {{ $statusText }}
        </span>
    </div>

    <!-- Low Stock Warning - Dipindahkan ke luar container gambar -->
    @if ($productStock > 0 && $productStock <= 10)
        <div class="absolute bottom-3 left-3">
            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200 backdrop-blur-sm">
                <i class='bx bx-error-alt mr-1'></i>Stok Menipis
            </span>
        </div>
    @endif
</div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3 class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 leading-tight group-hover:text-amber-700 transition-colors">
                                            {{ $product['title'] ?? 'N/A' }}
                                        </h3>

                                        <p class="text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed">
                                            {{ $cleanDescription ?: 'Deskripsi tidak tersedia' }}
                                        </p>

                                        <!-- Price and Stock -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <span class="text-lg font-bold text-amber-600">Rp {{ number_format($productPrice, 0, ',', '.') }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Harga</p>
                                            </div>
                                            <div class="text-right">
                                                <span class="text-sm font-semibold text-gray-900 {{ $productStock <= 10 ? 'text-amber-600' : '' }}">
                                                    {{ number_format($productStock, 0, ',', '.') }}
                                                </span>
                                                <p class="text-xs text-gray-500">Stok</p>
                                            </div>
                                        </div>

                                        <!-- Product Value -->
                                        <div class="mb-3 p-2 bg-gray-50/80 rounded-lg border border-gray-100 backdrop-blur-sm">
                                            <p class="text-xs text-gray-600">Nilai Inventori:</p>
                                            <p class="text-sm font-semibold text-amber-600">Rp {{ number_format($productValue, 0, ',', '.') }}</p>
                                        </div>

                                        <!-- STOCK MANAGEMENT SECTION -->
                                        @if (count($skus) > 0)
                                            <div class="mb-3 p-3 bg-blue-50/80 rounded-lg border border-blue-200 backdrop-blur-sm">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs font-medium text-blue-700">Kelola Stok per Varian</p>
                                                    <i class='bx bx-package text-blue-500'></i>
                                                </div>

                                                @foreach ($skus as $index => $sku)
                                                    @php
                                                        $skuStock = 0;
                                                        $skuId = $sku['id'] ?? '';
                                                        $warehouseId = null;

                                                        if (isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                                                            foreach ($sku['stock_info'] as $inv) {
                                                                $skuStock += $inv['available_stock'] ?? 0;
                                                                $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                                            }
                                                        } elseif (isset($sku['inventory']) && is_array($sku['inventory'])) {
                                                            foreach ($sku['inventory'] as $inv) {
                                                                $skuStock += $inv['quantity'] ?? 0;
                                                                $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                                            }
                                                        }

                                                        if ($skuStock === 0) {
                                                            $skuStock = $productStock;
                                                        }
                                                    @endphp

                                                    @if ($skuId && $warehouseId)
                                                        <div class="stock-item flex items-center justify-between py-2 {{ $index > 0 ? 'border-t border-blue-100' : '' }}">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-medium text-gray-700 truncate">
                                                                    {{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">Stok: <span class="sku-stock-display">{{ $skuStock }}</span></p>
                                                            </div>
                                                            <div class="flex items-center space-x-2 ml-3">
                                                                <div class="relative">
                                                                    <input type="number" min="0" value="{{ $skuStock }}"
                                                                        class="stock-input w-20 px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                        data-sku-id="{{ $skuId }}"
                                                                        data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                        data-warehouse-id="{{ $warehouseId }}"
                                                                        data-original-stock="{{ $skuStock }}"
                                                                        placeholder="Qty">
                                                                </div>
                                                                <button class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    data-sku-id="{{ $skuId }}"
                                                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                    data-warehouse-id="{{ $warehouseId }}"
                                                                    title="Update stok ke TikTok Shop">
                                                                    <i class='bx bx-upload text-xs mr-1'></i>
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <!-- Fallback jika tidak ada SKUs -->
                                            <div class="mb-3 p-3 bg-gray-50/80 rounded-lg border border-gray-200 backdrop-blur-sm">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs font-medium text-gray-700">Kelola Stok Produk</p>
                                                    <i class='bx bx-package text-gray-500'></i>
                                                </div>
                                                <div class="stock-item flex items-center justify-between py-2">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-700">Stok Utama</p>
                                                        <p class="text-xs text-gray-500">Stok: <span class="sku-stock-display">{{ $productStock }}</span></p>
                                                    </div>
                                                    <div class="flex items-center space-x-2 ml-3">
                                                        <div class="relative">
                                                            <input type="number" min="0" value="{{ $productStock }}"
                                                                class="stock-input w-20 px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                data-sku-id="default"
                                                                data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                data-warehouse-id="default_warehouse"
                                                                data-original-stock="{{ $productStock }}"
                                                                placeholder="Qty">
                                                        </div>
                                                        <button class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                                            data-sku-id="default"
                                                            data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                            data-warehouse-id="default_warehouse"
                                                            title="Update stok ke TikTok Shop">
                                                            <i class='bx bx-upload text-xs mr-1'></i>
                                                            Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Actions -->
                                        <a href="{{ route('overview.products.detail', ['id' => $product['tiktok_product_id'] ?? ($product['id'] ?? '')]) }}">
                                            <div class="flex space-x-2">
                                                <button class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-lg font-medium transition-all duration-200 hover:shadow-md flex items-center justify-center text-sm edit-product-btn"
                                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}">
                                                    <i class='bx bx-edit mr-1.5'></i>
                                                    <span>Cek Produk</span>
                                                </button>
                                            </div>
                                        </a>

                                        <!-- Product Meta -->
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <p class="text-xs text-gray-500">TikTok ID: {{ $product['tiktok_product_id'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">Database ID: {{ $product['id'] ?? 'N/A' }}</p>
                                            @if (isset($product['synced_at']))
                                                <p class="text-xs text-gray-400">Synced: {{ \Carbon\Carbon::parse($product['synced_at'])->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                                <i class='bx bx-package text-3xl text-gray-400'></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Produk</h3>
                            <p class="text-gray-600 mb-6">Mulai bangun katalog produk batik eksklusif Anda.</p>
                            @if (isset($products['error']))
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 max-w-md mx-auto">
                                    <p class="text-red-700 text-sm">Sync Error: {{ $products['error'] }}</p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- No Results State -->
                <div id="noResults" class="hidden text-center py-16">
                    <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <i class='bx bx-search text-xl text-gray-300'></i>
                    </div>
                    <p class="text-gray-500 font-medium text-sm">Produk tidak ditemukan</p>
                    <p class="text-xs text-gray-400 mt-1">Coba ubah kata kunci pencarian atau filter yang Anda gunakan</p>
                </div>
            </div>

            <!-- Pagination & Summary -->
            @if (isset($products['products']) && count($products['products']) > 0)
            <div class="px-4 lg:px-6 py-4 border-t border-gray-200 bg-gradient-to-r from-gray-50/50 to-white/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm text-gray-700 mb-4 sm:mb-0">
                        Menampilkan <span class="font-medium">1-{{ count($products['products'] ?? []) }}</span> dari <span class="font-medium">{{ $totalProducts }}</span> produk
                    </p>
                    <div class="flex items-center space-x-3">
                        <button class="px-4 py-2 border border-gray-300 rounded-xl text-sm font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class='bx bx-chevron-left mr-2'></i>
                            Sebelumnya
                        </button>
                        <div class="flex items-center space-x-1">
                            <button class="w-9 h-9 bg-amber-500 text-white rounded-lg text-sm font-medium">1</button>
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
        <a href="{{ route('products_menu') }}" class="flex flex-col items-center justify-center p-2 text-amber-600 rounded-lg bg-amber-50/80 backdrop-blur-sm">
            <i class='bx bx-package text-lg mb-0.5'></i>
            <span class="text-xs font-medium">Produk</span>
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

<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2 w-full max-w-xs"></div>

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

    /* Glass morphism effect */
    .glass-effect {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Stock Management Styles */
    .stock-input:focus {
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .update-stock-btn {
        transition: all 0.2s ease-in-out;
    }

    .update-stock-btn:not(:disabled):hover {
        transform: translateY(-1px);
    }

    .stock-item {
        transition: background-color 0.2s ease;
    }

    .stock-item:hover {
        background-color: rgba(59, 130, 246, 0.05);
    }

    /* Loading state for update button */
    .update-stock-btn.loading {
        position: relative;
        color: transparent !important;
        pointer-events: none;
    }

    .update-stock-btn.loading::after {
        content: '';
        position: absolute;
        width: 16px;
        height: 16px;
        top: 50%;
        left: 50%;
        margin-left: -8px;
        margin-top: -8px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-right-color: transparent;
        animation: spin 0.8s linear infinite;
    }

    /* Success state */
    .update-stock-btn.success {
        background-color: #10b981 !important;
    }

    .update-stock-btn.success:hover {
        background-color: #059669 !important;
    }

    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    .hidden {
        display: none !important;
    }

    /* Elegant animations */
    .fade-in-up {
        animation: fadeInUp 0.5s ease-out;
    }

    /* Loading animation */
    .animate-spin {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    /* Smooth hover effects */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }

    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }

    .hover\:-translate-y-1:hover {
        transform: translateY(-4px);
    }

    /* Backdrop blur for modern look */
    .backdrop-blur-sm {
        backdrop-filter: blur(8px);
    }

    /* Gradient text effect */
    .bg-clip-text {
        -webkit-background-clip: text;
        background-clip: text;
    }
</style>

<script>
(function() {
  'use strict';

  // ======== KONFIGURASI ========
  const UPDATE_URL = '/tiktok/inventory/update-single';

  // ======== UTIL ========
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  function getCsrf() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
  }

  function showNotification(message, type = 'info') {
    const container = document.getElementById('notificationContainer');
    if (!container) return;

    const typeStyles = {
      success: 'bg-gradient-to-r from-emerald-500 to-emerald-600 text-white shadow-md',
      error:   'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-md',
      warning: 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-md',
      info:    'bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-md'
    };
    const icons = {
      success: 'bx-check-circle',
      error:   'bx-error',
      warning: 'bx-error-alt',
      info:    'bx-info-circle'
    };

    const el = document.createElement('div');
    el.className = `px-4 py-3 rounded-xl shadow-lg transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]}`;
    el.innerHTML = `
      <div class="flex items-center">
        <i class="bx ${icons[type]} mr-2 text-lg"></i>
        <span class="text-sm font-medium flex-1">${message}</span>
        <button class="ml-4 text-white hover:text-gray-200 transition-colors" aria-label="Close">
          <i class="bx bx-x text-lg"></i>
        </button>
      </div>
    `;
    el.querySelector('button').addEventListener('click', () => el.remove());

    container.appendChild(el);
    requestAnimationFrame(() => {
      el.classList.remove('translate-x-full','opacity-0');
      el.classList.add('translate-x-0','opacity-100');
    });
    setTimeout(() => {
      el.classList.remove('translate-x-0','opacity-100');
      el.classList.add('translate-x-full','opacity-0');
      setTimeout(() => el.remove(), 300);
    }, 5000);
  }

  function setBtnLoading(btn, isLoading) {
    if (isLoading) {
      btn.dataset.prevHtml = btn.innerHTML;
      btn.classList.add('loading');
      btn.disabled = true;
      btn.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-1"></i>Loading...';
    } else {
      btn.classList.remove('loading');
      btn.innerHTML = btn.dataset.prevHtml || '<i class="bx bx-upload text-xs mr-1"></i>Update';
      delete btn.dataset.prevHtml;
    }
  }

  function disableBtnAccordingToValues(btn, input) {
    const cur = parseInt(input.value, 10);
    const orig = parseInt(input.dataset.originalStock, 10);
    btn.disabled = (cur === orig);
  }

  // ======== CORE: UPDATE STOK ========
  async function updateTikTokStock({skuId, productId, warehouseId, newStock, button, input}) {
    const csrf = getCsrf();
    if (!csrf) {
      showNotification('CSRF token tidak ditemukan', 'error');
      return;
    }

    const payload = {
      sku_id: skuId,
      product_id: productId,
      warehouse_id: warehouseId,
      new_stock: newStock
    };

    try {
      setBtnLoading(button, true);

      const res = await fetch(UPDATE_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          'Accept': 'application/json'
        },
        body: JSON.stringify(payload)
      });

      let data;
      try { data = await res.json(); } catch { data = {}; }

      if (!res.ok || !data?.success) {
        const msg = data?.message || `HTTP ${res.status}`;
        throw new Error(msg);
      }

      // Sukses
      input.dataset.originalStock = String(newStock);
      const stockDisplay = input.closest('.stock-item')?.querySelector('.sku-stock-display');
      if (stockDisplay) stockDisplay.textContent = Number(newStock).toLocaleString();

      button.classList.add('success');
      button.innerHTML = '<i class="bx bx-check text-xs mr-1"></i>Berhasil';
      showNotification(`Stok updated ke ${newStock} (SKU ${skuId})`, 'success');

      setTimeout(() => {
        button.classList.remove('success');
        button.innerHTML = '<i class="bx bx-upload text-xs mr-1"></i>Update';
        disableBtnAccordingToValues(button, input);
      }, 1800);
    } catch (err) {
      showNotification(`Gagal update stok: ${err.message}`, 'error');
      disableBtnAccordingToValues(button, input);
    } finally {
      setBtnLoading(button, false);
    }
  }

  // ======== INISIALISASI STOCK WIDGET ========
  function initializeStockManagement() {
    const inputs = $$('.stock-input');
    const buttons = $$('.update-stock-btn');

    // Validasi input: non-negatif
    inputs.forEach(input => {
      input.addEventListener('input', () => {
        const v = parseInt(input.value, 10);
        if (isNaN(v) || v < 0) input.value = '0';

        const item = input.closest('.stock-item');
        if (!item) return;
        const btn = item.querySelector('.update-stock-btn');
        if (btn) disableBtnAccordingToValues(btn, input);
      });
    });

    // Set state awal tombol
    buttons.forEach(btn => {
      const item = btn.closest('.stock-item');
      if (!item) return;
      const input = item.querySelector('.stock-input');
      if (input) disableBtnAccordingToValues(btn, input);
    });

    // Klik update per baris
    buttons.forEach(btn => {
      btn.addEventListener('click', () => {
        const skuId = btn.dataset.skuId;
        const productId = btn.dataset.productId;
        const warehouseId = btn.dataset.warehouseId;

        const row = btn.closest('.stock-item');
        const input = row?.querySelector(`.stock-input[data-sku-id="${CSS.escape(skuId)}"][data-warehouse-id="${CSS.escape(warehouseId)}"][data-product-id="${CSS.escape(productId)}"]`)
                  || row?.querySelector('.stock-input');

        if (!input) {
          showNotification('Input stok tidak ditemukan untuk baris ini', 'error');
          return;
        }

        const newStock = parseInt(input.value, 10);
        const originalStock = parseInt(input.dataset.originalStock, 10);

        if (isNaN(newStock) || newStock < 0) {
          showNotification('Masukkan jumlah stok yang valid', 'error');
          return;
        }
        if (newStock === originalStock) {
          showNotification('Tidak ada perubahan stok', 'warning');
          disableBtnAccordingToValues(btn, input);
          return;
        }

        updateTikTokStock({
          skuId, productId, warehouseId, newStock, button: btn, input
        });
      });
    });
  }

  // ======== INISIALISASI FITUR LAIN (SEARCH/ETC) ========
  function initializeProductsSearchAndStats() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const searchInfo = document.getElementById('searchInfo');
    const searchQuery = document.getElementById('searchQuery');
    const searchResultsCount = document.getElementById('searchResultsCount');
    const clearSearch = document.getElementById('clearSearch');
    const resetSearch = document.getElementById('resetSearch');
    const searchLoading = document.getElementById('searchLoading');
    const productsGrid = document.getElementById('productsGrid');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');
    const productsCount = document.getElementById('productsCount');
    const visibleProductsCount = document.getElementById('visibleProductsCount');
    const editButtons = $$('.edit-product-btn');

    let allProducts = [];
    let searchTimeout;

    function initializeProducts() {
      const productCards = $$('.product-card');
      allProducts = productCards.map(card => ({
        element: card,
        title: (card.dataset.title || '').toLowerCase(),
        description: (card.dataset.description || '').toLowerCase(),
        status: card.dataset.status || '',
        price: parseInt(card.dataset.price || '0', 10),
        stock: parseInt(card.dataset.stock || '0', 10),
        value: parseInt(card.dataset.value || '0', 10),
      }));
    }

    function performSearch() {
      const term = (searchInput?.value || '').toLowerCase().trim();
      const filter = statusFilter?.value || 'all';
      if (searchLoading) searchLoading.classList.add('hidden');

      let visible = 0, totalStock = 0, totalValue = 0, active = 0;

      allProducts.forEach(p => {
        const matchTerm = !term || p.title.includes(term) || p.description.includes(term);
        const matchStatus = filter === 'all' || p.status === filter;
        const show = matchTerm && matchStatus;

        if (show) {
          p.element.classList.remove('hidden');
          visible++;
          totalStock += p.stock;
          totalValue += p.value;
          if (p.status === 'ACTIVATE') active++;
          p.element.style.animationDelay = `${visible * 0.05}s`;
          p.element.classList.add('fade-in-up');
        } else {
          p.element.classList.add('hidden');
          p.element.classList.remove('fade-in-up');
        }
      });

      if (searchInfo) {
        if (term || filter !== 'all') {
          searchInfo.classList.remove('hidden');
          let q = [];
          if (term) q.push(`"${term}"`);
          if (filter !== 'all') q.push(statusFilter.options[statusFilter.selectedIndex].text);
          if (searchQuery) searchQuery.textContent = q.join(' • ');
          if (searchResultsCount) searchResultsCount.textContent = `${visible} dari ${allProducts.length} produk ditemukan`;
        } else {
          searchInfo.classList.add('hidden');
        }
      }

      // No results state
      if (productsGrid && noResults && pagination) {
        if (visible === 0 && (term || filter !== 'all')) {
          productsGrid.classList.add('hidden');
          noResults.classList.remove('hidden');
          pagination.classList.add('hidden');
        } else {
          productsGrid.classList.remove('hidden');
          noResults.classList.add('hidden');
          pagination.classList.remove('hidden');
        }
      }

      if (productsCount) productsCount.textContent = visible + ' produk';
      if (visibleProductsCount) visibleProductsCount.textContent = visible;

      // Update statistic cards
      const statsCards = $$('.metric-card');
      if (statsCards[0]) {
        statsCards[0].querySelector('.text-lg').textContent = visible;
        statsCards[0].querySelector('.text-xs').innerHTML = `<i class='bx bx-check-circle mr-1.5 text-xs'></i>${active} aktif`;
      }
      if (statsCards[1]) {
        statsCards[1].querySelector('.text-lg').textContent = totalStock.toLocaleString();
      }
      if (statsCards[2]) {
        statsCards[2].querySelector('.text-lg').textContent = 'Rp ' + totalValue.toLocaleString();
      }
      if (statsCards[3]) {
        statsCards[3].querySelector('.text-lg').textContent = active;
      }
    }

    // Hook up listeners
    if (searchInput) {
      searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        if (searchInput.value.trim() && searchLoading) searchLoading.classList.remove('hidden');
        searchTimeout = setTimeout(performSearch, 300);
      });
      searchInput.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
          searchInput.value = '';
          performSearch();
        }
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
          e.preventDefault();
          searchInput.focus();
        }
      });
    }

    if (statusFilter) statusFilter.addEventListener('change', performSearch);
    if (clearSearch) clearSearch.addEventListener('click', () => { searchInput.value = ''; performSearch(); });
    if (resetSearch) resetSearch.addEventListener('click', () => {
      searchInput.value = '';
      if (statusFilter) statusFilter.value = 'all';
      performSearch();
    });

    document.addEventListener('keydown', e => {
      if ((e.ctrlKey || e.metaKey) && e.key === 'k' && searchInput) {
        e.preventDefault();
        searchInput.focus();
      }
    });

    // Edit product button FX
    $$('.edit-product-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        this.style.transform = 'scale(0.95)';
        setTimeout(() => { this.style.transform = 'scale(1)'; }, 150);
        const pid = this.dataset.productId;
        showNotification(`Membuka editor untuk produk ${pid}`, 'info');
      });
    });

    initializeProducts();
    performSearch();
  }

  // ======== BOOT ========
  document.addEventListener('DOMContentLoaded', () => {
    initializeStockManagement();
    initializeProductsSearchAndStats();
    
    // Update datetime
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        };
        
        const currentDay = document.getElementById('currentDay');
        const currentTime = document.getElementById('currentTime');
        
        if (currentDay) currentDay.textContent = now.toLocaleDateString('id-ID', options);
        if (currentTime) currentTime.textContent = now.toLocaleTimeString('id-ID', { 
            hour: '2-digit', 
            minute: '2-digit', 
            second: '2-digit',
            hour12: false 
        });
    }

    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    setTimeout(() => showNotification('Products management system ready', 'success'), 600);
  });
})();
</script>
@endsection