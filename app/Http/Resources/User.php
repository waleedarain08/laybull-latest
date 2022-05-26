<?php

namespace App\Http\Resources;

use App\Follow;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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

        $request->user_id;
        $check=Follow::where('follow_id', $request->user_id)->where('user_id', Auth::user()->id)->first();
        if ($check){
            $is_follow = true;
        }
        else{
            $is_follow = false;
        }
        $image_url = $this->profile_picture ?  '' . $this->profile_picture : "";
        $no_of_following = Follow::where('user_id', $request->user_id)->count();
        $no_of_follower = Follow::where('follow_id', $request->user_id)->count();

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
            'followers' => $no_of_follower,
            'followeings' => $no_of_following,
            'is_follow' => $is_follow,
            'products' => Product::collection($this->whenLoaded('products')),
        ];
    }
}
