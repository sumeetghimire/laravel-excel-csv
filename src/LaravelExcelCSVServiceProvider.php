<?php

namespace SumeetGhimire\LaravelExcelCSV;

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
        // Bind your LaravelExcelCSV class to the service container
        $this->app->bind('laravel-excel-csv', function () {
            return new LaravelExcelCSV();
        });
    }
}
