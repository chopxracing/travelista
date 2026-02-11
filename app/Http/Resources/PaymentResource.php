<?php

namespace App\Http\Resources;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
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
            'payment_reference' => $this->payment_reference,
            'payment_type' => $this->payment_type,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'paid_at' => $this->paid_at,
            'refunded_at' => $this->refunded_at,
            'refunded_amount' => $this->refunded_amount,
            'refunded_reason' => $this->refunded_reason,
            'payment_details' => $this->payment_details,
            'card_last_four' => $this->card_last_four,

        ];
    }
}
