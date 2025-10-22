@extends('layouts.app')

@section('title', 'Dashboard Management')
@section('subtitle', 'Monitor your business performance in real-time')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-2xl shadow-lg p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500 rounded-full -translate-y-32 translate-x-32 opacity-20"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400 rounded-full translate-y-24 -translate-x-24 opacity-20"></div>
        
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between relative z-10">
            <div class="mb-6 lg:mb-0">
                <h2 class="text-3xl font-bold mb-2">Selamat Datang di Butik Solo Jala Buana</h2>
                <p class="text-amber-100 text-lg mb-1">Management System</p>
                <p class="text-amber-200 text-sm">Monitor your business performance in real-time</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center bg-white/10 backdrop-blur-sm rounded-xl p-3">
                    <div class="text-2xl font-bold">{{ now()->format('d') }}</div>
                    <div class="text-xs text-amber-200">{{ now()->format('M Y') }}</div>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                    <i class='bx bx-calendar text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Total Products -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Total Products</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $total_products ?? '1,248' }}</p>
                    <p class="text-xs text-emerald-600 flex items-center">
                        <i class='bx bx-up-arrow-alt mr-1'></i>
                        12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-package text-blue-500 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Active Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $active_orders ?? '48' }}</p>
                    <p class="text-xs text-blue-600 flex items-center">
                        <i class='bx bx-time mr-1'></i>
                        {{ $pending_shipment ?? '8' }} pending shipment
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-cart-alt text-green-500 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Monthly Revenue</p>
                    <p class="text-xl font-bold text-gray-900 mb-1">{{ $monthly_revenue ?? 'Rp 89.2M' }}</p>
                    <p class="text-xs text-amber-600 flex items-center">
                        <i class='bx bx-trending-up mr-1'></i>
                        18% growth
                    </p>
                </div>
                <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-dollar-circle text-amber-500 text-xl'></i>
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs font-medium mb-1 uppercase tracking-wide">Satisfaction</p>
                    <p class="text-2xl font-bold text-gray-900 mb-1">4.8/5</p>
                    <p class="text-xs text-purple-600 flex items-center">
                        <i class='bx bx-star mr-1'></i>
                        96% positive
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-heart text-purple-500 text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Performance yang Disederhanakan -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Platform Performance</h3>
                <p class="text-sm text-gray-500 mt-1">Orders distribution across platforms</p>
            </div>
            <div class="text-sm text-gray-500">Last 7 days</div>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @foreach([
                ['platform' => 'TikTok Shop', 'orders' => 156, 'growth' => '+18%', 'color' => 'bg-black', 'icon' => 'bx-store'],
                ['platform' => 'Shopee', 'orders' => 98, 'growth' => '+12%', 'color' => 'bg-orange-500', 'icon' => 'bx-store'],
                ['platform' => 'Website', 'orders' => 45, 'growth' => '+22%', 'color' => 'bg-blue-500', 'icon' => 'bx-globe']
            ] as $platform)
            <div class="text-center p-4 bg-gray-50 rounded-lg hover:shadow-sm transition-all duration-300 hover:-translate-y-1">
                <div class="w-10 h-10 {{ $platform['color'] }} rounded-lg flex items-center justify-center mx-auto mb-3 shadow-sm">
                    <i class='bx {{ $platform['icon'] }} text-white text-lg'></i>
                </div>
                <h4 class="font-semibold text-gray-800 text-sm mb-1">{{ $platform['platform'] }}</h4>
                <p class="text-lg font-bold text-amber-600 mb-1">{{ number_format($platform['orders']) }}</p>
                <p class="text-xs text-emerald-600 font-medium">{{ $platform['growth'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <p class="text-sm text-gray-500 mt-1">Latest customer orders</p>
            </div>
            <a href="{{ route('orders_menu') }}" class="text-amber-600 hover:text-amber-700 text-sm font-medium flex items-center">
                View All
                <i class='bx bx-chevron-right ml-1'></i>
            </a>
        </div>
        <div class="space-y-3">
            @if(isset($recent_orders) && count($recent_orders) > 0)
                @foreach($recent_orders->take(5) as $order)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-200 border border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center shadow-sm">
                            <i class='bx bx-receipt text-amber-500'></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800">ORD-{{ substr($order['id'] ?? '000000', -6) }}</h4>
                            <p class="text-sm text-gray-600">{{ $order['recipient_address']['name'] ?? 'Customer' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="font-semibold text-gray-900 block">Rp {{ number_format($order['payment']['total_amount'] ?? 0) }}</span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                            @if(($order['status'] ?? '') === 'completed') bg-emerald-100 text-emerald-800 border border-emerald-200
                            @elseif(($order['status'] ?? '') === 'processing') bg-blue-100 text-blue-800 border border-blue-200
                            @elseif(($order['status'] ?? '') === 'pending') bg-amber-100 text-amber-800 border border-amber-200
                            @else bg-gray-100 text-gray-800 border border-gray-200 @endif">
                            {{ ucfirst($order['status'] ?? 'unknown') }}
                        </span>
                    </div>
                </div>
                @endforeach
            @else
                <div class="text-center py-8">
                    <i class='bx bx-receipt text-4xl text-gray-300 mb-3'></i>
                    <p class="text-gray-500">No recent orders</p>
                    <p class="text-sm text-gray-400 mt-1">New orders will appear here</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Elegant Footer -->
    <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl shadow-lg p-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <h3 class="text-2xl font-bold mb-3">Butik Solo Jala Buana</h3>
                <p class="text-gray-300 mb-4 max-w-md">
                    Menyediakan batik berkualitas tinggi dengan desain elegan dan modern. 
                    Setiap karya mencerminkan keindahan budaya Indonesia.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class='bx bxl-instagram text-xl'></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class='bx bxl-facebook text-xl'></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                        <i class='bx bxl-whatsapp text-xl'></i>
                    </a>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <h4 class="font-semibold text-amber-400 mb-3">Kontak</h4>
                    <div class="space-y-2 text-sm text-gray-300">
                        <p class="flex items-center">
                            <i class='bx bx-phone mr-2 text-amber-400'></i>
                            +62 812-3456-7890
                        </p>
                        <p class="flex items-center">
                            <i class='bx bx-envelope mr-2 text-amber-400'></i>
                            hello@jalabuana.com
                        </p>
                        <p class="flex items-center">
                            <i class='bx bx-map mr-2 text-amber-400'></i>
                            Solo, Indonesia
                        </p>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-amber-400 mb-3">Jam Operasional</h4>
                    <div class="space-y-2 text-sm text-gray-300">
                        <p>Senin - Jumat: 09:00 - 18:00</p>
                        <p>Sabtu: 09:00 - 15:00</p>
                        <p>Minggu: Tutup</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row md:items-center md:justify-between">
            <p class="text-gray-400 text-sm mb-4 md:mb-0">
                &copy; 2024 Butik Solo Jala Buana. All rights reserved.
            </p>
            <div class="flex space-x-6 text-sm text-gray-400">
                <a href="#" class="hover:text-amber-400 transition-colors duration-300">Privacy Policy</a>
                <a href="#" class="hover:text-amber-400 transition-colors duration-300">Terms of Service</a>
                <a href="#" class="hover:text-amber-400 transition-colors duration-300">Support</a>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
.transition-all {
    transition: all 0.3s ease-in-out;
}

.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Smooth hover effects */
.hover\:-translate-y-1:hover {
    transform: translateY(-4px);
}

/* Elegant shadow transitions */
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}

.hover\:shadow-md:hover {
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

/* Custom scrollbar untuk konsistensi */
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
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh functionality untuk metrics
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            refreshMetrics();
        }, 30000); // 30 detik
    }
    
    function refreshMetrics() {
        // Simulasi refresh data metrics
        const metrics = document.querySelectorAll('.bg-white.rounded-xl');
        metrics.forEach(metric => {
            metric.classList.add('opacity-80');
            setTimeout(() => {
                metric.classList.remove('opacity-80');
            }, 1000);
        });
    }
    
    // Start auto-refresh
    startAutoRefresh();
    
    // Cleanup pada page unload
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });

    // Smooth hover effects untuk order items
    const orderItems = document.querySelectorAll('.bg-gray-50.rounded-lg');
    orderItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Platform performance cards interaction
    const platformCards = document.querySelectorAll('.text-center.p-4');
    platformCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>