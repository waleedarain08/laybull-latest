<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable=[
        'name',
        'image',
    ];
    public $timestamps=false;
    public function products()
    {
        return $this->hasMany(Product::class, 'brand_id')->orderBy('id','desc');
    }
}
