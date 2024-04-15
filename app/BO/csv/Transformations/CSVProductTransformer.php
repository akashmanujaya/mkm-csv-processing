<?php

namespace App\BO\csv\Transformations;

use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Trait CSVProductTransformer
 * 
 * Provides a method to transform product data into a standardized format.
 */
trait CSVProductTransformer
{
    /**
     * Transforms a product model into a structured array format.
     * 
     * @param mixed $product The product object to be transformed.
     * @return array The transformed product data.
     * @throws Exception If the product is null or the necessary attributes are not set.
     */
    public function transformProduct($product)
    {
        try {
            if (!$product) {
                throw new Exception("No product provided for transformation.");
            }

            return [
                'sku' => $product->sku,
                'name' => $product->name,
                'description' => $product->description,
                'brand' => $product->brand,
                'created' => $product->created_at->format('d-M-Y --- H:i A'),
                'last_edited' => $product->updated_at->format('d-M-Y --- H:i A')
            ];
        } catch (Exception $e) {
            Log::error("Failed to transform product data: " . $e->getMessage());
            throw new Exception("Error transforming product data: " . $e->getMessage());
        }
    }
}
