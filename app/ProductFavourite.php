<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductFavourite extends Model
{
    protected $fillable=['product_id','user_id'];
    public $timestamps=false;
}
