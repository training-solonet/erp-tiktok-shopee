<?php

namespace App\Http\Controllers;

use App\Helpers\Authtentication;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $accessToken = Authtentication::getTikTokAccessToken();
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $shopCipher = Setting::where('key', 'tiktok-shop-cipher')->first();

            if (! $appKey || ! $shopCipher) {
                Log::error('TikTok credentials missing', [
                    'app_key' => $appKey ? 'exists' : 'missing',
                    'shop_cipher' => $shopCipher ? 'exists' : 'missing',
                ]);
                throw new Exception('TikTok credentials not complete in settings');
            }

            $path = '/order/202309/orders/search';
            $bodyArray = [
                'page_size' => 10,
                'sort_by' => 'CREATE_TIME',
                'sort_order' => 'DESC',
            ];

            $params = [
                'app_key' => $appKey->value,
                'shop_cipher' => $shopCipher->value,
                'page_size' => 10,
            ];

            $signData = Authtentication::generateTikTokSignature($path, $params, json_encode($bodyArray));
            $params['sign'] = $signData['sign'];
            $params['timestamp'] = $signData['timestamp'];

            $url = 'https://open-api.tiktokglobalshop.com' . $path;

            Log::info('TikTok API Request', [
                'url' => $url,
                'params' => $params,
                'body' => $bodyArray,
            ]);

            $response = Http::timeout(30)
                ->asJson()
                ->withHeaders([
                    'x-tts-access-token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])
                ->withQueryParameters($params)
                ->post($url, $bodyArray);

            Log::info('TikTok API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['code']) && $data['code'] === 0) {
                    $orders = $data['data']['orders'] ?? [];

                    Log::info('Orders fetched successfully', [
                        'count' => count($orders),
                        'total_count' => $data['data']['total_count'] ?? 0,
                    ]);

                    return view('pages.orders', [
                        'orders' => $orders,
                        'total_orders' => $data['data']['total_count'] ?? 0,
                        'success' => true,
                    ]);
                }

                Log::error('TikTok API error', [
                    'code' => $data['code'] ?? 'unknown',
                    'message' => $data['message'] ?? 'No message',
                ]);

                return view('pages.orders', [
                    'orders' => [],
                    'error' => $data['message'] ?? 'Unknown error from TikTok API',
                    'success' => false,
                ]);
            }

            Log::error('HTTP request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return view('pages.orders', [
                'orders' => [],
                'error' => 'Failed to fetch orders. HTTP Status: ' . $response->status(),
                'success' => false,
            ]);

        } catch (Exception $e) {
            Log::error('OrderController exception', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return view('pages.orders', [
                'orders' => [],
                'error' => $e->getMessage(),
                'success' => false,
            ]);
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
