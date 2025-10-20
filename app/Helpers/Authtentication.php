<?php

namespace App\Helpers;

use App\Models\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class Authtentication
{
    /**
     * Get TikTok access token
     *
     * @return string|null
     *
     * @throws Exception
     */
    public static function getTikTokAccessToken()
    {
        // 1. Cek apakah di database ada key access-token
        $accessTokenSetting = Setting::where('key', 'tiktok-access-token')->first();

        if ($accessTokenSetting) {
            // 2. Jika ada, cek apakah sudah expired
            $expiredSetting = Setting::where('key', 'tiktok-expired-access-token')->first();

            if ($expiredSetting) {
                $expiredAt = Carbon::parse($expiredSetting->value);

                // 3. Jika belum expired, return tokennya
                if ($expiredAt->isFuture()) {
                    return $accessTokenSetting->value;
                }
            }
        }

        // 4. Jika belum ada atau sudah expired, ambil dari API
        return self::refreshTikTokAccessToken();
    }

    /**
     * Refresh TikTok access token from API
     *
     * @return string|null
     *
     * @throws Exception
     */
    private static function refreshTikTokAccessToken()
    {
        try {
            // Ambil parameter dari database
            $appKey = Setting::where('key', 'tiktok-app-key')->first();
            $appSecret = Setting::where('key', 'tiktok-app-secret')->first();
            $authCode = Setting::where('key', 'tiktok-auth-code')->first();

            if (! $appKey || ! $appSecret || ! $authCode) {
                throw new Exception('TikTok credentials not found in settings');
            }

            // Call TikTok API menggunakan Laravel HTTP Client
            $response = Http::withHeaders([
                'content-type' => 'application/json',
            ])->get('https://auth.tiktok-shops.com/api/v2/token/get', [
                'app_key' => $appKey->value,
                'app_secret' => $appSecret->value,
                'auth_code' => $authCode->value,
                'grant_type' => 'authorized_code',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Cek response code dari TikTok
                if (isset($data['code']) && $data['code'] === 0 && isset($data['data']['access_token'])) {
                    $responseData = $data['data'];

                    // Simpan access token ke database
                    Setting::updateOrCreate(
                        ['key' => 'tiktok-access-token'],
                        ['value' => $responseData['access_token']]
                    );

                    // Simpan expired time (expire_in adalah timestamp unix)
                    if (isset($responseData['access_token_expire_in'])) {
                        $expiredAt = Carbon::createFromTimestamp($responseData['access_token_expire_in']);
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-expired-access-token'],
                            ['value' => $expiredAt->toDateTimeString()]
                        );
                    }

                    // Simpan refresh token
                    if (isset($responseData['refresh_token'])) {
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-refresh-token'],
                            ['value' => $responseData['refresh_token']]
                        );
                    }

                    // Simpan refresh token expired time
                    if (isset($responseData['refresh_token_expire_in'])) {
                        $refreshExpiredAt = Carbon::createFromTimestamp($responseData['refresh_token_expire_in']);
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-refresh-token-expired'],
                            ['value' => $refreshExpiredAt->toDateTimeString()]
                        );
                    }

                    // Simpan informasi seller
                    if (isset($responseData['open_id'])) {
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-open-id'],
                            ['value' => $responseData['open_id']]
                        );
                    }

                    if (isset($responseData['seller_name'])) {
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-seller-name'],
                            ['value' => $responseData['seller_name']]
                        );
                    }

                    if (isset($responseData['seller_base_region'])) {
                        Setting::updateOrCreate(
                            ['key' => 'tiktok-seller-region'],
                            ['value' => $responseData['seller_base_region']]
                        );
                    }

                    return $responseData['access_token'];
                }

                // Jika ada error dari TikTok API
                $errorMessage = $data['message'] ?? 'Unknown error';
                throw new Exception('TikTok API error: ' . $errorMessage);
            }

            throw new Exception('Failed to get TikTok access token: ' . $response->body());

        } catch (Exception $e) {
            throw new Exception('Error refreshing TikTok access token: ' . $e->getMessage());
        }
    }

    /**
     * Generate TikTok API signature based on official Postman algorithm
     *
     * @param  string  $path  API path (e.g., '/product/202309/products/search')
     * @param  array  $params  Query parameters (without sign and access_token)
     * @param  string  $body  Request body (JSON string)
     * @return array ['sign' => signature, 'timestamp' => timestamp]
     */
    public static function generateTikTokSignature($path, $params = [], $body = '')
    {
        $appSecret = Setting::where('key', 'tiktok-app-secret')->first();

        if (! $appSecret) {
            throw new Exception('TikTok app secret not found in settings');
        }

        $timestamp = time();

        // Add timestamp to params
        $params['timestamp'] = $timestamp;

        // Remove sign and access_token if they exist
        unset($params['sign']);
        unset($params['access_token']);

        // Sort parameters by key alphabetically
        ksort($params);

        // Build concatenated string: key1value1key2value2...
        $concatenatedParams = '';
        foreach ($params as $key => $value) {
            $concatenatedParams .= $key . $value;
        }

        // Build signature string: secret + path + concatenated_params + body + secret
        $signString = $appSecret->value . $path . $concatenatedParams . $body . $appSecret->value;

        // Generate HMAC SHA256 signature
        $sign = hash_hmac('sha256', $signString, $appSecret->value);

        return [
            'sign' => $sign,
            'timestamp' => $timestamp,
        ];
    }
}
