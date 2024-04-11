<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvFileController extends Controller
{
    public function upload(Request $request)
    {
        $required->validate([
            'csv-file' => 'required|file|mimes:csv,txt'
        ]);

        $path = $request->file('csv-file')->store('csv-file');

        // Dispatch a job to validate the CSV before processing.
        ValidateCsvImportJob::dispatch($path);

        return back()->with('status', 'File uploaded successfully! Validation is in progress');
    }
}
