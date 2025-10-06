<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\RegionController;
use App\Http\Controllers\Api\V1\ProvinceController;
use App\Http\Controllers\Api\V1\CityMunicipalityController;
use App\Http\Controllers\Api\V1\BarangayController;

Route::prefix('v1')->group(function () {
    Route::apiResource('regions', RegionController::class);
    Route::apiResource('provinces', ProvinceController::class);
    Route::apiResource('city-municipalities', CityMunicipalityController::class);
    Route::apiResource('barangays', BarangayController::class);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
