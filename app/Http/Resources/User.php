<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $image_url = $this->profile_picture ?  '/' . $this->profile_picture : "";

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'city' => $this->city,
            'country' => $this->country,
            'contact' => $this->phone_number,
            'is_seller' => $this->is_seller,
            'profile_picture' => $image_url,
            'iban' => $this->iban,
            'card_number' => $this->card_number,
            'account_holder_name' => $this->account_name,
            'bank_name' => $this->bank_name,
            'products' => Product::collection($this->whenLoaded('products')),
        ];
    }
}
