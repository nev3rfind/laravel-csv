<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CSVReader;
use App\Services\CSVValidator;
use App\Services\ProductImporter;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CSVReader::class, function ($app) {
            return new CSVReader();
        });
    
        $this->app->bind(CSVValidator::class, function ($app) {
            return new CSVValidator();
        });

        $this->app->bind(ProductImporter::class, function ($app) {
            return new ProductImporter();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
