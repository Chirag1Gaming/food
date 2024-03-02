<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodPics extends Model
{
    use HasFactory;

    protected $table = "foods_pic";

    function user(){
       return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

    function food(){
        return $this->belongsTo('App\Models\Food', 'food_id', 'id');
     }
}
