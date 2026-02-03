<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomTypeResource extends JsonResource
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
            'price' => $this->price,
            'bed_type' => $this->bed_type,
            'capacity' => $this->capacity,
            'size_sqm' => $this->size_sqm,
            'preview_image' => $this->preview_image,
            'photos' => HotelImageResource::collection($this->photos)
        ];
    }
}
