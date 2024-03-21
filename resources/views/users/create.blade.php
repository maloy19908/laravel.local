@extends('layouts.app')

@section('content')
  <h1 class="text-center">Добавить Пользователя</h1>
  @include('layouts.alerts')
  <form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="form-group">
      <label for="name">Name:</label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" name="email">
    </div>
    <div class="form-group">
      <label for="password">Password:</label>
      <input type="password" class="form-control" id="password" name="password">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>
@endsection
