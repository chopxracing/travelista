<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cities', [\App\Http\Controllers\Api\AdminController::class, 'cities']);
Route::get('/countries', [\App\Http\Controllers\Api\AdminController::class, 'countries']);
