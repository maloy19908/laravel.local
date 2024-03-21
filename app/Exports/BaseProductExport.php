<?php

namespace App\Exports;

use App\Models\Product;
use App\Models\ProductField;
use App\Services\ProductDescriptionModifier;
use App\Services\ProductImportExportModifier;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

abstract class BaseProductExport implements FromView, WithEvents
{
 use Exportable;
 protected $userIdOrClientId; // Используется вместо userId и clientId
 protected $filter;
 protected $productDescriptionModifier;

 abstract protected function getProductsQuery();
 abstract protected function getProductsCondition();

 protected function filterProducts($products) {
  return $products->when(isset($this->filter['active']) || isset($this->filter['arhive']), function ($query) {
    $query->where(function ($query) {
      if (isset($this->filter['active'])) {
        $query->orWhere('productStatus', 'Активно');
      }
      if (isset($this->filter['arhive'])) {
        $query->orWhere('productStatus','!=', 'Активно');
      }
    });
  });
 }

 public function __construct($userIdOrClientId=null,$filter=[]) {
   $this->userIdOrClientId = $userIdOrClientId;
   $this->filter = $filter;
   $this->productDescriptionModifier = ProductImportExportModifier::getInstance();

 }

 public function view(): View {

  $productFields = $this->getProductsQuery();
  $productFields->with('productField');
  $productFields = $productFields->get();
  $modifyDescriptions = [];
  foreach ($productFields as $key => $product) {
    $options = $product->productField->options;
    $modifyDescriptions[$product->id] = $this->productDescriptionModifier->exportFromShortcode($product);
  }

  list($field, $value) = $this->getProductsCondition();
  $products = Product::query()->where($field, $value);
  $products->with('client', 'nPrice', 'nomenclature', 'productUniqTitle', 'productField');
  $products = $this->filterProducts($products);
  $products = $products->get();
  $rowMax = $products->max(function ($product) {
    if(!isset($product->productField->options)){
      return -INF;
    }
      return $product->productField->options;
  });
  if(is_array($rowMax)){
    $rowMax = Arr::add($rowMax, 'datebegin','');
    $rowMax = Arr::add($rowMax, 'avitostatus','');
  }
  $productListingFee = Product::where('listingFee', 'Package+Single')->pluck('listingFee','id');
  if ($products->isEmpty()) {
    throw new Exception("No products found for client ID {$this->userIdOrClientId}");
  }
  if(isset($rowMax) && !is_array($rowMax)){
    throw new Exception("Invalid data structure for rowMax");
  }
  return view('exports.product.product', [
    'products' => $products,
    'productListingFee' => $productListingFee,
    'rowMax' => $rowMax,
    'modifyDescriptions' => $modifyDescriptions,
  ]);
}

 public function registerEvents(): array {
  return [
    AfterSheet::class => function (AfterSheet $event) {
      $sheet = $event->sheet->getDelegate();
      $sheet->freezePane('A2');
      $sheet->setAutoFilter('A1:' . $sheet->getHighestColumn() . '1');
      $sheet->getStyle('A1:' . $sheet->getHighestColumn() . '1')->applyFromArray([
        'fill' => [
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => [
            'argb' => 'fcf18f',
          ],
        ],
      ]);
    },
  ];
}
}