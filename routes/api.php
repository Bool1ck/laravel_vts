<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CityDataController;
use Illuminate\Support\Facades\Route;

Route::get('/region/{region}/cities/{city}/data', [CityDataController::class, 'index'])
    ->middleware('auth', 'can:create,App\Models\ConnectingPoint,region')
    ->name('api.v1.city-data');
