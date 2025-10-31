<?php

namespace App\Services;

use App\Models\Product;

class InventoryService
{
    /**
     * Validate stock update
     */
    public function validateStockUpdate($productId, $newStock): bool
    {
        if ($newStock < 0) {
            throw new \Exception('Stock cannot be negative');
        }

        $product = Product::where('tiktok_product_id', $productId)->first();
        if (! $product) {
            throw new \Exception("Product not found: {$productId}");
        }

        return true;
    }

    /**
     * Get stock summary
     */
    public function getStockSummary($productId): array
    {
        $product = Product::where('tiktok_product_id', $productId)->first();

        if (! $product) {
            throw new \Exception("Product not found: {$productId}");
        }

        return [
            'current_stock' => $product->stock,
            'product_title' => $product->title,
            'last_updated' => $product->synced_at,
            'status' => $product->status,
        ];
    }
}
