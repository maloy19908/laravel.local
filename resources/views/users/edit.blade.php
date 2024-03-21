@extends('layouts.app')

@section('content')
@include('layouts.alerts')
  <form method="POST" action="{{route('users.update',$user->id)}}">
    @csrf
    @method('PUT')
    <input type="text" value="{{$user->name}}" name="name"><br>
    <input type="text" value="{{$user->email}}" name="email"><br>
    <input type="submit" value="изменить"><br>
  </form>
@endsection