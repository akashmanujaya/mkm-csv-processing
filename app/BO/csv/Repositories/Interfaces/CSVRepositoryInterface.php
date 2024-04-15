<?php

namespace App\BO\csv\Repositories\Interfaces;

/**
 * Interface for a CSV repository.
 *
 * Defines the standard functionality that any CSV repository implementation must provide,
 * ensuring consistency across various implementations that deal with product data.
 */
interface CSVRepositoryInterface
{
    /**
     * Retrieve a product by its SKU.
     *
     * This method is responsible for fetching a product's details from the database based on its SKU.
     * Implementations should return the product instance if found, or null if no product is found.
     *
     * @param string $sku The stock keeping unit (SKU) of the product to retrieve.
     * @return \App\BO\csv\Models\Product|null The product model if found, otherwise null.
     */
    public function getProductBySKU($sku);
}
