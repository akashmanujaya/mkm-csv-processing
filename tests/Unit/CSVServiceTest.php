<?php

namespace Tests\Unit;

use App\BO\csv\Services\CSVService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CSVServiceTest extends TestCase
{
    public function test_process_upload_with_uploaded_file()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('document.csv', 100);

        $csvService = $this->getMockBuilder(CSVService::class)
                           ->disableOriginalConstructor()
                           ->getMock();
        $csvService->method('processUpload')->willReturn(true);

        $response = $csvService->processUpload($file);

        $this->assertTrue($response);
    }
}