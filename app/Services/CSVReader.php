<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;

class CSVReader
{
    /**
     * Read a CSV file into a lazy collection.
     * LSP - this service should be easily substituted by another similar service as logn as it adheres same interface (returns LazyColection)
     *
     * @param string $path Relative path within the 'public' disk
     * @return LazyCollection
     */
    public function readCSV($path): array
    {
        $fullPath = Storage::disk('public')->path($path);

        $handle = fopen($fullPath, 'r');

        // Checking if the file could be opened
        if ($handle === false) {
            throw new \RuntimeException("Cannot open file at path: {$fullPath}");
        }

        $headers = fgetcsv($handle);
        if ($headers === false) {
            fclose($handle);
            throw new \RuntimeException("Cannot read headers from file at path: {$fullPath}");
        }

        // Create a LazyCollection to handle the file reading - processing one item at a time - reduce memory usage
        $lazyCollection = LazyCollection::make(function () use ($handle, $headers) {
            while (($row = fgetcsv($handle)) !== false) {
                yield array_combine($headers, $row);
            }
            fclose($handle);
        });

        // Return both headers and the LazyCollection of rows
        return ['headers' => $headers, 'rows' => $lazyCollection];
    }
}
