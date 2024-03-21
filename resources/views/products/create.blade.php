@extends('layouts.app')
@section('content')
<h1 class="text-center">Создать Авито товар</h1>
Это должна быть сложная карточка товара с условиями и выбором категорий !!!
@include('layouts.alerts')
<form method="POST" action="{{route('products.store',[
            'client_id' => request('client_id'),
            'category_id' => request('category_id'),
        ])}}">
    @csrf
    @method('POST')
    <input class="form-control" type="text" value="{{old('my_id')}}" name="my_id" placeholder="my_id"><br>
    <input class="form-control" type="text" value="{{old('title')}}" name="title" placeholder="title"><br>
    <textarea class="form-control" name="description" rows="7" placeholder="description">{{old('description')}}</textarea><br>
    {{-- <input class="form-control" type="text" value="{{old('address')}}" name="address" placeholder="address"><br>
    <input class="form-control" type="text" value="{{old('contactphone')}}" name="contactphone" placeholder="contactphone"><br> --}}
    <input class="btn btn-primary" type="submit" value="Создать">
  </form>
@endsection