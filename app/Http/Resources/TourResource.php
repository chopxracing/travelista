<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TourResource extends JsonResource
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
            'description' => $this->description,
            'tour_operator' => new TourOperatorResource($this->tour_operator),
            'tour_type' => new TourTypeResource($this->tour_type),
            'country' => new CountryResource($this->country),
            'city' => new CityResource($this->city),
            'price' => $this->price,
            'days' => $this->days,
            'hotel' => new HotelResource($this->hotel),
            'photos' => HotelImageResource::collection($this->hotel->photos),
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,
        ];
    }
}
