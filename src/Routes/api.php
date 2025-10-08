<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Psgc\RegionController;
use App\Http\Controllers\Psgc\ProvinceController;
use App\Http\Controllers\Psgc\CityMunicipalityController;
use App\Http\Controllers\Psgc\BarangayController;

/*
|--------------------------------------------------------------------------
| PSGC API Routes
|--------------------------------------------------------------------------
|
| Here are the routes for the Philippine Standard Geographic Code (PSGC) API.
| You can customize these routes according to your needs.
|
| Data Source: @jobuntux/psgc (https://www.npmjs.com/package/@jobuntux/psgc)
| Official Source: Philippine Statistics Authority (PSA)
| Developer: Edeeson Opina (https://edeesonopina.vercel.app/)
|
*/

Route::prefix('api/v1')->middleware(['api'])->group(function () {
    // Regions
    Route::get('regions', [RegionController::class, 'index']);
    Route::get('regions/{id}', [RegionController::class, 'show']);
    
    // Provinces
    Route::get('provinces', [ProvinceController::class, 'index']);
    Route::get('provinces/{id}', [ProvinceController::class, 'show']);
    
    // City/Municipalities
    Route::get('city-municipalities', [CityMunicipalityController::class, 'index']);
    Route::get('city-municipalities/{id}', [CityMunicipalityController::class, 'show']);
    
    // Barangays
    Route::get('barangays', [BarangayController::class, 'index']);
    Route::get('barangays/{id}', [BarangayController::class, 'show']);
});
