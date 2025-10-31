<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Product;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductOverviewController extends Controller
{
    /**
     * Display products overview from database
     */
    public function index()
    {
        try {
            $products = Product::orderBy('synced_at', 'desc')->get();

            $productsData = [
                'success' => true,
                'count' => $products->count(),
                'products' => $products->toArray(),
                'last_sync' => $products->first()->synced_at ?? now()->toDateTimeString(),
                'source' => 'database',
                'message' => 'Data loaded from database - Instant loading!',
            ];

            Log::info('Product overview loaded', [
                'count' => $products->count(),
                'source' => 'database',
            ]);

            return view('pages.products', ['products' => $productsData]);
        } catch (Exception $e) {
            Log::error('Product overview error: ' . $e->getMessage());

            return view('pages.products', [
                'products' => [
                    'success' => false,
                    'count' => 0,
                    'products' => [],
                    'error' => 'Failed to load products: ' . $e->getMessage(),
                    'source' => 'database_error',
                ],
            ]);
        }
    }

    /**
     * Show individual product detail
     */
    public function show($id)
    {
        try {
            $product = Product::where('tiktok_product_id', $id)
                ->orWhere('id', $id)
                ->first();

            if (! $product) {
                return view('pages.product-detail', [
                    'success' => false,
                    'error' => "Product not found: {$id}",
                ]);
            }

            return view('pages.product-detail', [
                'product' => $product,
                'success' => true,
            ]);
        } catch (Exception $e) {
            Log::error('Product detail error: ' . $e->getMessage());

            return view('pages.product-detail', [
                'success' => false,
                'error' => 'Failed to load product: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Update product stock (ERP → DB → TikTok)
     */
    public function updateStock(Request $request)
    {
        Log::info('=== STOCK UPDATE REQUEST START ===', $request->all());

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required|string',
                'sku_id' => 'required|string',
                'warehouse_id' => 'required|string',
                'new_stock' => 'required|integer|min:0',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed', $validator->errors()->toArray());

                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors(),
                ], 400);
            }

            $productId = $request->product_id;
            $skuId = $request->sku_id;
            $warehouseId = $request->warehouse_id;
            $newStock = (int) $request->new_stock;

            $product = Product::where('tiktok_product_id', $productId)->first();
            if (! $product) {
                return response()->json([
                    'success' => false,
                    'message' => "Product not found in database: {$productId}",
                ], 404);
            }

            $oldStock = $product->stock;
            $product->update(['stock' => $newStock, 'synced_at' => now()]);

            Log::info('Database stock updated', [
                'product_id' => $productId,
                'old' => $oldStock,
                'new' => $newStock,
            ]);

            // Sinkron ke TikTok
            $tiktokResult = $this->syncStockToTikTok($productId, $skuId, $warehouseId, $newStock);

            if (! $tiktokResult['success']) {
                DB::rollBack();
                Log::error('TikTok sync failed', ['error' => $tiktokResult['message']]);

                return response()->json([
                    'success' => false,
                    'message' => 'Database updated but TikTok sync failed: ' . $tiktokResult['message'],
                    'tiktok' => $tiktokResult,
                ], 500);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stok berhasil diupdate ke ERP dan TikTok',
                'data' => [
                    'product_id' => $productId,
                    'sku_id' => $skuId,
                    'warehouse_id' => $warehouseId,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                ],
                'tiktok' => $tiktokResult,
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Stock update exception', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal update stok: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync stock ke TikTok pakai struktur resmi skus[]
     */
    private function syncStockToTikTok($productId, $skuId, $warehouseId, $newStock)
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->value('value');

            if (! $appKey || ! $shopCipher || ! $accessToken) {
                throw new Exception('Missing TikTok API credentials');
            }

            $path = "/product/202309/products/{$productId}/inventory/update";
            $params = [
                'app_key' => $appKey,
                'shop_cipher' => $shopCipher,
            ];
            $url = "https://open-api.tiktokglobalshop.com{$path}";

            // Payload sesuai spesifikasi endpoint
            $payload = [
                'skus' => [[
                    'id' => $skuId,
                    'inventory' => [[
                        'warehouse_id' => $warehouseId,
                        'quantity' => (int) $newStock,
                    ]],
                ]],
            ];

            $bodyJson = json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $signData = Authtentication::generateTikTokSignature($path, $params, $bodyJson);

            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $response = Http::withHeaders([
                'x-tts-access-token' => $accessToken,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
                ->withQueryParameters($params)
                ->timeout(30)
                ->post($url, $payload);

            $data = $response->json();

            if ($response->successful() && (($data['code'] ?? -1) === 0)) {
                return [
                    'success' => true,
                    'message' => 'TikTok sync successful',
                    'response' => $data,
                ];
            }

            // Fallback ke available_stock
            $payloadAlt = [
                'skus' => [[
                    'id' => $skuId,
                    'inventory' => [[
                        'warehouse_id' => $warehouseId,
                        'available_stock' => (int) $newStock,
                    ]],
                ]],
            ];

            $bodyAlt = json_encode($payloadAlt, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $signAlt = Authtentication::generateTikTokSignature($path, $params, $bodyAlt);
            $params['sign'] = $signAlt['sign'];
            $params['timestamp'] = $signAlt['timestamp'];

            $res2 = Http::withHeaders([
                'x-tts-access-token' => $accessToken,
                'Content-Type' => 'application/json',
            ])
                ->withQueryParameters($params)
                ->timeout(30)
                ->post($url, $payloadAlt);

            $data2 = $res2->json();
            if ($res2->successful() && (($data2['code'] ?? -1) === 0)) {
                return [
                    'success' => true,
                    'message' => 'TikTok sync successful (via available_stock)',
                    'response' => $data2,
                ];
            }

            Log::error('TikTok inventory update failed', [
                'product_id' => $productId,
                'sku_id' => $skuId,
                'warehouse' => $warehouseId,
                'response' => $data2 ?? $data,
            ]);

            return [
                'success' => false,
                'message' => $data2['message'] ?? $data['message'] ?? 'TikTok API Error',
                'response' => $data2 ?? $data,
            ];

        } catch (Exception $e) {
            Log::error('TikTok sync exception', ['error' => $e->getMessage()]);

            return [
                'success' => false,
                'message' => 'TikTok sync failed: ' . $e->getMessage(),
            ];
        }
    }
}
