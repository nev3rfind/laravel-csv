<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductImporter
{
    public function importRow(array $row)
    {
        try {
            Product::updateOrCreate(
                ['sku' => $row['SKU']],
                [
                    'name' => $row['Name'], 
                    'description' => $row['Description'],
                    'brand' => $row['Brand']
                ]
            );
        } catch (\Exception $e) {
            Log::error("Failed processing row: {$e->getMessage()}");
            Log::info(json_encode($row));
        }
    }
}
