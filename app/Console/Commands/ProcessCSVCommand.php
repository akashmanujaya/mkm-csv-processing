<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\BO\csv\Services\CSVService;

class ProcessCSVCommand extends Command
{
    protected $signature = 'csv:process {file}';
    protected $description = 'Processes a CSV file and stores the data in the database.';

    protected $csvService;

    public function __construct(CSVService $csvService)
    {
        parent::__construct();
        $this->csvService = $csvService;
    }

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("The file at {$filePath} does not exist.");
            return 1;
        }

        $this->info("Processing file: {$filePath}");
        try {
            $this->csvService->processUpload($filePath);
            $this->info('CSV processing has been successfully started in the background.');
        } catch (\Exception $e) {
            $this->error("Error processing file: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
