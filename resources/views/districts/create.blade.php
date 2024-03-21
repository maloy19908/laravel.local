@extends('layouts.app')

@section('content')
<h1 class="text-center">Создать Область</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('districts.store')}}">
    @csrf
    @method('POST')
    <div class="input-group input-group-sm mb-3">
      <div class="input-group-prepend">
        <span class="input-group-text">Имя</span>
      </div>
      <input type="text" class="form-control aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm"
        value="{{old('name')}}" name="name">
    </div>
    <button type="submit" class="btn btn-primary">Создать</button>
  </form>
@endsection