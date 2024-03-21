<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NPrice extends Model{
    // public const  PriceType = [
    //     'общие' => [
    //         '___' => '___',
    //         'за все' => 'за все',
    //         'за m3' => 'за m3',
    //     ],
    //     'пиломатериалы' => [
    //         'за m2' => 'за m2',
    //         'за штуку' => 'за штуку',
    //         'за погонный метр' => 'за погонный метр',
    //         'за упаковку'   => 'за упаковку',
    //     ],
    //     'cыпучие материалы' => [
    //         'за тонну' => 'за тонну',
    //         'за киллограм' => 'за киллограм',
    //         'за мешок'      => [
    //             'кг' => 'кг',
    //             'литры' => 'литры',
    //         ],
    //     ]
    // ];
    use HasFactory;
    protected $fillable = [
        'cost',
        'goodsSubType',
        'priceType',
        'bagUnits',
        'bagValue',
        'client_id',
        'nomenclature_id',
    ];
    public $timestamps = false;
    public function nomenclature(){
        return $this->belongsTo(Nomenclature::class, 'nomenclature_id');
    }
    
    public function Products() {
        return $this->hasMany(Product::class);
    }
}
