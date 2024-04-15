<?php

namespace App\Http\Controllers;

use App\BO\csv\Requests\CSVUploadRequest;
use App\BO\csv\Services\CSVService;
use App\Helpers\ApiResponse;

class CSVController extends Controller
{
    protected $csvService;

    public function __construct(CSVService $csvService)
    {
        $this->csvService = $csvService;
    }

    public function getProductBySKU($sku)
    {
        $product = $this->csvService->getProductBySKU($sku);

        return ApiResponse::send(['product'=> $product], 'Product found.');
    }

    public function upload(CSVUploadRequest $request)
    {
        $file = $request->file('csv_file');
        $this->csvService->processUpload($file);

        if ($request->is('api/*')) {
            return ApiResponse::send(message: 'Your data is being processed.');
        }

        return response()->json([
            'message' => 'Your data is being processed.',
        ]);
    }
}
