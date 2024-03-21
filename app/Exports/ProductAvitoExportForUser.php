<?php

namespace App\Exports;

use App\Models\Product;

class ProductAvitoExportForUser extends BaseProductExport{

  protected function getProductsQuery() {
    return Product::query()->Where('user_id', $this->userIdOrClientId);
  }
  
  protected function getProductsCondition() {
    return ['user_id', $this->userIdOrClientId];
  }
}
