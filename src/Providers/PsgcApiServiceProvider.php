<?php

namespace EdeesonOpina\PsgcApi\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use EdeesonOpina\PsgcApi\Console\Commands\PsgcImport;
use EdeesonOpina\PsgcApi\Console\Commands\PsgcExport;

/**
 * PSGC API Service Provider
 * 
 * Provides PSGC API functionality to Laravel applications
 * 
 * Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
 * Official Source: Philippine Statistics Authority (PSA)
 * Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
 */
class PsgcApiServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__.'/../Config/psgc.php', 'psgc'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../Config/psgc.php' => config_path('psgc.php'),
        ], 'psgc-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../Database/Migrations' => database_path('migrations'),
        ], 'psgc-migrations');

        // Publish models
        $this->publishes([
            __DIR__.'/../Models' => app_path('Models'),
        ], 'psgc-models');

        // Publish controllers
        $this->publishes([
            __DIR__.'/../Http/Controllers' => app_path('Http/Controllers/Psgc'),
        ], 'psgc-controllers');

        // Publish routes
        $this->publishes([
            __DIR__.'/../Routes/api.php' => base_path('routes/psgc-api.php'),
        ], 'psgc-routes');

        // Register commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                PsgcImport::class,
                PsgcExport::class,
            ]);
        }

        // Load routes only if not published
        if (!file_exists(base_path('routes/psgc-api.php'))) {
            $this->loadRoutes();
        }
    }

    /**
     * Load API routes
     */
    protected function loadRoutes(): void
    {
        Route::prefix(config('psgc.api_prefix', 'api/v1'))
            ->middleware(config('psgc.middleware', ['api']))
            ->group(function () {
                Route::apiResource('regions', \EdeesonOpina\PsgcApi\Http\Controllers\Psgc\RegionController::class);
                Route::apiResource('provinces', \EdeesonOpina\PsgcApi\Http\Controllers\Psgc\ProvinceController::class);
                Route::apiResource('city-municipalities', \EdeesonOpina\PsgcApi\Http\Controllers\Psgc\CityMunicipalityController::class);
                Route::apiResource('barangays', \EdeesonOpina\PsgcApi\Http\Controllers\Psgc\BarangayController::class);
            });
    }
}
