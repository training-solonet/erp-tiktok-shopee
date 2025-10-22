@extends('layouts.app')

@section('title', 'Koleksi Batik Eksklusif')
@section('subtitle', 'Keindahan seni batik dalam setiap detail')

@section('content')
<section id='products'>
    <div class="space-y-8">
        <!-- Header dengan Pencarian dan Filter -->
        <div class="flex flex-col md:flex-row gap-4 items-start md:items-center justify-between">
            <!-- Search Bar yang Lebih Halus -->
            <div class="flex-1 max-w-md">
                <div class="relative">
                    <input type="text" placeholder="Cari koleksi batik..." 
                           class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-1 focus:ring-amber-400 focus:border-amber-300 transition-all duration-200 bg-white/80 backdrop-blur-sm">
                    <i class='bx bx-search absolute left-3.5 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
                </div>
            </div>
            
            <!-- Filter dan Actions -->
            <div class="flex flex-wrap gap-3 items-center">
                <select class="px-3 py-2.5 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-amber-400 focus:border-amber-300 transition bg-white/80 backdrop-blur-sm">
                    <option>Semua Kategori</option>
                    <option>Batik Tulis</option>
                    <option>Batik Cap</option>
                    <option>Batik Modern</option>
                </select>
                
                <button class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center text-sm shadow-sm hover:shadow">
                    <i class='bx bx-plus mr-1.5'></i>
                    Tambah Produk
                </button>
            </div>
        </div>

        <!-- Products Grid yang Lebih Compact dan Elegan -->
        @if(isset($products) && count($products) > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
            @php
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
                
                // Status styling yang lebih refined
                $statusClass = 'bg-gray-100 text-gray-700';
                $statusText = $product['status'] ?? 'Unknown';
                
                if(($product['status'] ?? '') === 'ACTIVATE') {
                    $statusClass = 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                } elseif(($product['status'] ?? '') === 'LIMITED') {
                    $statusClass = 'bg-amber-50 text-amber-700 border border-amber-200';
                } elseif(($product['status'] ?? '') === 'SOLD_OUT') {
                    $statusClass = 'bg-rose-50 text-rose-700 border border-rose-200';
                }
            @endphp
            
            <!-- Product Card yang Lebih Compact -->
            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 group hover:-translate-y-1">
                <!-- Product Image -->
                <div class="h-48 relative overflow-hidden bg-gradient-to-br from-amber-50 to-amber-100/30">
                    @if(isset($product['main_images']) && count($product['main_images']) > 0)
                        <img src="{{ $product['main_images'][0]['url_list'][0] ?? '' }}" 
                             alt="{{ $product['title'] ?? 'Product Image' }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class='bx bx-package text-amber-300 text-3xl'></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge yang Lebih Halus -->
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }} backdrop-blur-sm">
                            {{ $statusText }}
                        </span>
                    </div>
                    
                    <!-- Quick Actions yang Minimalis -->
                    <div class="absolute top-3 left-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <button class="bg-white/90 hover:bg-white text-amber-600 p-1.5 rounded-lg shadow-sm transition-all duration-200">
                            <i class='bx bx-heart text-sm'></i>
                        </button>
                    </div>
                </div>

                <!-- Product Info yang Lebih Padat -->
                <div class="p-4">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-medium text-gray-800 text-sm leading-tight line-clamp-2 flex-1 pr-2">{{ $product['title'] ?? 'N/A' }}</h3>
                        <button class="text-gray-400 hover:text-amber-500 transition-colors flex-shrink-0">
                            <i class='bx bx-bookmark-plus text-lg'></i>
                        </button>
                    </div>
                    
                    <p class="text-xs text-gray-600 mb-3 line-clamp-2 leading-relaxed">{{ $product['description'] ?? 'Deskripsi tidak tersedia' }}</p>
                    
                    <!-- Price and Stock Compact -->
                    <div class="flex items-center justify-between mb-3">
                        <div>
                            <span class="text-lg font-semibold text-amber-600">Rp {{ number_format($productPrice, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class='bx bx-package mr-1'></i>
                            <span>{{ number_format($productStock, 0, ',', '.') }} stok</span>
                        </div>
                    </div>

                    <!-- Actions yang Lebih Compact -->
                    <div class="flex space-x-2">
                        <button class="flex-1 bg-amber-500 hover:bg-amber-600 text-white py-2 rounded-lg font-medium transition-all duration-200 flex items-center justify-center text-sm shadow-sm hover:shadow">
                            <i class='bx bx-shopping-bag mr-1.5'></i>
                            Beli
                        </button>
                        <button class="w-10 h-10 border border-gray-200 hover:border-amber-300 rounded-lg flex items-center justify-center transition-all duration-200 hover:bg-amber-50">
                            <i class='bx bx-dots-vertical-rounded text-gray-500 text-lg'></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination yang Lebih Minimalis -->
        <div class="flex flex-col sm:flex-row items-center justify-between pt-6 border-t border-gray-100">
            <p class="text-sm text-gray-600 mb-3 sm:mb-0">
                Menampilkan <span class="font-medium text-amber-600">{{ count($products) }}</span> produk
            </p>
            <div class="flex space-x-1">
                <button class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-amber-50 transition-all duration-200 flex items-center">
                    <i class='bx bx-chevron-left mr-1'></i>
                    Prev
                </button>
                <button class="px-3 py-2 text-sm bg-amber-500 text-white rounded-lg font-medium">1</button>
                <button class="px-3 py-2 text-sm border border-gray-200 rounded-lg hover:bg-amber-50 transition-all duration-200 flex items-center">
                    Next
                    <i class='bx bx-chevron-right ml-1'></i>
                </button>
            </div>
        </div>
        @else
        <!-- Empty State yang Lebih Minimalis -->
        <div class="bg-white rounded-xl border border-gray-100 p-12 text-center max-w-md mx-auto">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-amber-50 rounded-full mb-4">
                <i class='bx bx-package text-2xl text-amber-400'></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Produk</h3>
            <p class="text-gray-600 text-sm mb-6">Mulai dengan menambahkan produk batik pertama Anda.</p>
            <button class="bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-lg font-medium transition-all duration-200 flex items-center text-sm mx-auto shadow-sm hover:shadow">
                <i class='bx bx-plus mr-1.5'></i>Tambah Produk
            </button>
        </div>
        @endif
    </div>
</section>
@endsection

<style>
/* Custom styles untuk elegan dan profesional */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Smooth transitions yang lebih refined */
.transition-all {
    transition: all 0.2s ease-in-out;
}

/* Hover effects yang subtle */
.group:hover .group-hover\:scale-105 {
    transform: scale(1.05);
}

/* Backdrop blur untuk modern touch */
.backdrop-blur-sm {
    backdrop-filter: blur(4px);
}

/* Custom scrollbar untuk elegance */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}
</style>

<script>
// Enhanced interactions untuk user experience yang smooth
document.addEventListener('DOMContentLoaded', function() {
    // Wishlist functionality dengan animasi halus
    const wishlistButtons = document.querySelectorAll('.bx-bookmark-plus');
    
    wishlistButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const parentButton = this.closest('button');
            
            // Smooth color transition
            parentButton.classList.toggle('text-amber-500');
            
            // Icon change dengan transition
            if (this.classList.contains('bx-bookmark-plus')) {
                this.classList.remove('bx-bookmark-plus');
                this.classList.add('bx-bookmark');
                // Tambahkan notifikasi subtle
                showNotification('Ditambahkan ke wishlist');
            } else {
                this.classList.remove('bx-bookmark');
                this.classList.add('bx-bookmark-plus');
                showNotification('Dihapus dari wishlist');
            }
        });
    });
    
    // Product image hover effect enhancement
    const productCards = document.querySelectorAll('.group');
    
    productCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Search functionality dengan debounce
    let searchTimeout;
    const searchInput = document.querySelector('input[type="text"]');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                console.log('Searching for:', this.value);
                // Implement search logic here
            }, 300);
        });
    }
    
    // Helper function untuk notifikasi subtle
    function showNotification(message) {
        // Buat element notifikasi
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-gray-800 text-white px-4 py-2 rounded-lg text-sm opacity-0 transform translate-y-2 transition-all duration-300 z-50';
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Trigger animation
        setTimeout(() => {
            notification.classList.remove('opacity-0', 'translate-y-2');
            notification.classList.add('opacity-100', 'translate-y-0');
        }, 10);
        
        // Remove after 2 seconds
        setTimeout(() => {
            notification.classList.remove('opacity-100', 'translate-y-0');
            notification.classList.add('opacity-0', 'translate-y-2');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 2000);
    }
    
    // Add to cart animation
    const buyButtons = document.querySelectorAll('button:has(.bx-shopping-bag)');
    
    buyButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add subtle click feedback
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
            
            showNotification('Ditambahkan ke keranjang');
        });
    });
});
</script>