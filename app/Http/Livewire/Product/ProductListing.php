<?php

namespace App\Http\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ProductListing extends Component
{
    public $product;
    public $productListingFee = [];
    public function toogle(){
        if (isset($this->productListingFee[$this->product])) {
            $product = Product::find($this->product);
            $product->listingFee = 'Package';
            $product->save();
        }else{
            $product = Product::find($this->product);
            $product->listingFee = 'Package+Single';
            $product->save();
        }
    }

    public function mount($product) {
        $this->product = $product;
    }
    public function render(){
        $this->productListingFee = Product::where('listingFee', 'Package+Single')->pluck('listingFee', 'id');
        $thisListing = isset($this->productListingFee[$this->product]);
        return view('livewire.product.product-listing',[
            'thisListing'=>$thisListing,
        ]);
    }
}
