<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\BO\csv\Models\Product;
use Illuminate\Support\Facades\Log;

class ProcessCSVChunkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        foreach ($this->rows as $row) {
            // Corrected log statement
            Log::info('Processing row: ' . json_encode($row['name']));

            // Assuming the data is correctly mapped and the fields are directly accessible
            Product::updateOrCreate(
                ['sku' => $row['sku']], 
                [
                    'name' => $row['name'], 
                    'description' => $row['description'], 
                    'brand' => $row['brand']
                ]
            );
        }
    }
}