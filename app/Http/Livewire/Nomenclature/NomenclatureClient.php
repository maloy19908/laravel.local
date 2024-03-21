<?php

namespace App\Http\Livewire\Nomenclature;

use App\Models\Nomenclature;
use App\Models\NPrice;
use Livewire\Component;

class NomenclatureClient extends Component {
  public $prices;
  public $client_id;
  public $editingID;

  protected $listeners = [
    'fileUploaded' => '$refresh',
    'nomenclatureCreate' => '$refresh',
  ];

  public $fields = [
    'услуги' => [
      'за услугу' => 'за услугу',
      'за час' => 'за час',
      'за метр' => 'за метр',
      'за день' => 'за день',
      'за м3' => 'за м3',
      'за кг' => 'за кг',
      'за м2' => 'за м2',
      'за штуку' => 'за штуку',
      'за упаковку'   => 'за упаковку',
    ],
    'общие' => [
      '___' => '___',
      'за все' => 'за все',
      'за м3' => 'за м3',
    ],
    'пиломатериалы' => [
      'за м2' => 'за м2',
      'за м3' => 'за м3',
      'за штуку' => 'за штуку',
      'за погонный метр' => 'за погонный метр',
      'за упаковку'   => 'за упаковку',
      'за все' => 'за все',
    ],
    'cыпучие материалы' => [
      'за все' => 'за все',
      'за м3' => 'за м3',
      'за штуку' => 'за штуку',
      'за тонну' => 'за тонну',
      'за киллограм' => 'за киллограм',
      'за мешок'      => [
        'кг' => 'кг',
        'литры' => 'литры',
      ],
    ]
  ];

  public function updated($name, $value) {
    if ($name === 'prices.goodsSubType') {
      $this->prices['priceType'] = '';
      $this->prices['bagUnits'] = '';
      $this->prices['bagValue'] = '';
    }
    if ($name === 'prices.priceType') {
      $this->prices['bagUnits'] = '';
      $this->prices['bagValue'] = '';
    }
    if ($name === 'prices.BagUnits') {
      $this->prices['bagValue'] = '';
    }
  }
  public function addPrice($id) {
    $this->validate([
      'prices.goodsSubType' => 'required',
      'prices.priceType' => 'required',
    ]);
    Nomenclature::with('nPrices')->find($id);
    $nPrices = NPrice::firstOrNew([
      'nomenclature_id' => $id,
      'client_id' => $this->client_id
    ]);
    if(isset($this->prices[$id]['cost']) && !empty($this->prices[$id]['cost']) && is_numeric($this->prices[$id]['cost'])) {
      $this->prices['cost'] = $this->prices[$id]['cost'];
      $nPrices->fill($this->prices);
      $nPrices->save();
      $this->prices[$id] = '';
      $this->editingID = '';
    }
  }
  public function clearPrice($id) {
    $price = NPrice::where('nomenclature_id', $id)->first();
    $price->delete();
  }
  public function editPrice($id) {
    $this->editingID = $id;
    $price = NPrice::where('nomenclature_id', $id)->where('client_id', $this->client_id)->first();
    $this->prices = [
      'cost' => $price->cost,
      'goodsSubType' => $price->goodsSubType,
      'priceType' => $price->priceType,
      'bagUnits' => $price->bagUnits,
      'bagValue' => $price->bagValue,
    ];
  }
  public function updatePrice($id) {
    $this->validate([
      'prices.cost' => 'required|numeric',
      'prices.goodsSubType' => 'required',
      'prices.priceType' => 'required',
    ]);
    $nPrices = NPrice::firstOrNew([
      'nomenclature_id' => $id,
      'client_id' => $this->client_id
    ]);
    $nPrices->fill($this->prices);
    $nPrices->save();
    return  $this->editingID = '';
  }
  public function deleteNomenclatureClient($id) {
    return Nomenclature::find($id)->delete($id);
  }
  public function mount($client_id) {
    $this->client_id = $client_id;
  }
  public function render() {
    
    $groups = Nomenclature::with('nPrices')
      ->where('client_id', null)
      ->orWhere('client_id', $this->client_id)
      ->get()->groupBy(function ($item, $key) {
        return $item->category;
      });

    $sortedGroups = $groups->sortBy(function ($group, $key) {
      return $key;
    });
    return view('livewire.nomenclature.nomenclature-client', [
      'groops' => $sortedGroups
    ]);
  }
}
