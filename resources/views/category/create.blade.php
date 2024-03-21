@extends('layouts.app')

@section('content')
<h1 class="text-center">Создать Категорию</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('category.store')}}">
  @csrf
  @include('category._form',['parent_id'=>request('parent_id')])
</form>
@endsection