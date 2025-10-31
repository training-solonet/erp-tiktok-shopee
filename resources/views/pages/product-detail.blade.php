@extends('layouts.app')

@section('title', 'Product Overview - Camellia Boutique99')
@section('subtitle', 'Detail lengkap produk batik eksklusif')

@section('content')
<div class="lg:ml-4 space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('overview.products') }}" class="hover:text-amber-600 transition-colors">
            <i class='bx bx-package mr-1'></i>Products
        </a>
        <i class='bx bx-chevron-right text-gray-400'></i>
        <span class="text-gray-900 font-medium">Product Overview</span>
    </nav>

    @if($success && $product)
        <!-- Main Content -->
        <div id="productOverview" class="space-y-6">
            @php
                // Data langsung dari database model
                $productData = $product;
                
                // Decode data JSON dari database
                $skus = json_decode($productData->skus ?? '[]', true) ?: [];
                $images = json_decode($productData->images ?? '[]', true) ?: [];
                
                // DEBUG: Tampilkan struktur data asli
                // echo "<!-- DEBUG: Main Image: " . ($productData->image ?? 'NULL') . " -->";
                // echo "<!-- DEBUG: Images Array: " . json_encode($images) . " -->";
                
                // SOLUSI RADICAL: Bersihkan semua gambar dan buat dari awal
                $allImages = [];
                
                // Step 1: Ambil main image jika ada dan valid
                $mainImage = $productData->image ?? '';
                if (!empty($mainImage) && is_string($mainImage)) {
                    $allImages[] = $mainImage;
                }
                
                // Step 2: Process images array dengan cara yang lebih agresif
                if (is_array($images) && count($images) > 0) {
                    foreach ($images as $image) {
                        // Validasi ketat: harus string, tidak kosong, dan belum ada di array
                        if (!empty($image) && is_string($image) && !in_array($image, $allImages)) {
                            $allImages[] = $image;
                        }
                    }
                }
                
                // Step 3: Hapus duplikasi akhir dengan cara yang lebih kuat
                $allImages = array_values(array_unique($allImages));
                
                // Hitung metrics
                $originalCount = count($images) + (!empty($mainImage) ? 1 : 0);
                $finalCount = count($allImages);
                $duplicatesRemoved = $originalCount - $finalCount;
                
                // Hitung metrics dari data database
                $totalStock = $productData->stock ?? 0;
                $totalValue = ($productData->price ?? 0) * $totalStock;
                $skuCount = count($skus);
                $averagePrice = $productData->price ?? 0;
                
                // Tentukan tingkat stok
                if ($totalStock == 0) {
                    $stockLevel = 'Habis';
                    $stockColor = 'red';
                } elseif ($totalStock <= 10) {
                    $stockLevel = 'Rendah';
                    $stockColor = 'amber';
                } elseif ($totalStock <= 50) {
                    $stockLevel = 'Sedang';
                    $stockColor = 'blue';
                } else {
                    $stockLevel = 'Aman';
                    $stockColor = 'emerald';
                }

                $metrics = [
                    'total_stock' => $totalStock,
                    'sku_count' => $skuCount,
                    'average_price' => $averagePrice,
                    'total_value' => $totalValue,
                    'stock_level' => $stockLevel,
                    'stock_color' => $stockColor
                ];
                
                // Status styling
                $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                $statusText = $productData->status ?? 'Unknown';
                $statusIcon = 'bx-question-mark';
                
                if(($productData->status ?? '') === 'ACTIVATE') {
                    $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                    $statusText = 'Aktif';
                    $statusIcon = 'bx-check-circle';
                } elseif(($productData->status ?? '') === 'LIMITED') {
                    $statusClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                    $statusText = 'Terbatas';
                    $statusIcon = 'bx-time';
                } elseif(($productData->status ?? '') === 'SOLD_OUT') {
                    $statusClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                    $statusText = 'Habis';
                    $statusIcon = 'bx-x-circle';
                } elseif(($productData->status ?? '') === 'DRAFT') {
                    $statusClass = 'bg-gray-50 text-gray-700 border border-gray-200';
                    $statusText = 'Draft';
                    $statusIcon = 'bx-edit';
                } elseif(($productData->status ?? '') === 'ARCHIVED') {
                    $statusClass = 'bg-gray-200 text-gray-700 border border-gray-300';
                    $statusText = 'Arsip';
                    $statusIcon = 'bx-archive';
                }

                // Bersihkan deskripsi
                $cleanDescription = strip_tags($productData->description ?? '');
                $cleanDescription = str_replace(['&nbsp;', '&amp;'], [' ', '&'], $cleanDescription);
                $cleanDescription = trim($cleanDescription);
            @endphp

            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium {{ $statusClass }}">
                                <i class='bx {{ $statusIcon }} mr-2'></i>
                                {{ $statusText }}
                            </span>
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                                <i class='bx bx-barcode mr-1'></i>
                                TikTok ID: {{ $productData->tiktok_product_id ?? 'N/A' }}
                            </span>
                            <span class="text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                                <i class='bx bx-data mr-1'></i>
                                DB ID: {{ $productData->id }}
                            </span>
                        </div>
                        
                        <h1 class="text-2xl lg:text-4xl font-bold text-gray-900 mb-4 text-center lg:text-left leading-tight">
                            {{ $productData->title ?? 'N/A' }}
                        </h1>
                        
                        <!-- Info Sync Status -->
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mt-4">
                            @if($productData->synced_at)
                            <span class="flex items-center bg-blue-50 px-3 py-2 rounded-lg">
                                <i class='bx bx-time mr-2 text-blue-600'></i>
                                Terakhir sync: {{ \Carbon\Carbon::parse($productData->synced_at)->diffForHumans() }}
                            </span>
                            @endif
                            <span class="flex items-center bg-green-50 px-3 py-2 rounded-lg">
                                <i class='bx bx-data mr-2 text-green-600'></i>
                                Sumber: Database
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ route('overview.products') }}" 
                           class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover:shadow-lg flex items-center shadow-md">
                            <i class='bx bx-arrow-back mr-2'></i>Kembali ke Produk
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image Gallery Section - COMPLETELY REWRITTEN -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl lg:text-2xl font-bold text-gray-900 flex items-center">
                        <i class='bx bx-images mr-3 text-amber-600 text-2xl'></i>Galeri Produk
                    </h2>
                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-2 rounded-lg">
                            <i class='bx bx-image mr-1'></i>
                            {{ count($allImages) }} Gambar Unik
                        </span>
                        @if($duplicatesRemoved > 0)
                        <span class="text-sm text-amber-600 bg-amber-50 px-3 py-2 rounded-lg border border-amber-200">
                            <i class='bx bx-check-shield mr-1'></i>
                            {{ $duplicatesRemoved }} duplikasi dihapus
                        </span>
                        @endif
                    </div>
                </div>
                
                @if(count($allImages) > 0)
                    <!-- Debug Info -->
                    <div class="mb-4 p-3 bg-blue-50 rounded-lg text-sm text-blue-700">
                        <strong>Debug Info:</strong> Menampilkan {{ count($allImages) }} gambar unik dari 
                        {{ $originalCount }} gambar yang ditemukan di database.
                    </div>

                    <!-- Horizontal Scroll Container -->
                    <div class="relative">
                        <!-- Navigation Arrows -->
                        <button class="carousel-prev absolute left-2 top-1/2 transform -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-gray-700 hover:text-amber-600 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-gray-200 hover:border-amber-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                aria-label="Previous image">
                            <i class='bx bx-chevron-left text-xl'></i>
                        </button>
                        
                        <button class="carousel-next absolute right-2 top-1/2 transform -translate-y-1/2 z-10 bg-white/80 hover:bg-white text-gray-700 hover:text-amber-600 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-gray-200 hover:border-amber-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                aria-label="Next image">
                            <i class='bx bx-chevron-right text-xl'></i>
                        </button>

                        <!-- Image Carousel -->
                        <div class="carousel-container overflow-x-auto scrollbar-hide snap-x snap-mandatory scroll-smooth"
                             style="scrollbar-width: none; -ms-overflow-style: none;">
                            <div class="flex space-x-4 pb-4 min-h-96">
                                @foreach($allImages as $index => $imageUrl)
                                    <div class="carousel-slide flex-none w-80 md:w-96 lg:w-[28rem] snap-start">
                                        <div class="group relative bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 h-full">
                                            <div class="aspect-square w-full bg-gray-100 flex items-center justify-center">
                                                <img src="{{ $imageUrl }}" 
                                                     alt="{{ $productData->title ?? 'Product Image' }} - {{ $index + 1 }}"
                                                     class="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                     loading="lazy">
                                                <div class="w-full h-full hidden items-center justify-center bg-gradient-to-br from-gray-200 to-gray-300">
                                                    <div class="text-center">
                                                        <i class='bx bx-image text-gray-400 text-4xl mb-2'></i>
                                                        <p class="text-gray-500 text-sm">Gagal memuat gambar</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Overlay dengan informasi -->
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-end justify-start p-4">
                                                <span class="text-white font-medium text-sm bg-black/70 px-3 py-2 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300 backdrop-blur-sm">
                                                    Gambar {{ $index + 1 }} dari {{ count($allImages) }}
                                                </span>
                                            </div>
                                            
                                            @if($index === 0 && !empty($mainImage) && $imageUrl === $mainImage)
                                                <span class="absolute top-3 left-3 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg flex items-center">
                                                    <i class='bx bx-star mr-1'></i>Utama
                                                </span>
                                            @endif

                                            <!-- Zoom Button -->
                                            <button class="carousel-zoom absolute top-3 right-3 bg-white/80 hover:bg-white text-gray-700 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200 backdrop-blur-sm border border-gray-300 hover:border-amber-300 hover:text-amber-600 opacity-0 group-hover:opacity-100"
                                                    data-image="{{ $imageUrl }}"
                                                    data-index="{{ $index }}"
                                                    aria-label="Zoom image">
                                                <i class='bx bx-zoom-in text-sm'></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Carousel Indicators -->
                    <div class="flex justify-center items-center space-x-2 mt-6">
                        @foreach($allImages as $index => $imageUrl)
                            <button class="carousel-indicator w-3 h-3 rounded-full bg-gray-300 hover:bg-gray-400 transition-all duration-200 {{ $index === 0 ? 'bg-amber-500 w-8' : '' }}"
                                    data-index="{{ $index }}"
                                    aria-label="Go to image {{ $index + 1 }}">
                            </button>
                        @endforeach
                    </div>

                    <!-- Image Counter -->
                    <div class="text-center mt-4">
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            <span class="carousel-current">1</span> dari {{ count($allImages) }} gambar unik
                        </span>
                    </div>
                @else
                    <!-- No Images State -->
                    <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                        <i class='bx bx-image text-6xl text-gray-300 mb-4'></i>
                        <p class="text-gray-500 text-lg font-medium">Tidak ada gambar produk</p>
                        <p class="text-gray-400 text-sm mt-2">Gambar akan muncul setelah sinkronisasi</p>
                    </div>
                @endif
            </div>

            <!-- Description Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-detail mr-3 text-amber-600 text-2xl'></i>Deskripsi Produk
                </h2>
                
                <div class="prose prose-lg max-w-none">
                    @if($cleanDescription)
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                            <p class="text-gray-700 leading-relaxed text-lg whitespace-pre-line">
                                {{ $cleanDescription }}
                            </p>
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50 rounded-xl border-2 border-dashed border-gray-300">
                            <i class='bx bx-note text-5xl text-gray-300 mb-4'></i>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada deskripsi produk</p>
                            <p class="text-gray-400 text-sm mt-2">Deskripsi akan muncul setelah sinkronisasi</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Inventory & Pricing Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-8">
                <!-- Inventory Management -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-package mr-3 text-amber-600 text-2xl'></i>Manajemen Stok
                    </h2>
                    
                    <!-- Stock Summary -->
                    <div class="mb-6 p-6 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-blue-700 font-medium uppercase tracking-wide mb-2">Total Stok Tersedia</p>
                                <p class="text-3xl font-bold text-blue-900">{{ number_format($metrics['total_stock']) }} unit</p>
                                <p class="text-sm text-blue-600 mt-2 flex items-center">
                                    <i class='bx bx-{{ $metrics["stock_color"] }}-circle mr-1'></i>
                                    Status: <span class="font-semibold ml-1">{{ $metrics['stock_level'] }}</span>
                                </p>
                            </div>
                            <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class='bx bx-package text-3xl text-white'></i>
                            </div>
                        </div>
                    </div>
                    
                    @if(count($skus) > 0)
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 text-lg mb-4 flex items-center">
                                <i class='bx bx-list-ul mr-2 text-gray-500'></i>Stok per Varian
                            </h3>
                            @foreach($skus as $index => $sku)
                                @php
                                    $skuStock = 0;
                                    $warehouseId = null;
                                    $skuId = $sku['id'] ?? '';
                                    
                                    // Hitung stok dari struktur database
                                    if(isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                                        foreach($sku['stock_info'] as $inv) {
                                            $skuStock += $inv['available_stock'] ?? 0;
                                            $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                        }
                                    } elseif(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                        foreach($sku['inventory'] as $inv) {
                                            $skuStock += $inv['quantity'] ?? 0;
                                            $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                        }
                                    } else {
                                        // Fallback ke stock utama jika tidak ada data varian
                                        $skuStock = $totalStock;
                                        $warehouseId = 'default_warehouse';
                                    }
                                @endphp
                                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 {{ $skuStock <= 10 ? 'border-amber-200 bg-amber-50' : 'bg-white' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $sku['sku_code'] ?? 'SKU-' . ($index + 1) }}</h4>
                                                @if($skuStock <= 10)
                                                    <span class="ml-3 bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-xs font-medium">
                                                        <i class='bx bx-error-alt mr-1'></i>Menipis
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-600">
                                                @if(isset($sku['sku_attr']))
                                                    @if(is_array($sku['sku_attr']))
                                                        {{ implode(' • ', array_filter($sku['sku_attr'])) }}
                                                    @else
                                                        {{ $sku['sku_attr'] }}
                                                    @endif
                                                @else
                                                    Varian Standar
                                                @endif
                                            </p>
                                        </div>
                                        <span class="text-xl font-bold {{ $skuStock <= 10 ? 'text-amber-600' : 'text-gray-900' }} ml-4">
                                            {{ number_format($skuStock) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback untuk produk tanpa SKU detail -->
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 {{ $totalStock <= 10 ? 'border-amber-200 bg-amber-50' : 'bg-white' }}">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <div class="flex items-center mb-2">
                                        <h4 class="font-semibold text-gray-900 text-lg">Stok Utama</h4>
                                        @if($totalStock <= 10)
                                            <span class="ml-3 bg-amber-100 text-amber-800 px-2 py-1 rounded-full text-xs font-medium">
                                                <i class='bx bx-error-alt mr-1'></i>Menipis
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-600">Stok produk utama dari database</p>
                                </div>
                                <span class="text-xl font-bold {{ $totalStock <= 10 ? 'text-amber-600' : 'text-gray-900' }} ml-4">
                                    {{ number_format($totalStock) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pricing Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-dollar-circle mr-3 text-amber-600 text-2xl'></i>Informasi Harga
                    </h2>
                    
                    @if(count($skus) > 0)
                        <div class="space-y-5">
                            @foreach($skus as $index => $sku)
                                @php
                                    $originalPrice = 0;
                                    $discountPrice = 0;
                                    
                                    // Ambil harga dari struktur database
                                    if(isset($sku['price_info'])) {
                                        $originalPrice = (int)($sku['price_info']['original_price'] ?? 0);
                                        $discountPrice = (int)($sku['price_info']['discount_price'] ?? $originalPrice);
                                    } elseif(isset($sku['price'])) {
                                        $originalPrice = (int)($sku['price']['tax_exclusive_price'] ?? 0);
                                        $discountPrice = $originalPrice;
                                    } else {
                                        // Fallback ke harga utama dari database
                                        $originalPrice = $productData->price ?? 0;
                                        $discountPrice = $originalPrice;
                                    }
                                    
                                    $hasDiscount = $discountPrice < $originalPrice;
                                @endphp
                                
                                @if($originalPrice > 0)
                                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 bg-white">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    @if(isset($sku['sku_attr']))
                                                        @if(is_array($sku['sku_attr']))
                                                            {{ implode(' • ', array_filter($sku['sku_attr'])) }}
                                                        @else
                                                            {{ $sku['sku_attr'] }}
                                                        @endif
                                                    @else
                                                        Harga Standar
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <div class="space-y-3">
                                            @if($hasDiscount)
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600">Harga Normal:</span>
                                                    <span class="text-gray-500 line-through text-lg">Rp {{ number_format($originalPrice) }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600 font-medium">Harga Diskon:</span>
                                                    <span class="text-2xl font-bold text-amber-600">Rp {{ number_format($discountPrice) }}</span>
                                                </div>
                                                <div class="flex items-center justify-between text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg">
                                                    <span class="font-medium">Anda hemat:</span>
                                                    <span class="font-bold">Rp {{ number_format($originalPrice - $discountPrice) }}</span>
                                                </div>
                                            @else
                                                <div class="flex items-center justify-between">
                                                    <span class="text-gray-600 font-medium">Harga:</span>
                                                    <span class="text-2xl font-bold text-amber-600">Rp {{ number_format($originalPrice) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback: Tampilkan harga utama dari database -->
                        <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 bg-white">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 text-lg">Harga Utama</h4>
                                    <p class="text-sm text-gray-600 mt-1">Harga produk utama dari database</p>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-600 font-medium">Harga:</span>
                                    <span class="text-2xl font-bold text-amber-600">Rp {{ number_format($productData->price ?? 0) }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-stats mr-3 text-amber-600 text-2xl'></i>Statistik Produk
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-layer text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-blue-700 mb-2">Total Varian</h3>
                        <p class="text-3xl font-bold text-blue-900">{{ $metrics['sku_count'] }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-dollar text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-emerald-700 mb-2">Harga Rata-rata</h3>
                        <p class="text-2xl font-bold text-emerald-900">Rp {{ number_format($metrics['average_price']) }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-trending-up text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-amber-700 mb-2">Nilai Inventori</h3>
                        <p class="text-2xl font-bold text-amber-900">Rp {{ number_format($metrics['total_value']) }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-{{ $metrics['stock_color'] }}-50 to-{{ $metrics['stock_color'] }}-100 border border-{{ $metrics['stock_color'] }}-200 rounded-xl p-6 text-center hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="w-16 h-16 bg-{{ $metrics['stock_color'] }}-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-pie-chart-alt text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-{{ $metrics['stock_color'] }}-700 mb-2">Tingkat Stok</h3>
                        <p class="text-2xl font-bold text-{{ $metrics['stock_color'] }}-900">{{ $metrics['stock_level'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Stock Management Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 lg:p-8">
                <h2 class="text-xl lg:text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-edit mr-3 text-amber-600 text-2xl'></i>Kelola Stok
                </h2>
                
                <!-- Transaction Status Alert -->
                <div id="transactionAlert" class="hidden mb-6 p-4 rounded-xl border bg-blue-50 border-blue-200">
                    <div class="flex items-center">
                        <i class='bx bx-loader-alt animate-spin mr-3 text-xl text-blue-600'></i>
                        <div>
                            <h4 class="font-semibold text-blue-900">Memproses Update Stok</h4>
                            <p class="text-sm text-blue-700">Sedang menyinkronkan dengan TikTok Shop...</p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 lg:p-8">
                    <div class="flex items-center mb-6">
                        <i class='bx bx-info-circle text-2xl text-blue-600 mr-3'></i>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900">Update Stok Produk</h3>
                            <p class="text-sm text-blue-700">Perubahan stok akan langsung disinkronkan ke TikTok Shop</p>
                        </div>
                    </div>
                    
                    @if(count($skus) > 0)
                        <div class="space-y-4" id="stockManagementContainer">
                            @foreach($skus as $index => $sku)
                                @php
                                    $skuStock = 0;
                                    $warehouseId = null;
                                    $skuId = $sku['id'] ?? '';
                                    
                                    // Hitung stok dari struktur database
                                    if(isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                                        foreach($sku['stock_info'] as $inv) {
                                            $skuStock += $inv['available_stock'] ?? 0;
                                            $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                        }
                                    } elseif(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                        foreach($sku['inventory'] as $inv) {
                                            $skuStock += $inv['quantity'] ?? 0;
                                            $warehouseId = $inv['warehouse_id'] ?? $warehouseId;
                                        }
                                    } else {
                                        // Fallback ke stock utama jika tidak ada data varian
                                        $skuStock = $totalStock;
                                        $warehouseId = 'default_warehouse';
                                    }
                                    
                                    // Pastikan warehouseId tidak kosong
                                    $warehouseId = $warehouseId ?: 'default_warehouse_' . $index;
                                @endphp
                                
                                @if($skuId && $warehouseId)
                                <div class="stock-item flex flex-col lg:flex-row lg:items-center lg:justify-between p-5 bg-white rounded-lg border border-blue-100 hover:border-blue-200 transition-all duration-200 gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-700 mb-2">
                                            {{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-3 text-xs text-gray-500">
                                            <span class="bg-gray-100 px-2 py-1 rounded">Stok: <strong class="sku-stock-display text-gray-700">{{ $skuStock }}</strong></span>
                                            <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                                <i class='bx bx-building mr-1'></i>
                                                {{ substr($warehouseId, 0, 8) }}...
                                            </span>
                                            <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                                <i class='bx bx-id-card mr-1'></i>
                                                {{ substr($skuId, 0, 8) }}...
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 flex-shrink-0">
                                        <div class="relative">
                                            <input type="number" 
                                                   min="0"
                                                   value="{{ $skuStock }}"
                                                   class="stock-input w-28 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                                                   data-sku-id="{{ $skuId }}"
                                                   data-product-id="{{ $productData->tiktok_product_id }}"
                                                   data-warehouse-id="{{ $warehouseId }}"
                                                   data-original-stock="{{ $skuStock }}"
                                                   placeholder="Stok baru"
                                                   aria-label="Jumlah stok baru untuk {{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}">
                                        </div>
                                        <button class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-blue-600 shadow-sm"
                                                data-sku-id="{{ $skuId }}"
                                                data-product-id="{{ $productData->tiktok_product_id }}"
                                                data-warehouse-id="{{ $warehouseId }}"
                                                data-sku-name="{{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}"
                                                title="Update stok ke TikTok Shop">
                                            <i class='bx bx-upload text-sm mr-2'></i>
                                            <span class="update-text">Update</span>
                                        </button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <!-- Fallback untuk produk tanpa SKU detail -->
                        <div class="stock-item flex flex-col lg:flex-row lg:items-center lg:justify-between p-5 bg-white rounded-lg border border-blue-100 gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-700 mb-2">Stok Utama</p>
                                <div class="flex items-center gap-3 text-xs text-gray-500">
                                    <span class="bg-gray-100 px-2 py-1 rounded">Stok: <strong class="sku-stock-display text-gray-700">{{ $totalStock }}</strong></span>
                                    <span class="flex items-center bg-gray-100 px-2 py-1 rounded">
                                        <i class='bx bx-building mr-1'></i>
                                        Warehouse: Default
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input type="number" 
                                           min="0"
                                           value="{{ $totalStock }}"
                                           class="stock-input w-28 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                                           data-sku-id="default"
                                           data-product-id="{{ $productData->tiktok_product_id }}"
                                           data-warehouse-id="default_warehouse"
                                           data-original-stock="{{ $totalStock }}"
                                           placeholder="Stok baru">
                                </div>
                                <button class="update-stock-btn bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                                        data-sku-id="default"
                                        data-product-id="{{ $productData->tiktok_product_id }}"
                                        data-warehouse-id="default_warehouse"
                                        data-sku-name="Stok Utama"
                                        title="Update stok ke TikTok Shop">
                                    <i class='bx bx-upload text-sm mr-2'></i>
                                    <span class="update-text">Update</span>
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Bulk Update Section -->
                    <div class="mt-6 pt-6 border-t border-blue-200">
                        <h4 class="text-md font-semibold text-blue-900 mb-4 flex items-center">
                            <i class='bx bx-bulk mr-2'></i>Update Massal
                        </h4>
                        <div class="flex flex-wrap gap-3">
                            <button id="bulkUpdateBtn" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center shadow-sm">
                                <i class='bx bx-check-double mr-2'></i>
                                Update Semua Stok
                            </button>
                            <button id="resetAllBtn" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md flex items-center shadow-sm">
                                <i class='bx bx-reset mr-2'></i>
                                Reset Semua
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Error State -->
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-200">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 rounded-full mb-6 border border-red-200">
                <i class='bx bx-error text-4xl text-red-500'></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Produk Tidak Ditemukan</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Produk yang Anda cari tidak ditemukan atau telah dihapus.</p>
            <a href="{{ route('overview.products') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-xl font-medium transition-colors duration-200 inline-flex items-center shadow-md">
                <i class='bx bx-arrow-back mr-2'></i>Kembali ke Daftar Produk
            </a>
        </div>
    @endif
</div>

<!-- Image Modal for Zoom -->
<div id="imageModal" class="fixed inset-0 bg-black/90 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full w-full h-auto">
        <button id="modalClose" class="absolute top-4 right-4 text-white hover:text-amber-400 text-2xl z-10 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
            <i class='bx bx-x'></i>
        </button>
        <button id="modalPrev" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-amber-400 text-2xl z-10 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
            <i class='bx bx-chevron-left'></i>
        </button>
        <button id="modalNext" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-amber-400 text-2xl z-10 bg-black/50 rounded-full w-10 h-10 flex items-center justify-center backdrop-blur-sm">
            <i class='bx bx-chevron-right'></i>
        </button>
        <img id="modalImage" src="" alt="" class="w-full h-auto object-contain max-h-[80vh] rounded-lg">
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white bg-black/50 px-3 py-1 rounded-full text-sm backdrop-blur-sm">
            <span id="modalCounter">1 / {{ count($allImages ?? []) }}</span>
        </div>
    </div>
</div>

<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm"></div>
@endsection

@push('styles')
<style>
/* Horizontal Carousel Styles */
.carousel-container {
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.carousel-container::-webkit-scrollbar {
    display: none; /* Chrome, Safari and Opera */
}

.carousel-slide {
    scroll-snap-align: start;
    flex: 0 0 auto;
}

/* Smooth scrolling */
.scroll-smooth {
    scroll-behavior: smooth;
}

/* Hide scrollbar but maintain functionality */
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

/* Carousel navigation enhancements */
.carousel-prev:hover,
.carousel-next:hover {
    transform: translateY(-50%) scale(1.1);
}

.carousel-prev:active,
.carousel-next:active {
    transform: translateY(-50%) scale(0.95);
}

/* Carousel indicators */
.carousel-indicator {
    transition: all 0.3s ease;
    cursor: pointer;
}

.carousel-indicator.active {
    background-color: #f59e0b;
    width: 2rem;
}

/* Image modal styles */
#imageModal {
    backdrop-filter: blur(8px);
}

#imageModal img {
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* Hover effects for carousel items */
.carousel-slide:hover .carousel-zoom {
    opacity: 1;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .carousel-slide {
        width: 85vw;
    }
    
    .carousel-prev,
    .carousel-next {
        width: 8vw;
        height: 8vw;
        min-width: 32px;
        min-height: 32px;
    }
}

/* Enhanced shadow and transitions */
.shadow-lg {
    box-shadow: 0 10px 25px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
}

.hover\:shadow-xl:hover {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Backdrop blur support */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Smooth transitions */
.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Stock Management Styles */
.stock-input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    border-color: #3b82f6;
}

.update-stock-btn {
    transition: all 0.2s ease-in-out;
    position: relative;
    overflow: hidden;
}

.update-stock-btn:not(:disabled):hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.update-stock-btn.loading {
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

.update-stock-btn.success {
    background-color: #10b981 !important;
}

.update-stock-btn.success:hover {
    background-color: #059669 !important;
}

.update-stock-btn.error {
    background-color: #ef4444 !important;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Image hover animations */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}
</style>
@endpush

@push('scripts')
<script>
// HORIZONTAL CAROUSEL FUNCTIONALITY
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Initializing horizontal carousel...');
    
    // Carousel Elements
    const carouselContainer = document.querySelector('.carousel-container');
    const carouselSlides = document.querySelectorAll('.carousel-slide');
    const prevButton = document.querySelector('.carousel-prev');
    const nextButton = document.querySelector('.carousel-next');
    const indicators = document.querySelectorAll('.carousel-indicator');
    const currentCounter = document.querySelector('.carousel-current');
    const zoomButtons = document.querySelectorAll('.carousel-zoom');
    
    // Modal Elements
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalClose = document.getElementById('modalClose');
    const modalPrev = document.getElementById('modalPrev');
    const modalNext = document.getElementById('modalNext');
    const modalCounter = document.getElementById('modalCounter');
    
    let currentSlide = 0;
    const totalSlides = carouselSlides.length;
    
    // Initialize carousel
    function initCarousel() {
        if (totalSlides === 0) return;
        
        updateCarousel();
        setupEventListeners();
        console.log('✅ Carousel initialized with', totalSlides, 'unique slides');
    }
    
    // Update carousel state
    function updateCarousel() {
        // Update buttons state
        prevButton.disabled = currentSlide === 0;
        nextButton.disabled = currentSlide === totalSlides - 1;
        
        // Update indicators
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('bg-amber-500', index === currentSlide);
            indicator.classList.toggle('w-8', index === currentSlide);
            indicator.classList.toggle('bg-gray-300', index !== currentSlide);
            indicator.classList.toggle('w-3', index !== currentSlide);
        });
        
        // Update counter
        if (currentCounter) {
            currentCounter.textContent = currentSlide + 1;
        }
        
        // Scroll to current slide
        if (carouselContainer && carouselSlides[currentSlide]) {
            const slide = carouselSlides[currentSlide];
            const containerWidth = carouselContainer.offsetWidth;
            const slideWidth = slide.offsetWidth;
            const scrollPosition = (slideWidth + 16) * currentSlide; // 16px for gap
            
            carouselContainer.scrollTo({
                left: scrollPosition,
                behavior: 'smooth'
            });
        }
    }
    
    // Navigate to specific slide
    function goToSlide(index) {
        if (index < 0 || index >= totalSlides) return;
        currentSlide = index;
        updateCarousel();
    }
    
    // Next slide
    function nextSlide() {
        if (currentSlide < totalSlides - 1) {
            currentSlide++;
            updateCarousel();
        }
    }
    
    // Previous slide
    function prevSlide() {
        if (currentSlide > 0) {
            currentSlide--;
            updateCarousel();
        }
    }
    
    // Setup event listeners
    function setupEventListeners() {
        // Navigation buttons
        prevButton?.addEventListener('click', prevSlide);
        nextButton?.addEventListener('click', nextSlide);
        
        // Indicators
        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => goToSlide(index));
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') prevSlide();
            if (e.key === 'ArrowRight') nextSlide();
            if (e.key === 'Escape') closeModal();
        });
        
        // Touch/swipe support
        let startX = 0;
        let endX = 0;
        
        carouselContainer?.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });
        
        carouselContainer?.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = startX - endX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    nextSlide(); // Swipe left
                } else {
                    prevSlide(); // Swipe right
                }
            }
        }
        
        // Zoom modal functionality
        zoomButtons.forEach((button, index) => {
            button.addEventListener('click', () => openModal(index));
        });
        
        // Modal controls
        modalClose?.addEventListener('click', closeModal);
        modalPrev?.addEventListener('click', modalPrevSlide);
        modalNext?.addEventListener('click', modalNextSlide);
        
        // Close modal on background click
        imageModal?.addEventListener('click', (e) => {
            if (e.target === imageModal) {
                closeModal();
            }
        });
    }
    
    // Modal functions
    let modalCurrentIndex = 0;
    
    function openModal(index) {
        modalCurrentIndex = index;
        updateModal();
        imageModal.classList.remove('hidden');
        imageModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    
    function closeModal() {
        imageModal.classList.add('hidden');
        imageModal.classList.remove('flex');
        document.body.style.overflow = '';
    }
    
    function modalNextSlide() {
        if (modalCurrentIndex < totalSlides - 1) {
            modalCurrentIndex++;
            updateModal();
        }
    }
    
    function modalPrevSlide() {
        if (modalCurrentIndex > 0) {
            modalCurrentIndex--;
            updateModal();
        }
    }
    
    function updateModal() {
        const imageUrl = zoomButtons[modalCurrentIndex]?.dataset.image;
        if (imageUrl && modalImage) {
            modalImage.src = imageUrl;
            modalImage.alt = `Product Image ${modalCurrentIndex + 1}`;
            modalCounter.textContent = `${modalCurrentIndex + 1} / ${totalSlides}`;
        }
        
        // Update modal navigation buttons
        modalPrev.style.visibility = modalCurrentIndex === 0 ? 'hidden' : 'visible';
        modalNext.style.visibility = modalCurrentIndex === totalSlides - 1 ? 'hidden' : 'visible';
    }
    
    // Initialize everything
    initCarousel();
});

// SIMPLE & EFFECTIVE STOCK MANAGEMENT
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 Initializing stock management...');
    
    // CSRF Token dengan multiple fallbacks
    function getCsrfToken() {
        const token = document.querySelector('meta[name="csrf-token"]')?.content || 
                     '{{ csrf_token() }}';
        console.log('🛡️ CSRF Token:', token ? 'Found' : 'Missing');
        return token;
    }

    // Handle stock update
    async function handleStockUpdate(button) {
        const skuId = button.dataset.skuId;
        const productId = button.dataset.productId;
        const warehouseId = button.dataset.warehouseId;
        const skuName = button.dataset.skuName;
        const input = document.querySelector(`.stock-input[data-sku-id="${skuId}"]`);
        
        if (!input) {
            alert('❌ Input stok tidak ditemukan!');
            return;
        }
        
        const newStock = parseInt(input.value);
        const originalStock = parseInt(input.dataset.originalStock);
        
        // Validasi
        if (newStock === originalStock) {
            alert('⚠️ Tidak ada perubahan stok!');
            return;
        }
        
        if (newStock < 0) {
            alert('❌ Stok tidak boleh negatif!');
            return;
        }
        
        // UI Loading state
        button.disabled = true;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-1"></i>Loading...';
        
        try {
            console.log('🚀 Sending stock update:', { 
                productId, 
                skuId, 
                newStock,
                skuName 
            });
            
            // Prepare form data - GUNAKAN FormData untuk compatibility terbaik
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('sku_id', skuId);
            formData.append('warehouse_id', warehouseId);
            formData.append('new_stock', newStock);
            formData.append('_token', getCsrfToken());
            
            // Send request
            const response = await fetch('/overview/products/update-stock', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            const result = await response.json();
            console.log('📥 Server response:', result);
            
            if (result.success) {
                // ✅ SUCCESS
                input.dataset.originalStock = newStock;
                button.style.background = '#10b981';
                button.innerHTML = '<i class="bx bx-check mr-1"></i>Berhasil!';
                
                // Update display
                const stockDisplay = input.closest('.stock-item')?.querySelector('.sku-stock-display');
                if (stockDisplay) {
                    stockDisplay.textContent = newStock.toLocaleString();
                }
                
                showNotification(`✅ Stok ${skuName} berhasil diupdate ke ${newStock}`, 'success');
                
                // Reset button setelah 2 detik
                setTimeout(() => {
                    button.style.background = '';
                    button.disabled = false;
                    button.innerHTML = originalText;
                    updateButtonState(button, input);
                }, 2000);
                
            } else {
                throw new Error(result.message || 'Gagal update stok');
            }
            
        } catch (error) {
            console.error('💥 Error:', error);
            button.disabled = false;
            button.innerHTML = originalText;
            showNotification(`❌ Gagal update stok ${skuName}: ${error.message}`, 'error');
        }
    }

    // Update button state based on input change
    function updateButtonState(button, input) {
        if (!button || !input) return;

        const currentValue = parseInt(input.value);
        const originalValue = parseInt(input.dataset.originalStock);
        button.disabled = (currentValue === originalValue);
    }

    // Input validation
    function handleInputChange(input) {
        const value = parseInt(input.value);
        if (isNaN(value) || value < 0) {
            input.value = 0;
        }
        
        // Enable/disable button based on change
        const skuId = input.dataset.skuId;
        const button = document.querySelector(`.update-stock-btn[data-sku-id="${skuId}"]`);
        updateButtonState(button, input);
    }

    // Initialize all button states
    function initializeButtonStates() {
        document.querySelectorAll('.update-stock-btn').forEach(button => {
            const skuId = button.dataset.skuId;
            const input = document.querySelector(`.stock-input[data-sku-id="${skuId}"]`);
            updateButtonState(button, input);
        });
    }

    // Bulk update functionality
    document.getElementById('bulkUpdateBtn')?.addEventListener('click', async function() {
        const updateButtons = document.querySelectorAll('.update-stock-btn:not(:disabled)');
        
        if (updateButtons.length === 0) {
            showNotification('Tidak ada perubahan stok yang perlu diupdate', 'warning');
            return;
        }

        const confirmed = confirm(`Anda akan mengupdate ${updateButtons.length} stok sekaligus. Lanjutkan?`);
        if (!confirmed) return;

        for (const button of updateButtons) {
            await handleStockUpdate(button);
            // Delay antara requests
            await new Promise(resolve => setTimeout(resolve, 1000));
        }
    });

    // Reset all functionality
    document.getElementById('resetAllBtn')?.addEventListener('click', function() {
        document.querySelectorAll('.stock-input').forEach(input => {
            const originalStock = parseInt(input.dataset.originalStock);
            input.value = originalStock;
            handleInputChange(input);
        });
        showNotification('Semua nilai stok telah direset ke nilai awal', 'info');
    });

    // Attach event listeners
    document.querySelectorAll('.update-stock-btn').forEach(button => {
        button.addEventListener('click', function() {
            handleStockUpdate(this);
        });
    });

    document.querySelectorAll('.stock-input').forEach(input => {
        input.addEventListener('input', function() {
            handleInputChange(this);
        });
    });

    // Initialize button states
    initializeButtonStates();

    console.log('✅ Stock management initialized successfully');
});

// Simple notification system
function showNotification(message, type = 'info') {
    // Create notification container if not exists
    let container = document.getElementById('notificationContainer');
    if (!container) {
        container = document.createElement('div');
        container.id = 'notificationContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2 max-w-sm';
        document.body.appendChild(container);
    }
    
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
    
    notification.className = `px-4 py-3 rounded-xl shadow-lg transform translate-x-full opacity-0 transition-all duration-300 ${typeStyles[type]}`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class='bx ${icons[type]} mr-2 text-lg'></i>
            <span class="text-sm font-medium flex-1">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition-colors">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>
    `;
    
    container.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 10);
    
    // Auto remove after 5 seconds
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
</script>
@endpush