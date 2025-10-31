<?php

use App\Http\Controllers\Api\CallbackController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OverviewController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductOverviewController;

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

Route::get('/orders', function () {
    return view('pages.orders');
})->name('orders_menu');

// Dashboard Routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/api/dashboard/data', [DashboardController::class, 'getDashboardData'])->name('dashboard.data');
Route::post('/api/dashboard/refresh', [DashboardController::class, 'refreshMetrics'])->name('dashboard.refresh');

Route::resource('/test', OrderController::class);


Route::post('/tiktok/inventory/update-single', [ProductController::class, 'updateStock'])
    ->name('products.updateStock');

// Product Overview Routes
Route::get('/overview/products', [ProductOverviewController::class, 'index'])
    ->name('overview.products');

// Detail produk individual
Route::get('/overview/products/{id}', [ProductOverviewController::class, 'show'])
    ->name('overview.products.detail');

// Update stock produk (ERP → Database → TikTok)
Route::post('/overview/products/update-stock', [ProductOverviewController::class, 'updateStock'])
    ->name('overview.products.update-stock');

// Bulk update stock multiple produk
Route::post('/overview/products/bulk-update-stock', [ProductOverviewController::class, 'bulkUpdateStock'])
    ->name('overview.products.bulk-update-stock');

// Get stock history untuk produk
Route::get('/overview/products/{productId}/stock-history', [ProductOverviewController::class, 'getStockHistory'])
    ->name('overview.products.stock-history');

// Get statistics produk untuk dashboard
Route::get('/overview/products/stats/get', [ProductOverviewController::class, 'getStats'])
    ->name('overview.products.stats');

// Search products
Route::get('/overview/products/search/data', [ProductOverviewController::class, 'search'])
    ->name('overview.products.search');

// Manual sync dari TikTok ke Database
Route::post('/overview/products/manual-sync', [ProductOverviewController::class, 'manualSync'])
    ->name('overview.products.manual-sync');
// Route untuk update single inventory dari frontend
// Route::post('/tiktok/inventory/update-single', [ProductController::class, 'updateSingleInventory'])
//     ->name('tiktok.inventory.update.single');

// Route yang sudah ada (untuk batch update)
Route::post('/tiktok/inventory/update', [ProductController::class, 'updateTikTokInventory'])
    ->name('tiktok.inventory.update');



