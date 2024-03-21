@extends('layouts.app')

@section('content')
<h1 class="text-center">Редактировать Покупателя {{$client->id}}</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('clients.update',$client->id)}}">
  @csrf
  @method('PUT')
  <p class="text-center">Личные данные:</p>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Имя</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('name',$client->name)}}" name="name">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Распознаватель пользователя</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('recognize',$client->recognize)}}" name="recognize">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Личны телефон</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('phone_personal',$client->phone_personal)}}" name="phone_personal">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Личны телефон второй</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('phone_personal_other',$client->phone_personal_other)}}" name="phone_personal_other">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Ссылка на профиль на Авито</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('avito_url',$client->avito_url)}}" name="avito_url">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Личный сайт</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('site',$client->site)}}" name="site">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">@email</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('email',$client->email)}}" name="email">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Комментарий</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('comment',$client->comment)}}" name="comment">
  </div>
  <p class="text-center">Технические данные:</p>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Телефон на Авито</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('phone_avito',$client->phone_avito)}}" name="phone_avito">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Телефон на Юле</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('phone_yula',$client->phone_yula)}}" name="phone_yula">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Какой транспорт имеется</span>
    </div>
    <textarea name="cars" class="form-control" rows="3">{{old('cars',$client->cars)}}</textarea>
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Стоимость доставки по городу</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('delivery_cost',$client->delivery_cost)}}" name="delivery_cost">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Какая есть спецтехника помимо самосвалов</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('cars_other',$client->cars_other)}}" name="cars_other">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Порядок оплаты</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('order_pay',$client->order_pay)}}" name="order_pay">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Адрес базы</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('adress',$client->adress)}}" name="adress">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Минимальный заказ</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('min_cost',$client->min_cost)}}" name="min_cost">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Минимальный заказ уникальный</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('min_cost_unique',$client->min_cost_unique)}}" name="min_cost_unique">
  </div>
  <div class="input-group input-group-sm mb-1">
    <div class="input-group-prepend">
      <span class="input-group-text">Комментарий другой</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{old('comment_other',$client->comment_other)}}" name="comment_other">
  </div>
  <button type="submit" class="btn btn-primary">Сохранить</button>
</form>
@endsection