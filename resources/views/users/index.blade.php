@extends('layouts.app')
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        @isset($users)
          <table class="table">
            <thead class="thead-dark">
              <tr>
                <th scope="col">ID</th>
                <th scope="col">Имя</th>
                <th scope="col">Емаил</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr>
                  <th scope="row">
                    <a class="btn btn-link" href="{{ route('users.show', $user->id) }}">{{ $user->id }}</a>
                  </th>
                  <td>
                    <form action="{{ route('switch.user', ['userId' => $user->id]) }}" method="POST">
                      @csrf
                      <button class="btn btn-link" type="submit">{{ $user->name }}</button>
                    </form>
                  </td>
                  <td>{{ $user->email }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
          <a href="{{ route('users.create') }}">Создать</a>
        @endisset
      </div>
    </div>
  </div>
@endsection
