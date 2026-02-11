<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'stars' => $this->stars,
            'country' => new CityResource($this->country),
            'city' => new CityResource($this->city),
            'meters_to_sea' => $this->meters_to_sea,
            'meters_to_center' => $this->meters_to_center,
            'address' => $this->address,
            'description' => $this->description,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'is_active' => $this->is_active,
            'preview_image' => $this->preview_image,
            'email' => $this->email,
            'phone' => $this->phone,
            'amenities' => AmenityResource::collection($this->amenities),
            'room_types' => RoomTypeResource::collection($this->room_type),
            'min_price' => $this->room_type->min('price'),
            'max_price' => $this->room_type->max('price'),
            'photos' => HotelImageResource::collection($this->photos),
            'avg_rating' => $this->reviews_avg_rating
                ? round($this->reviews_avg_rating, 1)
                : null,
            'reviews_count' => $this->reviews_count,
        ];
    }
}
