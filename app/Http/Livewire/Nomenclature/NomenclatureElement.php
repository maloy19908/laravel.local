<?php

namespace App\Http\Livewire\Nomenclature;

use App\Models\Client;
use App\Models\Nomenclature;
use App\Models\NPrice;
use Doctrine\DBAL\Schema\Index;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class NomenclatureElement extends Component {
  public $nomenclature;
  public $priceType;
  public $cost;
  public $clientId;
  public $edit;
  public $iteration;
  public $groopName;
  public $fields = [
    'общие' => [
      '___' => '___',
      'за все' => 'за все',
      'за м3' => 'за м3',
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
  protected $listeners = ['fileUploaded' => '$refresh'];
  protected function rules() {
    return [
      'priceType.cost' => 'required|numeric',
      'priceType.goodsSubType' => 'required',
      'priceType.priceType' => 'required',
    ];
  }
  public function addPrice() {
    $this->validate();
    $price = NPrice::updateOrCreate([
      'nomenclature_id' => $this->nomenclature->id,
      'client_id' => $this->clientId,
    ],[
      'cost' => $this->priceType['cost'],
      'goodsSubType' =>$this->priceType['goodsSubType'] ?? '',
      'priceType' => $this->priceType['priceType'] ?? '',
      'bagUnits' => $this->priceType['bagUnits'] ?? '',
      'bagValue' => $this->priceType['bagValue'] ?? '',
    ]);
    $this->nomenclature->nPrices()->save($price);
    $this->edit = false;
    $this->reset('priceType');
  }
  public function editPrice() {
    $this->priceType  = $this->nomenclature
      ->nPrices()
      ->where('nomenclature_id', $this->nomenclature->id)
      ->where('client_id', $this->clientId)
      ->first()
      ->getAttributes();
      $this->edit = !$this->edit;
  }
  public function deleteNomenclature() {
    $this->nomenclature->delete();
    $this->emit('nomenclatureDeleted');
  }
  public function updated($name,$value) {
    if ($name === 'priceType.goodsSubType') {
      $this->priceType['priceType'] = '';
      $this->priceType['bagUnits'] = '';
      $this->priceType['bagValue'] = '';
    }
    if ($name === 'priceType.priceType') {
      $this->priceType['bagUnits'] = '';
      $this->priceType['bagValue'] = '';
    }
    if ($name === 'priceType.BagUnits') {
      $this->priceType['bagValue'] = '';
    }
  }
  public function mount($nomenclature,$clientId,$iteration,$groopName) {
    $this->iteration = $iteration;
    $this->nomenclature = $nomenclature;
    $this->clientId = $clientId;
    $this->groopName = $groopName;
  }
  public function render() {
    
    return view('livewire.nomenclature.nomenclature-element');
  }
}
