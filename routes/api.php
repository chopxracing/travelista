<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DataController;
use App\Http\Resources\BookingResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/cities', [DataController::class, 'getCities'])->withoutMiddleware('throttle:api');
Route::get('/countries', [DataController::class, 'getCountries'])->withoutMiddleware('throttle:api');

Route::post('/hotels', [DataController::class, 'getHotels'])->withoutMiddleware('throttle:api');
Route::get('/hotels/filters', [DataController::class, 'filterHotels'])->withoutMiddleware('throttle:api');
Route::get('/hotels/{hotel}', [DataController::class, 'getHotel']);
Route::get('/reviews/{hotel}', [DataController::class, 'getReviews']);

Route::post('/tours', [DataController::class, 'getTours'])->withoutMiddleware('throttle:api');
Route::get('/tours/{tour}', [DataController::class, 'getTour']);
Route::post('/tours/put', [DataController::class, 'putToBasket']);

// auth
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware('throttle:api');
Route::post('/register', [AuthController::class, 'register'])->withoutMiddleware('throttle:api');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return new UserResource($request->user());
});
Route::middleware('auth:sanctum')->get('/bookings', [DataController::class, 'getBookings']);
Route::middleware('auth:sanctum')->post('/tourist', [DataController::class, 'saveTourist']);
Route::middleware('auth:sanctum')->delete('/tourist/{tourist}', [DataController::class, 'deleteTourist']);

Route::middleware('auth:sanctum')->delete('/bookings/{booking}', [DataController::class, 'deleteBooking']);


Route::middleware('auth:sanctum')->post('/contacts', [DataController::class, 'saveMessage']);
