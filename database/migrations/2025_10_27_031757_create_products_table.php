<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('tiktok_product_id')->unique(); // id dari TikTok
    $table->string('title');
    $table->text('description')->nullable();
    $table->string('status')->nullable(); // ACTIVATE, INACTIVE, dll.
    $table->json('skus')->nullable(); // menyimpan sku, harga, stok, warehouse
    $table->string('currency')->default('IDR');
    $table->integer('price')->default(0);
    $table->integer('stock')->default(0);
    $table->string('image')->nullable();
    $table->timestamp('synced_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
