<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\Authtentication;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Get access token dari helper
            $accessToken = Authtentication::getTikTokAccessToken();

            // Ambil parameter dari database
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (!$appKey || !$shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // Prepare request untuk orders
            $path = '/order/202309/orders/search';
            $bodyArray = [
                'page_size' => 50,
            ];

            // Prepare query parameters
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 50,
            ];

            // Generate signature
            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            // Build full URL
            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            // Hit TikTok API
            $response = Http::asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            if ($response->successful()) {
                $data = $response->json();

                // Check TikTok response code
                if (isset($data['code']) && $data['code'] === 0) {
                    $orders = $data['data']['orders'] ?? [];
                    
                    // Calculate order metrics - MENGGUNAKAN VERSI YANG SUDAH DIPERBAIKI
                    $orderMetrics = $this->calculateOrderMetrics($orders);

                    return view('pages.orders', array_merge($orderMetrics, [
                        'orders' => $orders,
                        'total' => $data['data']['total_count'] ?? count($orders),
                        'success' => true,
                    ]));
                }

                // TikTok API error
                return view('pages.orders', [
                    'orders' => [],
                    'error' => $data['message'] ?? 'Unknown error from TikTok API',
                    'success' => false,
                ]);
            }

            // HTTP error
            return view('pages.orders', [
                'orders' => [],
                'error' => 'Failed to fetch orders: ' . $response->body(),
                'success' => false,
            ]);

        } catch (Exception $e) {
            return view('pages.orders', [
                'orders' => [],
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }

    /**
     * Calculate order metrics from orders data - FIXED VERSION
     */
    public function getOrderDataForDashboard()
    {
        try {
            // Get access token dari helper
            $accessToken = Authtentication::getTikTokAccessToken();

            // Ambil parameter dari database
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (!$appKey || !$shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // Prepare request untuk orders - ambil lebih sedikit data untuk dashboard
            $path = '/order/202309/orders/search';
            $bodyArray = [
                'page_size' => 10, // Hanya ambil 10 order terbaru untuk dashboard
            ];

            // Prepare query parameters
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 10,
            ];

            // Generate signature
            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            // Build full URL
            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            // Hit TikTok API
            $response = Http::asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            if ($response->successful()) {
                $data = $response->json();

                // Check TikTok response code
                if (isset($data['code']) && $data['code'] === 0) {
                    $orders = $data['data']['orders'] ?? [];
                    
                    // Calculate order metrics - MENGGUNAKAN VERSI YANG SUDAH DIPERBAIKI
                    $orderMetrics = $this->calculateOrderMetrics($orders);
                    
                    // Format recent orders untuk dashboard
                    $recentOrders = $this->formatOrdersForDashboard($orders);

                    return [
                        'success' => true,
                        'orders' => $orders,
                        'metrics' => $orderMetrics,
                        'recent_orders' => $recentOrders,
                        'total' => $data['data']['total_count'] ?? count($orders),
                    ];
                }

                return [
                    'success' => false,
                    'error' => $data['message'] ?? 'Unknown error from TikTok API',
                    'orders' => [],
                    'metrics' => $this->getEmptyOrderMetrics(),
                    'recent_orders' => [],
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to fetch orders: ' . $response->body(),
                'orders' => [],
                'metrics' => $this->getEmptyOrderMetrics(),
                'recent_orders' => [],
            ];

        } catch (Exception $e) {
            Log::error('getOrderDataForDashboard Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'orders' => [],
                'metrics' => $this->getEmptyOrderMetrics(),
                'recent_orders' => [],
            ];
        }
    }

    /**
     * Format orders for dashboard display - FIXED VERSION
     */
    private function formatOrdersForDashboard($orders)
    {
        $formattedOrders = [];
        
        foreach ($orders as $order) {
            // Format data untuk kompatibilitas dengan tampilan dashboard
            $formattedOrders[] = [
                'id' => $order['id'] ?? 'N/A',
                'recipient_address' => [
                    'name' => $order['recipient_address']['name'] ?? 'Customer'
                ],
                // ✅ FIX: Pastikan struktur payment konsisten dengan yang diharapkan view
                'payment' => [
                    'total_amount' => $order['payment_info']['total_amount'] ?? 0
                ],
                // ✅ TAMBAH: Simpan juga payment_info asli untuk backup
                'payment_info' => $order['payment_info'] ?? [],
                'status' => $this->mapOrderStatus($order['status'] ?? 'unknown'),
                'create_time' => $order['create_time'] ?? time(),
                // Tambahkan field lain yang diperlukan dashboard
                'product_name' => $order['line_items'][0]['product_name'] ?? 'Product',
                'item_count' => count($order['line_items'] ?? []),
                // ✅ TAMBAH: Field penting untuk filtering di view
                'line_items' => $order['line_items'] ?? [],
                'shipping_provider' => $order['shipping_provider'] ?? null,
                'tracking_number' => $order['tracking_number'] ?? null,
            ];
        }
        
        return $formattedOrders;
    }

    /**
     * Map TikTok order status to dashboard status
     */
    private function mapOrderStatus($tiktokStatus)
    {
        $statusMap = [
            'completed' => 'completed',
            'delivered' => 'completed',
            'shipped' => 'processing',
            'processed' => 'processing',
            'awaiting_shipment' => 'pending',
            'unpaid' => 'pending',
            'cancelled' => 'cancelled'
        ];
        
        return $statusMap[strtolower($tiktokStatus)] ?? 'unknown';
    }

    /**
     * Calculate order metrics from orders data - FIXED VERSION
     * ✅ PERBAIKAN UTAMA: Total revenue hanya dari completed orders
     */
    private function calculateOrderMetrics($orders)
    {
        if (empty($orders) || !is_array($orders)) {
            return $this->getEmptyOrderMetrics();
        }

        $totalOrders = count($orders);
        $pendingOrders = 0;
        $completedOrders = 0;
        $cancelledOrders = 0;
        $totalRevenue = 0; // ✅ Hanya akan diisi dari completed orders

        foreach ($orders as $order) {
            if (!is_array($order)) continue;
            
            $status = strtolower($order['status'] ?? 'unknown');
            
            switch ($status) {
                case 'unpaid':
                case 'awaiting_shipment':
                    $pendingOrders++;
                    break;
                    
                case 'completed':
                case 'delivered':
                    $completedOrders++;
                    // ✅ FIX: Hanya hitung revenue dari completed orders
                    $amount = $this->safeExtractAmount($order);
                    $totalRevenue += $amount;
                    break;
                    
                case 'cancelled':
                    $cancelledOrders++;
                    break;
                    
                default:
                    $pendingOrders++;
                    break;
            }
        }

        // Log untuk debugging (opsional)
        Log::info('Order Metrics Calculated', [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'total_revenue' => $totalRevenue,
            'calculation_note' => 'Revenue hanya dari completed/delivered orders'
        ]);

        return [
            'total_orders' => $totalOrders,
            'pending_orders' => $pendingOrders,
            'completed_orders' => $completedOrders,
            'cancelled_orders' => $cancelledOrders,
            'total_revenue' => $totalRevenue, // ✅ Sekarang hanya dari completed orders
            'active_orders' => $pendingOrders, // Active orders = pending orders
            'monthly_revenue' => $completedOrders > 0 ? $totalRevenue : 0,
        ];
    }

    /**
     * Safe amount extraction dengan error handling
     */
    private function safeExtractAmount($order): int
    {
        try {
            // Priority 1: payment_info->total_amount (dari TikTok API)
            if (isset($order['payment_info']['total_amount'])) {
                return (int) $order['payment_info']['total_amount'];
            }
            
            // Priority 2: payment->total_amount (fallback untuk kompatibilitas)
            if (isset($order['payment']['total_amount'])) {
                return (int) $order['payment']['total_amount'];
            }
            
            return 0;
        } catch (\Exception $e) {
            Log::warning('Error extracting order amount: ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get empty order metrics for fallback
     */
    private function getEmptyOrderMetrics()
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
     * Sync orders manually
     */
    public function syncOrders()
    {
        try {
            // Get access token dari helper
            $accessToken = Authtentication::getTikTokAccessToken();

            // Ambil parameter dari database
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (!$appKey || !$shopCipher) {
                return response()->json([
                    'success' => false,
                    'message' => 'TikTok credentials not complete in settings'
                ], 400);
            }

            // Prepare request
            $path = '/order/202309/orders/search';
            $bodyArray = [
                'page_size' => 50,
            ];

            // Prepare query parameters
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 50,
            ];

            // Generate signature
            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            // Build full URL
            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            // Hit TikTok API
            $response = Http::asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['code']) && $data['code'] === 0) {
                    $orders = $data['data']['orders'] ?? [];
                    // ✅ GUNAKAN VERSI YANG SUDAH DIPERBAIKI
                    $orderMetrics = $this->calculateOrderMetrics($orders);

                    return response()->json([
                        'success' => true,
                        'message' => 'Orders synced successfully',
                        'data' => [
                            'orders' => $orders,
                            'metrics' => $orderMetrics,
                            'total' => $data['data']['total_count'] ?? count($orders)
                        ]
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => $data['message'] ?? 'Unknown error from TikTok API'
                ], 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to sync orders: ' . $response->body()
            ], 400);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}