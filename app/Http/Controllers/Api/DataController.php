<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Filters\HotelFilter;
use App\Http\Filters\TourFilter;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountryResource;
use App\Http\Resources\HotelResource;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\TourResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Review;
use App\Models\Tour;
use Illuminate\Http\Request;

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
            'dates.from' => 'nullable|date',
            'dates.to'   => 'nullable|date',
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
}
