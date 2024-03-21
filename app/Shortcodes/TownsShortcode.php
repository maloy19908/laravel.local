<?php

namespace App\Shortcodes;
use App\Models\Shortcode;

class TownsShortcode{
  public function register($shortcode, $content, $compiler, $shortcode_name, $viewData) {
    $content = Shortcode::where('shortcode_name',$shortcode_name)
    ->where('name',$shortcode->name)
    ->first();
    if(isset($content)){
      return "<b>".$content->description."</b>";
    }
    
    return $content;
  }
}