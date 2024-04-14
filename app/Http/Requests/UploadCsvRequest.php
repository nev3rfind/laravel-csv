<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// Will be good to abstract the validation logic and error handling instead of leaving in the controller
class UploadCsvRequest extends FormRequest
{
    public function rules()
    {
        return [
            'csv_file' => 'required|file|mimes:csv,txt',
        ];
    }

    // Those messages will be returned if there is something wrong with the uploaded file
    // before even dispatching a job
    public function messages()
    {
        return [
            'csv_file.required' => 'File was not provided.',
            'csv_file.file' => 'The provided file was not valid.',
            'csv_file.mimes' => 'Only CSV and TXT files are allowed to upload.',
        ];
    }
}


