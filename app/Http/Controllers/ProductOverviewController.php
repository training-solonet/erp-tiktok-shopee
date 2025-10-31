<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Helpers\Authtentication;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class ProductOverviewController extends Controller
{
    /**
     * Display products overview from database - SUPER CEPAT!
     */
    public function index()
    {
        try {
            // 🎯 LANGSUNG AMBIL DATA DARI DATABASE - NO API WAITING!
            $products = Product::orderBy('synced_at', 'desc')->get();

            // Format data sesuai kebutuhan view
            $productsData = [
                'success' => true,
                'count' => $products->count(),
                'products' => $products->toArray(),
                'last_sync' => $products->first()->synced_at ?? now()->toDateTimeString(),
                'source' => 'database',
                'message' => 'Data loaded from database - Instant loading!'
            ];

            Log::info('Product overview loaded from database', [
                'count' => $products->count(),
                'source' => 'database',
                'load_time' => 'instant'
            ]);

            return view('pages.products', [
                'products' => $productsData
            ]);

        } catch (Exception $e) {
            Log::error('Product overview error: ' . $e->getMessage());

            return view('pages.products', [
                'products' => [
                    'success' => false,
                    'count' => 0,
                    'products' => [],
                    'error' => 'Failed to load products from database: ' . $e->getMessage(),
                    'source' => 'database_error'
                ]
            ]);
        }
    }

    /**
     * Show individual product detail
     */
    /**
 * Show individual product detail - DIPERBAIKI
 */
public function show($id)
{
    try {
        Log::info('Product detail requested', ['product_id' => $id]);

        // Cari product by TikTok ID atau Database ID
        $product = Product::where('tiktok_product_id', $id)
            ->orWhere('id', $id)
            ->first();

        if (!$product) {
            Log::warning('Product not found', ['search_id' => $id]);
            return view('pages.product-detail', [
                'success' => false,
                'error' => 'Product not found: ' . $id
            ]);
        }

        Log::info('Product found', [
            'product_id' => $product->id,
            'tiktok_id' => $product->tiktok_product_id,
            'title' => $product->title
        ]);

        return view('pages.product-detail', [
            'product' => $product,
            'success' => true
        ]);

    } catch (Exception $e) {
        Log::error('Product detail error: ' . $e->getMessage());
        return view('pages.product-detail', [
            'success' => false,
            'error' => 'Failed to load product: ' . $e->getMessage()
        ]);
    }
}
    /**
     * Get products statistics for dashboard
     */
    public function getStats()
    {
        try {
            $totalProducts = Product::count();
            $activeProducts = Product::where('status', 'ACTIVATE')->count();
            $totalStock = Product::sum('stock');
            $inventoryValue = Product::sum(DB::raw('price * stock'));

            return response()->json([
                'success' => true,
                'stats' => [
                    'total_products' => $totalProducts,
                    'active_products' => $activeProducts,
                    'total_stock' => $totalStock,
                    'inventory_value' => $inventoryValue,
                    'avg_price' => $totalProducts > 0 ? Product::avg('price') : 0
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Product stats error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products by title or description
     */
    public function search(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->has('search') && $request->search) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('description', 'like', "%{$searchTerm}%");
                });
            }

            if ($request->has('status') && $request->status !== 'all') {
                $query->where('status', $request->status);
            }

            $products = $query->orderBy('synced_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'count' => $products->count(),
                'products' => $products->toArray(),
                'search_term' => $request->search ?? '',
                'status_filter' => $request->status ?? 'all'
            ]);

        } catch (Exception $e) {
            Log::error('Product search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update product stock (ERP Master → Database → TikTok)
     */
    /**
 * Update product stock (ERP Master → Database → TikTok)
 */
/**
 * Update product stock (ERP Master → Database → TikTok) - SIMPLE DEBUG VERSION
 */
public function updateStock(Request $request)
{
    // DEBUG: Log semua request data
    Log::info('=== STOCK UPDATE REQUEST START ===');
    Log::info('Request Data:', $request->all());
    Log::info('Headers:', $request->headers->all());
    Log::info('CSRF Token from request:', ['_token' => $request->input('_token')]);

    DB::beginTransaction();

    try {
        // Validasi sederhana dan jelas
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|string',
            'sku_id' => 'required|string',
            'warehouse_id' => 'required|string', 
            'new_stock' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                'errors' => $validator->errors()->toArray()
            ], 400);
        }

        $productId = $request->input('product_id');
        $skuId = $request->input('sku_id');
        $warehouseId = $request->input('warehouse_id');
        $newStock = $request->input('new_stock');

        Log::info('Processing stock update:', [
            'product_id' => $productId,
            'sku_id' => $skuId,
            'warehouse_id' => $warehouseId,
            'new_stock' => $newStock
        ]);

        // 1️⃣ CARI PRODUCT DI DATABASE
        $product = Product::where('tiktok_product_id', $productId)->first();
        
        if (!$product) {
            Log::error('Product not found in database:', ['tiktok_id' => $productId]);
            return response()->json([
                'success' => false,
                'message' => "Product tidak ditemukan dengan TikTok ID: {$productId}"
            ], 404);
        }

        Log::info('Product found:', [
            'db_id' => $product->id,
            'current_stock' => $product->stock,
            'title' => $product->title
        ]);

        $oldStock = $product->stock;

        // 2️⃣ UPDATE DATABASE - STEP 1: ERP MASTER
        $product->update([
            'stock' => $newStock,
            'synced_at' => now()
        ]);

        Log::info('Database updated successfully:', [
            'old_stock' => $oldStock,
            'new_stock' => $newStock
        ]);

        // 3️⃣ SYNC KE TIKTOK - STEP 2: TIKTOK SYNC
        Log::info('Starting TikTok sync...');
        $tiktokResult = $this->syncStockToTikTok($productId, $skuId, $warehouseId, $newStock);
        Log::info('TikTok sync result:', $tiktokResult);

        if (!$tiktokResult['success']) {
            // ROLLBACK JIKA TIKTOK GAGAL - untuk menjaga konsistensi
            DB::rollBack();
            
            Log::error('TikTok sync failed, database rolled back:', [
                'error' => $tiktokResult['message']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Update database berhasil, tetapi sync ke TikTok gagal: ' . $tiktokResult['message'],
                'erp_stock' => $oldStock, // Kembalikan stock lama di response
                'tiktok_sync' => $tiktokResult
            ], 500);
        }

        // 4️⃣ COMMIT JIKA SEMUA BERHASIL
        DB::commit();

        Log::info('=== STOCK UPDATE SUCCESS ===', [
            'product_id' => $productId,
            'final_stock' => $newStock,
            'tiktok_sync' => 'success'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diupdate di ERP dan TikTok Shop!',
            'data' => [
                'old_stock' => $oldStock,
                'new_stock' => $newStock,
                'product' => $product->fresh()->toArray(),
                'tiktok_sync' => $tiktokResult
            ]
        ]);

    } catch (Exception $e) {
        DB::rollBack();
        
        Log::error('=== STOCK UPDATE FAILED ===', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal update stok: ' . $e->getMessage(),
            'debug_info' => [
                'product_id' => $productId ?? 'unknown',
                'timestamp' => now()->toDateTimeString()
            ]
        ], 500);
    }
}
    /**
     * Sync stock to TikTok Shop - dengan timeout
     */
    private function syncStockToTikTok($productId, $skuId, $warehouseId, $newStock)
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->value('value');

            if (!$appKey || !$shopCipher) {
                throw new Exception('TikTok credentials not found');
            }

            $bodyArray = [
                'product_id' => $productId,
                'sku_id' => $skuId,
                'inventory' => [
                    [
                        'available_stock' => (int)$newStock,
                        'warehouse_id' => $warehouseId,
                    ],
                ],
            ];

            $path = "/product/202309/products/{$productId}/inventory/update";
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];

            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = "https://open-api.tiktokglobalshop.com{$path}";

            $response = Http::timeout(10)
                ->asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                    'Content-Type' => 'application/json'
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            $result = $response->json();

            if (($result['code'] ?? -1) === 0) {
                return [
                    'success' => true,
                    'message' => 'TikTok sync successful',
                    'response' => $result
                ];
            } else {
                $errorMessage = $result['message'] ?? 'Unknown TikTok API error';
                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            Log::error('TikTok sync error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'TikTok sync failed: ' . $e->getMessage()
            ];
        }
    }
    /**
     * Manual sync from TikTok to Database - FULL CODE
     */
    public function manualSync()
    {
        try {
            Log::info('Starting manual sync from TikTok to database');

            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first()?->value;
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first()?->value;

            if (!$appKey || !$shopCipher) {
                throw new Exception('TikTok credentials not complete in settings');
            }

            // Step 1: Get basic product list from TikTok
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

            if (!$response->successful()) {
                throw new Exception('Failed to fetch product list from TikTok: ' . $response->body());
            }

            $data = $response->json();
            if (($data['code'] ?? -1) !== 0) {
                throw new Exception('TikTok API error: ' . ($data['message'] ?? 'Unknown'));
            }

            $basicProducts = $data['data']['products'] ?? [];
            $syncedCount = 0;

            // Step 2: Get detailed info and sync to database
            foreach ($basicProducts as $product) {
                $productId = $product['id'] ?? null;
                if (!$productId) continue;

                try {
                    // Get product details
                    $detail = $this->fetchProductDetail($productId, $accessToken, $appKey, $shopCipher);
                    $merged = array_merge($product, ['detail' => $detail]);

                    // Sync to database
                    $this->syncSingleProductToDatabase($merged);
                    $syncedCount++;

                } catch (Exception $e) {
                    Log::error("Failed to sync product {$productId}: " . $e->getMessage());
                    continue;
                }
            }

            Log::info('Manual sync completed', ['synced_count' => $syncedCount]);

            return response()->json([
                'success' => true,
                'message' => 'Manual sync completed successfully',
                'synced_count' => $syncedCount,
                'total_products' => count($basicProducts)
            ]);

        } catch (Exception $e) {
            Log::error('Manual sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Manual sync failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Fetch product detail from TikTok - FULL CODE
     */
    private function fetchProductDetail(string $productId, string $accessToken, string $appKey, string $shopCipher)
    {
        try {
            $path = '/product/202309/products/' . $productId;
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];

            $signData = Authtentication::generateTikTokSignature($path, $params, '');
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            $response = Http::withHeaders(['x-tts-access-token' => $accessToken])
                ->withQueryParameters($params)
                ->get($url);

            if (!$response->successful()) {
                Log::warning("Failed to fetch detail for Product ID {$productId}: " . $response->body());
                return [];
            }

            $data = $response->json();
            if (($data['code'] ?? -1) !== 0) {
                Log::warning("TikTok detail error for {$productId}: " . ($data['message'] ?? 'Unknown'));
                return [];
            }

            $detail = $data['data']['product'] ?? $data['data'] ?? [];

            if (empty($detail)) {
                Log::info("No detail content found for Product ID {$productId}");
            }

            return $detail;

        } catch (Exception $e) {
            Log::error("Detail fetch exception for {$productId}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Sync single product to database - FULL CODE
     */
    private function syncSingleProductToDatabase(array $product)
    {
        if (empty($product['id'])) {
            return;
        }

        // Ensure detail is properly formatted
        if (!empty($product['detail']) && is_string($product['detail'])) {
            $decoded = json_decode($product['detail'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $product['detail'] = $decoded;
            }
        }

        $skuData = $product['skus'][0] ?? [];
        $firstInventory = $skuData['inventory'][0] ?? [];
        $price = (int)($skuData['price']['tax_exclusive_price'] ?? 0);
        $stock = (int)($firstInventory['quantity'] ?? 0);

        // Extract all images
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
     * Extract all images from product data - FULL CODE
     */
    private function extractAllImages(array $product): array
    {
        $images = [];

        // 1️⃣ Main images from detail.main_images
        if (!empty($product['detail']['main_images'])) {
            foreach ($product['detail']['main_images'] as $img) {
                if (!empty($img['urls']) && is_array($img['urls'])) {
                    foreach ($img['urls'] as $url) {
                        $images[] = $url;
                    }
                }
            }
        }

        // 2️⃣ Images from size chart
        if (!empty($product['detail']['size_chart']['image']['urls'])) {
            foreach ($product['detail']['size_chart']['image']['urls'] as $url) {
                $images[] = $url;
            }
        }

        // 3️⃣ Fallback from other image keys
        foreach (['images', 'cover_images'] as $key) {
            if (!empty($product[$key]) && is_array($product[$key])) {
                foreach ($product[$key] as $img) {
                    if (is_array($img) && !empty($img['url'])) {
                        $images[] = $img['url'];
                    } elseif (is_string($img)) {
                        $images[] = $img;
                    }
                }
            }
        }

        // Clean and return unique images
        $images = array_values(array_unique(array_filter($images)));
        return $images;
    }

    /**
     * Get product metrics for dashboard
     */
    public function getProductMetrics()
    {
        try {
            $totalProducts = Product::count();
            $totalStock = Product::sum('stock');
            $activeProducts = Product::where('status', 'ACTIVATE')->count();
            $inventoryValue = Product::sum(DB::raw('price * stock'));

            return [
                'total_products' => $totalProducts,
                'total_stock' => $totalStock,
                'active_products' => $activeProducts,
                'inventory_value' => $inventoryValue,
            ];

        } catch (Exception $e) {
            Log::error('Product metrics error: ' . $e->getMessage());
            return [
                'total_products' => 0,
                'total_stock' => 0,
                'active_products' => 0,
                'inventory_value' => 0,
            ];
        }
    }
}