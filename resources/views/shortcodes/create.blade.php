@extends('layouts.app')

@section('content')
<h1 class="text-center">Создать Шорткод</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('shortcodes.store',[
  'client_id'=>request('client_id')
])}}">
  @csrf
  @method('POST')
  <input class="form-control" type="text" value="{{old('name')}}" name="name" placeholder="имя шорткода"><br>
  <input class="form-control" type="text" value="{{old('mask')}}" name="mask" placeholder="маска после экспорта"><br>
  <textarea class="form-control" name="description" rows="7" placeholder="описание">{{old('description')}}</textarea><br>
  <input class="btn btn-primary" type="submit" value="сохранить">
</form>
@endsection