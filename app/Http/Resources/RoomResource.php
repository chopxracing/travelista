<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'room_type' => new RoomTypeResource($this->room_type),
            'hotel' => new HotelResource($this->hotel),
            'room_number' => $this->room_number,
            'floor' => $this->floor,
            'view_type' => $this->view_type,
            'is_smoking_available' => $this->is_smoking_available,
            'room_status' => $this->room_status
        ];
    }
}
