<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Price extends Model {

    public const  PriceType = [
        'общие' => [
            '___' => '___',
            'за все' => 'за все',
            'за m3' => 'за m3',
        ],
        'пиломатериалы' => [
            'за m2' => 'за m2',
            'за штуку' => 'за штуку',
            'за погонный метр' => 'за погонный метр',
            'за упаковку'   => 'за упаковку',
        ],
        'cыпучие материалы' => [
            'за тонну' => 'за тонну',
            'за киллограм' => 'за киллограм',
            'за мешок'      => [
                'кг' => 'кг',
                'литры' => 'литры',
            ],
        ]
    ];
    
    use HasFactory;
    protected $fillable = [
        'name',
        'cost',
        'priceType',
        'goodsSubType',
        'bagUnits',
        'bagValue',
        'client_id',
        'category_id',
    ];
    public $timestamps = false;
    protected $table = 'prices';



    // Смежная таблица отключена пока не нужна
    // public function Products(){
    //     return $this->belongsToMany(Product::class, 'product_price');
    // }

    public function Products() {
        return $this->hasMany(Product::class);
    }
    public function client() {
        return $this->belongsTo(Client::class);
    }
    public function category() {
        return $this->belongsTo(Category::class);
    }
}
