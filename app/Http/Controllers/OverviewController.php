<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OverviewController extends Controller
{
    /**
     * Display product overview page
     */
    public function show(string $id)
    {
        try {
            // === 1️⃣ Ambil credential dari DB ===
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first()?->value;
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first()?->value;

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // === 2️⃣ Ambil detail produk berdasarkan ID ===
            $productDetail = $this->fetchProductDetail($id, $accessToken, $appKey, $shopCipher);

            if (! $productDetail) {
                return redirect()->route('products.index')
                    ->with('error', 'Produk tidak ditemukan atau telah dihapus');
            }

            // === 3️⃣ Hitung metrics untuk produk ini ===
            $productMetrics = $this->calculateSingleProductMetrics($productDetail);

            // === 4️⃣ Return VIEW dengan data produk lengkap ===
            return view('pages.overview', [
                'product' => [
                    'success' => true,
                    'data' => $productDetail,
                    'metrics' => $productMetrics,
                    'productId' => $id,
                ],
            ]);

        } catch (Exception $e) {
            Log::error('Product Overview Error: ' . $e->getMessage());

            return redirect()->route('products.index')
                ->with('error', 'Terjadi kesalahan saat memuat detail produk: ' . $e->getMessage());
        }
    }

    /**
     * Fetch single product detail from TikTok API
     */
    private function fetchProductDetail(string $productId, string $accessToken, string $appKey, string $shopCipher)
    {
        try {
            $path = '/product/202309/products/' . $productId;
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];

            // Signature untuk endpoint GET
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

            // Ambil data produk dari response
            $productData = $data['data']['product'] ?? $data['data'] ?? null;

            if (empty($productData)) {
                Log::info("No detail content found for Product ID {$productId}");

                return;
            }

            return $productData;

        } catch (Exception $e) {
            Log::error("Detail fetch exception for {$productId}: " . $e->getMessage());

            return;
        }
    }

    /**
     * Calculate metrics for single product
     */
    private function calculateSingleProductMetrics($productData)
    {
        $totalStock = 0;
        $totalValue = 0;
        $skuCount = 0;
        $averagePrice = 0;

        if (isset($productData['skus']) && is_array($productData['skus'])) {
            $skuCount = count($productData['skus']);

            foreach ($productData['skus'] as $sku) {
                $skuStock = 0;
                $skuPrice = 0;

                // Hitung stok dari struktur TikTok
                if (isset($sku['stock_info']) && is_array($sku['stock_info'])) {
                    foreach ($sku['stock_info'] as $inv) {
                        $skuStock += $inv['available_stock'] ?? 0;
                    }
                }
                // Fallback untuk struktur inventory lama
                elseif (isset($sku['inventory']) && is_array($sku['inventory'])) {
                    foreach ($sku['inventory'] as $inv) {
                        $skuStock += $inv['quantity'] ?? 0;
                    }
                }

                // Ambil harga dari struktur TikTok
                if (isset($sku['price_info']['original_price'])) {
                    $skuPrice = (int) $sku['price_info']['original_price'];
                } elseif (isset($sku['price']['tax_exclusive_price'])) {
                    $skuPrice = (int) $sku['price']['tax_exclusive_price'];
                }

                $totalStock += $skuStock;
                $totalValue += $skuStock * $skuPrice;
            }

            $averagePrice = $totalStock > 0 ? $totalValue / $totalStock : 0;
        }

        return [
            'total_stock' => $totalStock,
            'total_value' => $totalValue,
            'average_price' => $averagePrice,
            'sku_count' => $skuCount,
            'stock_level' => $totalStock > 50 ? 'Aman' : ($totalStock > 10 ? 'Sedang' : 'Rendah'),
        ];
    }
}
