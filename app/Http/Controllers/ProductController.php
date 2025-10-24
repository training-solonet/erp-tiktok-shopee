<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // === 1️⃣ Ambil credential dari DB ===
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first()?->value;
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first()?->value;

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // === 2️⃣ Ambil daftar produk dasar ===
            $path = '/product/202502/products/search';
            $bodyArray = [
                'status' => 'ACTIVATE',
                'listing_quality_tier' => 'GOOD',
            ];
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
                'page_size' => 100,
            ];

            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = 'https://open-api.tiktokglobalshop.com' . $path;
            $response = Http::asJson()
                ->withHeaders(['x-tts-access-token' => $accessToken])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            if (! $response->successful()) {
                throw new Exception('Failed to fetch product list: ' . $response->body());
            }

            $data = $response->json();
            if (($data['code'] ?? -1) !== 0) {
                throw new Exception('TikTok API error: ' . ($data['message'] ?? 'Unknown'));
            }

            $basicProducts = $data['data']['products'] ?? [];

            // === 3️⃣ Ambil detail lengkap untuk setiap produk ===
            $detailedProducts = collect($basicProducts)->map(function ($product) use ($accessToken, $appKey, $shopCipher) {
                $productId = $product['id'] ?? null;
                if (! $productId) {
                    return $product;
                }

                $detail = $this->fetchProductDetail($productId, $accessToken, $appKey, $shopCipher);

                // Jika detail tidak null, merge
                if (! empty($detail)) {
                    return array_merge($product, ['detail' => $detail]);
                }

                // Jika gagal ambil detail, tetap kembalikan produk dasar
                return array_merge($product, ['detail' => null]);
            });

            // === 4️⃣ Return VIEW dengan data produk ===
            return view('pages.products', [
                'products' => [
                    'success' => true,
                    'count' => $detailedProducts->count(),
                    'products' => $detailedProducts->values()->toArray(),
                ],
            ]);

        } catch (Exception $e) {
            Log::error('TikTok Product Fetch Error: ' . $e->getMessage());

            // Return view dengan data kosong jika error
            return view('pages.products', [
                'products' => [
                    'success' => false,
                    'count' => 0,
                    'products' => [],
                    'error' => $e->getMessage(),
                ],
            ]);
        }
    }

    /**
     * Fetch single product detail from TikTok
     */
    private function fetchProductDetail(string $productId, string $accessToken, string $appKey, string $shopCipher)
    {
        try {
            $path = '/product/202309/products/' . $productId;
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];

            // Signature untuk endpoint GET tidak butuh body (kosong string)
            $signData = Authtentication::generateTikTokSignature($path, $params, '');
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            $response = Http::withHeaders(['x-tts-access-token' => $accessToken])
                ->withQueryParameters($params)
                ->get($url);

            if (! $response->successful()) {
                Log::warning("Failed to fetch detail for Product ID {$productId}: " . $response->body());

                return;
            }

            $data = $response->json();
            if (($data['code'] ?? -1) !== 0) {
                Log::warning("TikTok detail error for {$productId}: " . ($data['message'] ?? 'Unknown'));

                return;
            }

            // Beberapa response TikTok menempatkan data di ['data']['product'] atau ['data']
            $detail = $data['data']['product'] ?? $data['data'] ?? null;

            if (empty($detail)) {
                Log::info("No detail content found for Product ID {$productId}");
            }

            return $detail;
        } catch (Exception $e) {
            Log::error("Detail fetch exception for {$productId}: " . $e->getMessage());

            return;
        }
    }

    // ======================= METODE LAINNYA ======================= //

    private function calculateProductMetrics($products)
    {
        $totalProducts = count($products);
        $totalStock = 0;
        $activeProducts = 0;
        $inventoryValue = 0;

        foreach ($products as $product) {
            $productStock = 0;
            $productPrice = 0;

            if (isset($product['skus']) && is_array($product['skus'])) {
                foreach ($product['skus'] as $sku) {
                    if (isset($sku['inventory']) && is_array($sku['inventory'])) {
                        foreach ($sku['inventory'] as $inv) {
                            $productStock += $inv['quantity'] ?? 0;
                        }
                    }
                    if (isset($sku['price']['tax_exclusive_price'])) {
                        $productPrice = (int) $sku['price']['tax_exclusive_price'];
                    }
                }
            }

            $totalStock += $productStock;
            $inventoryValue += $productPrice * $productStock;

            if (($product['status'] ?? '') === 'ACTIVATE') {
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

    public function getProductMetrics()
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            $path = '/product/202502/products/search';
            $bodyArray = [
                'status' => 'ACTIVATE',
                'listing_quality_tier' => 'GOOD',
            ];
            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 100,
            ];

            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = 'https://open-api.tiktokglobalshop.com' . $path;
            $response = Http::asJson()
                ->withHeaders(['x-tts-access-token' => $accessToken])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['code']) && $data['code'] === 0) {
                    $products = $data['data']['products'] ?? [];

                    return $this->calculateProductMetrics($products);
                }
            }

            return [
                'total_products' => 0,
                'total_stock' => 0,
                'active_products' => 0,
                'inventory_value' => 0,
            ];
        } catch (Exception $e) {
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'active_products' => 0,
                'inventory_value' => 0,
            ];
        }
    }

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
