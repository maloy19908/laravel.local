<?php

namespace App\Http\Livewire\Prices;

use App\Models\Client;
use App\Models\Price;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class PriceIndex extends Component
{
    public Price $price;
    public $content;
    public $target;
    public $priceFilds;
    public $edit = false;
    public $client_id;
    public $category_id;
    // protected $rules = [
    //     "price.name" => 'required',
    //     "price.cost" => 'required|integer',
    //     "price.client_id" => '',
    //     "price.category_id" => '',
    // ];
    protected $listeners = ['edit'];
    public function rules(){
        $client_id = $this->client_id;
        return [
        "price.name" => ['required',Rule::unique('prices','name')->ignore($this->price->id)->where(function($query) use ($client_id){
            
                return $query->where('client_id', $client_id);
        })],
        "price.cost" => 'required|integer',
        "price.goodsSubType" => '',
        "price.priceType" => '',
        "price.bagUnits" => '',
        "price.bagValue" => '',
        "price.client_id" => '',
        "price.category_id" => '',
        ];
    }
    public function close(){
        $this->reset(['target']);
    }
    public function target($id) {
        $this->target = $id;
    }

    public function update(Price $price){
        $this->target = $price->id;
        $this->edit = true;
        $this->price = $price;
    }

    public function store(Price $price){
        $data = $this->validate();
        if (!$this->edit) {
            $this->price->client_id = $this->client_id ?? null;
            $this->price->category_id = $this->category_id ?? null;
        }
        $this->price->save($data);
        $this->close();
    }
    public function delete(Price $price){
        if ($price){
            $this->price = $price;
            $this->price->delete();
        }
    }
    public function change(){
        $this->emit('price.priceType');
    }
    public function mount(Price $price, Request $request) {
        $this->price = $price;
        $this->priceFilds = collect(Price::PriceType);
        $this->client_id = $request->client_id;
        $this->category_id = $request->category_id;
    }
    public function render(){
        $clients = Client::get();
        $prices = Price::where('client_id', null)
            ->Orwhere('client_id', $this->client_id)
            ->get()->sortBy('name');
        $groops = $prices
            ->groupBy(function($g) {
                return $g->priceType === 'за мешок';
            })->sortKeys();
        return view('livewire.prices.price-index',[
            'groops' => $groops,
            'clients' => $clients,
            'client_id' => $this->client_id,
        ]);
    }
}
