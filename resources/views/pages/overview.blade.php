@extends('layouts.app')

@section('title', 'Product Overview - Camellia Boutique99')
@section('subtitle', 'Detail lengkap produk batik eksklusif')

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('products.index') }}" class="hover:text-amber-600 transition-colors">
            <i class='bx bx-package mr-1'></i>Products
        </a>
        <i class='bx bx-chevron-right text-gray-400'></i>
        <span class="text-gray-900 font-medium">Product Overview</span>
    </nav>

    @if($product['success'] && $product['data'])
        <!-- Main Content -->
        <div id="productOverview">
            @php
                $productData = $product['data'];
                $metrics = $product['metrics'];
                
                // Status styling
                $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                $statusText = $productData['status'] ?? 'Unknown';
                $statusIcon = 'bx-question-mark';
                
                if(($productData['status'] ?? '') === 'ACTIVATE') {
                    $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                    $statusText = 'Aktif';
                    $statusIcon = 'bx-check-circle';
                } elseif(($productData['status'] ?? '') === 'LIMITED') {
                    $statusClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                    $statusText = 'Terbatas';
                    $statusIcon = 'bx-time';
                } elseif(($productData['status'] ?? '') === 'SOLD_OUT') {
                    $statusClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                    $statusText = 'Habis';
                    $statusIcon = 'bx-x-circle';
                } elseif(($productData['status'] ?? '') === 'DRAFT') {
                    $statusClass = 'bg-gray-50 text-gray-700 border border-gray-200';
                    $statusText = 'Draft';
                    $statusIcon = 'bx-edit';
                } elseif(($productData['status'] ?? '') === 'ARCHIVED') {
                    $statusClass = 'bg-gray-200 text-gray-700 border border-gray-300';
                    $statusText = 'Arsip';
                    $statusIcon = 'bx-archive';
                }

                // Bersihkan deskripsi
                $cleanDescription = strip_tags($productData['description'] ?? '');
                $cleanDescription = str_replace(['&nbsp;', '&amp;'], [' ', '&'], $cleanDescription);
                $cleanDescription = trim($cleanDescription);
            @endphp

            <!-- Header Section - NAMA PRODUK PALING ATAS -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusClass }}">
                                <i class='bx {{ $statusIcon }} mr-1'></i>
                                {{ $statusText }}
                            </span>
                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">
                                ID: {{ $product['productId'] }}
                            </span>
                        </div>
                        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 text-center lg:text-left">
                            {{ $productData['title'] ?? 'N/A' }}
                        </h1>
                    </div>
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <a href="{{ route('products.index') }}" 
                           class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 hover:shadow-lg flex items-center">
                            <i class='bx bx-arrow-back mr-2'></i>Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- MARQUEE SECTION - GAMBAR PRODUCT DENGAN ANIMASI -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center justify-center">
                    <i class='bx bx-images mr-2 text-amber-600 text-xl'></i>Galeri Produk
                </h2>
                
                @if(isset($productData['main_images']) && count($productData['main_images']) > 0)
                    <!-- Marquee Container -->
                    <div class="marquee-wrapper relative overflow-hidden bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl py-8">
                        <!-- Gradient Overlays -->
                        <div class="absolute left-0 top-0 bottom-0 w-32 bg-gradient-to-r from-white to-transparent z-10"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-32 bg-gradient-to-l from-white to-transparent z-10"></div>
                        
                        <!-- Marquee Content - FIXED STRUCTURE -->
                        <div class="marquee-content flex animate-marquee">
                            @foreach(array_merge($productData['main_images'], $productData['main_images']) as $index => $image)
                                @php
                                    $imageUrl = $image['urls'][0] ?? '';
                                    $originalIndex = $index % count($productData['main_images']);
                                @endphp
                                <div class="marquee-item flex-shrink-0 mx-3 w-80 h-64 bg-white rounded-xl shadow-lg overflow-hidden group cursor-pointer transform hover:scale-105 transition-transform duration-500">
                                    <div class="relative w-full h-full">
                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}" 
                                                 alt="{{ $productData['title'] ?? 'Product Image' }} - {{ $originalIndex + 1 }}"
                                                 class="w-full h-full object-cover object-center"
                                                 style="object-position: center 5%;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                                 loading="lazy">
                                            <div class="w-full h-full hidden items-center justify-center bg-gray-200">
                                                <i class='bx bx-image text-gray-400 text-2xl'></i>
                                            </div>
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <i class='bx bx-image text-gray-400 text-2xl'></i>
                                            </div>
                                        @endif
                                        
                                        <!-- Overlay dengan informasi -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-40 transition-all duration-300 flex items-end justify-start p-4">
                                            <span class="text-white font-medium text-sm bg-black bg-opacity-60 px-3 py-2 rounded-full transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                                Gambar {{ $originalIndex + 1 }}
                                            </span>
                                        </div>
                                        
                                        @if($originalIndex === 0 && $index < count($productData['main_images']))
                                            <span class="absolute top-4 left-4 bg-amber-500 text-white px-3 py-1 rounded-full text-xs font-medium shadow-lg">
                                                Utama
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Image Counter -->
                    <div class="flex justify-center items-center mt-6 space-x-3">
                        <i class='bx bx-image text-amber-600 text-lg'></i>
                        <span class="text-gray-600 font-medium">
                            {{ count($productData['main_images']) }} Gambar Produk
                        </span>
                    </div>
                @else
                    <!-- No Images State -->
                    <div class="text-center py-16">
                        <i class='bx bx-image text-6xl text-gray-300 mb-4'></i>
                        <p class="text-gray-500 text-lg font-medium">Tidak ada gambar produk</p>
                    </div>
                @endif
            </div>

            <!-- DESCRIPTION & DETAILS SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-detail mr-3 text-amber-600'></i>Deskripsi Produk
                </h2>
                
                <div class="prose prose-lg max-w-none">
                    @if($cleanDescription)
                        <p class="text-gray-700 leading-relaxed text-lg">
                            {{ $cleanDescription }}
                        </p>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class='bx bx-note text-4xl text-gray-300 mb-3'></i>
                            <p class="text-lg">Tidak ada deskripsi produk</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- INVENTORY & PRICING SECTION -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                <!-- Inventory Management -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-package mr-3 text-amber-600'></i>Manajemen Stok
                    </h2>
                    
                    <div class="mb-6 p-5 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200 shadow-sm">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-sm text-blue-700 font-medium uppercase tracking-wide">Total Stok Tersedia</p>
                                <p class="text-3xl font-bold text-blue-900 mt-2">{{ number_format($metrics['total_stock']) }}</p>
                            </div>
                            <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class='bx bx-package text-2xl text-white'></i>
                            </div>
                        </div>
                    </div>
                    
                    @if(isset($productData['skus']) && count($productData['skus']) > 0)
                        <div class="space-y-4">
                            <h3 class="font-semibold text-gray-900 text-lg mb-4">Stok per Varian</h3>
                            @foreach($productData['skus'] as $index => $sku)
                                @php
                                    $skuStock = 0;
                                    if(isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                                        foreach($sku['stock_info'] as $inv) {
                                            $skuStock += $inv['available_stock'] ?? 0;
                                        }
                                    } elseif(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                        foreach($sku['inventory'] as $inv) {
                                            $skuStock += $inv['quantity'] ?? 0;
                                        }
                                    }
                                @endphp
                                <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300 {{ $skuStock <= 10 ? 'border-amber-200 bg-amber-50' : '' }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 text-lg">{{ $sku['sku_code'] ?? 'SKU-' . ($index + 1) }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">
                                                @if(isset($sku['sku_attr']))
                                                    @if(is_array($sku['sku_attr']))
                                                        {{ implode(' • ', $sku['sku_attr']) }}
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
                                    @if($skuStock <= 10)
                                        <div class="flex items-center text-amber-600 text-sm bg-amber-100 px-3 py-2 rounded-lg">
                                            <i class='bx bx-error-alt mr-2 text-lg'></i>
                                            <span class="font-medium">Stok menipis - perlu restok</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <i class='bx bx-package text-4xl text-gray-300 mb-3'></i>
                            <p class="text-lg">Tidak ada data stok</p>
                        </div>
                    @endif
                </div>

                <!-- Pricing Information -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class='bx bx-dollar-circle mr-3 text-amber-600'></i>Informasi Harga
                    </h2>
                    
                    @if(isset($productData['skus']) && count($productData['skus']) > 0)
                        <div class="space-y-5">
                            @foreach($productData['skus'] as $index => $sku)
                                @php
                                    $originalPrice = 0;
                                    $discountPrice = 0;
                                    
                                    if(isset($sku['price_info'])) {
                                        $originalPrice = (int)($sku['price_info']['original_price'] ?? 0);
                                        $discountPrice = (int)($sku['price_info']['discount_price'] ?? $originalPrice);
                                    } elseif(isset($sku['price'])) {
                                        $originalPrice = (int)($sku['price']['tax_exclusive_price'] ?? 0);
                                        $discountPrice = $originalPrice;
                                    }
                                    
                                    $hasDiscount = $discountPrice < $originalPrice;
                                @endphp
                                
                                @if($originalPrice > 0)
                                    <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition-shadow duration-300">
                                        <div class="flex justify-between items-start mb-4">
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 text-lg">{{ $sku['sku_code'] ?? 'Varian ' . ($index + 1) }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">
                                                    @if(isset($sku['sku_attr']))
                                                        @if(is_array($sku['sku_attr']))
                                                            {{ implode(' • ', $sku['sku_attr']) }}
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
                        <div class="text-center py-8 text-gray-500">
                            <i class='bx bx-dollar-circle text-4xl text-gray-300 mb-3'></i>
                            <p class="text-lg">Informasi harga tidak tersedia</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- QUICK STATS SECTION -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class='bx bx-stats mr-3 text-amber-600'></i>Statistik Produk
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 text-center hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-layer text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-blue-700 mb-2">Total Varian</h3>
                        <p class="text-3xl font-bold text-blue-900">{{ $metrics['sku_count'] }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 rounded-xl p-5 text-center hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-dollar text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-emerald-700 mb-2">Harga Rata-rata</h3>
                        <p class="text-2xl font-bold text-emerald-900">Rp {{ number_format($metrics['average_price']) }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-5 text-center hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-trending-up text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-amber-700 mb-2">Nilai Inventori</h3>
                        <p class="text-2xl font-bold text-amber-900">Rp {{ number_format($metrics['total_value']) }}</p>
                    </div>
                    
                    <div class="bg-gradient-to-br from-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-50 to-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-100 border border-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-200 rounded-xl p-5 text-center hover:shadow-lg transition-all duration-300">
                        <div class="w-16 h-16 bg-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-500 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                            <i class='bx bx-pie-chart-alt text-2xl text-white'></i>
                        </div>
                        <h3 class="text-sm font-medium text-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-700 mb-2">Tingkat Stok</h3>
                        <p class="text-2xl font-bold text-{{ $metrics['stock_level'] == 'Aman' ? 'emerald' : ($metrics['stock_level'] == 'Sedang' ? 'amber' : 'red') }}-900">{{ $metrics['stock_level'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Error State -->
        <div class="text-center py-16">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-50 rounded-full mb-6 border border-red-200">
                <i class='bx bx-error text-3xl text-red-500'></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 mb-3">Produk Tidak Ditemukan</h3>
            <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Produk yang Anda cari tidak ditemukan atau telah dihapus.</p>
            <a href="{{ route('products.index') }}" class="bg-amber-600 hover:bg-amber-700 text-white px-8 py-3 rounded-xl font-medium transition-colors duration-200 inline-flex items-center">
                <i class='bx bx-arrow-back mr-2'></i>Kembali ke Daftar Produk
            </a>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
/* Marquee Container */
.marquee-wrapper {
    width: 100%;
    overflow: hidden;
    position: relative;
    mask-image: linear-gradient(
        to right,
        transparent,
        black 10%,
        black 90%,
        transparent
    );
}

/* Marquee Content - FIXED ANIMATION */
.marquee-content {
    display: flex;
    animation: marquee-scroll 40s linear infinite;
    will-change: transform;
}

/* Marquee Item */
.marquee-item {
    flex: 0 0 auto;
    transition: all 0.3s ease;
}

/* Keyframes for marquee animation - FIXED */
@keyframes marquee-scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-100% / 2));
    }
}

/* Pause animation on hover */
.marquee-wrapper:hover .marquee-content {
    animation-play-state: paused;
}

/* Smooth hover effects for images */
.marquee-item:hover {
    transform: scale(1.05);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}

/* Custom scrollbar hiding */
.marquee-wrapper::-webkit-scrollbar {
    display: none;
}

.marquee-wrapper {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    .marquee-item {
        width: 300px;
        height: 225px;
    }
    
    @keyframes marquee-scroll {
        0% {
            transform: translateX(0);
        }
        100% {
            transform: translateX(calc(-100% / 2));
        }
    }
}

@media (max-width: 768px) {
    .marquee-item {
        width: 250px;
        height: 188px;
    }
    
    .marquee-content {
        animation-duration: 30s;
    }
}

@media (max-width: 640px) {
    .marquee-item {
        width: 200px;
        height: 150px;
    }
    
    .marquee-content {
        animation-duration: 25s;
    }
    
    .marquee-wrapper {
        mask-image: linear-gradient(
            to right,
            transparent,
            black 5%,
            black 95%,
            transparent
        );
    }
}

/* Ensure smooth animation */
.marquee-content {
    backface-visibility: hidden;
    perspective: 1000px;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pause marquee when hovering over individual items
    const marqueeItems = document.querySelectorAll('.marquee-item');
    const marqueeWrapper = document.querySelector('.marquee-wrapper');
    
    marqueeItems.forEach(item => {
        item.addEventListener('mouseenter', () => {
            const marqueeContent = document.querySelector('.marquee-content');
            marqueeContent.style.animationPlayState = 'paused';
        });
        
        item.addEventListener('mouseleave', () => {
            const marqueeContent = document.querySelector('.marquee-content');
            marqueeContent.style.animationPlayState = 'running';
        });
    });

    // Add click functionality to images
    marqueeItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            const totalImages = {{ count($productData['main_images'] ?? []) }};
            const imageIndex = (index % totalImages) + 1;
            showImageModal(imageIndex);
        });
    });

    // Debug: Log marquee status
    console.log('Marquee initialized with', {{ count($productData['main_images'] ?? []) }}, 'images');
});

// Function to show image modal
function showImageModal(imageIndex) {
    // Simple alert for now - can be replaced with a proper modal
    alert(`Membuka gambar ${imageIndex} dalam ukuran penuh`);
    
    // Untuk implementasi modal yang lebih advanced:
    // 1. Buat modal element
    // 2. Tampilkan gambar yang dipilih
    // 3. Tambahkan navigasi antara gambar
}
</script>
@endpush