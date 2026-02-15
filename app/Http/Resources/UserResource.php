<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'surname' => $this->surname,
            'last_name' => $this->last_name,
            'country' => new CityResource($this->country),
            'city' => new CityResource($this->city),
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'gender' => $this->gender,
            'role' => $this->role,
            'tourists' => TouristResource::collection($this->tourists),
        ];
    }
}
