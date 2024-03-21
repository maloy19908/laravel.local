<?php

namespace App\Http\Livewire\Product;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

abstract class BaseProductExport extends Component {
  public $loading;
  public $filter = [];
  public $fileName;
  public $file;

 public function download() {
   return Storage::download('public/' . $this->fileName);
 }

 public function searchFile() {
   if (Storage::exists('public/' . $this->fileName)) {
     $this->reset('loading', 'filter');
     $this->file = true;
   }
 }

 abstract public function export();

 public function render() {
   $this->filter = array_filter($this->filter, function ($el) {
     return $el !== false;
   });
   return view('livewire.product.product-export');
 }
}