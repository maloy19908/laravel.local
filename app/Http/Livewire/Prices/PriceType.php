<?php

namespace App\Http\Livewire\Prices;

use App\Models\Category;
use App\Models\Price;
use App\Models\ProductField;
use Livewire\Component;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PriceType extends Component
{
    /* 
    Продумать реализацию подвыбора и сортировки по категории 
    Не готово!!!!!
    
    */
    public $edit = false;
    public Price $price;
    public $priceFilds = [];
    public $goodsSubType;
    public $name;
    public $cost;
    public $category_id;
    public $client_id;
    public $priceType;
    public $bagUnits;
    public $bagValue;

    protected function rules(){
        return [
            'name' => [
                'required',
                'string',
                Rule::unique('prices')->where(function($query){
                    return $query->where('client_id', $this->client_id);
                }),
            ],
            'cost' => 'required|integer',
            'client_id' => '',
            'category_id' => '',
            'goodsSubType' => '',
            'priceType' => '',
            'bagUnits' => '',
            'bagValue' => '',
        ];
    }

    public function mount(Request $request){
        $this->client_id = $request->client_id;
        $this->category_id = $request->category_id;
        $this->priceFilds = collect(Price::PriceType);
    }
    public function submit(){
        if($this->edit){
            $validatedData = $this->validate();
            dd(1);
        }
        else{
            $validatedData = $this->validate();
            Price::Create($validatedData);
        }

        return redirect(route('prices.index',[
            'category_id'=>$this->category_id,
            'client_id'=>$this->client_id,
        ]))->with('success', 'успех');
    }

    public function change() {
        $this->emit('change');
    }
    public function edit($id){
        dd($id);
    }
    public function updatedgoodsSubType($value) {
        $this->reset('priceType');
        $this->reset('bagUnits');
        $this->reset('bagValue');
    }
    public function updatedpriceType($value) {
        $this->reset('bagUnits');
        $this->reset('bagValue');
    }
    public function updatedbagUnits($value) {
        $this->reset('bagValue');
    }
    public function render(){
        return view('livewire.price-type');
    }
}
