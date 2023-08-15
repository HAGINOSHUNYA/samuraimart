<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function products()//商品モデルとの紐づけ
     {
         return $this->hasMany('App\Models\Product');
     }

     public function major_category()//親カテゴリとの紐づけ
     {
         return $this->belongsTo('App\Models\MajorCategory');
     }
}
