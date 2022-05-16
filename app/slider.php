<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class slider extends Model
{
    //
    public function category()
    {
        return $this->belongsTo(Category::class,'category_to_redirect');
    }
}
