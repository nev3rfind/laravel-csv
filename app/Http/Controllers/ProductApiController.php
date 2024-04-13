<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Product;

class ProductApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function showBySku($sku)
    {
        // Define a cache key unqiue to the SKU being requested
        $cacheKey = 'product_'.$sku;
        // Attempt to retrieve product from the cache 
        $product = Cache::remember($cacheKey, 60, function () use ($sku) {
            // And if product was not found there...
            return Product::where('sku', $sku)->first();
        });

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json($product);
    }

}
