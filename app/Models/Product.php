<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'tiktok_product_id', 'title', 'description', 'status', 'skus',
        'currency', 'price', 'stock', 'image', 'images', 'synced_at',
    ];

    protected $casts = [
        'skus' => 'array',
        'images' => 'array',
    ];
}


