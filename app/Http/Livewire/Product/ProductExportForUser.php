<?php

namespace App\Http\Livewire\Product;

use App\Exports\ProductAvitoExportForUser;
use App\Jobs\ProductExportForUserJob;
use App\Services\ProductDescriptionModifier;
use Illuminate\Support\Facades\Auth;

class ProductExportForUser extends BaseProductExport {
  public $clientOfUserID;

 public function export() {
   $this->fileName = "User" . $this->clientOfUserID . ".xlsx";
   $this->loading = true;
   if (isset($this->clientOfUserID)) {
     $job = ProductExportForUserJob::dispatch($this->clientOfUserID, $this->filter, $this->fileName, new ProductDescriptionModifier);
   }
 }

 public function mount() {
   if (Auth::check()) {
     $this->clientOfUserID = Auth::id();
     $this->fileName = "User" . $this->clientOfUserID . ".xlsx";
   }
 }
 public function render() {
   return view('livewire.product.product-export');
  }
}