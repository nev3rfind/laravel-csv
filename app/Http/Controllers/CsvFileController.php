<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CsvFileController extends Controller
{
    public function upload(Request $request)
    {
        $messages = [
            'csv_file.required' => 'You must provide a file.',
            'csv_file.file' => 'The provided file was invalid.',
            'csv_file.mimes' => 'Only CSV and TXT files are allowed.',
        ];

        $validator = Validator::make($request->all(), [
            'csv_file' => 'required|file|mimes:csv,txt',
        ], $messages);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $path = $request->file('csv-file')->store('csv-file');

        // Dispatch a job to validate the CSV before processing.
        ValidateCsvImportJob::dispatch($path);

        return back()->with('status', 'File uploaded successfully! Validation is in progress');
    }
}
