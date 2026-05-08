<?php

use App\Http\Controllers\Api\StreetController;
use App\Models\City;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::get('/region/{region}/cities/{city}/streets', [StreetController::class, 'index'])->name('api.v1.city-streets');
//Route::get('/region/{region}/cities/{city}/streets', [StreetController::class, 'index'])->middleware('auth:sanctum')->name('api.v1.city-streets');
