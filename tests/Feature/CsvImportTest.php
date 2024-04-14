<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\CsvImport;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ValidateCsvImportJob;
use App\Jobs\ProcessCsvFileJob;
use App\Services\CSVReader;
use App\Services\CSVValidator;
use App\Services\ProductImporter;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_import_products_from_csv()
    {
        Storage::fake('public');
        Queue::fake();

        // Create a CSV file in memory
        $csvContent = "SKU,Name,Description,Brand\n" .
                      "SKU001,Product1,Description1,Brand1\n" .
                      "SKU002,Product2,Description2,Brand2";

        // Create a fake file for testing
        $file = UploadedFile::fake()->createWithContent('products.csv', $csvContent);

        // Simulate post request to upload CSV file
        $response = $this->post('/csv/upload', [
            'csv_file' => $file,
        ]);

        // Assert the file was uploded and redirection is returned
        $response->assertStatus(302);

        // Assert a CsvImport record was created
        $this->assertCount(1, CsvImport::all());

        // Retrieve the created CsvImport instance
        $csvImport = CsvImport::first();

        // Simulate running the validation job
        $validateJob = new ValidateCsvImportJob($csvImport);
        $validateJob->handle(new CSVReader, new CSVValidator);

        // Assert the validation status was updated
        $this->assertEquals('validated', $csvImport->fresh()->status);

        // Simulate running the process job
        $processJob = new ProcessCsvFileJob($csvImport);
        $processJob->handle(new CSVReader, new ProductImporter);

        // Assert the completed status
        $this->assertEquals('completed', $csvImport->fresh()->status);

        $this->assertDatabaseHas('products', [
            'sku' => 'SKU001',
            'name' => 'Product1',
            'description' => 'Description1',
            'brand' => 'Brand1'
        ]);
    
        $this->assertDatabaseHas('products', [
            'sku' => 'SKU002',
            'name' => 'Product2',
            'description' => 'Description2',
            'brand' => 'Brand2'
        ]);
    }
}
