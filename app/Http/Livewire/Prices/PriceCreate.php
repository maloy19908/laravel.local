<?php

namespace App\Http\Livewire\Prices;

use App\Models\Category;
use App\Models\Price;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Session;

class PriceCreate extends Component
{
    public Price $price;
    public $referer;
    public $fields = [];

    protected function rules() {
        return [
            'price.name' => [
                'required',
                'price.name' =>
                Rule::unique('prices','name')->where(function ($query){
                    return $query->where('client_id', $this->price->client_id);
                })->ignore($this->price->id),
                //'price.name' => 'unique:prices,name,' . $this->price->id,
            ],
            'price.cost' => 'required|integer',
            'price.goodsSubType' => '',
            'price.priceType' => '',
            'price.bagUnits' => '',
            'price.bagValue' => '',
            'price.client_id' => '',
            'price.category_id' => '',
        ];
    }
    public function save(){
        $this->validate();
        $this->price->save();
        Session::flash('success', 'успех');
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

    public function mount(Request $request){
        $this->referer = (Empty($this->referer)) ? url()->previous(): $this->referer;
        $this->fields = Price::PriceType;
        $this->price = new Price();
        $this->price->client_id = $request->client_id;
        $this->price->category_id = $request->category_id;
    }
    public function render(){
        $category = ($this->price->category_id) ? Category::find($this->price->category_id) : '';
        return view('livewire.prices.price-create',['category' => $category]);
    }
}
