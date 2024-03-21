<?php

namespace App\Http\Filters;


use Illuminate\Database\Eloquent\Builder;

class ProductsAvitoFilter extends AbstractFilter {
  public const AVITO_ID = 'avito_id';
  public const AVITO_MY_ID = 'avito_my_id';
  public const CATEGORY = 'category';
  public const TITLE = 'title';
  public const ADRESS = 'address';
  public const CONTACTPHONE = 'contactphone';
  public const PRODUCTSTATUS = 'productStatus';

  protected function getCallbacks(): array {
    return [
      self::AVITO_ID => [$this, 'avito_id'],
      self::AVITO_MY_ID => [$this, 'avito_my_id'],
      self::CATEGORY => [$this, 'category'],
      self::TITLE => [$this, 'title'],
      self::ADRESS => [$this, 'address'],
      self::CONTACTPHONE => [$this, 'contactphone'],
      self::PRODUCTSTATUS => [$this, 'productStatus'],
    ];
  }

  public function avito_id(Builder $builder, $value) {
    $builder->where('avito_id', 'like', "%{$value}%");
  }
  public function avito_my_id(Builder $builder, $value) {
    $builder->where('avito_my_id', 'like', "%{$value}%");
  }
  public function category(Builder $builder, $value) {
    $builder->join('categories AS category', 'category_id', '=', 'category.id')->where('category.name', 'like', "%{$value}%");
  }
  public function title(Builder $builder, $value) {
    $builder->join('fields AS title', 'field_id', '=', 'title.id')->where('title', 'like', "%{$value}%");
  }
  public function address(Builder $builder, $value) {
    $builder->join('towns AS town', 'town_id', '=', 'town.id')->where('town.name', 'like', "%{$value}%");
  }
  public function contactphone(Builder $builder, $value) {
    $builder->join('clients AS client', 'client_id', '=', 'client.id')->where('client.phone_personal', 'like', "%{$value}%");
  }
  public function productStatus(Builder $builder, $value) {
    if ($value == 'trashed') {
      $builder->where('trashed', true);
    } elseif ($value == 'arhived') {
      $builder->where('arhived', true);
    } elseif ($value == 'active') {
      $builder->where('trashed', false)->orderBy('time_end','Desc')
      ->where('arhived', false);
    } elseif ($value == 'noPublic') {
      $builder->where('time_end','<', now()->format('Y-m-d'));
    }
  }
}
