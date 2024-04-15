<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

use function PHPUnit\Framework\fileExists;

class ProcessCSVCommandTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_process_csv_command_with_existing_file()
    {
        $sampleFilePath = base_path('tests/sample_files/sample.csv');  // Ensure this file exists for testing

        // Simulate running the command with the actual file path
        $this->artisan('csv:process', ['file' => $sampleFilePath])
             ->expectsOutput("Processing file: $sampleFilePath")
             ->expectsOutput('CSV processing has been successfully started in the background.')
             ->assertExitCode(0);
    }

    public function test_process_csv_command_with_nonexistent_file()
    {
        $nonexistentFilePath = base_path('tests/sample_files/nonexistent.csv');  // This file should not exist

        // Run the command with a non-existing file path
        $this->artisan('csv:process', ['file' => $nonexistentFilePath])
             ->expectsOutput("The file at $nonexistentFilePath does not exist.")
             ->assertExitCode(1);
    }
}

