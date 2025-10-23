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
            <div class="absolute top-0 left-0 w-32 h-32 border-2 border-amber-300/20 rounded-full -translate-x-16 -translate-y-16"></div>
            <div class="absolute bottom-0 right-0 w-40 h-40 border-2 border-amber-300/20 rounded-full translate-x-20 translate-y-20"></div>
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
                        <input type="text" 
                               id="searchInput"
                               placeholder="Cari produk..." 
                               class="w-64 pl-10 pr-4 py-2.5 bg-white/95 backdrop-blur-sm border border-amber-200/30 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500 shadow-lg">
                        <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-amber-500'></i>
                        <div id="searchLoading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-amber-500"></div>
                        </div>
                    </div>
                    
                    <!-- Filter Status -->
                    <select id="statusFilter" class="px-3 py-2.5 text-sm bg-white/95 backdrop-blur-sm border border-amber-200/30 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-transparent transition text-gray-900 shadow-lg">
                        <option value="all">Semua Status</option>
                        <option value="ACTIVATE">Aktif</option>
                        <option value="DRAFT">Draft</option>
                        <option value="ARCHIVED">Arsip</option>
                    </select>
                </div>
            </div>
            
            <!-- Search Info -->
            <div id="searchInfo" class="mt-4 hidden">
                <div class="flex items-center justify-between bg-amber-400/20 backdrop-blur-sm border border-amber-300/30 rounded-lg p-3">
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-amber-100">Hasil pencarian untuk:</span>
                        <span id="searchQuery" class="text-sm font-medium text-white bg-amber-500/50 px-3 py-1 rounded-md"></span>
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
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Produk</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ count($products ?? []) }}</p>
                    <p class="text-xs text-emerald-600 flex items-center">
                        <i class='bx bx-up-arrow-alt mr-1'></i>
                        @php
                            $activeProducts = 0;
                            if(isset($products)) {
                                foreach($products as $product) {
                                    if(($product['status'] ?? '') === 'ACTIVATE') {
                                        $activeProducts++;
                                    }
                                }
                            }
                            echo $activeProducts;
                        @endphp aktif
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-package text-blue-500 text-xl'></i>
                </div>
            </div>
        </div>
        
        <!-- Total Stock -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Stok</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">
                        @php
                            $totalStock = 0;
                            if(isset($products)) {
                                foreach($products as $product) {
                                    if(isset($product['skus']) && is_array($product['skus'])) {
                                        foreach($product['skus'] as $sku) {
                                            if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                                foreach($sku['inventory'] as $inv) {
                                                    $totalStock += $inv['quantity'] ?? 0;
                                                }
                                            }
                                        }
                                    }
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
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-check-circle text-green-500 text-xl'></i>
                </div>
            </div>
        </div>
        
        <!-- Inventory Value -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Nilai Inventori</p>
                    <p class="text-xl font-bold text-gray-900 mb-1">
                        @php
                            $inventoryValue = 0;
                            if(isset($products)) {
                                foreach($products as $product) {
                                    $productStock = 0;
                                    $productPrice = 0;
                                    
                                    if(isset($product['skus']) && is_array($product['skus'])) {
                                        foreach($product['skus'] as $sku) {
                                            if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                                foreach($sku['inventory'] as $inv) {
                                                    $productStock += $inv['quantity'] ?? 0;
                                                }
                                            }
                                            if(isset($sku['price']['tax_exclusive_price'])) {
                                                $productPrice = (int)$sku['price']['tax_exclusive_price'];
                                            }
                                        }
                                    }
                                    
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
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-dollar-circle text-amber-500 text-xl'></i>
                </div>
            </div>
        </div>
        
        <!-- Active Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Produk Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">
                        @php
                            $activeProductsCount = 0;
                            if(isset($products)) {
                                foreach($products as $product) {
                                    if(($product['status'] ?? '') === 'ACTIVATE') {
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
                <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
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
                    <span id="productsCount">{{ count($products ?? []) }}</span>
                    <span>produk</span>
                </div>
            </div>
        </div>

        <!-- Products Content -->
        <div class="p-6 relative z-10">
            <div id="productsContainer">
                @if(isset($products) && count($products) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productsGrid">
                    @foreach($products as $product)
                    @php
                        $productStock = 0;
                        $productPrice = 0;
                        $productValue = 0;
                        
                        if(isset($product['skus']) && is_array($product['skus'])) {
                            foreach($product['skus'] as $sku) {
                                if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                    foreach($sku['inventory'] as $inv) {
                                        $productStock += $inv['quantity'] ?? 0;
                                    }
                                }
                                if(isset($sku['price']['tax_exclusive_price'])) {
                                    $productPrice = (int)$sku['price']['tax_exclusive_price'];
                                }
                            }
                        }
                        
                        $productValue = $productStock * $productPrice;
                        
                        // Status styling yang elegant
                        $statusClass = 'bg-gray-100 text-gray-700 border border-gray-200';
                        $statusText = $product['status'] ?? 'Unknown';
                        $statusIcon = 'bx-question-mark';
                        
                        if(($product['status'] ?? '') === 'ACTIVATE') {
                            $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                            $statusText = 'Aktif';
                            $statusIcon = 'bx-check-circle';
                        } elseif(($product['status'] ?? '') === 'LIMITED') {
                            $statusClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                            $statusText = 'Terbatas';
                            $statusIcon = 'bx-time';
                        } elseif(($product['status'] ?? '') === 'SOLD_OUT') {
                            $statusClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                            $statusText = 'Habis';
                            $statusIcon = 'bx-x-circle';
                        } elseif(($product['status'] ?? '') === 'DRAFT') {
                            $statusClass = 'bg-gray-50 text-gray-700 border border-gray-200';
                            $statusText = 'Draft';
                            $statusIcon = 'bx-edit';
                        }
                    @endphp
                    
                    <!-- Product Card yang Elegant dengan Hover Effect -->
                    <div class="product-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-lg transition-all duration-300 group relative" 
                         data-title="{{ strtolower($product['title'] ?? '') }}"
                         data-description="{{ strtolower($product['description'] ?? '') }}"
                         data-status="{{ $product['status'] ?? '' }}"
                         data-price="{{ $productPrice }}"
                         data-stock="{{ $productStock }}"
                         data-value="{{ $productValue }}">
                        <!-- Corner accent pada hover -->
                        <div class="absolute top-0 right-0 w-3 h-3 bg-amber-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300 rounded-bl-lg"></div>
                        
                        <!-- Product Image -->
                        <div class="h-48 relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                            @if(isset($product['main_images']) && count($product['main_images']) > 0)
                                <img src="{{ $product['main_images'][0]['url_list'][0] ?? '' }}" 
                                     alt="{{ $product['title'] ?? 'Product Image' }}" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <i class='bx bx-package text-gray-300 text-4xl'></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <div class="absolute top-3 right-3">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    <i class='bx {{ $statusIcon }} mr-1'></i>
                                    {{ $statusText }}
                                </span>
                            </div>

                            <!-- Low Stock Warning -->
                            @if($productStock > 0 && $productStock <= 10)
                            <div class="absolute bottom-3 left-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 border border-amber-200">
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
                                {{ $product['description'] ?? 'Deskripsi tidak tersedia' }}
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
                                    <p class="text-xs text-gray-500">Stok Tersedia</p>
                                </div>
                            </div>

                            <!-- Product Value -->
                            <div class="mb-3 p-2 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-xs text-gray-600">Nilai Inventori:</p>
                                <p class="text-sm font-semibold text-amber-600">Rp {{ number_format($productValue, 0, ',', '.') }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <button class="flex-1 bg-amber-600 hover:bg-amber-700 text-white py-2.5 rounded-lg font-medium transition-all duration-200 hover:shadow-md flex items-center justify-center text-sm edit-product-btn"
                                        data-product-id="{{ $product['id'] ?? '' }}">
                                    <i class='bx bx-edit mr-1.5'></i>
                                    Edit
                                </button>
                                <button class="w-12 h-12 border border-gray-300 hover:border-amber-400 hover:bg-amber-50 rounded-lg flex items-center justify-center transition-all duration-200 group/action">
                                    <i class='bx bx-dots-vertical-rounded text-gray-600 group-hover/action:text-amber-600'></i>
                                </button>
                            </div>

                            <!-- Product Meta -->
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <p class="text-xs text-gray-500">ID: {{ $product['id'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State yang Elegant -->
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full mb-6 border border-amber-200">
                        <i class='bx bx-package text-3xl text-amber-500'></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Belum Ada Produk</h3>
                    <p class="text-gray-600 mb-8 max-w-md mx-auto text-lg">Mulai bangun katalog produk batik eksklusif Anda.</p>
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
                <button id="resetSearch" class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 inline-flex items-center">
                    <i class='bx bx-refresh mr-2'></i>Reset Pencarian
                </button>
            </div>

            <!-- Pagination dengan Style Elegant -->
            <div id="pagination" class="flex flex-col sm:flex-row items-center justify-between pt-6 mt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-3 sm:mb-0">
                    Menampilkan <span class="font-semibold text-amber-600" id="visibleProductsCount">{{ count($products ?? []) }}</span> dari 
                    <span class="font-semibold">{{ count($products ?? []) }}</span> produk
                </p>
                <div class="flex items-center space-x-2">
                    <button class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg hover:bg-amber-50 hover:border-amber-300 transition-colors duration-200 flex items-center">
                        <i class='bx bx-chevron-left mr-2'></i>
                        Sebelumnya
                    </button>
                    <button class="px-4 py-2.5 text-sm bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors">1</button>
                    <button class="px-4 py-2.5 text-sm border border-gray-300 rounded-lg hover:bg-amber-50 hover:border-amber-300 transition-colors duration-200 flex items-center">
                        Selanjutnya
                        <i class='bx bx-chevron-right ml-2'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

<style>
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
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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
</style>


<style>
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
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
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
// JavaScript code remains exactly the same as before...
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const searchInfo = document.getElementById('searchInfo');
    const searchQuery = document.getElementById('searchQuery');
    const searchResultsCount = document.getElementById('searchResultsCount');
    const clearSearch = document.getElementById('clearSearch');
    const resetSearch = document.getElementById('resetSearch');
    const searchLoading = document.getElementById('searchLoading');
    const productsContainer = document.getElementById('productsContainer');
    const productsGrid = document.getElementById('productsGrid');
    const noResults = document.getElementById('noResults');
    const pagination = document.getElementById('pagination');
    const productsCount = document.getElementById('productsCount');
    const visibleProductsCount = document.getElementById('visibleProductsCount');
    const editButtons = document.querySelectorAll('.edit-product-btn');

    let allProducts = [];
    let searchTimeout;

    // Initialize products data
    function initializeProducts() {
        const productCards = document.querySelectorAll('.product-card');
        allProducts = Array.from(productCards).map(card => {
            return {
                element: card,
                title: card.dataset.title,
                description: card.dataset.description,
                status: card.dataset.status,
                price: parseInt(card.dataset.price),
                stock: parseInt(card.dataset.stock),
                value: parseInt(card.dataset.value)
            };
        });
        
        console.log('Total products initialized:', allProducts.length);
    }

    // Search functionality dengan debounce
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        
        // Show loading indicator saat mengetik
        if (this.value.trim()) {
            searchLoading.classList.remove('hidden');
        }
        
        searchTimeout = setTimeout(() => {
            performSearch();
        }, 300);
    });

    // Status filter change
    statusFilter.addEventListener('change', function() {
        performSearch();
    });

    // Clear search
    clearSearch.addEventListener('click', function() {
        searchInput.value = '';
        performSearch();
    });

    // Reset search
    resetSearch.addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = 'all';
        performSearch();
    });

    // Perform search and filter
    function performSearch() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const statusFilterValue = statusFilter.value;
        
        // Hide loading indicator
        searchLoading.classList.add('hidden');
        
        let visibleProducts = 0;
        let totalStock = 0;
        let totalValue = 0;
        let activeProducts = 0;

        allProducts.forEach(product => {
            const matchesSearch = !searchTerm || 
                product.title.includes(searchTerm) || 
                product.description.includes(searchTerm);
            
            const matchesStatus = statusFilterValue === 'all' || 
                product.status === statusFilterValue;

            const isVisible = matchesSearch && matchesStatus;

            if (isVisible) {
                product.element.classList.remove('hidden');
                visibleProducts++;
                totalStock += product.stock;
                totalValue += product.value;
                if (product.status === 'ACTIVATE') {
                    activeProducts++;
                }
                
                // Add animation untuk produk yang visible
                product.element.style.animationDelay = `${visibleProducts * 0.05}s`;
                product.element.classList.add('fade-in-up');
            } else {
                product.element.classList.add('hidden');
                product.element.classList.remove('fade-in-up');
            }
        });

        // Update UI based on results
        updateSearchUI(searchTerm, visibleProducts);
        updateStats(visibleProducts, totalStock, totalValue, activeProducts);
    }

    // Update search UI
    function updateSearchUI(searchTerm, visibleCount) {
        const totalCount = allProducts.length;
        
        if (searchTerm || statusFilter.value !== 'all') {
            searchInfo.classList.remove('hidden');
            let queryText = '';
            if (searchTerm) queryText += `"${searchTerm}"`;
            if (statusFilter.value !== 'all') {
                queryText += `${queryText ? ' • ' : ''}${statusFilter.options[statusFilter.selectedIndex].text}`;
            }
            
            searchQuery.textContent = queryText;
            searchResultsCount.textContent = `${visibleCount} dari ${totalCount} produk ditemukan`;
        } else {
            searchInfo.classList.add('hidden');
        }

        // Show/hide no results message
        if (visibleCount === 0 && (searchTerm || statusFilter.value !== 'all')) {
            productsGrid.classList.add('hidden');
            noResults.classList.remove('hidden');
            pagination.classList.add('hidden');
        } else {
            productsGrid.classList.remove('hidden');
            noResults.classList.add('hidden');
            pagination.classList.remove('hidden');
        }

        // Update products count
        productsCount.textContent = visibleCount;
        visibleProductsCount.textContent = visibleCount;
    }

    // Update statistics display
    function updateStats(visibleCount, totalStock, totalValue, activeProducts) {
        // Update the stats cards dynamically
        const statsCards = document.querySelectorAll('.bg-white.rounded-xl');
        
        // Total Products card
        if (statsCards[0]) {
            const countElement = statsCards[0].querySelector('.text-2xl');
            const activeElement = statsCards[0].querySelector('.text-xs');
            if (countElement) countElement.textContent = visibleCount;
            if (activeElement) {
                activeElement.innerHTML = `<i class='bx bx-up-arrow-alt mr-1'></i>${activeProducts} aktif`;
            }
        }
        
        // Total Stock card
        if (statsCards[1]) {
            const stockElement = statsCards[1].querySelector('.text-2xl');
            if (stockElement) stockElement.textContent = totalStock.toLocaleString();
        }
        
        // Inventory Value card
        if (statsCards[2]) {
            const valueElement = statsCards[2].querySelector('.text-xl');
            if (valueElement) {
                valueElement.textContent = `Rp ${totalValue.toLocaleString()}`;
            }
        }
        
        // Active Products card
        if (statsCards[3]) {
            const activeCountElement = statsCards[3].querySelector('.text-2xl');
            if (activeCountElement) activeCountElement.textContent = activeProducts;
        }
    }

    // Edit product button functionality
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            // Add click animation
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            showNotification(`Membuka editor untuk produk ${productId}`, 'info');
            
            // Simulate navigation to edit page
            setTimeout(() => {
                // window.location.href = `/products/${productId}/edit`;
                console.log('Navigating to edit product:', productId);
            }, 500);
        });
    });

    // Keyboard shortcuts
    searchInput.addEventListener('keydown', function(e) {
        // Clear search with Escape key
        if (e.key === 'Escape') {
            this.value = '';
            performSearch();
        }
        
        // Focus search with Ctrl/Cmd + K
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            this.focus();
        }
    });

    // Global keyboard shortcut for search
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });

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

    function getNotificationIcon(type) {
        const icons = {
            success: 'bx-check-circle',
            error: 'bx-error',
            warning: 'bx-error-alt',
            info: 'bx-info-circle'
        };
        return icons[type] || 'bx-info-circle';
    }

    // Initialize the application
    initializeProducts();
    
    // Show initial notification
    setTimeout(() => {
        showNotification('Products management system ready', 'success');
    }, 1000);
});
</script>
