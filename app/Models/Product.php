<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use Filterable;
    use SoftDeletes;
    public $timestamps = false;
    protected $table = 'products';
    protected $guarded = [];

    public function client() {
        return $this->belongsTo(Client::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
    public function nPrice(){
        return $this->belongsTo(NPrice::class, 'nomenclature_id','nomenclature_id');
    }
    public function price(){
        return $this->hasOne(NPrice::class, 'nomenclature_id','nomenclature_id');
    }
    public function nomenclature() {
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id');
    }
    public function town(){
        return $this->belongsTo(Town::class);
    }
    public function productUniqTitle(){
        return $this->belongsTo(ProductUniqTitle::class);
    }
    public function productField(){
        return $this->hasOne(ProductField::class);
    }
    public function shortcodes(){
        return $this->hasMany(Shortcode::class,'client_id','client_id');
    }

    // public function productStatus(){
    //     return $this->hasOne(ProductStatus::class);
    // }
}
