<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\SimpleExcel\SimpleExcelReader;

class ProcessEntireCSVJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function handle()
    {
        Log::info('Processing entire CSV now: ');

        SimpleExcelReader::create($this->filePath)
        ->useHeaders(['sku', 'name', 'description', 'brand'])
        ->getRows()
        ->chunk(1000)  // Ensure chunk size is appropriate
        ->each(function ($chunk) {
            // Make sure that each chunk is an array of rows
            ProcessCSVChunkJob::dispatch($chunk->toArray());
        });
    }
}
