<?php

namespace App\BO\csv\Transformations;

trait CSVProductTransformer
{
    public function transformProduct($product)
    {
        return [
            'sku' => $product->sku,
            'name' => $product->name,
            'description' => $product->description,
            'brand' => $product->brand,
            'created' => $product->created_at->format('d-M-Y --- H:i A'),
            'last_edited' => $product->updated_at->format('d-M-Y --- H:i A')
        ];
    }
}