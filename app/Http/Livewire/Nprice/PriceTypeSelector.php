<?php

namespace App\Http\Livewire\Nprice;

use Livewire\Component;

class PriceTypeSelector extends Component {
  public $price;
  public $priceId;
  public $selectedCategory = null;
  public $selectedSubCategory = null;
  public $selectedSubSubCategory = null;
  protected $listeners = ['updated:selectedCategory' => 'resetSubCategories'];
  protected function PriceType() {
    return [
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
  }
  public function updated($name) {
    if ($name === 'price.goodsSubType') {
      $this->price['priceType'] = '';
      $this->price['bagUnits'] = '';
      $this->price['bagValue'] = '';
    }
    if ($name === 'price.PriceType') {
      $this->price['bagUnits'] = '';
      $this->price['bagValue'] = '';
    }
    if ($name === 'price.BagUnits') {
      $this->price['bagValue'] = '';
    }
  }

  public function savePriceType(){
    $this->emit('createPriceFields', $this->priceId, $this->price);
  }
  public function mount($priceId) {
    $this->priceId = $priceId;
  }
  public function render() {
    $fields = collect($this->PriceType());
    return view('livewire.nprice.price-type-selector', [
      'fields' => $fields,
    ]);
  }
}
