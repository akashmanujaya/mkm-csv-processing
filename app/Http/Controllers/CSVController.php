<?php

namespace App\Http\Controllers;

use App\BO\csv\Requests\CSVUploadRequest;
use App\BO\csv\Services\CSVService;

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

        return response()->json($product);
    }

    public function upload(CSVUploadRequest $request)
    {
        $file = $request->file('csv_file');
        $this->csvService->processUpload($file);

        return response()->json([
            'message' => 'Your data will be processed shortly. Please check back later.',
        ]);
    }
}
