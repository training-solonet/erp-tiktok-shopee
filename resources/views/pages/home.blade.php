@extends('layouts.app')

@section('title', 'Products Management')
@section('subtitle', 'Manage your product catalog and inventory')

@section('content')
<section id="dashboard">
    <div class="space-y-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-primary to-primary-600 rounded-2xl shadow-xl p-8 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="mb-6 lg:mb-0">
                <h2 class="text-3xl font-display font-bold mb-2">Selamat Datang!</h2>
                <p class="text-primary-100 text-lg">Butik Solo Jala Buana Management System</p>
                <p class="text-primary-200 mt-2">Monitor your business performance in real-time</p>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-center">
                    <div class="text-2xl font-bold">{{ now()->format('d') }}</div>
                    <div class="text-sm text-primary-200">{{ now()->format('M Y') }}</div>
                </div>
                <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                    <i class='bx bx-calendar text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Key Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Total Products</p>
                    <p class="text-3xl font-bold text-gray-900">1,248</p>
                    <p class="text-sm text-green-600 mt-1 flex items-center">
                        <i class='bx bx-up-arrow-alt mr-1'></i>
                        12% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-package text-blue-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <!-- Active Orders -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Active Orders</p>
                    <p class="text-3xl font-bold text-gray-900">48</p>
                    <p class="text-sm text-blue-600 mt-1 flex items-center">
                        <i class='bx bx-time mr-1'></i>
                        8 pending shipment
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-cart-alt text-green-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <!-- Monthly Revenue -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-gray-900">Rp 89.2M</p>
                    <p class="text-sm text-amber-600 mt-1 flex items-center">
                        <i class='bx bx-trending-up mr-1'></i>
                        18% growth
                    </p>
                </div>
                <div class="w-14 h-14 bg-amber-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-dollar-circle text-amber-600 text-2xl'></i>
                </div>
            </div>
        </div>

        <!-- Customer Satisfaction -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium mb-1">Satisfaction</p>
                    <p class="text-3xl font-bold text-gray-900">4.8/5</p>
                    <p class="text-sm text-purple-600 mt-1 flex items-center">
                        <i class='bx bx-star mr-1'></i>
                        96% positive
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bx-heart text-purple-600 text-2xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts & Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Sales Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Sales Performance</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:ring-2 focus:ring-primary focus:border-transparent">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>Last 90 Days</option>
                </select>
            </div>
            <div class="h-64 bg-gradient-to-b from-gray-50 to-white rounded-xl border border-gray-200 flex items-center justify-center">
                <div class="text-center">
                    <i class='bx bx-bar-chart-alt-2 text-4xl text-gray-400 mb-3'></i>
                    <p class="text-gray-600">Sales chart visualization</p>
                    <p class="text-sm text-gray-500 mt-1">Interactive chart will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Top Products</h3>
                <span class="text-sm text-gray-600">This Month</span>
            </div>
            <div class="space-y-4">
                @foreach([
                    ['name' => 'Dress Elegant Premium', 'sales' => 89, 'revenue' => 'Rp 16.8M'],
                    ['name' => 'Blouse Lux Signature', 'sales' => 76, 'revenue' => 'Rp 12.1M'],
                    ['name' => 'Set Premium Wanita', 'sales' => 64, 'revenue' => 'Rp 14.7M'],
                    ['name' => 'Kemeja Pria Premium', 'sales' => 52, 'revenue' => 'Rp 9.3M'],
                    ['name' => 'Tunik Modern', 'sales' => 48, 'revenue' => 'Rp 7.6M']
                ] as $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-50 to-secondary-50 rounded-lg flex items-center justify-center">
                            <i class='bx bx-star text-primary text-lg'></i>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-800 text-sm">{{ $product['name'] }}</h4>
                            <p class="text-xs text-gray-600">{{ $product['sales'] }} units sold</p>
                        </div>
                    </div>
                    <span class="font-semibold text-primary text-sm">{{ $product['revenue'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Recent Activity & Quick Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- Recent Orders Section -->
<div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
            <p class="text-sm text-gray-500 mt-1">Latest orders from TikTok Shop</p>
        </div>
        <div class="flex items-center space-x-2">
            @if(isset($success) && $success)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <i class='bx bx-check-circle mr-1'></i>
                    Connected
                </span>
            @elseif(isset($error))
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <i class='bx bx-error mr-1'></i>
                    Error
                </span>
            @endif
        </div>
    </div>
    <div class="p-6">
        <!-- Error Message -->
        @if(isset($error) && $error)
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-center">
                    <i class='bx bx-error text-red-500 text-xl mr-3'></i>
                    <div>
                        <h4 class="text-red-800 font-medium">Failed to load orders</h4>
                        <p class="text-red-600 text-sm mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Orders List -->
        @if(isset($orders) && count($orders) > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        // Format order data dengan error handling
                        $orderId = 'ORD-' . (isset($order['id']) ? substr($order['id'], -6) : 'N/A');
                        $customerName = $order['recipient_address']['name'] ?? 'Customer';
                        $customerInitials = strtoupper(substr($customerName, 0, 2));
                        $amount = 'Rp ' . number_format($order['payment']['total_amount'] ?? 0);
                        $status = strtolower($order['status'] ?? 'unknown');
                        $orderTime = $order['create_time'] ?? time();
                        
                        // Convert TikTok timestamp to readable format
                        try {
                            $formattedTime = \Carbon\Carbon::createFromTimestamp($orderTime)->diffForHumans();
                        } catch (Exception $e) {
                            $formattedTime = 'Recently';
                        }
                        
                        // Status configuration
                        $statusConfig = [
                            'completed' => ['color' => 'green', 'icon' => 'bx-check-circle', 'text' => 'Completed'],
                            'shipped' => ['color' => 'blue', 'icon' => 'bx-package', 'text' => 'Shipped'],
                            'processing' => ['color' => 'amber', 'icon' => 'bx-time', 'text' => 'Processing'],
                            'unpaid' => ['color' => 'gray', 'icon' => 'bx-money', 'text' => 'Unpaid'],
                            'cancelled' => ['color' => 'red', 'icon' => 'bx-x-circle', 'text' => 'Cancelled'],
                            'unknown' => ['color' => 'gray', 'icon' => 'bx-question-mark', 'text' => 'Unknown']
                        ];
                        
                        $statusInfo = $statusConfig[$status] ?? $statusConfig['unknown'];
                        
                        // Platform configuration
                        $platform = $order['commerce_platform'] ?? 'TIKTOK_SHOP';
                        $platformName = $platform === 'TIKTOK_SHOP' ? 'TikTok' : $platform;
                        
                        // Product info
                        $productName = $order['line_items'][0]['product_name'] ?? 'Product';
                        $truncatedProduct = strlen($productName) > 35 ? substr($productName, 0, 35) . '...' : $productName;
                        $itemCount = count($order['line_items'] ?? []);
                    @endphp
                    
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 group border border-transparent hover:border-gray-200">
                        <div class="flex items-center space-x-4 flex-1 min-w-0">
                            <!-- Platform & Customer Avatar -->
                            <div class="relative">
                                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 flex items-center justify-center relative overflow-hidden">
                                    <!-- Platform Badge -->
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-black rounded-full flex items-center justify-center">
                                        <i class='bx bx-music text-white text-xs'></i>
                                    </div>
                                    
                                    <!-- Customer Initials -->
                                    <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                                        {{ $customerInitials }}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Details -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $orderId }}</h4>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                                        bg-{{ $statusInfo['color'] }}-100 text-{{ $statusInfo['color'] }}-800">
                                        <i class='bx {{ $statusInfo['icon'] }} mr-1 text-xs'></i>
                                        {{ $statusInfo['text'] }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 truncate">{{ $customerName }}</p>
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <i class='bx bx-package mr-1'></i>
                                    {{ $itemCount }} item{{ $itemCount > 1 ? 's' : '' }} • {{ $platformName }}
                                </p>
                            </div>
                        </div>
                        
                        <!-- Order Amount & Time -->
                        <div class="text-right ml-4">
                            <span class="font-semibold text-gray-900 block text-lg">{{ $amount }}</span>
                            <div class="flex items-center justify-end space-x-1 mt-1">
                                <i class='bx bx-time text-gray-400 text-xs'></i>
                                <p class="text-xs text-gray-500">{{ $formattedTime }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- View All Orders -->
            <div class="mt-6 pt-4 border-t border-gray-200">
                <a href="{{ route('orders.index') }}" class="flex items-center justify-center space-x-2 text-primary hover:text-primary-600 transition-colors group">
                    <span class="font-medium">View All Orders</span>
                    <i class='bx bx-chevron-right group-hover:translate-x-1 transition-transform'></i>
                </a>
            </div>
            
        @else
            <!-- Empty State -->
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                    <i class='bx bx-receipt text-3xl text-gray-400'></i>
                </div>
                <h4 class="text-lg font-semibold text-gray-900 mb-2">No Orders Found</h4>
                <p class="text-gray-600 mb-4">There are no recent orders to display.</p>
                
                @if(isset($error) && $error)
                    <div class="mt-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                        <p class="text-amber-800 text-sm">
                            <i class='bx bx-info-circle mr-1'></i>
                            Check TikTok API configuration in settings
                        </p>
                    </div>
                @else
                    <button class="bg-primary hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-300 inline-flex items-center">
                        <i class='bx bx-plus mr-2'></i>
                        Sync Orders
                    </button>
                @endif
            </div>
        @endif
    </div>
</div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Inventory Status -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Inventory Status</h3>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>In Stock</span>
                            <span>1,048 items</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 84%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Low Stock</span>
                            <span>32 items</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: 12%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between text-sm text-gray-600 mb-1">
                            <span>Out of Stock</span>
                            <span>8 items</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-red-500 h-2 rounded-full" style="width: 3%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('products.index') }}" class="p-4 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors text-center group">
                        <i class='bx bx-plus-circle text-primary text-2xl mb-2 block group-hover:scale-110 transition-transform'></i>
                        <span class="text-sm font-medium text-gray-800">Add Product</span>
                    </a>
                    <a href="#" class="p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors text-center group">
                        <i class='bx bx-bar-chart-alt text-blue-600 text-2xl mb-2 block group-hover:scale-110 transition-transform'></i>
                        <span class="text-sm font-medium text-gray-800">View Reports</span>
                    </a>
                    <a href="#" class="p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors text-center group">
                        <i class='bx bx-cog text-green-600 text-2xl mb-2 block group-hover:scale-110 transition-transform'></i>
                        <span class="text-sm font-medium text-gray-800">Settings</span>
                    </a>
                    <a href="#" class="p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors text-center group">
                        <i class='bx bx-support text-purple-600 text-2xl mb-2 block group-hover:scale-110 transition-transform'></i>
                        <span class="text-sm font-medium text-gray-800">Support</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Platform Performance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Platform Performance</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach([
                ['platform' => 'TikTok Shop', 'orders' => 642, 'growth' => '+18%', 'color' => 'bg-black'],
                ['platform' => 'Shopee', 'orders' => 428, 'growth' => '+12%', 'color' => 'bg-orange-500'],
                ['platform' => 'Tokopedia', 'orders' => 356, 'growth' => '+8%', 'color' => 'bg-green-500'],
                ['platform' => 'Website', 'orders' => 189, 'growth' => '+22%', 'color' => 'bg-blue-500']
            ] as $platform)
            <div class="text-center p-4 bg-gray-50 rounded-xl hover:shadow-md transition-all duration-300">
                <div class="w-12 h-12 {{ $platform['color'] }} rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class='bx bx-store text-white text-xl'></i>
                </div>
                <h4 class="font-semibold text-gray-800 mb-1">{{ $platform['platform'] }}</h4>
                <p class="text-2xl font-bold text-primary mb-1">{{ $platform['orders'] }}</p>
                <p class="text-sm text-green-600">{{ $platform['growth'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>
<br>
<br>

</section>
<section id='products'>
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1 max-w-md">
            <div class="relative">
                <input type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary focus:border-transparent transition">
                <i class='bx bx-search absolute left-3 top-3.5 text-gray-400'></i>
            </div>
        </div>
        <button class="bg-primary hover:bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold transition-colors duration-300 flex items-center">
            <i class='bx bx-plus mr-2'></i>
            Add Product
        </button>
    </div>

    <!-- Products Grid -->
    @if(isset($products) && count($products) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
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
        @endphp
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-300">
            <!-- Product Image -->
            <div class="h-48 bg-gray-100 relative overflow-hidden">
                @if(isset($product['main_images']) && count($product['main_images']) > 0)
                    <img src="{{ $product['main_images'][0]['url_list'][0] ?? '' }}" 
                         alt="{{ $product['title'] ?? 'Product Image' }}" 
                         class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-br from-primary-50 to-secondary-50 flex items-center justify-center">
                        <i class='bx bx-package text-primary text-4xl'></i>
                    </div>
                @endif
                
                <!-- Status Badge -->
                <div class="absolute top-4 right-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                        {{ ($product['status'] ?? '') === 'ACTIVATE' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $product['status'] ?? 'Unknown' }}
                    </span>
                </div>
            </div>

            <!-- Product Info -->
            <div class="p-5">
                <h3 class="font-semibold text-gray-800 text-lg mb-2 line-clamp-2">{{ $product['title'] ?? 'N/A' }}</h3>
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $product['description'] ?? 'No description available' }}</p>
                
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <span class="text-2xl font-bold text-primary">Rp {{ number_format($productPrice) }}</span>
                        <p class="text-xs text-gray-500">Tax Exclusive</p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-semibold text-gray-800">{{ number_format($productStock) }}</span>
                        <p class="text-xs text-gray-500">in stock</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex space-x-2">
                    <button class="flex-1 bg-primary hover:bg-primary-600 text-white py-2.5 rounded-lg font-medium transition-colors duration-300 flex items-center justify-center">
                        <i class='bx bx-cart mr-2'></i>
                        Buy Now
                    </button>
                    <button class="w-12 h-12 border border-gray-300 hover:border-gray-400 rounded-lg flex items-center justify-center transition-colors">
                        <i class='bx bx-dots-vertical-rounded text-gray-600'></i>
                    </button>
                </div>

                <!-- Product ID -->
                <div class="mt-3 pt-3 border-t border-gray-200">
                    <p class="text-xs text-gray-500">ID: {{ $product['id'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-600">
            Showing <span class="font-semibold">{{ count($products) }}</span> products
        </p>
        <div class="flex space-x-2">
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Previous</button>
            <button class="px-4 py-2 bg-primary text-white rounded-lg font-semibold">1</button>
            <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">Next</button>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
            <i class='bx bx-package text-4xl text-gray-400'></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Found</h3>
        <p class="text-gray-600 mb-6">Get started by adding your first product to the catalog.</p>
        <button class="bg-primary hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-300">
            <i class='bx bx-plus mr-2'></i>Add Your First Product
        </button>
    </div>
    @endif
</div>
</section>
@endsection
<style>
/* Custom styles for order status badges */
.bg-green-100 { background-color: #f0f9f0; }
.text-green-800 { color: #166534; }
.bg-blue-100 { background-color: #eff6ff; }
.text-blue-800 { color: #1e40af; }
.bg-amber-100 { background-color: #fffbeb; }
.text-amber-800 { color: #92400e; }
.bg-red-100 { background-color: #fef2f2; }
.text-red-800 { color: #991b1b; }
.bg-gray-100 { background-color: #f9fafb; }
.text-gray-800 { color: #374151; }

/* Platform badge colors */
.bg-black { background-color: #000000; }
.bg-orange-500 { background-color: #f97316; }
.bg-green-500 { background-color: #22c55e; }
.bg-blue-500 { background-color: #3b82f6; }
.bg-gray-500 { background-color: #6b7280; }
</style>

<script>
// Optional: Auto-refresh orders every 30 seconds
document.addEventListener('DOMContentLoaded', function() {
    let refreshInterval;
    
    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            refreshOrders();
        }, 30000); // 30 seconds
    }
    
    function refreshOrders() {
        const loadingElement = document.getElementById('ordersLoading');
        const ordersContainer = document.querySelector('.space-y-4');
        
        if (loadingElement && ordersContainer) {
            loadingElement.classList.remove('hidden');
            ordersContainer.classList.add('hidden');
            
            // Simulate API call - replace with actual API call
            setTimeout(() => {
                loadingElement.classList.add('hidden');
                ordersContainer.classList.remove('hidden');
            }, 1000);
        }
    }
    
    // Start auto-refresh
    startAutoRefresh();
    
    // Cleanup on page leave
    window.addEventListener('beforeunload', () => {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
});
</script>