<?php

namespace App\Http\Livewire\Product;

use App\Exports\ProductAvitoExportForClient;
use App\Jobs\ProductExportForClientJob;
use App\Services\ProductDescriptionModifier;

class ProductExportForClient extends BaseProductExport {
 public $clientOfUserID;

 public function export() {
   $this->loading = true;
   if (isset($this->clientOfUserID)) {
     $this->fileName = "Client{$this->clientOfUserID}ForAvito.xlsx";
     $job = new ProductAvitoExportForClient($this->clientOfUserID, $this->filter, new ProductDescriptionModifier);
     $job->store($this->fileName,'public');
   }
 }

 public function mount($clientOfUserID = null){
   $this->clientOfUserID = $clientOfUserID;
 }
 public function render() {
  return view('livewire.product.product-export');
 }
}
