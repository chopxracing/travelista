<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
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
            'is_paid' => $this->is_paid,
            'date_from' => $this->date_from,
            'date_to' => $this->date_to,

            'hotel' => HotelResource::make(
                $this->whenLoaded('hotel')
            ),

            'tour' => TourResource::make(
                $this->whenLoaded('tour')
            ),

            'user' => UserResource::make(
                $this->whenLoaded('user')
            ),

            'room_type' => RoomTypeResource::make(
                $this->whenLoaded('room_type')
            ),
            'status' => StatusResource::make(
                $this->whenLoaded('status')
            ),
            'room' => RoomResource::make(
                $this->whenLoaded('room_type')
            ),
            'payment' => PaymentResource::make(
                $this->payment
            ),
            'city' => CityResource::make($this->whenLoaded('city')),
        ];
    }
}
