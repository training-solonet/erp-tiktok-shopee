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
            $path = '/product/202309/products/search';
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

                    return view('products.index', [
                        'products' => $products,
                        'total' => $data['data']['total'] ?? 0,
                        'success' => true,
                    ]);
                }

                // TikTok API error
                return view('products.index', [
                    'products' => [],
                    'error' => $data['message'] ?? 'Unknown error from TikTok API',
                    'success' => false,
                ]);
            }

            // HTTP error
            return view('products.index', [
                'products' => [],
                'error' => 'Failed to fetch products: ' . $response->body(),
                'success' => false,
            ]);

        } catch (Exception $e) {
            return view('products.index', [
                'products' => [],
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
