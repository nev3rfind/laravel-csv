<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\LazyCollection;

// Only responsiple for validating data inside (SRP)
class CSVValidator
{
    public function validateHeaders(array $headers): bool
    {
        $requiredHeaders = ['SKU', 'Name', 'Description', 'Brand'];
        return empty(array_diff($requiredHeaders, $headers));
    }

    public function validate(LazyCollection $rows, array $headers): void
    {
        // Iterates through each row and creates a validator instance 
        $rows ->each(function ($row, $key) use ($headers) {
            $validator = Validator::make($row, [
                'SKU' => 'required|string|max:255',
                'Name' => 'required|string|max:255',
                'Description' => 'nullable|string',
                'Brand' => 'nullable|string|max:255'
            ]);

            if ($validator->fails()) {
                throw new \Exception("Validation error on row " . ($key + 1) . ": " . $validator->errors()->first());
            }
        });
    }
}
