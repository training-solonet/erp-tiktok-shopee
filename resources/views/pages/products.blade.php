@extends('layouts.app')

@section('title', 'Products Management - Camellia Boutique99')
@section('subtitle', 'Kelola katalog produk batik eksklusif Anda')

@section('content')
    <div class="space-y-6">
        <!-- Header yang Elegant dengan Ornamen -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-lg p-6 relative overflow-hidden">
            <!-- Ornamen Background Elegant -->
            <div class="absolute inset-0 overflow-hidden">
                <!-- Pola geometris subtle -->
                <div
                    class="absolute top-0 left-0 w-32 h-32 border-2 border-amber-300/20 rounded-full -translate-x-16 -translate-y-16">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-40 h-40 border-2 border-amber-300/20 rounded-full translate-x-20 translate-y-20">
                </div>
                <div class="absolute top-1/2 right-1/4 w-24 h-24 border border-amber-300/30 rounded-lg rotate-45"></div>

                <!-- Garis-garis decorative -->
                <div class="absolute top-10 right-20 w-px h-20 bg-gradient-to-b from-amber-300/40 to-transparent"></div>
                <div class="absolute bottom-8 left-16 w-16 h-px bg-gradient-to-r from-amber-300/40 to-transparent"></div>

                <!-- Titik-titik ornamental -->
                <div class="absolute top-6 left-1/4 w-2 h-2 bg-amber-300/50 rounded-full"></div>
                <div class="absolute bottom-12 right-32 w-1.5 h-1.5 bg-amber-300/40 rounded-full"></div>
                <div class="absolute top-1/3 left-3/4 w-1 h-1 bg-amber-300/30 rounded-full"></div>
            </div>

            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-2xl font-bold text-white mb-2">Products Management</h1>
                        <p class="text-amber-100">Kelola dan pantau semua produk batik eksklusif dalam satu tempat</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Search Bar yang Elegant -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Cari produk..."
                                class="w-64 pl-10 pr-4 py-2.5 bg-white/95 backdrop-blur-sm border border-amber-200/30 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500 shadow-lg">
                            <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-amber-500'></i>
                            <div id="searchLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-amber-500"></div>
                            </div>
                        </div>

                        <!-- Filter Status -->
                        <select id="statusFilter"
                            class="px-3 py-2.5 text-sm bg-white/95 backdrop-blur-sm border border-amber-200/30 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-transparent transition text-gray-900 shadow-lg">
                            <option value="all">Semua Status</option>
                            <option value="ACTIVATE">Aktif</option>
                            <option value="DRAFT">Draft</option>
                            <option value="ARCHIVED">Arsip</option>
                        </select>
                    </div>
                </div>

                <!-- Search Info -->
                <div id="searchInfo" class="mt-4 hidden">
                    <div
                        class="flex items-center justify-between bg-amber-400/20 backdrop-blur-sm border border-amber-300/30 rounded-lg p-3">
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-amber-100">Hasil pencarian untuk:</span>
                            <span id="searchQuery"
                                class="text-sm font-medium text-white bg-amber-500/50 px-3 py-1 rounded-md"></span>
                            <button id="clearSearch" class="text-amber-200 hover:text-white transition-colors">
                                <i class='bx bx-x text-lg'></i>
                            </button>
                        </div>
                        <span id="searchResultsCount" class="text-sm text-amber-100"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid yang Konsisten -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <!-- Total Products -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">{{ $products['count'] ?? 0 }}</p>
                        <p class="text-xs text-emerald-600 flex items-center">
                            <i class='bx bx-up-arrow-alt mr-1'></i>
                            @php
                                $activeProducts = 0;
                                if (isset($products['products'])) {
                                    foreach ($products['products'] as $product) {
                                        if (($product['status'] ?? '') === 'ACTIVATE') {
                                            $activeProducts++;
                                        }
                                    }
                                }
                                echo $activeProducts;
                            @endphp aktif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-package text-blue-500 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Total Stock -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Stok</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">
                            @php
                                $totalStock = 0;
                                if (isset($products['products'])) {
                                    foreach ($products['products'] as $product) {
                                        // 🎯 PERUBAHAN: Ambil stock langsung dari database
                                        $totalStock += $product['stock'] ?? 0;
                                    }
                                }
                                echo number_format($totalStock);
                            @endphp
                        </p>
                        <p class="text-xs text-blue-600 flex items-center">
                            <i class='bx bx-package mr-1'></i>
                            Stok tersedia
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-check-circle text-green-500 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Inventory Value -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Nilai Inventori</p>
                        <p class="text-xl font-bold text-gray-900 mb-1">
                            @php
                                $inventoryValue = 0;
                                if (isset($products['products'])) {
                                    foreach ($products['products'] as $product) {
                                        // 🎯 PERUBAHAN: Hitung dari data database
                                        $productStock = $product['stock'] ?? 0;
                                        $productPrice = $product['price'] ?? 0;
                                        $inventoryValue += $productStock * $productPrice;
                                    }
                                }
                                echo 'Rp ' . number_format($inventoryValue, 0, ',', '.');
                            @endphp
                        </p>
                        <p class="text-xs text-amber-600 flex items-center">
                            <i class='bx bx-trending-up mr-1'></i>
                            Total nilai stok
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-dollar-circle text-amber-500 text-xl'></i>
                    </div>
                </div>
            </div>

            <!-- Active Products -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Produk Aktif</p>
                        <p class="text-2xl font-bold text-gray-900 mb-1">
                            @php
                                $activeProductsCount = 0;
                                if (isset($products['products'])) {
                                    foreach ($products['products'] as $product) {
                                        if (($product['status'] ?? '') === 'ACTIVATE') {
                                            $activeProductsCount++;
                                        }
                                    }
                                }
                                echo $activeProductsCount;
                            @endphp
                        </p>
                        <p class="text-xs text-emerald-600 flex items-center">
                            <i class='bx bx-check-circle mr-1'></i>
                            Sedang dijual
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class='bx bx-play-circle text-emerald-500 text-xl'></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid Container dengan Ornamen Subtle -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden relative">
            <!-- Ornamen corner subtle -->
            <div class="absolute top-0 right-0 w-16 h-16 border-t-2 border-r-2 border-amber-200/30 rounded-tr-2xl"></div>
            <div class="absolute bottom-0 left-0 w-16 h-16 border-b-2 border-l-2 border-amber-200/30 rounded-bl-2xl"></div>

            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-gray-100 relative z-10">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Katalog Produk</h3>
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <span id="productsCount">{{ $products['count'] ?? 0 }}</span>
                        <span>produk</span>
                        <!-- 🎯 TAMBAHAN: Info Source Data -->
                        @if (isset($products['source']))
                            <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded-full text-xs">
                                Source: {{ $products['source'] }}
                            </span>
                        @endif
                        @if (isset($products['last_sync']))
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs">
                                Last Sync: {{ $products['last_sync'] }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Products Content -->
            <div class="p-6 relative z-10">
                <div id="productsContainer">
                    @if (isset($products['products']) && count($products['products']) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productsGrid">
                            @foreach ($products['products'] as $product)
                                @php
                                    // 🎯 PERUBAHAN PENTING: Data sekarang dari database, bukan API
                                    $productData = $product;

                                    // 🎯 AMBIL LANGSUNG DARI DATABASE
                                    $productStock = $product['stock'] ?? 0;
                                    $productPrice = $product['price'] ?? 0;
                                    $productValue = $productStock * $productPrice;

                                    // Status styling yang elegant
                                    $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                                    $statusText = $product['status'] ?? 'Unknown';
                                    $statusIcon = 'bx-question-mark';

                                    if (($product['status'] ?? '') === 'ACTIVATE') {
                                        $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                                        $statusText = 'Aktif';
                                        $statusIcon = 'bx-check-circle';
                                    } elseif (($product['status'] ?? '') === 'LIMITED') {
                                        $statusClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                                        $statusText = 'Terbatas';
                                        $statusIcon = 'bx-time';
                                    } elseif (($product['status'] ?? '') === 'SOLD_OUT') {
                                        $statusClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                                        $statusText = 'Habis';
                                        $statusIcon = 'bx-x-circle';
                                    } elseif (($product['status'] ?? '') === 'DRAFT') {
                                        $statusClass = 'bg-gray-50 text-gray-700 border border-gray-200';
                                        $statusText = 'Draft';
                                        $statusIcon = 'bx-edit';
                                    } elseif (($product['status'] ?? '') === 'ARCHIVED') {
                                        $statusClass = 'bg-gray-200 text-gray-700 border border-gray-300';
                                        $statusText = 'Arsip';
                                        $statusIcon = 'bx-archive';
                                    }

                                    // Bersihkan deskripsi dari tag HTML
                                    $cleanDescription = strip_tags($product['description'] ?? '');
                                    $cleanDescription = str_replace(['&nbsp;', '&amp;'], [' ', '&'], $cleanDescription);
                                    $cleanDescription = trim($cleanDescription);

                                    // 🎯 PERUBAHAN: Ambil gambar dari database
                                    $productImage = $product['image'] ?? '';
                                    $allImages = json_decode($product['images'] ?? '[]', true) ?: [];

                                    // Jika tidak ada image utama, ambil dari array images
                                    if (empty($productImage) && count($allImages) > 0) {
                                        $productImage = $allImages[0];
                                    }

                                    // 🎯 PERUBAHAN: Decode SKUs dari database
                                    $skus = json_decode($product['skus'] ?? '[]', true) ?: [];
                                @endphp

                                <!-- Product Card yang Elegant dengan Hover Effect -->
                                <div class="product-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 group relative"
                                    data-title="{{ strtolower($product['title'] ?? '') }}"
                                    data-description="{{ strtolower($cleanDescription) }}"
                                    data-status="{{ $product['status'] ?? '' }}" data-price="{{ $productPrice }}"
                                    data-stock="{{ $productStock }}" data-value="{{ $productValue }}"
                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}">
                                    <!-- Corner accent pada hover -->
                                    <div
                                        class="absolute top-0 right-0 w-3 h-3 bg-amber-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-bl-lg">
                                    </div>

                                    <!-- Product Image -->
                                    <div class="h-56 relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                        @if (!empty($productImage))
                                            <img src="{{ $productImage }}"
                                                alt="{{ $product['title'] ?? 'Product Image' }}"
                                                class="w-full h-full object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                                style="object-position: center 10%;"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                loading="lazy">
                                            <div
                                                class="w-full h-full hidden items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <i class='bx bx-package text-gray-400 text-4xl'></i>
                                            </div>
                                        @else
                                            <div
                                                class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                                                <i class='bx bx-package text-gray-400 text-4xl'></i>
                                            </div>
                                        @endif

                                        <!-- Status Badge -->
                                        <div class="absolute top-3 right-3">
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                                <i class='bx {{ $statusIcon }} mr-1'></i>
                                                {{ $statusText }}
                                            </span>
                                        </div>

                                        <!-- Low Stock Warning -->
                                        @if ($productStock > 0 && $productStock <= 10)
                                            <div class="absolute bottom-3 left-3">
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
                                                    <i class='bx bx-error-alt mr-1'></i>Stok Menipis
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="p-4">
                                        <h3
                                            class="font-semibold text-gray-900 text-sm mb-2 line-clamp-2 leading-tight group-hover:text-amber-700 transition-colors">
                                            {{ $product['title'] ?? 'N/A' }}
                                        </h3>

                                        <p class="text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed">
                                            {{ $cleanDescription ?: 'Deskripsi tidak tersedia' }}
                                        </p>

                                        <!-- Price and Stock -->
                                        <div class="flex items-center justify-between mb-3">
                                            <div>
                                                <span class="text-lg font-bold text-amber-600">Rp
                                                    {{ number_format($productPrice, 0, ',', '.') }}</span>
                                                <p class="text-xs text-gray-500 mt-1">Harga</p>
                                            </div>
                                            <div class="text-right">
                                                <span
                                                    class="text-sm font-semibold text-gray-900 {{ $productStock <= 10 ? 'text-amber-600' : '' }}">
                                                    {{ number_format($productStock, 0, ',', '.') }}
                                                </span>
                                                <p class="text-xs text-gray-500">Stok Tersedia</p>
                                            </div>
                                        </div>

                                        <!-- Product Value -->
                                        <div class="mb-3 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                            <p class="text-xs text-gray-600">Nilai Inventori:</p>
                                            <p class="text-sm font-semibold text-amber-600">Rp
                                                {{ number_format($productValue, 0, ',', '.') }}</p>
                                        </div>

                                        <!-- STOCK MANAGEMENT SECTION - MENGGUNAKAN DATA DATABASE -->
                                        @if (count($skus) > 0)
                                            <div class="mb-3 p-3 bg-blue-50 rounded-lg border border-blue-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs font-medium text-blue-700">Kelola Stok per Varian</p>
                                                    <i class='bx bx-package text-blue-500'></i>
                                                </div>

                                                @foreach ($skus as $index => $sku)
                                                    @php
                                                        $skuStock = 0;
                                                        $skuId = $sku['id'] ?? '';
                                                        $warehouseId = null;

                                                        // 🎯 PERUBAHAN: Hitung stok dari struktur database
                                                        if (isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                                                            foreach ($sku['stock_info'] as $inv) {
                                                                $skuStock += $inv['available_stock'] ?? 0;
                                                                $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                                            }
                                                        } elseif (
                                                            isset($sku['inventory']) &&
                                                            is_array($sku['inventory'])
                                                        ) {
                                                            foreach ($sku['inventory'] as $inv) {
                                                                $skuStock += $inv['quantity'] ?? 0;
                                                                $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                                            }
                                                        }

                                                        // Fallback: jika tidak ada struktur inventory, gunakan stock utama
                                                        if ($skuStock === 0) {
                                                            $skuStock = $productStock;
                                                        }
                                                    @endphp

                                                    @if ($skuId && $warehouseId)
                                                        <div
                                                            class="stock-item flex items-center justify-between py-2 {{ $index > 0 ? 'border-t border-blue-100' : '' }}">
                                                            <div class="flex-1 min-w-0">
                                                                <p class="text-xs font-medium text-gray-700 truncate">
                                                                    {{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">Stok: <span
                                                                        class="sku-stock-display">{{ $skuStock }}</span>
                                                                </p>
                                                                <p class="text-xs text-gray-400">Warehouse:
                                                                    {{ $warehouseId }}</p>
                                                            </div>
                                                            <div class="flex items-center space-x-2 ml-3">
                                                                <!-- Stock Input -->
                                                                <div class="relative">
                                                                    <input type="number" min="0"
                                                                        value="{{ $skuStock }}"
                                                                        class="stock-input w-20 px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                        data-sku-id="{{ $skuId }}"
                                                                        data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                        data-warehouse-id="{{ $warehouseId }}"
                                                                        data-original-stock="{{ $skuStock }}"
                                                                        placeholder="Qty">
                                                                </div>
                                                                <!-- Update Button -->
                                                                <button
                                                                    class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
                                                                    data-sku-id="{{ $skuId }}"
                                                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                    data-warehouse-id="{{ $warehouseId }}"
                                                                    title="Update stok ke TikTok Shop">
                                                                    <i class='bx bx-upload text-xs mr-1'></i>
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="py-2 text-xs text-red-500">
                                                            ⚠️ Varian {{ $index + 1 }}: SKU ID atau Warehouse ID tidak
                                                            valid
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @else
                                            <!-- Fallback jika tidak ada SKUs -->
                                            <div class="mb-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-xs font-medium text-gray-700">Kelola Stok Produk</p>
                                                    <i class='bx bx-package text-gray-500'></i>
                                                </div>
                                                <div class="stock-item flex items-center justify-between py-2">
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-medium text-gray-700">Stok Utama</p>
                                                        <p class="text-xs text-gray-500">Stok: <span
                                                                class="sku-stock-display">{{ $productStock }}</span></p>
                                                    </div>
                                                    <div class="flex items-center space-x-2 ml-3">
                                                        <div class="relative">
                                                            <input type="number" min="0"
                                                                value="{{ $productStock }}"
                                                                class="stock-input w-20 px-2 py-1 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                                                                data-sku-id="default"
                                                                data-product-id="{{ $product['tiktok_product_id'] ?? '' }}"
                                                                data-warehouse-id="default_warehouse"
                                                                data-original-stock="{{ $productStock }}"
                                                                placeholder="Qty">
                                                        </div>
                                                        <button
                                                            class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed"
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
                                        <a
                                            href="{{ route('overview.products.detail', ['id' => $product['tiktok_product_id'] ?? ($product['id'] ?? '')]) }}">
                                            <div class="flex space-x-2">
                                                <button
                                                    class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-2.5 rounded-lg font-medium transition-all duration-200 hover:shadow-md flex items-center justify-center text-sm edit-product-btn"
                                                    data-product-id="{{ $product['tiktok_product_id'] ?? '' }}">
                                                    <i class='bx bx-edit mr-1.5'></i>
                                                    <span>Cek Produk</span>
                                                </button>
                                                <button
                                                    class="w-12 h-12 border border-gray-300 hover:border-amber-400 hover:bg-amber-50 rounded-lg flex items-center justify-center transition-all duration-200 group/action">
                                                    <i
                                                        class='bx bx-dots-vertical-rounded text-gray-600 group-hover/action:text-amber-600'></i>
                                                </button>
                                            </div>
                                        </a>
                                        <!-- Product Meta -->
                                        <div class="mt-3 pt-3 border-t border-gray-100">
                                            <p class="text-xs text-gray-500">TikTok ID:
                                                {{ $product['tiktok_product_id'] ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500">Database ID: {{ $product['id'] ?? 'N/A' }}
                                            </p>
                                            @if (isset($product['synced_at']))
                                                <p class="text-xs text-gray-400">Synced:
                                                    {{ \Carbon\Carbon::parse($product['synced_at'])->diffForHumans() }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State yang Elegant -->
                        <div class="text-center py-16">
                            <div
                                class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full mb-6 border border-amber-200">
                                <i class='bx bx-package text-3xl text-amber-500'></i>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Produk</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Mulai bangun katalog produk batik
                                eksklusif Anda.</p>
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
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
                        <i class='bx bx-search text-3xl text-gray-400'></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Produk Tidak Ditemukan</h3>
                    <p class="text-gray-600 mb-6">Coba ubah kata kunci pencarian atau filter yang Anda gunakan.</p>
                    <button id="resetSearch"
                        class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center">
                        <i class='bx bx-refresh mr-2'></i>Reset Pencarian
                    </button>
                </div>

                <!-- Pagination dengan Style Elegant -->
                <div id="pagination"
                    class="flex flex-col sm:flex-row items-center justify-between pt-6 mt-6 border-t border-gray-200">
                    <p class="text-sm text-gray-600 mb-3 sm:mb-0">
                        Menampilkan <span class="font-semibold text-amber-600"
                            id="visibleProductsCount">{{ $products['count'] ?? 0 }}</span> dari
                        <span class="font-semibold">{{ $products['count'] ?? 0 }}</span> produk
                    </p>
                    <div class="flex items-center space-x-2">
                        <button
                            class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg hover:bg-amber-50 hover:border-amber-300 transition-colors duration-200 flex items-center">
                            <i class='bx bx-chevron-left mr-2'></i>
                            Sebelumnya
                        </button>
                        <button
                            class="px-4 py-2.5 text-sm bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors">1</button>
                        <button
                            class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg hover:bg-amber-50 hover:border-amber-300 transition-colors duration-200 flex items-center">
                            Selanjutnya
                            <i class='bx bx-chevron-right ml-2'></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>
@endsection

<style>
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

    /* Loading state for update button - DIPERBAIKI */
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

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .transition-all {
        transition: all 0.3s ease-in-out;
    }

    .hidden {
        display: none !important;
    }

    /* Elegant animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

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

    /* Improved image display for fashion products */
    .object-center {
        object-position: center 30%;
    }
</style>

<script>
(function() {
  'use strict';

  // ======== KONFIGURASI ========
  const UPDATE_URL = '/tiktok/inventory/update-single'; // ganti kalau route kamu beda

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
      success: 'bg-emerald-500 text-white border border-emerald-600',
      error:   'bg-red-500 text-white border border-red-600',
      warning: 'bg-amber-500 text-white border border-amber-600',
      info:    'bg-blue-500 text-white border border-blue-600'
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

      // Coba baca body apa pun statusnya biar dapet pesan backend
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
      // Balikkan tombol sesuai nilai saat ini
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

        // Cari tombol di BARIS yang sama biar gak ketuker gudang lain
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
        // Ambil atribut dari tombol (sumber paling tepercaya)
        const skuId = btn.dataset.skuId;
        const productId = btn.dataset.productId;
        const warehouseId = btn.dataset.warehouseId;

        // Cari input yang match DI DALAM baris yang sama, bukan query global
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

  // ======== INISIALISASI FITUR LAIN (SEARCH/ETC) TETAP PUNYAMU ========
  function initializeProductsSearchAndStats() {
    // Narik elemen yang ada di view kamu.
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

      if (productsCount) productsCount.textContent = visible;
      if (visibleProductsCount) visibleProductsCount.textContent = visible;

      // Update statistic cards (opsional, sesuai punyamu)
      const statsCards = $$('.bg-white.rounded-xl');
      if (statsCards[0]) {
        const c = statsCards[0].querySelector('.text-2xl');
        const a = statsCards[0].querySelector('.text-xs');
        if (c) c.textContent = visible;
        if (a) a.innerHTML = `<i class='bx bx-up-arrow-alt mr-1'></i>${active} aktif`;
      }
      if (statsCards[1]) {
        const c = statsCards[1].querySelector('.text-2xl');
        if (c) c.textContent = totalStock.toLocaleString();
      }
      if (statsCards[2]) {
        const c = statsCards[2].querySelector('.text-xl');
        if (c) c.textContent = `Rp ${totalValue.toLocaleString()}`;
      }
      if (statsCards[3]) {
        const c = statsCards[3].querySelector('.text-2xl');
        if (c) c.textContent = active;
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
    setTimeout(() => showNotification('Products management system ready', 'success'), 600);
  });
})();
</script>
