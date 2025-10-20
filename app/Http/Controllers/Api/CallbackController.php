<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function handleAuthCallback(Request $request)
    {
        $authCode = $request->get('code');
        $state = $request->get('state');

        if (! $authCode) {
            return response()->json([
                'message' => 'Auth code not provided',
            ], 400);
        }

        // Simpan auth code ke database
        Setting::updateOrCreate(
            ['key' => 'tiktok-auth-code'],
            ['value' => $authCode]
        );

        return redirect('/products')->with('success', 'TikTok authorization successful. You can now manage your products.');
    }
}
