<?php

namespace App\Helpers;

use App\Models\Setting;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class Authtentication
{
    private const AUTH_BASE = 'https://auth.tiktok-shops.com';

    private const TOKEN_GET_PATH = '/api/v2/token/get';

    private const TOKEN_REFRESH_PATH = '/api/v2/token/refresh';

    /**
     * Ambil access token valid.
     * - Kalau masih hidup, langsung pakai.
     * - Kalau hampir/before expired, REFRESH pakai refresh_token.
     * - Kalau belum punya refresh_token (first link), tukar authorization_code sekali.
     */
    public static function getTikTokAccessToken(): string
    {
        $accessToken = Setting::where('key', 'tiktok-access-token')->value('value');
        $expiresAtStr = Setting::where('key', 'tiktok-expired-access-token')->value('value');
        $refreshToken = Setting::where('key', 'tiktok-refresh-token')->value('value');

        // Masih valid > 2 menit buffer
        if ($accessToken && $expiresAtStr) {
            $expiresAt = Carbon::parse($expiresAtStr);
            if ($expiresAt->gt(now()->addSeconds(120))) {
                return $accessToken;
            }
        }

        // Punya refresh token? Refresh, jangan tukar auth_code lagi
        if ($refreshToken) {
            return self::refreshTikTokAccessToken();
        }

        // First link: tukar authorization_code sekali
        return self::exchangeAuthorizationCodeOnce();
    }

    /**
     * FIRST LINK ONLY: tukar authorization_code -> access_token.
     * Setelah ini, simpan refresh_token dan jangan panggil ini lagi kecuali re-link manual.
     */
    private static function exchangeAuthorizationCodeOnce(): string
    {
        $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
        $appSecret = Setting::where('key', 'tiktok-app-secret')->value('value');
        $authCode = Setting::where('key', 'tiktok-auth-code')->value('value');

        if (! $appKey || ! $appSecret || ! $authCode) {
            throw new Exception('TikTok credentials not found in settings');
        }

        // grant_type yang benar: authorization_code (bukan authorized_code)
        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(30)
            ->get(self::AUTH_BASE . self::TOKEN_GET_PATH, [
                'app_key' => $appKey,
                'app_secret' => $appSecret,
                'auth_code' => $authCode,
                'grant_type' => 'authorization_code',
            ]);

        $data = $response->json();
        if (! $response->successful() || (($data['code'] ?? -1) !== 0)) {
            $msg = $data['message'] ?? $response->body();
            throw new Exception('TikTok API error (code exchange): ' . $msg);
        }

        $payload = $data['data'] ?? [];
        self::persistTokensFromPayload($payload);

        $token = $payload['access_token'] ?? null;
        if (! $token) {
            throw new Exception('TikTok API: access_token missing after code exchange');
        }

        return $token;
    }

    /**
     * REFRESH TOKEN FLOW (yang semestinya dipakai harian).
     * Menggunakan refresh_token yang disimpan.
     */
    private static function refreshTikTokAccessToken(): string
    {
        try {
            $appKey = Setting::where('key', 'tiktok-app-key')->value('value');
            $appSecret = Setting::where('key', 'tiktok-app-secret')->value('value');
            $refreshToken = Setting::where('key', 'tiktok-refresh-token')->value('value');

            if (! $appKey || ! $appSecret || ! $refreshToken) {
                throw new Exception('TikTok credentials not found in settings for refresh flow');
            }

            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(30)
                ->get(self::AUTH_BASE . self::TOKEN_REFRESH_PATH, [
                    'app_key' => $appKey,
                    'app_secret' => $appSecret,
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refreshToken,
                ]);

            $data = $response->json();

            if (! $response->successful()) {
                throw new Exception('Failed to refresh token: HTTP ' . $response->status() . ' ' . $response->body());
            }

            if (($data['code'] ?? -1) !== 0) {
                $errorMessage = $data['message'] ?? 'Unknown error';
                throw new Exception('TikTok API error (refresh): ' . $errorMessage);
            }

            $payload = $data['data'] ?? [];
            self::persistTokensFromPayload($payload);

            $token = $payload['access_token'] ?? null;
            if (! $token) {
                throw new Exception('TikTok API: access_token missing in refresh response');
            }

            return $token;

        } catch (Exception $e) {
            throw new Exception('Error refreshing TikTok access token: ' . $e->getMessage());
        }
    }

    /**
     * Simpan token + expiry dengan BENAR.
     * *_expire_in = durasi detik, bukan timestamp.
     * *_expire_at (kalau ada) = unix timestamp.
     */
    private static function persistTokensFromPayload(array $responseData): void
    {
        // Access token
        if (! empty($responseData['access_token'])) {
            Setting::updateOrCreate(['key' => 'tiktok-access-token'], [
                'value' => $responseData['access_token'],
            ]);
        }

        // Access token expiry
        if (isset($responseData['access_token_expire_in'])) {
            // detik → now + detik, kasih buffer 60s
            $expiredAt = now()->addSeconds((int) $responseData['access_token_expire_in'] - 60)->toDateTimeString();
            Setting::updateOrCreate(['key' => 'tiktok-expired-access-token'], [
                'value' => $expiredAt,
            ]);
        } elseif (isset($responseData['access_token_expire_at'])) {
            // unix ts → langsung
            $expiredAt = Carbon::createFromTimestamp((int) $responseData['access_token_expire_at'])->subSeconds(60)->toDateTimeString();
            Setting::updateOrCreate(['key' => 'tiktok-expired-access-token'], [
                'value' => $expiredAt,
            ]);
        }

        // Refresh token + expiry
        if (isset($responseData['refresh_token'])) {
            Setting::updateOrCreate(['key' => 'tiktok-refresh-token'], [
                'value' => $responseData['refresh_token'],
            ]);
        }
        if (isset($responseData['refresh_token_expire_in'])) {
            $refreshExpiredAt = now()->addSeconds((int) $responseData['refresh_token_expire_in'] - 300)->toDateTimeString(); // buffer 5 menit
            Setting::updateOrCreate(['key' => 'tiktok-refresh-token-expired'], [
                'value' => $refreshExpiredAt,
            ]);
        } elseif (isset($responseData['refresh_token_expire_at'])) {
            $refreshExpiredAt = Carbon::createFromTimestamp((int) $responseData['refresh_token_expire_at'])->subMinutes(5)->toDateTimeString();
            Setting::updateOrCreate(['key' => 'tiktok-refresh-token-expired'], [
                'value' => $refreshExpiredAt,
            ]);
        }

        // Info seller (opsional)
        if (isset($responseData['open_id'])) {
            Setting::updateOrCreate(['key' => 'tiktok-open-id'], ['value' => $responseData['open_id']]);
        }
        if (isset($responseData['seller_name'])) {
            Setting::updateOrCreate(['key' => 'tiktok-seller-name'], ['value' => $responseData['seller_name']]);
        }
        if (isset($responseData['seller_base_region'])) {
            Setting::updateOrCreate(['key' => 'tiktok-seller-region'], ['value' => $responseData['seller_base_region']]);
        }
    }

    /**
     * Signature buat product endpoints tetap punyamu (tidak dipakai di auth).
     */
    public static function generateTikTokSignature($path, $params = [], $body = '')
    {
        $appSecret = Setting::where('key', 'tiktok-app-secret')->first();

        if (! $appSecret) {
            throw new Exception('TikTok app secret not found in settings');
        }

        $timestamp = time();
        $params['timestamp'] = $timestamp;
        unset($params['sign'], $params['access_token']);
        ksort($params);

        $concatenatedParams = '';
        foreach ($params as $key => $value) {
            $concatenatedParams .= $key . $value;
        }

        $signString = $appSecret->value . $path . $concatenatedParams . $body . $appSecret->value;
        $sign = hash_hmac('sha256', $signString, $appSecret->value);

        return [
            'sign' => $sign,
            'timestamp' => $timestamp,
        ];
    }
}
