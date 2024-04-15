<?php

namespace App\BO\csv\Repositories;

use App\BO\csv\Models\Product;
use App\BO\csv\Repositories\Interfaces\CSVRepositoryInterface;
use App\Jobs\ProcessEntireCSVJob;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Class CSVRepository
 * 
 * Handles the data access logic for CSV related operations.
 */
class CSVRepository implements CSVRepositoryInterface
{
    /**
     * @var Product The product model instance.
     */
    protected $product;

    /**
     * CSVRepository constructor.
     * 
     * @param Product $product The product model injected dependency.
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Retrieves a product by its SKU.
     *
     * @param string $sku The SKU of the product to retrieve.
     * @return Product|null The product if found, or null if not.
     * @throws ModelNotFoundException When no product is found with the given SKU.
     */
    public function getProductBySKU($sku)
    {
        try {
            
            $product = $this->product::where('sku', $sku)->first();
            
            if (!$product) {
                Log::info("No product found for SKU: $sku");
                throw new ModelNotFoundException("No product found with SKU: $sku");
            }
    
            return $product;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Processes the CSV file by dispatching a job to handle the CSV data.
     *
     * @param string $filePath The path to the CSV file to be processed.
     * @return bool True on successful dispatch of the job.
     * @throws Exception When the file path is invalid or the job fails to dispatch.
     */
    public function processCSV($filePath)
    {
        if (!file_exists($filePath)) {
            Log::error("CSV file not found at path: $filePath");
            throw new Exception("File does not exist at the specified path: $filePath");
        }

        try {
            ProcessEntireCSVJob::dispatch($filePath);
        } catch (Exception $e) {
            Log::error("Failed to dispatch CSV processing job: " . $e->getMessage());
            throw new Exception("Failed to process CSV file: " . $e->getMessage());
        }

        return true;
    }
}
