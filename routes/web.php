<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return redirect('/products');
});

Route::resource('products', ProductController::class);
Route::resource('orders', OrderController::class);

Route::get('/tiktok/callback', [CallbackController::class, 'handleAuthCallback'])->name('tiktok.callback');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
