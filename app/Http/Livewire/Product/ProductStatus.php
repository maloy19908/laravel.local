<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Carbon\Carbon;
use Livewire\Component;
use Session;

class ProductStatus extends Component {
    public $product;
    public $dateBegin;
    public $showForm;
    public $status;

    public function createDateBegin($id) {
        $product = Product::withTrashed()->find($id);
        $this->validate([
            'dateBegin.*' => 'date|date_format:Y-m-d',
        ]);
        $product->dateBegin = $this->dateBegin[$id] ?? '';
        $this->product->productStatus = 'Активно';
        $this->product->save();
        $this->product->refresh();
        $this->showForm = false;
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
    public function mount($product) {
        $this->product = Product::withTrashed()->where('id', $product)->first();
    }
    public function render() {
        return view('livewire.product.product-status');
    }
}
