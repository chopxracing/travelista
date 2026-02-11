<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TouristResource extends JsonResource
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
            'passport_series' => $this->passport_series,
            'passport_number' => $this->passport_number,
            'passport_org' => $this->passport_org,
            'passport_date' => $this->passport_date,
            'passport_country' => new CountryResource($this->country),
            'birth_date' => $this->birth_date,
            'name' => $this->name,
            'surname' => $this->surname,
            'last_name' => $this->last_name,
        ];
    }
}
