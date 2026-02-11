<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\HotelFilter;
use App\Http\Filters\TourFilter;
use App\Http\Resources\BookingResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\HotelResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\TourResource;
use App\Models\Booking;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Messages;
use App\Models\Payment;
use App\Models\Review;
use App\Models\Tour;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DataController extends Controller
{
    // Логин и выдача токена
    public function getCities()
    {
        return CityResource::collection(City::all());
    }
    public function getCountries()
    {
        return CountryResource::collection(Country::all());
    }

    public function getHotels(Request $request)
    {
        $data = $request->validate([
            'city' => 'nullable|integer|exists:cities,id',
            'country' => 'nullable|integer|exists:countries,id',
            'stars' => 'nullable|array',
            'pricefrom' => 'nullable|integer',
            'priceto' => 'nullable|integer',
            'dates' => 'nullable|array',
        ]);
        $filter = app()->make(HotelFilter::class, ['queryParams' => array_filter($data)]);
        $hotels = Hotel::filter($filter)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->paginate(6);
        return HotelResource::collection($hotels);

    }
    public function getHotel(Hotel $hotel) {
        return new HotelResource($hotel);
    }
    public function filterHotels(Request $request)
    {
        $cities = City::all();
        $countries = Country::all();
        $hotels = Hotel::with('room_type', 'amenities')->get(); // берем все отели

        // Находим мин и макс цену среди всех комнат всех отелей
        $minPrice = $hotels->flatMap(fn($hotel) => $hotel->room_type->pluck('price'))->min();
        $maxPrice = $hotels->flatMap(fn($hotel) => $hotel->room_type->pluck('price'))->max();

        $stars = array_map('intval', $request->input('stars', []));
        $city = $request->input('city');
        $country = $request->input('country');
        $pricefrom = $request->input('pricefrom');
        $priceto = $request->input('priceto');

        $filteredHotels = $hotels->filter(function ($hotel) use ($stars, $city, $country, $pricefrom, $priceto) {
            // фильтр по звёздам
            if (!empty($stars) && !in_array($hotel->stars, $stars)) {
                return false;
            }

            // фильтр по городу
            if ($city && $hotel->city_id != $city) {
                return false;
            }

            // фильтр по стране
            if ($country && $hotel->country_id != $country) {
                return false;
            }

            // фильтр по цене (минимальная цена комнаты)
            $hotelMinPrice = $hotel->room_type->min('price');
            if ($pricefrom && $hotelMinPrice < $pricefrom) {
                return false;
            }
            if ($priceto && $hotelMinPrice > $priceto) {
                return false;
            }

            return true;
        });

        return response()->json([
            'cities' => $cities,
            'countries' => $countries,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'hotels' => HotelResource::collection($filteredHotels->values())
        ]);
    }

    public function getReviews(Hotel $hotel)
    {
        $reviews = Review::with(['user', 'tour', 'booking', 'hotel'])
        ->where('hotel_id', $hotel->id)
            ->paginate(6);
        return ReviewResource::collection($reviews);
    }

    public function getTours(Request $request)
    {
        $data = $request->validate([
            'city'       => 'nullable|integer|exists:cities,id',
            'country'    => 'nullable|integer|exists:countries,id',
            'stars'      => 'nullable|array',
            'stars.*'    => 'integer|min:1|max:5',
            'pricefrom'  => 'nullable|numeric|min:0',
            'priceto'    => 'nullable|numeric|min:0',
            'dates'      => 'nullable|array',
            'tour_type_id' => 'nullable|integer|exists:tour_types,id',
            'dates.check_in' => 'nullable|date',
            'dates.check_out' => 'nullable|date',
        ]);
        $filter = app()->make(TourFilter::class, [
            'queryParams' => array_filter($data)
        ]);

        $tours = Tour::with([
            'hotel',
            'hotel.photos',
            'tour_type',
            'tour_operator',
            'city',
            'country'
        ])
            ->filter($filter)
            ->paginate(6);

        return TourResource::collection($tours);
    }

    public function getTour(Tour $tour)
    {
        return new TourResource($tour);
    }

    public function putToBasket(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tour_id' => 'nullable|exists:tours,id',
            'hotel_id' => 'nullable|exists:hotels,id',
            'room_type_id' => 'nullable|exists:room_types,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date|after:date_from',
            'amount' => 'required|integer|min:1',
        ]);

        if (!$data['tour_id'] && !$data['hotel_id']) {
            return response()->json([
                'message' => 'Тур или отель обязателен'
            ], 422);
        }

        $booking = Booking::create([
            'user_id' => $data['user_id'],
            'tour_id' => $data['tour_id'] ?? null,
            'hotel_id' => $data['hotel_id'] ?? null,
            'room_type_id' => $data['room_type_id'] ?? null,
            'date_from' => $data['date_from'],
            'date_to' => $data['date_to'],
            'status_id' => 1, // в корзине
            'is_paid' => 0,
        ]);

        Payment::create([
            'booking_id' => $booking->id,
            'amount' => $data['amount'],
        ]);

        return response()->json([
            'message' => 'Добавлено в корзину',
            'booking' => $booking
        ], 201);
    }

    public function getBookings(Request $request)
    {
        $user = $request->user();

        $bookings = Booking::with([
            'hotel',
            'tour',
            'room_type',
            'status'
        ])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return BookingResource::collection($bookings);
    }

    public function saveTourist(Request $request)
    {
        $data = $request->validate([
            'surname'          => 'required|string|max:255',
            'name'             => 'required|string|max:255',
            'last_name'        => 'nullable|string|max:255',

            'passport_series'  => 'required|string|max:10',
            'passport_number'  => 'required|string|max:20',
            'passport_date'    => 'required|date',
            'passport_org'     => 'required|string|max:255',

            'birth_date'       => 'required|date',
        ]);
        $data['user_id'] = auth()->id();

        $tourist = Tourist::create($data);

        return response()->json([
            'message' => 'Турист успешно добавлен',
            'data' => $tourist,
        ], 201);
    }
    public function deleteTourist(Tourist $tourist)
    {
        $tourist->delete();
    }

    public function deleteBooking(Booking $booking)
    {
        $booking->payment()->delete();
        $booking->delete();
    }

    public function saveMessage(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        Messages::create($data);
        return response()->json([
            'message' => 'Сообщение успешно сохранено'
        ], 200);

    }
}
