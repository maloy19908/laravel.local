<?php

namespace App\Services;

use App\Models\Nomenclature;
use App\Models\Product;
use App\Models\Shortcode;
use Arr;
use Illuminate\Support\Str;

class ShortcodeFromNomenclature {
  private static $instance;

  public static function getInstance(): self {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }

  public function createShortcode($name) :bool{
    
    $create = Shortcode::firstOrCreate([
      'name'=>$name,
      'mask'=>'💲💲',
    ]);
    if($create){
      return  true;
    }
  }
  public function editShortcode($shortcodeName,$newName) :bool{
    $shortcode = Shortcode::where('name',$shortcodeName)->first();
    if(!$shortcode){
      Shortcode::create([
        'name'=>$newName,
        'mask'=>'💲💲',
      ]);
    }else{
      $update = $shortcode->update([
        'name'=>$newName,
      ]);
    }
    return true;
  }

  public function deleteShortcode($name) :bool {
    $shortcode = Shortcode::where('name',$name)->first();
    if(isset($shortcode)){
      return $shortcode->delete();
    }
    return false;
  }

  public function showShordcode($id){
    if(!$id){
      return false;
    }
    return Shortcode::find($id);
  }

  public function replaserShortcodesToText(){
    $shortcodes = Shortcode::all();
    $shortcodes->each(function($el){
      dump($el->name);
    });
    
  }
}
