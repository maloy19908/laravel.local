<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];
    public function shortcodes(){
        return $this->hasMany(Shortcode::class);
    }
    public function products(){
        return $this->hasMany(Product::class)->withTrashed();
    }
    public function prices(){
        return $this->hasMany(Price::class);
    }

}
