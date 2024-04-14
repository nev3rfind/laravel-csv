<?php

namespace App\Jobs;

use App\Services\CSVReader;
use App\Services\CSVValidator;
use App\Models\CsvImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ValidateCsvImportJob implements ShouldQueue
{
    // Traits to handle queue job efficiently
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $csvImport;

    /**
     * Create a new job instance.
     *
     * @param CSVValidator $csvValidator A CSV validator service.
     */
    public function __construct(CsvImport $csvImport)
    {
        $this->csvImport = $csvImport;
    }

    /**
     * Execute the job.
     *
     * Handles the validation of the CSV file and updates the CsvImport model's status accordingly.
     * Goes well with OCP - job class can eb extended without modifying existing code - just add a new service or a method
     * DIP - Depends on asbtraction CSSVReader and CSVValidator rather than concrete implementation 
     */
    public function handle(CSVReader $csvReader, CSVValidator $csvValidator)
    {
        $this->csvImport->update(['status' => 'validating']);

        try {
            $csvData = $csvReader->readCSV($this->csvImport->filename);
            $headers = $csvData['headers'];
            $rows = $csvData['rows'];

            // If there's no headers or the headers are not valid, throw an exception
            if (empty($headers) || !$csvValidator->validateHeaders($headers)) {
                throw new \Exception("CSV headers do not match required format.");
            }

            $csvValidator->validate($rows, $headers);
            
            $this->csvImport->update(['status' => 'validated']);
            // Continue with csv processing
            ProcessCsvFileJob::dispatch($this->csvImport);
            
        } catch (\Exception $e) {
            $this->csvImport->update(['status' => 'failed', 'error_message' => $e->getMessage()]);
            
            // Cleanup the file if validation fails
            Storage::delete($this->csvImport->filename);
        }
    }
}
