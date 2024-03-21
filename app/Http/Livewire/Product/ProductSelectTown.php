<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use App\Models\Town;
use Livewire\Component;
class ProductSelectTown extends Component {
    public $town;
    public $parent;
    public function mount($town) {
        dd($town);
    }
    public function render() {
        return view('livewire.product.product-select-town');
    }
}
