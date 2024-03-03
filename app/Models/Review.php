<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = "reviews";
    protected $with = ['user'];

    function user(){
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }
}
