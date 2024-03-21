<?php

namespace App\Http\Livewire\Towns;

use App\Models\Town;
use Illuminate\Http\Request;
use Livewire\Component;

class TownSelection extends Component{
    public $selectedTown;
    public $towns;
    public function mount(){
        $this->towns = Town::where('parent_id',null)->get();
    }
    public function render(){
        return view('livewire.towns.town-selection');
    }
    
}
