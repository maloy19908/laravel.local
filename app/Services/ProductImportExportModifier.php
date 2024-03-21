<?php

namespace App\Services;

use App\Models\Nomenclature;
use App\Models\Product;
use App\Models\Shortcode;
use Arr;
use Illuminate\Support\Str;

class ProductImportExportModifier {
  private static $instance;

  public static function getInstance(): self {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function importFromMaskToShortcode(Product $product): string {

    $description = data_get($product, 'productField.options.description', '');
    $shortcodes = $product->shortcodes;
    $shortcodesMap = collect();
    foreach ($shortcodes as $shortcode) {
      if (!empty($shortcode->mask)) {
        $shortcodesMap[$shortcode->mask] = "[" . $shortcode->name . "]";
      }
    }
    $patterns = $shortcodesMap->unique()
      ->mapWithKeys(function ($value, $key) {
        return [$key => "/$key"  . "(.+)" . "$key/muU"];
      });
    $patterns = $patterns->toArray();
    $modifiedDescription = preg_replace_callback($patterns, function ($matches) use ($shortcodesMap, $shortcodes) {
      $firstLetter = mb_substr($matches[0], 0, 1);
      foreach ($shortcodesMap as $key => $shortcode) {

        if (isset($firstLetter) && $firstLetter == $key) {
          return $shortcodesMap[$key];
        }
      }
    }, $description);
    return $modifiedDescription;
  }

  public function exportFromShortcode(Product $product): string {
    $clientId = $product->client->id;

    // Получаем все шорткоды для данного клиента
    $shortcodes = Shortcode::where('client_id', $clientId)->orWhereNull('client_id')->get()->keyBy('name');
    // Функция замены шорткодов на соответствующий текст
    $replaceShortcode = function ($match) use ($shortcodes, $clientId) {
      $shortcodeName = strip_tags($match[1]);
      $shortcode = $shortcodes->get($shortcodeName);

      if ($shortcode && !empty($shortcode->client_id)) {
        $escapedDescription = e($shortcode['description']);
        $mask = e($shortcode['mask']);
        return $mask . $escapedDescription . $mask;
      } else {
        $nomenclature = Nomenclature::where('name', $shortcodeName)->first();
        $price = optional($nomenclature)?->nPriceForClient($clientId)?->cost;
        return !empty($price) ? $price . ' р' : 'нет в наличии';
      }
    };
    $description = data_get($product, 'productField.options.description', '');

    // Заменяем шорткоды в описании на соответствующий текст
    $description = preg_replace_callback('/\[(.+?)\]/mui', $replaceShortcode, $description);

    $shortcodesMap = collect();
    $shortcodesMap["🚚"] = (!empty($product->client->cars)) ? "🚚" . $product->client->cars . "🚚" : '';
    $shortcodesMap["🦼"] = (!empty($product->client->cars_other)) ? "🦼" . $product->client->cars_other . "🦼" : '';
    $shortcodesMap["🛒"] = (!empty($product->client->min_cost)) ? "🛒" . $product->client->min_cost . "🛒" : '';

    // Заменяем специальные символы на значения из карты специальных символов
    $patterns = $shortcodesMap
      ->mapWithKeys(function ($value, $key) {
        return [$key => "/$key"  . "(.*)" . "$key/muUi"];
      })->toArray();
      $modifiedDescription = preg_replace_callback($patterns, function ($matches) use ($shortcodesMap) {
        $firstLetter = mb_substr($matches[0], 0, 1);
        if(!empty($shortcodesMap[$firstLetter])){
          return $shortcodesMap[$firstLetter];
        }else{
          return $matches[0]; // Возвращаем оригинальное значение, если оно не пустое
        }
      }, $description);
      dump($modifiedDescription);
    return $modifiedDescription;
  }
  // public function exportFromShortcode(Product $product): string {
  //   $clientId = $product->client->id;
  //   $shortcodesMap = collect();
  //   $shortcodesMap["🚚"] = (!empty($product->client->cars)) ? "🚚" . $product->client->cars . "🚚" : '';
  //   $shortcodesMap["🚔🚔"] = (!empty($product->client->cars_other)) ? "🚔🚔" . $product->client->cars_other . "🚔🚔" : '';

  //   $description = data_get($product, 'productField.options.description', '');
  //   //$shortcodes = $product->shortcodes;
  //   $shortcodes = Shortcode::where('client_id',$product->client->id)->orWhereNull('client_id')->get();
  //   // Паттерн для поиска шорткодов в тексте
  //   preg_match_all('/\[(.+?)\]/mui', $description, $matches);
  //   // Замена шорткодов на соответствующий текст
  //   $matches[1] = array_map(function($el){
  //     return strip_tags($el);
  //   },$matches[1]);
  //   $shortcodeReplacements = collect($matches[1])->mapWithKeys(function ($shortcodeName) use ($shortcodes,$clientId) {
  //     $shortcode = collect($shortcodes)->firstWhere('name', $shortcodeName);
  //     if ($shortcode && !empty($shortcode->client_id)) {
  //       $escapedDescription = e($shortcode['description']);
  //       $mask = e($shortcode['mask']);
  //       return ["[$shortcodeName]" => $mask . $escapedDescription . $mask];
  //     }else{
  //       $nomenclature = Nomenclature::where('name',$shortcodeName)->first();
  //       $price = $nomenclature?->nPriceForClient($clientId)?->cost;
  //       return !empty($price) ? ["[$shortcodeName]" => $price.' p.'] :["[$shortcodeName]" =>'нет в наличии'];
  //     }
  //     return ["[$shortcodeName]" => "[$shortcodeName]"];
  //     // Если шорткод не найден, возвращаем его без изменений
  //   })->toArray();
  //   $description = preg_replace_callback('/\[(.+?)\]/mui', function ($matches) use ($shortcodeReplacements) {
  //     if (array_key_exists($matches[0], $shortcodeReplacements)) {
  //       return $shortcodeReplacements[$matches[0]];
  //     }
  //     return $matches[0]; // если значение не найдено, то оставляем как есть
  //   }, $description);

  //   //$description = Str::replace(array_keys($shortcodeReplacements), array_values($shortcodeReplacements), $description);
  //   $patterns = $shortcodesMap->unique()
  //     ->mapWithKeys(function ($value, $key) {
  //       return [$key => "/$key"  . "(.+)" . "$key/muU"];
  //     })->toArray();
  //     $modifiedDescription = preg_replace_callback($patterns, function ($matches) use ($shortcodesMap) {
  //       $firstLetter = mb_substr($matches[0], 0, 1);
  //       return $shortcodesMap[$firstLetter];
  //     }, $description);
  //   return $modifiedDescription;
  // }
}
