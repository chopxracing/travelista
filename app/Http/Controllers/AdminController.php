<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingStatus;
use App\Models\BookingTourists;
use App\Models\City;
use App\Models\Country;
use App\Models\CountryCity;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use App\Models\HotelAmenityArray;
use App\Models\Room;
use App\Models\RoomAmenity;
use App\Models\RoomAmenityArray;
use App\Models\RoomStatus;
use App\Models\RoomType;
use App\Models\Tour;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function index()
    {
        return view('admin.index');
    }

    // Круд по странам
    public function country_index()
    {
        $countries = Country::all();
        return view('admin.country.index', compact('countries'));
    }
    public function country_show(Country $country)
    {
        return view('admin.country.show', compact('country'));
    }
    public function country_create()
    {
        return view('admin.country.create');
    }
    public function country_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        Country::firstOrCreate($data);
        return redirect()->route('country.index');
    }
    public function country_edit(Country $country)
    {
        return view('admin.country.edit', compact('country'));
    }
    public function country_update(Request $request, Country $country)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $country->update($data);
        return redirect()->route('country.index');
    }
    public function country_delete($id)
    {
        Country::destroy($id);
        return redirect()->route('country.index');
    }

    // Круд по городам
    public function city_index()
    {
        $cities = City::all();
        return view('admin.city.index', compact('cities'));
    }
    public function city_show(City $city)
    {
        return view('admin.city.show', compact('city'));
    }
    public function city_create()
    {
        $countries = Country::all();
        return view('admin.city.create', compact('countries'));
    }
    public function city_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|integer',
        ]);
        City::firstOrCreate($data);

        return redirect()->route('city.index');
    }
    public function city_edit(City $city)
    {
        return view('admin.city.edit', compact('city'));
    }
    public function city_update(Request $request, City $city)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $city->update($data);
        return redirect()->route('city.index');
    }
    public function city_delete($id)
    {
        City::destroy($id);
        return redirect()->route('city.index');
    }


    // Круд по удобствам номеров (room_amenities)
    public function room_amenity_index()
    {
        $room_amenities = RoomAmenity::all();
        return view('admin.room_amenity.index', compact('room_amenities'));
    }
    public function room_amenity_show(RoomAmenity $room_amenity)
    {
        return view('admin.room_amenity.show', compact('room_amenity'));
    }
    public function room_amenity_create()
    {
        return view('admin.room_amenity.create');
    }
    public function room_amenity_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        RoomAmenity::firstOrCreate($data);
        return redirect()->route('room_amenity.index');
    }
    public function room_amenity_edit(RoomAmenity $room_amenity)
    {
        return view('admin.room_amenity.edit', compact('room_amenity'));
    }
    public function room_amenity_update(Request $request, RoomAmenity $room_amenity)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $room_amenity->update($data);
        return redirect()->route('room_amenity.index');
    }
    public function room_amenity_delete($id)
    {
        RoomAmenity::destroy($id);
        return redirect()->route('room_amenity.index');
    }

    // Круд по удобствам отелей (hotel_amenities)
    public function hotel_amenity_index()
    {
        $hotel_amenities = HotelAmenity::all();
        return view('admin.hotel_amenity.index', compact('hotel_amenities'));
    }
    public function hotel_amenity_show(HotelAmenity $hotel_amenity)
    {
        return view('admin.hotel_amenity.show', compact('hotel_amenity'));
    }
    public function hotel_amenity_create()
    {
        return view('admin.hotel_amenity.create');
    }
    public function hotel_amenity_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        HotelAmenity::firstOrCreate($data);
        return redirect()->route('hotel_amenity.index');
    }
    public function hotel_amenity_edit(HotelAmenity $hotel_amenity)
    {
        return view('admin.hotel_amenity.edit', compact('hotel_amenity'));
    }
    public function hotel_amenity_update(Request $request, HotelAmenity $hotel_amenity)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $hotel_amenity->update($data);
        return redirect()->route('hotel_amenity.index');
    }
    public function hotel_amenity_delete($id)
    {
        HotelAmenity::destroy($id);
        return redirect()->route('room_amenity.index');
    }


    // Круд по пользователям
    public function user_index(Request $request)
    {
        $search = $request->get('search');
        $query = User::query(); // Builder

        if ($search) {
            $query->where('phone', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.user.index', compact('users'));
    }
    public function user_show(User $user)
    {
        $tourist = $user->tourist;
        return view('admin.user.show', compact('user', 'tourist'));
    }
    public function user_create()
    {
        $cities = City::all();
        $countries = Country::all();
        return view('admin.user.create', compact('cities', 'countries'));
    }
    public function user_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'string|required',
            'city_id' => 'integer|required',
            'country_id' => 'integer|required',
        ]);
        User::firstOrCreate($data);
        return redirect()->route('user.index');
    }
    public function user_edit(User $user)
    {
        $countries = Country::all();
        $cities = City::all();
        return view('admin.user.edit', compact('user', 'countries', 'cities'));
    }
    public function user_update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'string|nullable',
            'city_id' => 'integer|nullable',
            'country_id' => 'integer|nullable',
        ]);
        $user->update($data);
        return redirect()->route('user.show', ['user' => $user]);
    }
    public function user_delete($id)
    {
        User::destroy($id);
        return redirect()->route('user.index');
    }


    // Круд по отелям
    public function hotel_index(Request $request)
    {
        $search = $request->get('search');
        $hotels = Hotel::with(['country', 'city'])
            ->when($search, function($query) use ($search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('admin.hotel.index', compact('hotels', 'search'));
    }
    public function hotel_show(Hotel $hotel)
    {
        return view('admin.hotel.show', compact('hotel'));
    }
    public function hotel_create()
    {
        $hotel_amenities = HotelAmenity::all();
        $cities = City::all();
        $countries = Country::all();
        return view('admin.hotel.create', compact('cities', 'countries',  'hotel_amenities'));
    }
    public function hotel_store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:hotels',
            'stars' => 'required|integer',
            'meters_to_sea' => 'nullable|integer',
            'meters_to_center' => 'nullable|integer',
            'description' => 'nullable',
            'check_in_time' => 'string|required',
            'check_out_time' => 'string|required',
            'preview_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'is_active' => 'integer|required',
            'email' => 'required|string|email|max:255|unique:hotels',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'city_id' => 'integer|required',
            'country_id' => 'integer|required',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:hotel_amenities,id',
        ]);

        $data['preview_image'] = $request->file('preview_image')->store('hotels', 'public');

        $amenitiesIds = $data['amenities'] ?? [];
        unset($data['amenities']);

        $hotel = Hotel::create($data);

        foreach ($amenitiesIds as $amenityId) {
            HotelAmenityArray::firstOrCreate([
                'hotel_amenity_id' => $amenityId,
                'hotel_id' => $hotel->id,
            ]);
        }

        return redirect()->route('hotel.index');
    }
    public function hotel_edit(Hotel $hotel)
    {
        $countries = Country::all();
        $cities = City::all();
        $hotel_amenities = HotelAmenity::all();
        $selectedAmenities = $hotel->amenities->pluck('id')->toArray();
        return view('admin.hotel.edit', compact('hotel', 'countries', 'cities', 'hotel_amenities', 'selectedAmenities'));
    }
    public function hotel_update(Request $request, Hotel $hotel)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255|unique:hotels,name,' . $hotel->id,
            'stars' => 'nullable|integer|between:1,5',
            'meters_to_sea' => 'nullable|integer|min:0',
            'meters_to_center' => 'nullable|integer|min:0',
            'description' => 'nullable',
            'check_in_time' => 'string|nullable',
            'check_out_time' => 'string|nullable',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'is_active' => 'integer|nullable|in:0,1',
            'email' => 'nullable|string|email|max:255|unique:hotels,email,' . $hotel->id,
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'city_id' => 'required|integer|exists:cities,id',
            'country_id' => 'required|integer|exists:countries,id',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:hotel_amenities,id',
            'photos' => 'nullable|array',
        ]);
        $hotel->update(collect($data)->except('amenities')->toArray());

        //синхронизация удобств (удаляет старые, добавляет новые)
        $hotel->amenities()->sync($data['amenities'] ?? []);
        // Обработка изображения
        if ($request->hasFile('preview_image')) {
            // Удаляем старое изображение
            if ($hotel->preview_image) {
                Storage::disk('public')->delete($hotel->preview_image);
            }

            // Сохраняем новое
            $data['preview_image'] = $request->file('preview_image')->store('hotels', 'public');
        } else {
            // Если изображение не загружали, сохраняем старое
            $data['preview_image'] = $hotel->preview_image;
        }


        return redirect()->route('hotel.show', ['hotel' => $hotel])
            ->with('success', 'Отель успешно обновлен!');
    }
    public function hotel_delete($id)
    {
        Hotel::destroy($id);
        return redirect()->route('hotel.index');
    }

    // Круд по типам номеров
    public function room_type_index(Request $request, $hotel)
    {
        $room_types = RoomType::where('hotel_id', $hotel)->get();

        return view('admin.room_type.index', [
            'room_types' => $room_types,
            'hotelId' => $hotel,
        ]);
    }
    public function room_type_show($hotel, RoomType $room_type, Request $request)
    {

        $fullhotel = Hotel::where('id', $hotel)->firstOrFail();
        $room_type->load('amenities');
        $search = $request->get('search');
        $rooms = Room::where('room_type_id', $room_type->id)
            ->when($search, function($query) use ($search) {
                return $query->where('room_number', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(10);


        return view('admin.room_type.show', [
            'room_type' => $room_type,
            'hotelId' => $hotel,
            'rooms' => $rooms,
        ], compact('fullhotel'));
    }
    public function room_type_create($hotel)
    {
        $room_amenities = RoomAmenity::all();
        return view('admin.room_type.create', [
            'hotelId' => $hotel,
        ], compact('room_amenities'));
    }
    public function room_type_store(Request $request, $hotel)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable',
            'price' => 'required|integer',
            'bed_type' => 'required|string',
            'preview_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'capacity' => 'required|integer',
            'size_sqm' => 'required|integer',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:room_amenities,id',
        ]);


        $amenitiesIds = $data['amenities'] ?? [];
        unset($data['amenities']);

        $data['hotel_id'] = $hotel;
        $data['preview_image'] = $request->file('preview_image')->store('room_types', 'public');

        $room_type = RoomType::create($data);

        foreach ($amenitiesIds as $amenityId) {
            RoomAmenityArray::firstOrCreate([
                'room_type_id' => $room_type->id,
                'room_amenity_id' => $amenityId,
            ]);
        }
        return redirect()->route('room_type.index', ['hotel' => $hotel]);
    }
    public function room_type_edit($hotel, RoomType $room_type)
    {
        $room_amenities = RoomAmenity::all();
        $selectedAmenities = $room_type->amenities->pluck('id')->toArray();
        return view('admin.room_type.edit', [
            'hotelId' => $hotel,
        ], compact('room_type', 'room_amenities', 'selectedAmenities'));
    }
    public function room_type_update(Request $request, $hotel, RoomType $room_type)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable',
            'price' => 'nullable|integer',
            'bed_type' => 'nullable|string',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
            'capacity' => 'nullable|integer',
            'size_sqm' => 'nullable|integer',
            'amenities' => 'nullable|array',
            'amenities.*' => 'integer|exists:room_amenities,id',
        ]);

        //обновляем сам тип номера
        $room_type->update(collect($data)->except('amenities')->toArray());

        //синхронизация удобств (удаляет старые, добавляет новые)
        $room_type->amenities()->sync($data['amenities'] ?? []);

        // изображение
        if ($request->hasFile('preview_image')) {
            if ($room_type->preview_image) {
                Storage::disk('public')->delete($room_type->preview_image);
            }

            $room_type->update([
                'preview_image' => $request->file('preview_image')->store('room_types', 'public')
            ]);
        }

        return redirect()
            ->route('room_type.show', ['hotel' => $hotel, 'room_type' => $room_type])
            ->with('success', 'Тип номера успешно обновлён!');
    }
    public function room_type_delete($hotel, $id)
    {
        RoomType::destroy($id);
        $hotelId = $hotel;
        return redirect()->route('room_type.index', ['hotel' => $hotelId]);
    }

    // Круд по номерам
    public function room_create($hotel, $room_type)
    {
        $room_statuses = RoomStatus::all();
        return view('admin.room.create', [
            'hotelId' => $hotel,
            'room_type' => $room_type,
        ], compact('room_statuses'));
    }
    public function room_store(Request $request, $hotel, $room_type)
    {
        $data = $request->validate([
            'room_number' => 'required|string|max:255|unique:rooms,room_number',
            'floor' => 'required|string|max:255',
            'view_type' => 'required|string|max:255',
            'is_smoking_available' => 'required|integer|in:0,1',
            'room_status_id' => 'required|integer|exists:room_statuses,id',
        ]);
        $data['hotel_id'] = $hotel;
        $data['room_type_id'] = $room_type;
        Room::create($data);

        return redirect()->route('room_type.show', ['hotel' => $hotel, 'room_type' => $room_type]);
    }
    public function room_edit($hotel, $room_type, Room $room)
    {
        $room_statuses = RoomStatus::all();
        return view('admin.room.edit', [
            'hotelId' => $hotel,
            'room_type' => $room_type,
        ], compact('room', 'room_statuses'));
    }
    public function room_update(Request $request, $hotel, $room_type, Room $room)
        {
            $data = $request->validate([
                'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $room->id,
                'floor' => 'required|string|max:255',
                'view_type' => 'required|string|max:255',
                'is_smoking_available' => 'required|integer|in:0,1',
                'room_status_id' => 'required|integer|exists:room_statuses,id',
            ]);
            $data['hotel_id'] = $hotel;
            $data['room_type_id'] = $room_type;
            $room->update($data);


            return redirect()->route('room_type.show', ['hotel' => $hotel, 'room_type' => $room_type])
                ->with('success', 'Номер успешно обновлен!');
        }
    public function room_delete($hotel, $room_type, $id)
    {
        Room::destroy($id);
        $hotelId = $hotel;
        return redirect()->route('room_type.show', ['hotel' => $hotel, 'room_type' => $room_type]);
    }

    // Круд по бронированиям
    public function booking_index(Request $request)
    {
        $search = $request->get('search');
        $query = Booking::query(); // Builder

        if ($search) {
            $query->where('user_id', 'like', '%' . $search . '%')
                ->orWhere('name', 'like', '%' . $search . '%')
                ->orWhere('status_id', 'like', '%' . $search . '%');
        }

        $bookings = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.booking.index', compact('bookings'));
    }
    public function booking_show(Booking $booking)
    {
        $booking_tourist_count = $booking->booking_tourists->count();
        $tourist = $booking->tourist;
        return view('admin.booking.show', compact('booking', 'tourist', 'booking_tourist_count'));
    }
    public function booking_create()
    {
        $users = User::all();
        $hotels = Hotel::all();
        $statuses = BookingStatus::all();
        $room_types = RoomType::all();
        $tours = Tour::all();
        return view('admin.booking.create', compact('hotels', 'statuses', 'room_types', 'users', 'tours'));
    }
    public function booking_store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|integer',
            'hotel_id' => 'nullable|integer|exists:hotels,id',
            'tour_id' => 'nullable|integer|exists:tours,id',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);
        $data['status_id'] = 1;
        $data['is_paid'] = 0;
        Booking::create($data);
        return redirect()->route('booking.index');
    }
    public function booking_edit(Booking $booking)
    {
        $hotels = Hotel::all();
        $statuses = BookingStatus::all();
        $room_types = RoomType::all();
        return view('admin.booking.edit', compact('booking', 'hotels', 'statuses' , 'room_types'));
    }
    public function booking_update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'hotel_id' => 'required|integer|exists:hotels,id',
            'room_type_id' => 'required|integer|exists:room_types,id',
            'status_id' => 'required|integer|exists:room_statuses,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);
        $booking->update($data);
        return redirect()->route('booking.show', ['booking' => $booking]);
    }
    public function booking_delete($id)
    {
        Booking::destroy($id);
        return redirect()->route('booking.index');
    }

