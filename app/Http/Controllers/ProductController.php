<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Product;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // 1️⃣ Ambil credential TikTok
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first()?->value;
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first()?->value;

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // 2️⃣ Ambil daftar produk dasar
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

            // 3️⃣ Ambil detail lengkap tiap produk dan sinkronkan ke database
            foreach ($basicProducts as $product) {
                $productId = $product['id'] ?? null;
                if (! $productId) {
                    continue;
                }

                $detail = $this->fetchProductDetail($productId, $accessToken, $appKey, $shopCipher);
                $merged = array_merge($product, ['detail' => $detail]);

                // 🔥 Sync ke database
                $this->syncToDatabase($merged);
            }

            // 4️⃣ 🎯 PERUBAHAN PENTING: Ambil data dari DATABASE, bukan dari API response
            $databaseProducts = Product::orderBy('synced_at', 'desc')->get();

            // 5️⃣ Format response untuk view (structure sama, tapi data dari database)
            $productsForView = [
                'success' => true,
                'count' => $databaseProducts->count(),
                'products' => $databaseProducts->toArray(),
                'last_sync' => now()->toDateTimeString(),
                'source' => 'database', // Tambahan info untuk debugging
            ];

            return view('pages.products', [
                'products' => $productsForView,
            ]);

        } catch (Exception $e) {
            Log::error('TikTok Product Fetch Error: ' . $e->getMessage());

            // 🛡️ FALLBACK: Tetap tampilkan data dari database meski sync gagal
            $databaseProducts = Product::orderBy('synced_at', 'desc')->get();

            return view('pages.products', [
                'products' => [
                    'success' => false,
                    'count' => $databaseProducts->count(),
                    'products' => $databaseProducts->toArray(),
                    'error' => $e->getMessage(),
                    'last_sync' => now()->toDateTimeString(),
                    'source' => 'database_fallback',
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

    /**
     * Sinkronisasi data produk TikTok ke database lokal
     */
    private function syncToDatabase(array $product)
    {
        if (empty($product['id'])) {
            return;
        }

        // Pastikan detail ter-decode
        if (! empty($product['detail']) && is_string($product['detail'])) {
            $decoded = json_decode($product['detail'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $product['detail'] = $decoded;
            }
        }

        $skuData = $product['skus'][0] ?? [];
        $firstInventory = $skuData['inventory'][0] ?? [];
        $price = (int) ($skuData['price']['tax_exclusive_price'] ?? 0);
        $stock = (int) ($firstInventory['quantity'] ?? 0);

        // Ambil semua gambar dari berbagai sumber
        $allImages = $this->extractAllImages($product);

        Product::updateOrCreate(
            ['tiktok_product_id' => $product['id']],
            [
                'title' => $product['title'] ?? 'No title',
                'description' => $product['detail']['description'] ?? null,
                'status' => $product['status'] ?? 'UNKNOWN',
                'skus' => json_encode($product['skus'] ?? []),
                'currency' => $skuData['price']['currency'] ?? 'IDR',
                'price' => $price,
                'stock' => $stock,
                'image' => $allImages[0] ?? null,
                'images' => json_encode($allImages),
                'synced_at' => now(),
            ]
        );
    }

    /**
     * Ekstrak semua gambar dari struktur produk TikTok.
     */
    private function extractAllImages(array $product): array
    {
        $images = [];

        // 1️⃣ Gambar utama dari detail.main_images
        if (! empty($product['detail']['main_images'])) {
            foreach ($product['detail']['main_images'] as $img) {
                if (! empty($img['urls']) && is_array($img['urls'])) {
                    foreach ($img['urls'] as $url) {
                        $images[] = $url;
                    }
                }
            }
        }

        // 2️⃣ Gambar dari size chart (jika ada)
        if (! empty($product['detail']['size_chart']['image']['urls'])) {
            foreach ($product['detail']['size_chart']['image']['urls'] as $url) {
                $images[] = $url;
            }
        }

        // 3️⃣ Fallback: jika ada key "images" atau "cover_images"
        foreach (['images', 'cover_images'] as $key) {
            if (! empty($product[$key]) && is_array($product[$key])) {
                foreach ($product[$key] as $img) {
                    if (is_array($img) && ! empty($img['url'])) {
                        $images[] = $img['url'];
                    } elseif (is_string($img)) {
                        $images[] = $img;
                    }
                }
            }
        }

        // 4️⃣ Bersihkan dan kembalikan hasil unik
        $images = array_values(array_unique(array_filter($images)));

        return $images;
    }

    /**
     * Update stock from ERP to TikTok
     */

    // ======================= METODE LAINNYA YANG TETAP SAMA ======================= //

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

    private const TTS_API_VERSION = '202309';

    public function updateStock(Request $request)
    {
        // 1) Validasi input
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|string',
            'sku_id' => 'required|string',
            'warehouse_id' => 'required|string',
            'new_stock' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid: ' . $validator->errors()->first(),
            ], 400);
        }

        try {
            $productId = (string) $request->input('product_id');
            $skuId = (string) $request->input('sku_id');        // ID SKU TikTok
            $warehouseId = (string) $request->input('warehouse_id');  // ID gudang TikTok
            $newStock = (int) $request->input('new_stock');

            // 2) Validasi lokal (opsional tapi membantu)
            $product = Product::where('tiktok_product_id', $productId)->first();
            if (! $product) {
                return response()->json(['success' => false, 'message' => "Product not found: {$productId}"], 404);
            }
            $skusLocal = json_decode($product->skus ?? '[]', true);
            $skuExists = collect(is_array($skusLocal) ? $skusLocal : [])->first(function ($s) use ($skuId) {
                // Di DB terkadang tersimpan 'id' atau 'sku_id'
                $sid = $s['id'] ?? $s['sku_id'] ?? null;

                return $sid && (string) $sid === (string) $skuId;
            });
            if (! $skuExists) {
                return response()->json([
                    'success' => false,
                    'message' => "SKU {$skuId} tidak ditemukan pada product {$productId}",
                ], 400);
            }

            // 3) Kredensial TikTok
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->value('value');

            if (! $accessToken || ! $appKey || ! $shopCipher) {
                return response()->json(['success' => false, 'message' => 'Missing TikTok API credentials'], 500);
            }

            // 4) Endpoint & parameter
            $apiVersion = '202309';
            $path = "/product/{$apiVersion}/products/{$productId}/inventory/update";
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];
            $url = "https://open-api.tiktokglobalshop.com{$path}";

            // Helper untuk kirim request dengan signing yang konsisten
            $sendSigned = function (array $body) use ($path, $params, $url, $accessToken) {
                $bodyJson = json_encode($body, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $signData = Authtentication::generateTikTokSignature($path, $params, $bodyJson);
                $qs = $params + ['sign' => $signData['sign'], 'timestamp' => $signData['timestamp']];

                $resp = Http::withHeaders([
                    'x-tts-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                    ->withQueryParameters($qs)
                    ->withBody($bodyJson, 'application/json')
                    ->timeout(30)
                    ->post($url);

                return [$resp, $resp->json(), $body];
            };

            // 5) Payload sesuai dokumen: gunakan 'id' pada skus[0]
            $payloadQuantity = [
                'skus' => [[
                    'id' => $skuId,   // ← WAJIB pakai 'id', bukan 'sku_id'
                    'inventory' => [[
                        'warehouse_id' => $warehouseId,
                        'quantity' => $newStock,
                    ]],
                ]],
            ];

            [$resp1, $json1, $req1] = $sendSigned($payloadQuantity);

            if ($resp1->successful() && (($json1['code'] ?? -1) === 0)) {
                $product->update(['stock' => $newStock, 'synced_at' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stok berhasil diupdate ke TikTok dan ERP',
                    'data' => [
                        'product_id' => $productId,
                        'sku_id' => $skuId,
                        'warehouse_id' => $warehouseId,
                        'stock' => $newStock,
                    ],
                    'tiktok' => $json1,
                ]);
            }

            // 6) Fallback jika 'quantity' ditolak oleh tenant tertentu: gunakan 'available_stock'
            $payloadAvailable = [
                'skus' => [[
                    'id' => $skuId,
                    'inventory' => [[
                        'warehouse_id' => $warehouseId,
                        'available_stock' => $newStock,
                    ]],
                ]],
            ];

            [$resp2, $json2, $req2] = $sendSigned($payloadAvailable);

            if ($resp2->successful() && (($json2['code'] ?? -1) === 0)) {
                $product->update(['stock' => $newStock, 'synced_at' => now()]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stok berhasil diupdate ke TikTok dan ERP',
                    'data' => [
                        'product_id' => $productId,
                        'sku_id' => $skuId,
                        'warehouse_id' => $warehouseId,
                        'stock' => $newStock,
                    ],
                    'tiktok' => $json2,
                ]);
            }

            // 7) Gagal: log lengkap untuk diagnosa
            $httpStatus = $resp2->status() ?: $resp1->status();
            $resBody = $json2 ?: $json1 ?: $resp2->body();

            Log::error('TikTok inventory update failed', [
                'product_id' => $productId,
                'sku_id' => $skuId,
                'warehouse' => $warehouseId,
                'http_status' => $httpStatus,
                'response' => $resBody,
                'first_try' => $req1,
                'second_try' => $req2,
            ]);

            $msg = is_array($resBody) ? ($resBody['message'] ?? 'Bad Request') : (string) $resBody;

            return response()->json([
                'success' => false,
                'message' => "HTTP error: {$httpStatus}" . ($msg ? " — {$msg}" : ''),
                'tiktok' => $resBody,
            ], 400);

        } catch (Exception $e) {
            Log::error('Update Stock Error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Batch update (aman, gak meledak karena method kosong)
     * Memanggil helper updateSingleProductInventory() per SKU x warehouse
     */
    public function updateTikTokInventory()
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->value('value');

            if (! $appKey || ! $shopCipher || ! $accessToken) {
                throw new Exception('Missing TikTok API credentials.');
            }

            Product::where('status', 'ACTIVATE')
                ->select('id', 'tiktok_product_id', 'title', 'stock', 'skus')
                ->chunk(30, function ($chunk) use ($accessToken, $appKey, $shopCipher) {
                    foreach ($chunk as $product) {
                        $this->updateSingleProductInventory($product, $accessToken, $appKey, $shopCipher);
                        usleep(200000); // 0.2s delay
                    }
                });

            return response()->json([
                'success' => true,
                'message' => 'Inventory batch update diproses.',
            ]);
        } catch (Exception $e) {
            Log::error('TikTok Inventory Update Error: ' . $e->getMessage());

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Helper: update satu product per SKU x warehouse (dipakai batch)
     */
    private function updateSingleProductInventory($product, string $accessToken, string $appKey, string $shopCipher): void
    {
        $productId = (string) $product->tiktok_product_id;
        $path = '/product/' . self::TTS_API_VERSION . "/products/{$productId}/inventory/update";

        $skus = json_decode($product->skus ?? '[]', true);
        if (! is_array($skus) || empty($skus)) {
            Log::warning("No SKUs for product {$productId}");

            return;
        }

        foreach ($skus as $sku) {
            $skuId = $sku['sku_id'] ?? $sku['id'] ?? null;
            if (! $skuId) {
                continue;
            }

            $invList = $sku['stock_info'] ?? $sku['inventory'] ?? [];
            if (! is_array($invList) || empty($invList)) {
                Log::warning("No inventory slots for SKU {$skuId} product {$productId}");
                continue;
            }

            foreach ($invList as $inv) {
                $warehouseId = $inv['warehouse_id'] ?? null;
                if (! $warehouseId) {
                    continue;
                }

                $newStock = (int) ($inv['available_stock'] ?? $inv['quantity'] ?? $product->stock ?? 0);

                $params = [
                    'app_key' => $appKey,
                    'shop_cipher' => $shopCipher,
                ];

                $bodyArray = [
                    'product_id' => $productId,
                    'inventory_list' => [[
                        'sku_id' => (string) $skuId,
                        'warehouse_id' => (string) $warehouseId,
                        'available_stock' => $newStock,
                    ]],
                ];
                $bodyJson = json_encode($bodyArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                $signData = Authtentication::generateTikTokSignature($path, $params, $bodyJson);
                $params['sign'] = $signData['sign'];
                $params['timestamp'] = $signData['timestamp'];

                $url = "https://open-api.tiktokglobalshop.com{$path}";

                $res = Http::withHeaders([
                    'x-tts-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])
                    ->withQueryParameters($params)
                    ->withBody($bodyJson, 'application/json')
                    ->timeout(30)
                    ->post($url);

                $data = $res->json();
                if (! $res->successful() || (($data['code'] ?? -1) !== 0)) {
                    Log::error('Batch SKU update failed', [
                        'product' => $productId,
                        'sku' => $skuId,
                        'warehouse' => $warehouseId,
                        'http' => $res->status(),
                        'body' => $data,
                    ]);
                } else {
                    Log::info('Batch SKU updated', [
                        'product' => $productId,
                        'sku' => $skuId,
                        'warehouse' => $warehouseId,
                    ]);
                }

                usleep(200000); // throttle 0.2s
            }
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
