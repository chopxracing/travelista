<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/verify-email/{id}/{hash}', [AuthController::class, 'verifyEmailRedirect'])
    ->name('verify-email-redirect')
    ->middleware('signed');
Route::get('/{any}', function () {
    return view('app'); // твой app.blade.php
})->where('any','^(?!admin).*$');
// Админские роуты
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    // Роуты админ - страны
    Route::group(['prefix' => 'country'], function () {
        Route::get('/', [AdminController::class, 'country_index'])->name('country.index');
        Route::get('/create', [AdminController::class, 'country_create'])->name('country.create');
        Route::post('/', [AdminController::class, 'country_store'])->name('country.store');
        Route::get('/{country}/edit', [AdminController::class, 'country_edit'])->name('country.edit');
        Route::patch('/{country}', [AdminController::class, 'country_update'])->name('country.update');
        Route::get('/{country}', [AdminController::class, 'country_show'])->name('country.show');
        Route::delete('/{country}', [AdminController::class, 'country_delete'])->name('country.delete');
    });
    // Роуты админ - города
    Route::group(['prefix' => 'city'], function () {
        Route::get('/', [AdminController::class, 'city_index'])->name('city.index');
        Route::get('/create', [AdminController::class, 'city_create'])->name('city.create');
        Route::post('/', [AdminController::class, 'city_store'])->name('city.store');
        Route::get('/{city}/edit', [AdminController::class, 'city_edit'])->name('city.edit');
        Route::patch('/{city}', [AdminController::class, 'city_update'])->name('city.update');
        Route::get('/{city}', [AdminController::class, 'city_show'])->name('city.show');
        Route::delete('/{city}', [AdminController::class, 'city_delete'])->name('city.delete');
    });
    // Роуты админ - удобства номеров (room_amenities)
    Route::group(['prefix' => 'room_amenity'], function () {
        Route::get('/', [AdminController::class, 'room_amenity_index'])->name('room_amenity.index');
        Route::get('/create', [AdminController::class, 'room_amenity_create'])->name('room_amenity.create');
        Route::post('/', [AdminController::class, 'room_amenity_store'])->name('room_amenity.store');
        Route::get('/{room_amenity}/edit', [AdminController::class, 'room_amenity_edit'])->name('room_amenity.edit');
        Route::patch('/{room_amenity}', [AdminController::class, 'room_amenity_update'])->name('room_amenity.update');
        Route::get('/{room_amenity}', [AdminController::class, 'room_amenity_show'])->name('room_amenity.show');
        Route::delete('/{room_amenity}', [AdminController::class, 'room_amenity_delete'])->name('room_amenity.delete');
    });
    // Роуты админ - удобства отелей (hotel_amenities)
    Route::group(['prefix' => 'hotel_amenity'], function () {
        Route::get('/', [AdminController::class, 'hotel_amenity_index'])->name('hotel_amenity.index');
        Route::get('/create', [AdminController::class, 'hotel_amenity_create'])->name('hotel_amenity.create');
        Route::post('/', [AdminController::class, 'hotel_amenity_store'])->name('hotel_amenity.store');
        Route::get('/{hotel_amenity}/edit', [AdminController::class, 'hotel_amenity_edit'])->name('hotel_amenity.edit');
        Route::patch('/{hotel_amenity}', [AdminController::class, 'hotel_amenity_update'])->name('hotel_amenity.update');
        Route::get('/{hotel_amenity}', [AdminController::class, 'hotel_amenity_show'])->name('hotel_amenity.show');
        Route::delete('/{hotel_amenity}', [AdminController::class, 'hotel_amenity_delete'])->name('hotel_amenity.delete');
    });
    // Роуты админ - пользователи
    Route::group(['prefix' => 'user'], function () {
        Route::get('/', [AdminController::class, 'user_index'])->name('user.index');
        Route::get('/create', [AdminController::class, 'user_create'])->name('user.create');
        Route::post('/', [AdminController::class, 'user_store'])->name('user.store');
        Route::get('/{user}/edit', [AdminController::class, 'user_edit'])->name('user.edit');
        Route::patch('/{user}', [AdminController::class, 'user_update'])->name('user.update');
        Route::get('/{user}', [AdminController::class, 'user_show'])->name('user.show');
        Route::delete('/{user}', [AdminController::class, 'user_delete'])->name('user.delete');
    });
    // Роуты админ - отели
    Route::group(['prefix' => 'hotel'], function () {
        Route::get('/', [AdminController::class, 'hotel_index'])->name('hotel.index');
        Route::get('/create', [AdminController::class, 'hotel_create'])->name('hotel.create');
        Route::post('/', [AdminController::class, 'hotel_store'])->name('hotel.store');
        Route::get('/{hotel}/edit', [AdminController::class, 'hotel_edit'])->name('hotel.edit');
        Route::patch('/{hotel}', [AdminController::class, 'hotel_update'])->name('hotel.update');
        Route::get('/{hotel}', [AdminController::class, 'hotel_show'])->name('hotel.show');
        Route::delete('/{hotel}', [AdminController::class, 'hotel_delete'])->name('hotel.delete');
    });

    // Роуты админ - туры
    Route::group(['prefix' => 'tour'], function () {
        Route::get('/', [AdminController::class, 'tour_index'])->name('tour.index');
        Route::get('/create', [AdminController::class, 'tour_create'])->name('tour.create');
        Route::post('/', [AdminController::class, 'tour_store'])->name('tour.store');
        Route::get('/{tour}/edit', [AdminController::class, 'tour_edit'])->name('tour.edit');
        Route::patch('/{tour}', [AdminController::class, 'tour_update'])->name('tour.update');
        Route::get('/{tour}', [AdminController::class, 'tour_show'])->name('tour.show');
        Route::delete('/{tour}', [AdminController::class, 'tour_delete'])->name('tour.delete');
    });

    // Роуты админ - типы номеров отеля (room_types)
    Route::group(['prefix' => 'room_type'], function () {
        Route::get('/{hotel}', [AdminController::class, 'room_type_index'])->name('room_type.index');
        Route::get('/{hotel}/create', [AdminController::class, 'room_type_create'])->name('room_type.create');
        Route::post('/{hotel}/', [AdminController::class, 'room_type_store'])->name('room_type.store');
        Route::get('/{hotel}/{room_type}/edit', [AdminController::class, 'room_type_edit'])->name('room_type.edit');
        Route::patch('/{hotel}/{room_type}', [AdminController::class, 'room_type_update'])->name('room_type.update');
        Route::get('/{hotel}/{room_type}', [AdminController::class, 'room_type_show'])->name('room_type.show');
        Route::delete('/{hotel}/{room_type}', [AdminController::class, 'room_type_delete'])->name('room_type.delete');
    });

    // Роуты админ - номера (rooms)
    Route::group(['prefix' => 'room'], function () {
        Route::get('/{hotel}/{room_type}/create', [AdminController::class, 'room_create'])->name('room.create');
        Route::post('/{hotel}/{room_type}', [AdminController::class, 'room_store'])->name('room.store');
        Route::get('/{hotel}/{room_type}/{room}/edit', [AdminController::class, 'room_edit'])->name('room.edit');
        Route::patch('/{hotel}/{room_type}/{room}', [AdminController::class, 'room_update'])->name('room.update');
        Route::delete('/{hotel}/{room_type}/{room}', [AdminController::class, 'room_delete'])->name('room.delete');
    });

    // Роуты админ - бронирования
    Route::group(['prefix' => 'booking'], function () {
        Route::get('/', [AdminController::class, 'booking_index'])->name('booking.index');
        Route::get('/create', [AdminController::class, 'booking_create'])->name('booking.create');
        Route::post('/', [AdminController::class, 'booking_store'])->name('booking.store');
        Route::get('/{booking}/edit', [AdminController::class, 'booking_edit'])->name('booking.edit');
        Route::patch('/{booking}', [AdminController::class, 'booking_update'])->name('booking.update');
        Route::get('/{booking}', [AdminController::class, 'booking_show'])->name('booking.show');
        Route::delete('/{booking}', [AdminController::class, 'booking_delete'])->name('booking.delete');
    });

    // Роуты админ - участники бронирования
    Route::group(['prefix' => 'booking_tourist'], function () {
        Route::get('/{booking}', [AdminController::class, 'booking_tourist_index'])->name('booking_tourist.index');
        Route::get('/{booking}/create', [AdminController::class, 'booking_tourist_create'])->name('booking_tourist.create');
        Route::post('/{booking}/', [AdminController::class, 'booking_tourist_store'])->name('booking_tourist.store');
        Route::delete('/{booking}/{booking_tourist}', [AdminController::class, 'booking_tourist_delete'])->name('booking_tourist.delete');
    });


    // Роуты админ - туристы
    Route::group(['prefix' => 'tourist'], function () {
        Route::get('/', [AdminController::class, 'tourist_index'])->name('tourist.index');
        Route::get('/create', [AdminController::class, 'tourist_create'])->name('tourist.create');
        Route::post('/', [AdminController::class, 'tourist_store'])->name('tourist.store');
        Route::get('/{tourist}/edit', [AdminController::class, 'tourist_edit'])->name('tourist.edit');
        Route::patch('/{tourist}', [AdminController::class, 'tourist_update'])->name('tourist.update');
        Route::get('/{tourist}', [AdminController::class, 'tourist_show'])->name('tourist.show');
        Route::delete('/{tourist}', [AdminController::class, 'tourist_delete'])->name('tourist.delete');
    });



    // Роуты админ - фото отелей
    Route::group(['prefix' => 'hotel_photo'], function () {
        Route::get('/{hotel}', [ImageController::class, 'hotel_photo_index'])->name('hotel_photo.index');
        Route::get('/{hotel}/create', [ImageController::class, 'hotel_photo_create'])->name('hotel_photo.create');
        Route::post('/{hotel}', [ImageController::class, 'hotel_photo_store'])->name('hotel_photo.store');
        Route::delete('/{hotel}/{photo}', [ImageController::class, 'hotel_photo_delete'])->name('hotel_photo.delete');
    });
    // Роуты админ - фото номеров (тип)
    Route::group(['prefix' => 'room_type_photo'], function () {
        Route::get('/{room_type}', [ImageController::class, 'room_type_photo_index'])->name('room_type_photo.index');
        Route::get('/{room_type}/create', [ImageController::class, 'room_type_photo_create'])->name('room_type_photo.create');
        Route::post('/{room_type}', [ImageController::class, 'room_type_photo_store'])->name('room_type_photo.store');
        Route::delete('/{room_type}/{photo}', [ImageController::class, 'room_type_photo_delete'])->name('room_type_photo.delete');
    });
});

