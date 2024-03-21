@extends('layouts.app')

@section('content')
<h1 class="text-center">Редактировать Город</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('towns.update',[
  'town'=>$town,
  'parent_id'=>$town->parent_id,
])}}">
  @csrf
  @method('PUT')
  <div class="input-group input-group-sm mb-3">
    <div class="input-group-prepend">
      <span class="input-group-text">Имя</span>
    </div>
    <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
      value="{{$town->name}}" name="name">
  </div>
  <div class="input-group input-group-sm mb-3">
    @include('towns._tree')
  </div>
  <input class="btn btn-primary" type="submit" value="сохранить">
</form>
@endsection