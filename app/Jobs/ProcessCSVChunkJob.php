<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\BO\csv\Models\Product;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Processes chunks of CSV data for product updates or creation.
 *
 * This job takes a segment of data from a larger CSV file and either updates existing records
 * or creates new ones based on the provided data.
 */
class ProcessCSVChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The data rows to be processed.
     *
     * @var array
     */
    protected $rows;

    /**
     * Create a new job instance.
     *
     * @param array $rows The chunk of rows from the CSV to be processed.
     */
    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    /**
     * Execute the job.
     *
     * Processes each row by either updating an existing product or creating a new one based on the SKU.
     * Captures and logs any exceptions that occur during processing.
     */
    public function handle()
    {
        try {
            foreach ($this->rows as $row) {
                Log::info('Processing row: ' . json_encode($row));

                Product::updateOrCreate(
                    ['sku' => $row['sku']],
                    [
                        'name' => $row['name'], 
                        'description' => $row['description'], 
                        'brand' => $row['brand']
                    ]
                );
            }
        } catch (Exception $e) {
            Log::error("Failed to process CSV chunk: {$e->getMessage()}");
            throw $e;
        }
    }
}
