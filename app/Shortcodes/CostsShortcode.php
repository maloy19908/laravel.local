<?php

namespace App\Shortcodes;
use App\Models\Shortcode;
class CostsShortcode{
  public function register($shortcode, $content, $compiler, $shortcode_name, $viewData) {
    $content = Shortcode::where('shortcode_name',$shortcode_name)
    ->where('name',$shortcode->name)
    ->first();
    dump($content);
    if(isset($content)){
      return "<b>" . $content->description . "</b>";
    }
    
    return $content;
  }
}