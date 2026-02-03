<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
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
            'title' => $this->title,
            'rating' => $this->rating,
            'comment' => $this->comment,

            'booking' => $this->when($this->booking, fn() => new BookingResource($this->booking)),
            'hotel'   => $this->when($this->hotel, fn() => new HotelResource($this->hotel)),
            'tour'    => $this->when($this->tour, fn() => new TourResource($this->tour)),
            'user'    => $this->when($this->user, fn() => new UserResource($this->user)),
        ];
    }
}
