<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductField extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_id',
        'options',
    ];
    protected $casts = [
        'options' => 'array',
    ];
    public $timestamps = false;
    public function product(){
        return $this->hasOne(Product::class);
    }
}
