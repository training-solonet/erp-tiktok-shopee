<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    protected $productController;

    public function __construct()
    {
        $this->productController = new ProductController;
    }

    public function index()
    {
        // Get real product metrics from ProductController
        $productMetrics = $this->productController->getProductMetrics();

        // Generate dummy orders data (sementara)
        $dummyOrders = $this->generateDummyOrders();
        $orderMetrics = $this->calculateOrderMetrics($dummyOrders);

        return view('pages.dashboard', array_merge($productMetrics, [
            // Product metrics (real data)
            'total_products' => $productMetrics['total_products'],
            'total_stock' => $productMetrics['total_stock'],
            'active_products' => $productMetrics['active_products'],
            'inventory_value' => $productMetrics['inventory_value'],

            // Order metrics (dummy data for now)
            'active_orders' => $orderMetrics['active_orders'],
            'pending_shipment' => $orderMetrics['pending_shipment'],
            'monthly_revenue' => $orderMetrics['monthly_revenue'],
            'total_revenue' => $orderMetrics['total_revenue'],

            // Recent orders for display
            'recent_orders' => collect($dummyOrders)->take(5),
        ]));
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
}