// Круд по участникам бронирования
    public function booking_tourist_index(Request $request, $bookingId)
    {
        $tourists = BookingTourists::where('booking_id', $bookingId)->get();
        return view('admin.booking_tourist.index', [
            'bookingId' => $bookingId,
            'tourists' => $tourists,
        ]);
    }
    public function booking_tourist_create($bookingId)
    {
        $tourists = Tourist::all();
        return view('admin.booking_tourist.create', [
            'bookingId' => $bookingId,
            'tourists' => $tourists,
        ]);
    }
    public function booking_tourist_store(Request $request, $bookingId)
    {
        $data = $request->validate([
            'tourist_id' => 'required|integer|exists:tourists,id',
        ]);
        $data['booking_id'] = $bookingId;
        BookingTourists::create($data);
        return redirect()->route('booking_tourist.index', ['booking' => $bookingId]);
    }
    public function booking_tourist_delete($booking, $id)
    {
        BookingTourists::destroy($id);
        return redirect()->route('booking_tourist.index', ['booking' => $booking]);
    }


    // Круд по туристам
    public function tourist_index(Request $request)
    {
        $search = trim($request->get('search'));
        $query = Tourist::query();

        if ($search !== '') {

            // если ввели ТОЛЬКО число - ищем по id
            if (ctype_digit($search)) {
                $query->where('id', (int)$search);
            }
            //поиск по ФИО
            else {
                $words = preg_split('/\s+/', $search);

                $query->where(function ($q) use ($words) {
                    foreach ($words as $word) {
                        $q->where(function ($sub) use ($word) {
                            $sub->where('surname', 'like', "%{$word}%")
                                ->orWhere('name', 'like', "%{$word}%")
                                ->orWhere('last_name', 'like', "%{$word}%");
                        });
                    }
                });
            }
        }

        $tourists = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.tourist.index', compact('tourists'));
    }

    public function tourist_show(Tourist $tourist)
    {
        return view('admin.tourist.show', compact('tourist'));
    }
    public function tourist_create()
    {
        $users = User::all();
        return view('admin.tourist.create', compact('users'));
    }
    public function tourist_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:tourists',
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:255',
            'phone' => 'required|string|max:255',
            'gender' => 'string|required',
            'city_id' => 'integer|required',
            'country_id' => 'integer|required',
        ]);
        User::firstOrCreate($data);
        return redirect()->route('tourist.index');
    }
    public function tourist_edit(Tourist $tourist)
    {
        $countries = Country::all();
        return view('admin.tourist.edit', compact( 'countries', 'tourist'));
    }
    public function tourist_update(Request $request, Tourist $tourist)
    {
        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'passport_series' => 'nullable|string|max:255',
            'passport_number' => 'nullable|string|max:255',
            'passport_date' => 'nullable|string|max:255',
            'passport_org' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'passport_country_id' => 'integer|nullable',
        ]);
        $tourist->update($data);
        return redirect()->route('tourist.show', ['tourist' => $tourist]);
    }
    public function tourist_delete($id)
    {
        Tourist::destroy($id);
        return redirect()->route('tourist.index');
    }
}
