<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Livewire\Component;
use Illuminate\Support\Facades\Session;

class ProductCopy extends Component {
  public $product;
  protected $listeners = ['clearSessions'];

  public function copy() {
    $productCopied = Session::get('productCopied', []);
    if (isset($productCopied[$this->product])) {
      unset($productCopied[$this->product]);
    } else {
      $productCopied[$this->product] = $this->product;
    }
    Session::put('productCopied', $productCopied);
    $this->emit('copiedUpdated',$productCopied);
  }

  public function clearSessions() {
    $this->render();
  }
  public function mount($product) {
    $this->product = $product;
  }
  public function render() {
    $productCopied = Session::get('productCopied', []);
    $thisCopy = isset($productCopied[$this->product]);
    return view('livewire.product.product-copy', [
      'thisCopy' => $thisCopy,
    ]);
  }
}
