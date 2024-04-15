<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;
use Exception;

/**
 * Handles the processing of an entire CSV file.
 *
 * This job is responsible for reading a large CSV file and dispatching smaller chunks of data
 * to another job for further processing. It helps in managing large datasets efficiently
 * by breaking them into manageable pieces.
 */
class ProcessEntireCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The file path of the CSV to process.
     *
     * @var string
     */
    protected $filePath;

    /**
     * Create a new job instance.
     *
     * @param string $filePath The path to the CSV file to be processed.
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     *
     * Reads the CSV file in chunks and dispatches each chunk to the `ProcessCSVChunkJob`.
     * Logs the process and captures any exceptions that occur during the file reading or chunk dispatching.
     *
     * @throws Exception if there is an error during file processing.
     */
    public function handle()
    {
        try {
            Log::info('Starting CSV processing for: ' . $this->filePath);

            $reader = SimpleExcelReader::create($this->filePath);
            if (!$reader) {
                throw new Exception("Failed to open CSV file at path: {$this->filePath}");
            }

            $reader->useHeaders(['sku', 'name', 'description', 'brand'])
                   ->getRows()
                   ->chunk(1000)  // Ensure chunk size is appropriate
                   ->each(function ($chunk) {
                       ProcessCSVChunkJob::dispatch($chunk->toArray());
                   });

            Log::info('Successfully dispatched all chunks for CSV: ' . $this->filePath);
        } catch (Exception $e) {
            Log::error("Error processing CSV at {$this->filePath}: {$e->getMessage()}");
            throw $e;  // Re-throw the exception to ensure it's caught by the queue worker and logged appropriately.
        }
    }
}
