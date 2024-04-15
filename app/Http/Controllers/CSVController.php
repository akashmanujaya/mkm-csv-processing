<?php

namespace App\Http\Controllers;

use App\BO\csv\Requests\CSVUploadRequest;
use App\BO\csv\Services\CSVService;
use App\Helpers\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Log;

/**
 * Handles CSV and product-related operations via HTTP requests.
 *
 * This controller provides endpoints for uploading CSV files and retrieving product data by SKU.
 */
class CSVController extends Controller
{
    /**
     * The service handling CSV and product operations.
     *
     * @var CSVService
     */
    protected $csvService;

    /**
     * Create a new controller instance.
     *
     * @param CSVService $csvService Service responsible for CSV and product-related logic.
     */
    public function __construct(CSVService $csvService)
    {
        $this->csvService = $csvService;
    }

    /**
     * Retrieves a product by its SKU and returns detailed information.
     *
     * This method handles the GET request to fetch product details based on the SKU.
     * It returns a JSON response with the product data or an error message if not found.
     *
     * @param string $sku The SKU of the product to retrieve.
     * @return \Illuminate\Http\JsonResponse JSON response containing the product or error message.
     */
    public function getProductBySKU($sku)
    {
        try {
            $product = $this->csvService->getProductBySKU($sku);

            if (!$product) {
                return ApiResponse::error('Product not found', 404);
            }

            return ApiResponse::send(['product' => $product], 'Product found.');
        } catch (Exception $e) {
            Log::error("Failed to retrieve product by SKU: $sku, Error: " . $e->getMessage());
            return ApiResponse::error('Failed to retrieve product', 500);
        }
    }

    /**
     * Handles the upload of a CSV file and processes it.
     *
     * This method accepts a CSV file via POST request and uses the CSVService to process the file.
     * It returns a JSON response indicating that the data is being processed.
     *
     * @param CSVUploadRequest $request The custom request that validates the uploaded CSV.
     * @return \Illuminate\Http\JsonResponse JSON response indicating the processing status.
     */
    public function upload(CSVUploadRequest $request)
    {
        try {
            $file = $request->file('csv_file');
            $this->csvService->processUpload($file);

            if ($request->is('api/*')) {
                return ApiResponse::send(null, 'Your data is being processed.');
            }

            return response()->json(['message' => 'Your data is being processed.']);
        } catch (Exception $e) {
            Log::error("Error uploading CSV file: " . $e->getMessage());
            return ApiResponse::error('Error processing CSV file', 500);
        }
    }
}
