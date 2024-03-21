<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Nomenclature;
use App\Models\NPrice;
use App\Models\Price;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class NPriceListImport implements ToModel, WithHeadingRow, WithValidation, WithMultipleSheets {
    public $clientId;
    public function __construct($clientId)
    {
        $this->clientId = $clientId;
        
    }
    public function model(array $rows) {
        $v = Validator::make($rows, [
            'name' =>'required',
            'cost' => 'required',
            'pricetype' => 'required',
            'bagunits' => 'required_if:pricetype,за мешок',
            'bagvalue' => 'required_if:bagunits,exists',
        ],);
        $v->validate();
        $n = Nomenclature::with('nPrices')->where('name', $rows['name'])->first('id');
        $p = NPrice::where('nomenclature_id', $n->id)->first();
        if ($p) {
            if($n->id == $p->nomenclature_id && $this->clientId == $p->client_id){
                $p->update([
                    'cost' => $rows['cost'],
                    'pricetype' => $rows['pricetype'],
                    'bagunits' => $rows['bagunits'] ?? '',
                    'bagvalue' => $rows['bagvalue'] ?? '',
                ]);
            }else{
                $nPrices = NPrice::firstOrNew([
                    'nomenclature_id' => $n->id,
                    'client_id' => $this->clientId
                  ]);
                  $nPrices->cost = $rows['cost'];
                  $nPrices->priceType = $rows['pricetype'];
                  $nPrices->bagunits = $rows['bagunits'];
                  $nPrices->bagvalue = $rows['bagvalue'];
                  $nPrices->save();
            }
        }else{
            $nPrices = NPrice::firstOrNew([
                'nomenclature_id' => $n->id,
                'client_id' => $this->clientId
              ]);
              $nPrices->cost = $rows['cost'];
              $nPrices->priceType = $rows['pricetype'];
              $nPrices->bagunits = $rows['bagunits'];
              $nPrices->bagvalue = $rows['bagvalue'];
              $nPrices->save();
        }

    }
    public function rules(): array {

        return [];
    }
    public function sheets(): array {

        return [
            0 => new NPriceListImport($this->clientId),
        ];
    }
}
