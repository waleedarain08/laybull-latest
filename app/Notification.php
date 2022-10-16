<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    public function notification()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
