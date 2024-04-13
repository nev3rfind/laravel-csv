<?php

namespace App\Jobs;

use App\Models\CsvImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Services\CSVReader;
use App\Services\ProductImporter;

class ProcessCsvFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvImport;

    public function __construct(CsvImport $csvImport)
    {
        $this->csvImport = $csvImport;
    }

    public function handle(CSVReader $csvReader, ProductImporter $productImporter)
    {
        $this->csvImport->update(['status' => 'processing']);
        $path = $this->csvImport->filename;

        try {
            $csvData = $csvReader->readCSV($path);
            $csvData['rows']->each(function ($row) use ($productImporter) {
                $productImporter->importRow($row);
            });
    
            $this->csvImport->update(['status' => 'completed']);
        } catch (\Exception $e) {
            $this->csvImport->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            Log::error("Process failed: {$e->getMessage()}");
        }
    }
}
