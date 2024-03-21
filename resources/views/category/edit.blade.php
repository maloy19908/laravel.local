@extends('layouts.app')

@section('content')
<h1 class="text-center">{{$category->name}}</h1>
@include('layouts.alerts')
<form method="POST" action="{{route('category.update',$category->id)}}">
  @csrf
  @method('PUT')
  @include('category._form')
</form>

@endsection