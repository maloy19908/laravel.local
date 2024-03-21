@extends('layouts.app')
@section('content')
<h2>{{$category->name}}</h2>
@forelse($category->childrens as $child)
  <li>{{$child->name}}</li>
@empty
  нет вложенных категорий
@endforelse
@endsection
