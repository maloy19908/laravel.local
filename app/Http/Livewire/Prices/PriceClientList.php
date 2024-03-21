<?php

namespace App\Http\Livewire\Prices;

use App\Models\Price;
use Livewire\Component;
use Illuminate\Validation\Rule;

class PriceClientList extends Component
{
    public $edit = false;
    public $allFields = true;
    public price $price;
    protected function rules(){
        return [
            'price.cost' => 'required|integer',
        ];
    }
    public function edit(){
        $this->edit = true;
    }
    public function store(Price $price){
        $data = $this->validate();
        $this->edit = false;
        $this->price->save($data);
    }
    public function delete($id){
        $this->emit('afterDelete');
        Price::findOrFail($id)->delete();
    }
    public function mount(price $price){
        $this->price = $price;
    }
    public function render(){
        return view('livewire.prices.price-client-list');
    }
}
