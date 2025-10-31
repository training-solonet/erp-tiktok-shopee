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

            // Generate dummy orders data
            $dummyOrders = $this->generateDummyOrders();
            $orderMetrics = $this->calculateOrderMetrics($dummyOrders);

            return view('pages.dashboard', array_merge($productMetrics, [
                // Product metrics (real data from database)
                'total_products' => $productMetrics['total_products'],
                'total_stock' => $productMetrics['total_stock'],
                'active_products' => $productMetrics['active_products'],
                'inventory_value' => $productMetrics['inventory_value'],
                'low_stock_products' => $productMetrics['low_stock_products'],
                'out_of_stock_products' => $productMetrics['out_of_stock_products'],

                // Order metrics (dummy data for now)
                'active_orders' => $orderMetrics['active_orders'],
                'pending_shipment' => $orderMetrics['pending_shipment'],
                'monthly_revenue' => $orderMetrics['monthly_revenue'],
                'total_revenue' => $orderMetrics['total_revenue'],

                // Recent orders for display
                'recent_orders' => collect($dummyOrders)->take(5),

                // Additional info
                'data_source' => 'database',
                'last_updated' => now()->toDateTimeString(),
            ]));

        } catch (\Exception $e) {
            Log::error('Dashboard Error: ' . $e->getMessage());

            // Fallback dengan data minimal
            return view('pages.dashboard', $this->getFallbackData());
        }
    }

    /**
     * Ambil metrik produk langsung dari database
     */
    private function getProductMetricsFromDatabase(): array
    {
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
    }

    /**
     * Data fallback jika terjadi error
     */
    private function getFallbackData(): array
    {
        return [
            'total_products' => 0,
            'active_products' => 0,
            'total_stock' => 0,
            'inventory_value' => 0,
            'low_stock_products' => 0,
            'out_of_stock_products' => 0,
            'active_orders' => 0,
            'pending_shipment' => 0,
            'monthly_revenue' => 0,
            'total_revenue' => 0,
            'recent_orders' => [],
            'data_source' => 'fallback',
            'last_updated' => now()->toDateTimeString(),
        ];
    }

    /**
     * Generate dummy orders data
     */
    private function generateDummyOrders()
    {
        return [
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Sarah Wijaya'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'completed',
                'create_time' => time() - rand(3600, 86400),
            ],
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Budi Santoso'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'processing',
                'create_time' => time() - rand(3600, 86400),
            ],
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Maya Sari'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'pending',
                'create_time' => time() - rand(3600, 86400),
            ],
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Rizki Pratama'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'completed',
                'create_time' => time() - rand(3600, 86400),
            ],
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Dewi Anggraini'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'processing',
                'create_time' => time() - rand(3600, 86400),
            ],
            [
                'id' => 'ORD-' . rand(1000, 9999),
                'recipient_address' => ['name' => 'Ahmad Fauzi'],
                'payment' => ['total_amount' => rand(200000, 1000000)],
                'status' => 'pending',
                'create_time' => time() - rand(3600, 86400),
            ],
        ];
    }

    /**
     * Calculate order metrics from orders data
     */
    private function calculateOrderMetrics($orders)
    {
        $activeOrders = 0;
        $pendingShipment = 0;
        $totalRevenue = 0;

        foreach ($orders as $order) {
            $status = $order['status'] ?? '';
            $totalAmount = $order['payment']['total_amount'] ?? 0;

            // Count active orders (pending and processing)
            if (in_array($status, ['pending', 'processing'])) {
                $activeOrders++;
            }

            // Count pending shipment
            if ($status === 'pending') {
                $pendingShipment++;
            }

            // Calculate total revenue from completed orders
            if ($status === 'completed') {
                $totalRevenue += $totalAmount;
            }
        }

        // Monthly revenue estimation (3x total completed orders revenue)
        $monthlyRevenue = $totalRevenue * 3;

        return [
            'active_orders' => $activeOrders,
            'pending_shipment' => $pendingShipment,
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    /**
     * API endpoint untuk data real-time (AJAX)
     */
    public function getDashboardData()
    {
        try {
            $productMetrics = $this->getProductMetricsFromDatabase();

            return response()->json([
                'success' => true,
                'data' => $productMetrics,
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
}
