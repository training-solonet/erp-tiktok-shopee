@extends('layouts.app')

@section('title', 'Dashboard Management - Camellia Boutique99')
@section('subtitle', 'Monitor kinerja bisnis Anda secara real-time')

@section('content')
<div class="space-y-6">
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
                    <a href="{{ route('products_menu') }}" class="flex items-center p-3 bg-amber-50 rounded-lg hover:bg-amber-100 transition-colors duration-200 group">
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
                    
                    <button onclick="exportReport()" class="w-full flex items-center p-3 bg-green-50 rounded-lg hover:bg-green-100 transition-colors duration-200 group">
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

<!-- Notification Container -->
<div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
@endsection

<style>
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
</style>

<script>
// Global state
let refreshInterval;

document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
    startAutoRefresh();
    setupEventListeners();
});

function initializeDashboard() {
    updateDateTime();
    setInterval(updateDateTime, 1000);
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
    noOrders.classList.add('hidden');
    
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

function exportReport() {
    showNotification('Mempersiapkan laporan untuk diunduh...', 'info');
    
    // Simulate report generation
    setTimeout(() => {
        showNotification('Laporan berhasil diekspor!', 'success');
        
        // Create a dummy download link
        const link = document.createElement('a');
        link.href = '#';
        link.download = `laporan-bisnis-${new Date().toISOString().split('T')[0]}.pdf`;
        link.click();
    }, 2000);
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