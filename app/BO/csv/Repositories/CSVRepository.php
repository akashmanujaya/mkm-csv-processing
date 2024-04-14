<?php

namespace App\BO\csv\Repositories;

use App\BO\csv\Models\Product;
use App\BO\csv\Repositories\Interfaces\CSVRepositoryInterface;
use App\Jobs\ProcessEntireCSVJob;

class CSVRepository implements CSVRepositoryInterface
{
    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function getProductBySKU($sku)
    {
        // Fetch the product from the database
        return $this->product::where('sku', $sku)->first();
    }

    public function processCSV($filePath)
    {
        // Dispatch the entire process to a job
        ProcessEntireCSVJob::dispatch($filePath);

        return true;
    }
}
