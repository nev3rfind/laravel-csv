<?php

namespace App\Http\Controllers;

use App\Jobs\ValidateCsvImportJob;
use App\Models\CsvImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UploadCsvRequest;

class CsvFileController extends Controller
{
    public function upload(UploadCsvRequest $request)
    {
        $file = $request->file('csv_file');
        // Will be stored in var/www/html/laravel-csv/storage/app/public/csv_files
        $path = $file->store('csv_files', 'public');

        // Update file import status
        $csvImport = CsvImport::create([
            'filename' => $path,
            'status' => 'pending'
        ]);

        // Dispatch the validation job with the CsvImport record
        ValidateCsvImportJob::dispatch($csvImport);

        return back()->with('status', 'File uploaded successfully! Validation is in progress.');
    }

    public function showUploadForm()
    {
        $csvImports = CsvImport::all();

        return view('upload', ['csvImports' => $csvImports]);
    }
}
