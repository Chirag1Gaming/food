<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Food extends Model
{
    use HasFactory;

    protected $table = "foods";
    protected $with = ['pics'];

    function user(){
       return $this->belongsTo('App\Models\User', 'user_id', 'id');
    }

   //  public function pics()
   // {
   //    return $this->hasMany('App\Models\FoodPics', 'food_id', 'id');
   // }

   public function pics()
   {
      return $this->hasMany('App\Models\FoodPics', 'food_id', 'id')
                  ->select('foods_pic.*', DB::raw("CONCAT('" . asset('foods/') . '/' . "', foods_pic.path) AS full_path"));
   }


}
