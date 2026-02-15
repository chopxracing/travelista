<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\HotelFilter;
use App\Http\Filters\TourFilter;
use App\Http\Requests\getHotelRequest;
use App\Http\Requests\hotelEventRequest;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\roomEventRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\HotelResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\RoomResource;
use App\Http\Resources\TourResource;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Messages;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\Tour;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PartnerController extends Controller
{
    // Логин и выдача токена
    public function login(LoginUserRequest $request)
    {
        if (!Auth::attempt($request->only(['email', 'password']))) {
            return response()->json([
                'message' => 'wrong email or password'
            ], 401);
        }
        // $user = Auth::user();
        $user = User::query()->where('email', $request->email)->first();
        $user->tokens()->delete();
        return response()->json([
            'user' => $user,
            'token' => $user->createToken("authToken of user {$user->name}")->plainTextToken
        ]);
    }

    public function hotelEvents(hotelEventRequest $request) {
        $data = $request->validated();
        $hotel = Hotel::query()->findOrFail($data['id']);
        $hotel->update($data);
        return new HotelResource($hotel);
    }

    public function roomEvents(roomEventRequest $request) {
        $data = $request->validated();
        $status = RoomStatus::query()->findOrFail($data['name']);
        $room = Room::query()->findOrFail($data['id']);
        $room->update($data);
        return new RoomResource($room);
    }

    public function getHotel(getHotelRequest $request) {
        $data = $request->validated();
        $hotel = Hotel::query()->findOrFail($data['id']);
        return new HotelResource($hotel);
    }

}
