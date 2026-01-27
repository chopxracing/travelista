<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Country;
use App\Models\CountryCity;
use App\Models\Hotel;
use App\Models\HotelAmenity;
use App\Models\HotelAmenityArray;
use App\Models\Photo;
use App\Models\Room;
use App\Models\RoomAmenity;
use App\Models\RoomAmenityArray;
use App\Models\RoomStatus;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
// фото для отелей
    public function hotel_photo_index(Hotel $hotel)
    {
        return view('admin.hotel_photo.index', [
            'hotel' => $hotel,
            'photos' => $hotel->photos,
        ]);
    }

    public function hotel_photo_create(Hotel $hotel)
    {
        return view('admin.hotel_photo.create', compact('hotel'));
    }

    public function hotel_photo_store(Request $request, Hotel $hotel)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:4096',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('hotels/photos', 'public');

            Photo::create([
                'hotel_id' => $hotel->id,
                'file_path' => $path,
            ]);
        }

        return redirect()->route('photo.index', $hotel);
    }

    public function hotel_photo_delete(Hotel $hotel, Photo $photo)
    {
        abort_if($photo->hotel_id !== $hotel->id, 403);

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return back();
    }

    // фото для номеров
    public function room_type_photo_index(RoomType $room_type)
    {
        return view('admin.room_photo.index', [
            'room_type' => $room_type,
            'photos' => $room_type->photos,
        ]);
    }

    public function room_type_photo_create(RoomType $room_type)
    {
        return view('admin.room_photo.create', compact('room_type'));
    }

    public function room_type_photo_store(Request $request, RoomType $room_type)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|max:4096',
        ]);

        foreach ($request->file('images') as $image) {
            $path = $image->store('room_types/photos', 'public');

            Photo::create([
                'room_type_id' => $room_type->id,
                'file_path' => $path,
            ]);
        }

        return redirect()->route('photo.index', $room_type);
    }

    public function room_type_photo_delete(RoomType $room_type, Photo $photo)
    {
        abort_if($photo->room_type_id !== $room_type->id, 403);

        Storage::disk('public')->delete($photo->file_path);
        $photo->delete();

        return back();
    }



}
