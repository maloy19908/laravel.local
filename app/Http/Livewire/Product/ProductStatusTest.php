<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Session;

class ProductStatusTest extends Component {
    public $product;
    public $dateBegin;
    public $showForm;
    public $status;

    public function createDateBegin() {
        $this->validate([
            'dateBegin' => 'required|date|date_format:Y-m-d',
        ]);
        $this->product->restore();
        $this->product->dateBegin = $this->dateBegin;
        $this->product->productStatus = 'Активно';
        $this->product->save();
        $this->product->refresh();
    }
    public function removeDateBegin() {
        $this->product->dateBegin = '';
        $this->product->productStatus = 'Снято с публикации';
        $this->product->save();
        $this->product->refresh();
    }
    public function isPublished() {
        return now()->format('Y-m-d') >= $this->dateBegin;
    }
    public function mount($id) {
        $this->product = $id;
    }
    public function render() {
        return view('livewire.product.product-status-test',[
            'product'=>Product::where('id',$this->product)->first(),
        ]);
    }
}
