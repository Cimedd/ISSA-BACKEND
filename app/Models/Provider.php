<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
