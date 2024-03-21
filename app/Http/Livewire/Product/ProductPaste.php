<?php

namespace App\Http\Livewire\Product;

use App\Models\Client;
use App\Models\Product;
use App\Models\ProductField;
use App\Models\Town;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class ProductPaste extends Component {
  public $clientPhone;
  public $towns_ids;
  public $copied;
  protected function getListeners(): array {
    return [
      'copiedUpdated' => 'copiedUpdated',
      'clientPhoneChanger' => 'clientPhoneChanger',
      'selectedTowns' => 'selectedTowns',
    ];
  }
  protected function mostFrequentTown($town_id = 'town_id') {
    $clientId = Client::with('products')->where('phone_personal', $this->clientPhone)->first();
    $towns = $clientId->products->pluck($town_id);
    $counts = $towns->groupBy(function ($town) {
      return $town;
    })->map(function ($group) {
      return $group->count();
    })->sortByDesc(function ($count) {
      return $count;
    });
    return $counts->keys()->first();
  }

  public function selectedTowns($towns_ids) {
    $this->towns_ids = $towns_ids;
  }
  public function paste() {
    // Нужны проверки т.к. не дает сохранить если сущ my_id
    if (isset($this->clientPhone) && !empty($this->copied)) {
      $clientId = Client::with('products')->where('phone_personal', $this->clientPhone)->first();
      if ($clientId) {
        $this->copied = collect(Session::get('productCopied'));
        // Город который чаще всего 
        $mostFrequentTown_id = $this->mostFrequentTown();
        if(empty($mostFrequentTown_id)){
          $address_street = $this->mostFrequentTown('address_street');
        }
        // Сохраняем все копии
        $townIds = ($this->towns_ids && is_array($this->towns_ids)) ? $this->towns_ids : [$mostFrequentTown_id];
        foreach ($this->copied as $el) {
          $product = Product::withTrashed()
            ->with('client', 'category', 'nPrice', 'nomenclature', 'town', 'productUniqTitle', 'productField')
            ->findOrFail($el);
          foreach ($townIds as $townId) {
            $copy = $product->replicate();
            $copyName = "COPY-" . uniqid();
            $copy->avito_id = '';
            $copy->my_id = $copyName;
            $copy->client_id = $clientId ? $clientId->id : null;
            if(!empty($townId)){
              $copy->town_id = $townId;
            }else{
              $copy->town_id = null;
              $copy->address_street = $address_street;
            }
            $copy->dateBegin = null;
            $copy->productStatus = 'Удаленно';
            $copy->deleted_at = now();
            $copy->user_id = $clientId->user_id;
            $copy->save();

            $copyProductField = new ProductField([
              'product_id' => $copy->id,
              'options' => $product->productField->options,
            ]);
            $copyProductField->save();
          }
        }
        $this->clear();
        $this->emit('productUpdated');
      }
    }
  }
  public function clear() {
    session()->forget('productCopied');
    $this->copied = collect(Session::get('productCopied'));
    $this->emit('clearSessions');
  }
  public function clientPhoneChanger($clientPhone) {
    $this->clientPhone = $clientPhone;
  }
  public function copiedUpdated($data) {
    $this->copied = $data;
  }

  public function render() {
    $this->copied = Session::get('productCopied');
    return view('livewire.product.product-paste', [
      'copied' => $this->copied,
    ]);
  }
}
