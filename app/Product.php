<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable=[
        'category_id',
        'user_id',
        'brand_id',
        'name',
        'price',
        'color',
        'size_id',
        'condition',
        'description',
        'discount',
        'sold',
        'featured_image',
        'release'
    ];
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const REJECTED = 'REJECTED';


    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function highest_bid()
    {
        $highest_bid = ProductBid::orderBy('price', 'desc')->where('product_id', $this->id)->first();
//        dd($highest_bid);
        if($highest_bid!=null){
            return (int)$highest_bid->price;
        }
        return null;
    }
    public function favourited(){
        $result=false;
        $fav=ProductFavourite::where('product_id',$this->id)->where('user_id',auth()->user()->id)->first();
        if($fav!=null){
            $result=true;
        }
        return $result;
    }
}
