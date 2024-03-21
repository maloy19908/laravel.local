<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUniqTitle extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
    ];
    protected $guarded = [];
    protected $casts = [
        'options' => 'array',
    ];
    protected $table = 'productuniqtitle';
    public $timestamps = false;

    public function productUniqTitle(){
        return $this->hasMany(Product::class);
    }
}
