<?php

namespace App\BO\csv\Services;

use Illuminate\Http\UploadedFile;
use App\BO\csv\Repositories\CSVRepository;
use App\BO\csv\Transformations\CSVProductTransformer;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * CSVService handles the business logic for CSV and product-related operations.
 */
class CSVService
{
    use CSVProductTransformer;

    /**
     * @var CSVRepository The repository handling database operations for products.
     */
    protected $csvRepository;

    /**
     * Constructs the CSVService with a CSVRepository.
     * 
     * @param CSVRepository $csvRepository A repository instance for accessing product data.
     */
    public function __construct(CSVRepository $csvRepository)
    {
        $this->csvRepository = $csvRepository;
    }

    /**
     * Retrieves a product by its SKU and transforms it for presentation.
     * 
     * @param string $sku The SKU of the product to retrieve.
     * @return array The transformed product data.
     * @throws Exception If no product is found or if there is an error during the transformation.
     */
    public function getProductBySKU($sku)
    {
        try {
            $product = $this->csvRepository->getProductBySKU($sku);
            if (!$product) {
                throw new Exception("No product found with SKU: {$sku}");
            }
            return $this->transformProduct($product);
        } catch (Exception $e) {
            Log::error("Failed to retrieve product by SKU {$sku}: {$e->getMessage()}");
            throw new Exception("Error retrieving product: " . $e->getMessage());
        }
    }

    /**
     * Processes a CSV file upload by storing it and handing it off for further processing.
     * 
     * @param mixed $file An UploadedFile instance or a string representing the file path.
     * @return bool True on successful processing initiation.
     * @throws Exception If the file is invalid, does not exist, or if processing fails.
     */
    public function processUpload($file)
    {
        try {
            if ($file instanceof UploadedFile) {
                $filePath = $file->store('csv_uploads');
                $fullPath = storage_path('app/' . $filePath);
            } else if (is_string($file) && file_exists($file)) {
                $fullPath = $file;  // Use the provided path directly
            } else {
                throw new Exception("Provided file is invalid or does not exist.");
            }

            return $this->csvRepository->processCSV($fullPath);
        } catch (Exception $e) {
            Log::error("Error processing CSV upload: {$e->getMessage()}");
            throw new Exception("Error processing CSV file: " . $e->getMessage());
        }
    }
}
