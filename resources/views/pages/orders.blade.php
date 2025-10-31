@extends('layouts.app')

@section('title', 'Orders Management')
@section('subtitle', 'Manage and track your customer orders')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
            <p class="text-gray-600 mt-1">Track and manage all customer orders from multiple platforms</p>
        </div>
        <div class="flex items-center space-x-3 mt-4 lg:mt-0">
            <!-- Sync Button -->
            <button class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-lg font-medium transition-all duration-300 flex items-center shadow-sm hover:shadow-md">
                <i class='bx bx-refresh mr-2'></i>
                Sync Orders
            </button>
            
            <!-- Filter Button -->
            <button class="border border-gray-300 hover:border-gray-400 bg-white px-4 py-2.5 rounded-lg font-medium transition-all duration-300 flex items-center">
                <i class='bx bx-filter-alt mr-2'></i>
                Filter
            </button>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">1,248</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <i class='bx bx-shopping-bag text-blue-500 text-lg'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border bor der-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Pending</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">48</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                    <i class='bx bx-time text-amber-500 text-lg'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Completed</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">892</p>
                </div>
                <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <i class='bx bx-check-circle text-green-500 text-lg'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Revenue</p>
                    <p class="text-xl font-bold text-gray-900 mt-1">Rp 89.2M</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i class='bx bx-dollar-circle text-purple-500 text-lg'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Orders Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Table Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center space-x-4">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                    <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-xs font-medium">
                        {{ is_array($orders) ? count($orders) : $orders->count() }} orders
                    </span>
                </div>
                
                <!-- Platform Filter -->
                <div class="flex items-center space-x-3 mt-3 sm:mt-0">
                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white">
                        <option>All Platforms</option>
                        <option>TikTok Shop</option>
                        <option>Shopee</option>
                        <option>Website</option>
                    </select>
                    
                    <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-transparent bg-white">
                        <option>All Status</option>
                        <option>Pending</option>
                        <option>Processing</option>
                        <option>Shipped</option>
                        <option>Completed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="divide-y divide-gray-200">
            @if(isset($orders) && (is_array($orders) ? count($orders) : $orders->count()) > 0)
                @foreach($orders as $order)
                @php
                    // Format order data
                    $orderId = 'ORD-' . (isset($order['id']) ? substr($order['id'], -6) : 'N/A');
                    $customerName = $order['recipient_address']['name'] ?? 'Customer';
                    $customerInitials = strtoupper(substr($customerName, 0, 2));
                    $amount = 'Rp ' . number_format($order['payment']['total_amount'] ?? 0);
                    $status = strtolower($order['status'] ?? 'unknown');
                    
                    // Status configuration
                    $statusConfig = [
                        'completed' => ['color' => 'green', 'icon' => 'bx-check-circle', 'text' => 'Completed', 'bg' => 'bg-green-50', 'textColor' => 'text-green-700', 'border' => 'border-green-200'],
                        'shipped' => ['color' => 'blue', 'icon' => 'bx-package', 'text' => 'Shipped', 'bg' => 'bg-blue-50', 'textColor' => 'text-blue-700', 'border' => 'border-blue-200'],
                        'processing' => ['color' => 'amber', 'icon' => 'bx-time', 'text' => 'Processing', 'bg' => 'bg-amber-50', 'textColor' => 'text-amber-700', 'border' => 'border-amber-200'],
                        'pending' => ['color' => 'gray', 'icon' => 'bx-time-five', 'text' => 'Pending', 'bg' => 'bg-gray-50', 'textColor' => 'text-gray-700', 'border' => 'border-gray-200'],
                        'cancelled' => ['color' => 'red', 'icon' => 'bx-x-circle', 'text' => 'Cancelled', 'bg' => 'bg-red-50', 'textColor' => 'text-red-700', 'border' => 'border-red-200'],
                        'unknown' => ['color' => 'gray', 'icon' => 'bx-question-mark', 'text' => 'Unknown', 'bg' => 'bg-gray-50', 'textColor' => 'text-gray-700', 'border' => 'border-gray-200']
                    ];
                    
                    $statusInfo = $statusConfig[$status] ?? $statusConfig['unknown'];
                    
                    // Platform configuration
                    $platform = $order['commerce_platform'] ?? 'TIKTOK_SHOP';
                    $platformConfig = [
                        'TIKTOK_SHOP' => ['name' => 'TikTok', 'color' => 'bg-black', 'icon' => 'bx-music'],
                        'SHOPEE' => ['name' => 'Shopee', 'color' => 'bg-orange-500', 'icon' => 'bx-store'],
                        'WEBSITE' => ['name' => 'Website', 'color' => 'bg-blue-500', 'icon' => 'bx-globe']
                    ];
                    $platformInfo = $platformConfig[$platform] ?? ['name' => 'Unknown', 'color' => 'bg-gray-500', 'icon' => 'bx-question-mark'];
                    
                    // Product info
                    $productName = $order['line_items'][0]['product_name'] ?? 'Product';
                    $truncatedProduct = strlen($productName) > 40 ? substr($productName, 0, 40) . '...' : $productName;
                    $itemCount = count($order['line_items'] ?? []);
                    
                    // Format time
                    $orderTime = $order['create_time'] ?? time();
                    try {
                        $formattedTime = \Carbon\Carbon::createFromTimestamp($orderTime)->format('M d, Y H:i');
                    } catch (Exception $e) {
                        $formattedTime = 'Unknown date';
                    }
                @endphp
                
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                        <!-- Order Info -->
                        <div class="flex items-start space-x-4 flex-1">
                            <!-- Platform & Customer Avatar -->
                            <div class="relative">
                                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                    <!-- Platform Badge -->
                                    <div class="absolute -top-1 -right-1 w-5 h-5 {{ $platformInfo['color'] }} rounded-full flex items-center justify-center shadow-sm">
                                        <i class='bx {{ $platformInfo['icon'] }} text-white text-xs'></i>
                                    </div>
                                    
                                    <!-- Customer Initials -->
                                    <div class="w-8 h-8 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ $customerInitials }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $orderId }}</h4>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $statusInfo['bg'] }} {{ $statusInfo['textColor'] }} {{ $statusInfo['border'] }} border">
                                        <i class='bx {{ $statusInfo['icon'] }} mr-1'></i>
                                        {{ $statusInfo['text'] }}
                                    </span>
                                </div>
                                
                                <p class="text-gray-600 mb-1">{{ $customerName }}</p>
                                <p class="text-sm text-gray-500 mb-2">{{ $truncatedProduct }}</p>
                                
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <i class='bx bx-package mr-1'></i>
                                        {{ $itemCount }} item{{ $itemCount > 1 ? 's' : '' }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class='bx bx-store mr-1'></i>
                                        {{ $platformInfo['name'] }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class='bx bx-time mr-1'></i>
                                        {{ $formattedTime }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Amount & Actions -->
                        <div class="flex items-center justify-between lg:justify-end lg:space-x-4 mt-4 lg:mt-0">
                            <div class="text-right">
                                <span class="font-semibold text-gray-900 text-lg block">{{ $amount }}</span>
                                <p class="text-sm text-gray-500 mt-1">Total amount</p>
                            </div>
                            
                            <div class="flex items-center space-x-2 ml-4">
                                <button class="w-9 h-9 border border-gray-300 hover:border-gray-400 rounded-lg flex items-center justify-center transition-colors duration-200 hover:bg-gray-50">
                                    <i class='bx bx-dots-vertical-rounded text-gray-600'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <!-- Empty State -->
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <i class='bx bx-receipt text-3xl text-gray-400'></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-1">No orders found</h3>
                    @if(isset($error) && $error)
                        <p class="text-red-500 mb-2">Error: {{ $error }}</p>
                    @endif
                    <p class="text-gray-500 mb-6">There are currently no orders to display.</p>
                    <a href="{{ route('orders_menu') }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-300 inline-flex items-center">
                        <i class='bx bx-refresh mr-2'></i>
                        Refresh Orders
                    </a>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if(isset($orders) && (is_array($orders) ? count($orders) : $orders->count()) > 0)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <p class="text-sm text-gray-700 mb-4 sm:mb-0">
                    Showing <span class="font-medium">1-{{ is_array($orders) ? count($orders) : $orders->count() }}</span> of <span class="font-medium">{{ $total_orders ?? (is_array($orders) ? count($orders) : $orders->count()) }}</span> orders
                </p>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        <i class='bx bx-chevron-left mr-1'></i>
                        Previous
                    </button>
                    <button class="px-3 py-2 bg-amber-500 text-white rounded-lg text-sm font-medium">1</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">2</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200">3</button>
                    <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors duration-200 flex items-center">
                        Next
                        <i class='bx bx-chevron-right ml-1'></i>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

<style>
.transition-all {
    transition: all 0.3s ease-in-out;
}

/* Custom hover effects */
.hover\:bg-gray-50:hover {
    background-color: #f9fafb;
}

/* Smooth shadow transitions */
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
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

/* Animation for status badges */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.bg-amber-50 {
    animation: pulse 2s infinite;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Order item hover effects
    const orderItems = document.querySelectorAll('.divide-y > div');
    orderItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(4px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });

    // Sync button functionality
    const syncButton = document.querySelector('button:has(.bx-refresh)');
    if (syncButton) {
        syncButton.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="bx bx-loader-alt animate-spin mr-2"></i>Syncing...';
            this.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
                
                // Show success notification
                showNotification('Orders synced successfully!', 'success');
            }, 2000);
        });
    }

    // Filter functionality
    const filterSelects = document.querySelectorAll('select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            // Implement filter logic here
            console.log('Filter changed:', this.value);
        });
    });

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : 'bg-blue-500';
        
        notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg transform translate-x-full transition-transform duration-300 z-50`;
        notification.innerHTML = `
            <div class="flex items-center">
                <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-info-circle'} mr-2'></i>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 10);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
});
</script>