<?php

namespace App\Exports;

use App\Models\Product;


class ProductAvitoExportForClient extends BaseProductExport {
  protected function getProductsQuery() {
    return Product::query()->Where('client_id', $this->userIdOrClientId);
  }
 
  protected function getProductsCondition() {
    return ['client_id', $this->userIdOrClientId];
  }
  
 }