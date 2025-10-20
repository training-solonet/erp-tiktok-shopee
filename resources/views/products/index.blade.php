<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TikTok Products - ERP System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <!-- Header -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-boxes text-3xl text-purple-600"></i>
                        <span class="ml-3 text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                            TikTok Shop ERP
                        </span>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="text-gray-600 hover:text-gray-900 transition">
                        <i class="fas fa-bell text-xl"></i>
                    </button>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-purple-500 to-pink-500 flex items-center justify-center text-white font-semibold">
                        AD
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Product Catalog</h1>
                    <p class="text-gray-600">Manage your TikTok Shop products</p>
                </div>
                <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200 flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add Product</span>
                </button>
            </div>
        </div>

        @if(isset($error))
            <!-- Error Alert -->
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                    <div>
                        <p class="font-semibold text-red-800">Error</p>
                        <p class="text-red-700 text-sm">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($success) && $success)
            @php
                // Calculate total stock and total value
                $totalStock = 0;
                $totalValue = 0;
                foreach($products as $product) {
                    if(isset($product['skus']) && is_array($product['skus'])) {
                        foreach($product['skus'] as $sku) {
                            if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                foreach($sku['inventory'] as $inv) {
                                    $totalStock += $inv['quantity'] ?? 0;
                                }
                            }
                            if(isset($sku['price']['tax_exclusive_price'])) {
                                $price = (int)$sku['price']['tax_exclusive_price'];
                                $qty = 0;
                                if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                    foreach($sku['inventory'] as $inv) {
                                        $qty += $inv['quantity'] ?? 0;
                                    }
                                }
                                $totalValue += ($price * $qty);
                            }
                        }
                    }
                }
            @endphp
            
            <!-- Success Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Products</p>
                            <p class="text-3xl font-bold text-gray-900">{{ $total ?? count($products) }}</p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-4">
                            <i class="fas fa-box text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Active Products</p>
                            <p class="text-3xl font-bold text-green-600">{{ count($products) }}</p>
                        </div>
                        <div class="bg-green-100 rounded-full p-4">
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Stock</p>
                            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalStock) }}</p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-4">
                            <i class="fas fa-warehouse text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200 hover:shadow-lg transition">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium mb-1">Total Value</p>
                            <p class="text-2xl font-bold text-amber-600">Rp {{ number_format($totalValue) }}</p>
                        </div>
                        <div class="bg-amber-100 rounded-full p-4">
                            <i class="fas fa-dollar-sign text-amber-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            @if(count($products) > 0)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
                    <!-- Filter Bar -->
                    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0">
                            <div class="relative flex-1 max-w-md">
                                <input type="text" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <div class="flex space-x-2">
                                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                                    <option>All Status</option>
                                    <option>Active</option>
                                    <option>Inactive</option>
                                </select>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($products as $product)
                                @php
                                    // Calculate total stock for this product
                                    $productStock = 0;
                                    $productPrice = 0;
                                    $currency = 'IDR';
                                    
                                    if(isset($product['skus']) && is_array($product['skus'])) {
                                        foreach($product['skus'] as $sku) {
                                            if(isset($sku['inventory']) && is_array($sku['inventory'])) {
                                                foreach($sku['inventory'] as $inv) {
                                                    $productStock += $inv['quantity'] ?? 0;
                                                }
                                            }
                                            if(isset($sku['price']['tax_exclusive_price'])) {
                                                $productPrice = (int)$sku['price']['tax_exclusive_price'];
                                                $currency = $sku['price']['currency'] ?? 'IDR';
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex-shrink-0 h-16 w-16 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center">
                                                @if(isset($product['main_images']) && count($product['main_images']) > 0)
                                                    <img src="{{ $product['main_images'][0]['url_list'][0] ?? '' }}" alt="Product" class="h-full w-full object-cover rounded-lg">
                                                @else
                                                    <i class="fas fa-image text-purple-400 text-2xl"></i>
                                                @endif
                                            </div>
                                            <div class="max-w-md">
                                                <p class="font-semibold text-gray-900 line-clamp-2">{{ $product['title'] ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Created: {{ isset($product['create_time']) ? date('d M Y', $product['create_time']) : 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $product['id'] ?? 'N/A' }}</code>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold 
                                            {{ ($product['status'] ?? '') === 'ACTIVATE' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            <i class="fas fa-circle text-xs mr-1.5"></i>
                                            {{ $product['status'] ?? 'Unknown' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="flex items-center">
                                                <i class="fas fa-boxes text-gray-400 mr-2"></i>
                                                <span class="font-semibold text-gray-900">{{ number_format($productStock) }}</span>
                                            </div>
                                            <span class="text-xs text-gray-500 mt-1">
                                                {{ count($product['skus'] ?? []) }} SKU(s)
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <div class="font-semibold text-gray-900">
                                                Rp {{ number_format($productPrice) }}
                                            </div>
                                            <span class="text-xs text-gray-500">
                                                Tax Exclusive
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-800 transition" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="text-green-600 hover:text-green-800 transition" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-800 transition" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                Showing <span class="font-semibold">1</span> to <span class="font-semibold">{{ count($products) }}</span> of <span class="font-semibold">{{ $total ?? count($products) }}</span> results
                            </p>
                            <div class="flex space-x-2">
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-white transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button class="px-4 py-2 bg-purple-600 text-white rounded-lg font-semibold">1</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-white transition">2</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-white transition">3</button>
                                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-white transition">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-xl shadow-md border border-gray-200 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-4">
                        <i class="fas fa-box-open text-4xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Found</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first product to the catalog.</p>
                    <button class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transform hover:-translate-y-0.5 transition duration-200">
                        <i class="fas fa-plus mr-2"></i>Add Your First Product
                    </button>
                </div>
            @endif
        @endif
    </div>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row items-center justify-between">
                <p class="text-gray-600 text-sm">© 2025 TikTok Shop ERP. All rights reserved.</p>
                <div class="flex space-x-6 mt-3 sm:mt-0">
                    <a href="#" class="text-gray-600 hover:text-purple-600 transition text-sm">Documentation</a>
                    <a href="#" class="text-gray-600 hover:text-purple-600 transition text-sm">Support</a>
                    <a href="#" class="text-gray-600 hover:text-purple-600 transition text-sm">API</a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
