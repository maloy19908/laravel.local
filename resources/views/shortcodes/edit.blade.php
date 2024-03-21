@extends('layouts.app')

@section('content')
<h1 class="text-center">Редактировать Шорткод</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('shortcodes.update',$shortcode)}}">
  @csrf
  @method('PUT')
  <input class="form-control" type="text" value="{{$shortcode->name}}" name="name" placeholder="Имя шорткода"><br>
  <input class="form-control" type="text" value="{{$shortcode->mask}}" name="mask" placeholder="маска после экспорта"><br>
  <textarea class="form-control" name="description" rows="7" placeholder="описание">{{$shortcode->description}}</textarea><br>
  <input class="btn btn-primary" type="submit" value="сохранить">
</form>
@endsection