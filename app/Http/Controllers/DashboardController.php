<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            // Ambil data produk langsung dari database
            $productMetrics = $this->getProductMetricsFromDatabase();

            // Ambil data order REAL dan LENGKAP dari OrderController
            $orderController = new OrderController();
            $orderData = $orderController->getOrderDataForDashboard();
            
            // ✅ PERBAIKAN: Gunakan data orders ASLI, bukan yang sudah diformat terbatas
            $rawOrders = $orderData['orders'] ?? []; // Data lengkap dari TikTok API
            $orderMetrics = $orderData['metrics'] ?? $this->getEmptyOrderMetrics();
            
            // ✅ PERBAIKAN: Format data orders untuk dashboard dengan data LENGKAP
            $recentOrders = $this->formatRecentOrdersForDashboard($rawOrders);
            
            // Debug log untuk memastikan data lengkap
            Log::info('Dashboard Data Summary', [
                'total_products' => $productMetrics['total_products'],
                'total_orders' => count($rawOrders),
                'recent_orders_count' => count($recentOrders),
                'total_revenue' => $orderMetrics['total_revenue'],
                'data_source' => $orderData['success'] ? 'tiktok_api' : 'api_error'
            ]);

            return view('pages.dashboard', array_merge($productMetrics, [
                // Product metrics (real data from database)
                'total_products' => $productMetrics['total_products'],
                'total_stock' => $productMetrics['total_stock'],
                'active_products' => $productMetrics['active_products'],
                'inventory_value' => $productMetrics['inventory_value'],
                'low_stock_products' => $productMetrics['low_stock_products'],
                'out_of_stock_products' => $productMetrics['out_of_stock_products'],

                // Order metrics (real data from TikTok API)
                'active_orders' => $orderMetrics['active_orders'] ?? 0,
                'pending_shipment' => $orderMetrics['pending_orders'] ?? 0,
                'monthly_revenue' => $orderMetrics['monthly_revenue'] ?? 0,
                'total_revenue' => $orderMetrics['total_revenue'] ?? 0,

                // ✅ PERBAIKAN: Recent orders dengan data LENGKAP
                'recent_orders' => $recentOrders,

                // Additional info
                'data_source' => $orderData['success'] ? 'tiktok_api' : 'api_error',
                'last_updated' => now()->toDateTimeString(),
            ]));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());

            // Fallback dengan data minimal
            return view('pages.dashboard', $this->getFallbackData());
        }
    }

    /**
     * ✅ PERBAIKAN: Format recent orders untuk dashboard dengan data LENGKAP
     */
    private function formatRecentOrdersForDashboard($orders): array
    {
        $formattedOrders = [];
        
        // Batasi hanya 5 order terbaru untuk dashboard
        $recentOrders = array_slice($orders, 0, 5);
        
        foreach ($recentOrders as $order) {
            if (!is_array($order)) continue;
            
            // ✅ AMBIL DATA LENGKAP dari struktur TikTok API
            $orderId = $order['id'] ?? 'N/A';
            $shortOrderId = 'ORD-' . substr($orderId, -6);
            $customerName = $order['recipient_address']['name'] ?? 'Customer';
            
            // ✅ PERBAIKAN: Ambil amount dari berbagai kemungkinan struktur
            $totalAmount = $this->extractOrderAmount($order);
            $formattedAmount = 'Rp ' . number_format($totalAmount, 0, ',', '.');
            
            // ✅ PERBAIKAN: Status mapping yang komprehensif
            $status = strtolower($order['status'] ?? 'unknown');
            $displayStatus = $this->mapOrderStatus($status);
            
            // ✅ PERBAIKAN: Ambil informasi produk LENGKAP
            $productName = 'Tidak ada produk';
            $itemCount = 0;
            
            if (isset($order['line_items']) && is_array($order['line_items']) && count($order['line_items']) > 0) {
                $firstItem = $order['line_items'][0];
                $productName = $firstItem['product_name'] ?? 'Produk';
                $itemCount = count($order['line_items']);
                
                // Potong nama produk jika terlalu panjang
                if (strlen($productName) > 30) {
                    $productName = substr($productName, 0, 30) . '...';
                }
            }
            
            // Format tanggal
            $orderTime = $order['create_time'] ?? time();
            try {
                $formattedTime = \Carbon\Carbon::createFromTimestamp($orderTime)->format('M d, Y H:i');
            } catch (\Exception $e) {
                $formattedTime = 'Tanggal tidak diketahui';
            }

            $formattedOrders[] = [
                // ✅ DATA IDENTITAS LENGKAP
                'id' => $orderId,
                'short_id' => $shortOrderId,
                'customer_name' => $customerName,
                
                // ✅ DATA PEMBAYARAN LENGKAP
                'payment' => [
                    'total_amount' => $totalAmount,
                    'formatted_amount' => $formattedAmount
                ],
                'payment_info' => $order['payment_info'] ?? [],
                
                // ✅ DATA STATUS LENGKAP
                'status' => $status,
                'display_status' => $displayStatus,
                'create_time' => $orderTime,
                'formatted_time' => $formattedTime,
                
                // ✅ DATA PRODUK LENGKAP
                'product_name' => $productName,
                'item_count' => $itemCount,
                'line_items' => $order['line_items'] ?? [],
                
                // ✅ DATA PENGIRIMAN LENGKAP
                'shipping_provider' => $order['shipping_provider'] ?? null,
                'tracking_number' => $order['tracking_number'] ?? null,
                'recipient_address' => $order['recipient_address'] ?? [],
            ];
        }
        
        return $formattedOrders;
    }

    /**
     * ✅ PERBAIKAN: Extract order amount dari berbagai struktur TikTok API
     */
    private function extractOrderAmount($order): int
    {
        try {
            // Priority 1: payment_info->total_amount
            if (isset($order['payment_info']['total_amount'])) {
                return (int) $order['payment_info']['total_amount'];
            }
            
            // Priority 2: payment->total_amount
            if (isset($order['payment']['total_amount'])) {
                return (int) $order['payment']['total_amount'];
            }
            
            // Priority 3: Calculate from line items
            if (isset($order['line_items']) && is_array($order['line_items'])) {
                $total = 0;
                foreach ($order['line_items'] as $item) {
                    $quantity = (int) ($item['quantity'] ?? 1);
                    $price = (int) ($item['price'] ?? 0);
                    $total += $quantity * $price;
                }
                return $total;
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::warning('Error extracting order amount in Dashboard: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * ✅ PERBAIKAN: Map order status untuk display di dashboard
     */
    private function mapOrderStatus($tiktokStatus): string
    {
        $statusMap = [
            'completed' => 'Selesai',
            'delivered' => 'Selesai',
            'shipped' => 'Dikirim',
            'processed' => 'Diproses',
            'awaiting_shipment' => 'Menunggu Pengiriman',
            'unpaid' => 'Belum Dibayar',
            'cancelled' => 'Dibatalkan',
            'unknown' => 'Tidak Diketahui'
        ];
        
        return $statusMap[strtolower($tiktokStatus)] ?? 'Tidak Diketahui';
    }

    /**
     * Ambil metrik produk langsung dari database
     */
    private function getProductMetricsFromDatabase(): array
    {
        try {
            // Hitung total produk dan produk aktif
            $productStats = Product::selectRaw('
                COUNT(*) as total_products,
                SUM(CASE WHEN status = "ACTIVATE" THEN 1 ELSE 0 END) as active_products,
                SUM(stock) as total_stock,
                SUM(price * stock) as inventory_value,
                SUM(CASE WHEN stock < 10 AND stock > 0 THEN 1 ELSE 0 END) as low_stock_products,
                SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock_products
            ')->first();

            return [
                'total_products' => (int) ($productStats->total_products ?? 0),
                'active_products' => (int) ($productStats->active_products ?? 0),
                'total_stock' => (int) ($productStats->total_stock ?? 0),
                'inventory_value' => (int) ($productStats->inventory_value ?? 0),
                'low_stock_products' => (int) ($productStats->low_stock_products ?? 0),
                'out_of_stock_products' => (int) ($productStats->out_of_stock_products ?? 0),
            ];
        } catch (\Exception $e) {
            Log::error('Error getting product metrics: ' . $e->getMessage());
            return $this->getEmptyProductMetrics();
        }
    }

    /**
     * Data fallback jika terjadi error
     */
    private function getFallbackData(): array
    {
        return array_merge($this->getEmptyProductMetrics(), [
            'active_orders' => 0,
            'pending_shipment' => 0,
            'monthly_revenue' => 0,
            'total_revenue' => 0,
            'recent_orders' => [],
            'data_source' => 'fallback',
            'last_updated' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Empty product metrics for fallback
     */
    private function getEmptyProductMetrics(): array
    {
        return [
            'total_products' => 0,
            'active_products' => 0,
            'total_stock' => 0,
            'inventory_value' => 0,
            'low_stock_products' => 0,
            'out_of_stock_products' => 0,
        ];
    }

    /**
     * Empty order metrics for fallback
     */
    private function getEmptyOrderMetrics(): array
    {
        return [
            'total_orders' => 0,
            'pending_orders' => 0,
            'completed_orders' => 0,
            'cancelled_orders' => 0,
            'total_revenue' => 0,
            'active_orders' => 0,
            'monthly_revenue' => 0,
        ];
    }

    /**
     * API endpoint untuk data real-time (AJAX)
     */
    public function getDashboardData()
    {
        try {
            $productMetrics = $this->getProductMetricsFromDatabase();
            
            // Ambil data order terbaru dari TikTok API
            $orderController = new OrderController();
            $orderData = $orderController->getOrderDataForDashboard();
            
            // ✅ PERBAIKAN: Gunakan data lengkap
            $rawOrders = $orderData['orders'] ?? [];
            $recentOrders = $this->formatRecentOrdersForDashboard($rawOrders);
            
            $responseData = array_merge($productMetrics, [
                'active_orders' => $orderData['metrics']['active_orders'] ?? 0,
                'pending_shipment' => $orderData['metrics']['pending_orders'] ?? 0,
                'monthly_revenue' => $orderData['metrics']['monthly_revenue'] ?? 0,
                'total_revenue' => $orderData['metrics']['total_revenue'] ?? 0,
                'recent_orders' => $recentOrders, // ✅ Data yang sudah diformat dengan lengkap
            ]);

            Log::info('Dashboard API Response', [
                'product_count' => $productMetrics['total_products'],
                'order_count' => count($rawOrders),
                'recent_orders_count' => count($recentOrders),
                'data_source' => $orderData['success'] ? 'tiktok_api' : 'api_error'
            ]);

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'last_updated' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Dashboard API Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch dashboard data',
                'data' => $this->getFallbackData(),
            ], 500);
        }
    }

    /**
     * API untuk refresh specific metrics
     */
    public function refreshDashboardMetrics()
    {
        try {
            $request = request();
            $metrics = $request->input('metrics', ['products', 'orders']);
            
            $responseData = [];
            
            if (in_array('products', $metrics)) {
                $responseData = array_merge($responseData, $this->getProductMetricsFromDatabase());
            }
            
            if (in_array('orders', $metrics)) {
                $orderController = new OrderController();
                $orderData = $orderController->getOrderDataForDashboard();
                
                // ✅ PERBAIKAN: Format data orders untuk response
                $rawOrders = $orderData['orders'] ?? [];
                $recentOrders = $this->formatRecentOrdersForDashboard($rawOrders);
                
                $responseData = array_merge($responseData, [
                    'active_orders' => $orderData['metrics']['active_orders'] ?? 0,
                    'pending_shipment' => $orderData['metrics']['pending_orders'] ?? 0,
                    'monthly_revenue' => $orderData['metrics']['monthly_revenue'] ?? 0,
                    'total_revenue' => $orderData['metrics']['total_revenue'] ?? 0,
                    'recent_orders' => $recentOrders,
                ]);
            }

            return response()->json([
                'success' => true,
                'data' => $responseData,
                'last_updated' => now()->toDateTimeString(),
            ]);

        } catch (\Exception $e) {
            Log::error('Refresh Dashboard Metrics Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Failed to refresh dashboard metrics',
            ], 500);
        }
    }
}