<?php

namespace App\Http\Controllers;

use App\Jobs\ValidateCsvImportJob;
use App\Models\CsvImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CsvFileController extends Controller
{
    public function upload(Request $request)
    {
        $messages = [
            'csv_file.required' => 'File was not provided.',
            'csv_file.file' => 'The provided file was not valid.',
            'csv_file.mimes' => 'Only CSV and TXT files are allowed to upload.',
        ];

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $file = $request->file('csv_file');
        $path = $file->store('csv_files', 'public');

        $csvImport = CsvImport::create([
            'filename' => $path,
            'status' => 'pending'
        ]);

        // Dispatch the validation job with the CsvImport record
        ValidateCsvImportJob::dispatch($csvImport);

        dispatch(new ValidateCsvImportJob($csvImport));

        return back()->with('status', 'File uploaded successfully! Validation is in progress.');
    }

    public function showUploadForm()
    {
        $csvImports = CsvImport::all();

        return view('upload', ['csvImports' => $csvImports]);
    }
}
