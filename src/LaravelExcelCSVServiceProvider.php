<?php

namespace Sumeetghimire\LaravelExcelCSV;

use Illuminate\Support\ServiceProvider;

class LaravelExcelCSVServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
           // Registering a singleton instance of LaravelExcelCSV
         $this->app->singleton('laravel-excel-csv', function ($app) {
        return new LaravelExcelCSV();
    });
    }
}
