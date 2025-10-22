<?php

use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/products');
})->name('products_menu');


// Route::get('dashboard', function(){
//     return view('dashboard');
// });

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

// Route::get('/products', function(){
//     return view('pages.products');
// })->name('products_menu');

Route::get('/orders', function(){
    return view('pages.orders');
})->name('orders_menu');

Route::get('/dashboard', function(){
    return view('pages.dashboard');
})->name('dashboard_menu');

