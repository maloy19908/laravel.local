<?php

namespace App\Services;

use App\Models\Product;

class ProductDescriptionModifier {
  private $masks;

  public function __construct() {
    $this->masks = [
      'cars' => '🚚(.+)?🚚',
    ];
  }

  public function modifyDescription(Product $product): void {
    $replacements = [
      'cars' => "🚚{$product->client->cars}🚚",
    ];
    $description = $product->productField->options['description'];
    foreach ($this->masks as $key => $mask) {
      if (!empty($replacements[$key])) {
        if ($key == 'cars' && empty($product->client->cars)) {
          break;
        }
        $pattern = '/' . $mask . '/muU';
        $description = preg_replace_callback($pattern, function ($matches) use ($replacements, $key) {
          return $replacements[$key];
        }, $description);
      }
    }
    $options = $product->productField->options;
    $options['description'] = $description;
    $product->productField->options = $options;
    $product->productField->save();
  }
  

}
