<?php

namespace App\Http\Resources;

use App\ProductSize;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $size =  ProductSize::find($this->size_id);
        return [
        'id'=>$this->id,
        'category'=>optional($this->category)->name,
        'brand'=>optional($this->brand)->name,
        'name'=>$this->name,
        'price'=>$this->price,
        'color'=>$this->color,
        'size'=>$size->text,
        'condition'=>$this->condition,
        'description'=>$this->description,
        'discount'=>$this->discount,
        'sold'=>$this->sold,
        'favourite'=>$this->favourited(),
        'release_date'=>$this->release_date,
        'featured_image'=>$this->featured_image,
        'user'=>new User($this->user),
        'highest_bid'=>$this->highest_bid(),
        'images'=>ProductImage::collection($this->whenLoaded('images')),


        ];
    }
}
