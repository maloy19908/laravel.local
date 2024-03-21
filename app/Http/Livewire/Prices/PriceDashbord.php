<?php

namespace App\Http\Livewire\Prices;

use App\Models\Category;
use App\Models\Price;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;

class PriceDashbord extends Component {
  public $client_id;
  public $category_id;

  protected $listeners = ['afterDelete' => '$refresh'];

  public function mount($client_id, $category_id) {
    $this->client_id = $client_id;
    $this->category_id = $category_id;
  }
  public function render() {
    $prices = Price::where('client_id', $this->client_id)
      ->where('category_id', $this->category_id)
      ->orWhere('category_id', $this->category_id)
      ->where('client_id', Null)
      ->get();
    $categories = Category::withCount(['children', 'products' => function (Builder $query){
      $query->where('client_id', $this->client_id);
    }])->get();
    $products = '';
    if ($this->category_id) {
      $category = $categories->find($this->category_id);
      $products = $category->products->load('field', 'price')->where('client_id', $this->client_id)->groupBy('field.title');
    }
    return view('livewire.prices.price-dashbord',[
      'prices'=> $prices,
      'products'=> $products,
    ]);
  }
}
