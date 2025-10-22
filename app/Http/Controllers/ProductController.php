<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductController extends Controller
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

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // Prepare request
            $path = '/product/202502/products/search';
            $bodyArray = [
                'status' => 'ACTIVATE',
                'listing_quality_tier' => 'GOOD',
            ];
            $body = json_encode($bodyArray);

            // Prepare query parameters (without sign and timestamp)
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 100,
            ];

            // Generate signature (will add timestamp inside)
            $signData = Authtentication::generateTikTokSignature($path, $params, $body);

            // Add sign and timestamp to params for URL
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            // Build full URL with query parameters
            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            // Hit TikTok API using POST with proper JSON formatting
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
                    $products = $data['data']['products'] ?? [];
                    
                    // Calculate product metrics
                    $productMetrics = $this->calculateProductMetrics($products);

                    return view('pages.products', array_merge($productMetrics, [
                        'products' => $products,
                        'total' => $data['data']['total'] ?? 0,
                        'success' => true,
                    ]));
                }

                // TikTok API error
                return view('pages.products', [
                    'products' => [],
                    'error' => $data['message'] ?? 'Unknown error from TikTok API',
                    'success' => false,
                ]);
            }

            // HTTP error
            return view('pages.products', [
                'products' => [],
                'error' => 'Failed to fetch products: ' . $response->body(),
                'success' => false,
            ]);

        } catch (Exception $e) {
            return view('pages.products', [
                'products' => [],
                'error' => $e->getMessage(),
                'success' => false,
            ]);
        }
    }

    /**
     * Calculate product metrics from products data
     */
    private function calculateProductMetrics($products)
    {
        $totalProducts = count($products);
        $totalStock = 0;
        $activeProducts = 0;
        $inventoryValue = 0;

        foreach ($products as $product) {
            $productStock = 0;
            $productPrice = 0;
            
            // Calculate stock and price from SKUs
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
            
            $totalStock += $productStock;
            $inventoryValue += $productPrice * $productStock;
            
            // Count active products
            if(($product['status'] ?? '') === 'ACTIVATE') {
                $activeProducts++;
            }
        }

        return [
            'total_products' => $totalProducts,
            'total_stock' => $totalStock,
            'active_products' => $activeProducts,
            'inventory_value' => $inventoryValue,
        ];
    }

    /**
     * Get product metrics for dashboard (new method)
     */
    public function getProductMetrics()
    {
        try {
            // Get access token dari helper
            $accessToken = Authtentication::getTikTokAccessToken();

            // Ambil parameter dari database
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // Prepare request
            $path = '/product/202502/products/search';
            $bodyArray = [
                'status' => 'ACTIVATE',
                'listing_quality_tier' => 'GOOD',
            ];

            // Prepare query parameters
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 100,
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
                    $products = $data['data']['products'] ?? [];
                    $productMetrics = $this->calculateProductMetrics($products);
                    
                    return $productMetrics;
                }
            }

            // Return default values if API fails
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'active_products' => 0,
                'inventory_value' => 0,
            ];

        } catch (Exception $e) {
            // Return default values on error
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'active_products' => 0,
                'inventory_value' => 0,
            ];
        }
    }
    // ... method lainnya tetap sama

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
