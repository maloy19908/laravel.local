<?php

namespace App\Http\Livewire\Prices;

use App\Models\Price;
use Arr;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;

class PriceEdit extends Component
{
    public Price $price;
    public $referer;
    public $fields;
    protected function rules() {
        return [
            'price.name' => [
                'required',
                'price.name' =>
                Rule::unique('prices', 'name')->where(function ($query) {
                    return $query->where('client_id', $this->price->client_id);
                })->ignore($this->price->id),
                //'price.name' => 'unique:prices,name,' . $this->price->id,
            ],
            'price.cost' => 'required|integer',
            'price.goodsSubType' => '',
            'price.priceType' => '',
            'price.bagUnits' => '',
            'price.bagValue' => '',
        ];
    }
    public function save() {
        $this->validate();
        $this->price->save();
        Session::flash('success', 'успех');
    }
    public function delete(){
        $this->price->delete();
        Session::flash('success', 'успех');
        return redirect($this->referer);
    }
    public function updatedPriceGoodsSubType($value) {
        $this->price['priceType'] = '';
        $this->price['bagUnits'] = '';
        $this->price['bagValue'] = '';
    }
    public function updatedPricePriceType($value) {
        $this->price['bagUnits'] = '';
        $this->price['bagValue'] = '';
    }
    public function updatedPriceBagUnits($value) {
        $this->price['bagValue'] = '';
    }

    public function getContent($opt= '') {
        $arr = [
            'общие' => [
                '___',
                'за все',
                'за m3',
            ],
            'пиломатериалы' => [
                'за m2',
                'за штуку',
                'за погонный метр',
                'за упаковку',
            ],
            'cыпучие материалы' => [
                'за тонну',
                'за киллограм',
                'за мешок' => [
                    'кг',
                    'литры',
                ],
            ],
        ];
        return Arr::pluck($arr, $opt);
    }
    public function mount(price $price){
        $this->referer = (empty($this->referer)) ? url()->previous() : $this->referer;
        $this->fields = collect(Price::PriceType);
        $this->price = $price;
    }
    public function render(){
        return view('livewire.prices.price-edit',[
            'price'=>$this->price,
            'content'=>$this->getContent(),
        ]);
    }
}
