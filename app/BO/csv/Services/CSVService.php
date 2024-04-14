<?php

namespace App\BO\csv\Services;

use Illuminate\Http\UploadedFile;
use App\BO\csv\Repositories\CSVRepository;
use App\BO\csv\Transformations\CSVProductTransformer;

class CSVService
{
    use CSVProductTransformer;

    protected $csvRepository;

    public function __construct(CSVRepository $csvRepository)
    {
        $this->csvRepository = $csvRepository;
    }

    public function getProductBySKU($sku)
    {
        $product =  $this->csvRepository->getProductBySKU($sku);
        return $this->transformProduct($product);
    }

    public function processUpload($file)
    {
        // Check if the input is an UploadedFile instance (typically from HTTP requests)
        if ($file instanceof UploadedFile) {
            $filePath = $file->store('csv_uploads');
            $fullPath = storage_path('app/' . $filePath);
        } else if (is_string($file)) {
            // Direct file path provided, typically from the command line
            if (!file_exists($file)) {
                throw new \Exception("File does not exist: {$file}");
            }
            $fullPath = $file;  // Use the provided path directly
        } else {
            throw new \Exception("Invalid file input provided.");
        }

        // Process the file in the repository
        return $this->csvRepository->processCSV($fullPath);
    }
}
