<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nomenclature extends Model{
    
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'client_id',
    ];
    public $timestamps = false;

    public function setCategoryAttribute($value){
        $this->attributes['category'] = trim(mb_strtolower($value));
    }

    public function nPrices() {
        return $this->hasMany(NPrice::class, 'nomenclature_id');
    }
    public function nPriceForClient($clientId) {
        
        return $this->nPrices()->where('client_id', $clientId)->first();
    }
    public function products() {
        return $this->hasMany(Product::class, 'nomenclature_id');
    }

}
