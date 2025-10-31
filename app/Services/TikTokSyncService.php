<?php

namespace App\Services;

use App\Helpers\Authtentication;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TikTokSyncService
{
    /**
     * Update stock to TikTok Shop
     */
    public function updateStock(string $productId, string $skuId, string $warehouseId, int $newStock): array
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->value('value');

            if (! $appKey || ! $shopCipher) {
                throw new Exception('TikTok credentials not found');
            }

            $bodyArray = [
                'product_id' => $productId,
                'sku_id' => $skuId,
                'inventory' => [
                    [
                        'available_stock' => (int) $newStock,
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

            Log::info('Sending TikTok stock update request', [
                'url' => $url,
                'product_id' => $productId,
                'sku_id' => $skuId,
                'new_stock' => $newStock,
            ]);

            $response = Http::timeout(30)
                ->asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            $result = $response->json();

            Log::info('TikTok API response', [
                'product_id' => $productId,
                'response_code' => $result['code'] ?? 'unknown',
                'response_message' => $result['message'] ?? 'No message',
            ]);

            if (($result['code'] ?? -1) === 0) {
                return [
                    'success' => true,
                    'message' => 'TikTok sync successful',
                    'response' => $result,
                ];
            } else {
                $errorMessage = $result['message'] ?? 'Unknown TikTok API error';
                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            Log::error('TikTok sync service error: ' . $e->getMessage(), [
                'product_id' => $productId,
                'sku_id' => $skuId,
                'warehouse_id' => $warehouseId,
            ]);

            return [
                'success' => false,
                'message' => 'TikTok sync failed: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Batch update multiple stocks
     */
    public function batchUpdateStock(array $updates): array
    {
        $results = [];

        foreach ($updates as $index => $update) {
            $results[$index] = $this->updateStock(
                $update['product_id'],
                $update['sku_id'],
                $update['warehouse_id'],
                $update['new_stock']
            );
        }

        return $results;
    }
}
