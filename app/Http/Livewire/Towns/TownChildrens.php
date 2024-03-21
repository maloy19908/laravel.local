<?php

namespace App\Http\Livewire\Towns;

use App\Models\Town;
use Livewire\Component;

class TownChildrens extends Component{
    public $selectedTown;
    public $towns;

    public function mount($towns){
        $this->towns = $towns;
    }
    public function render(){
        return view('livewire.towns.town-childrens',['towns'=> $this->towns]);
    }
    
}
