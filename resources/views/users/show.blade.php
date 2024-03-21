@extends('layouts.app')

@section('content')
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">Имя</th>
      <th scope="col">Емаил</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row">{{ $user->id }}</th>
      <td>{{ $user->name }}</td>
      <td>{{ $user->email }}</td>
      <td><a href="{{route('users.edit', $user->id)}}">Редактировать</a></td>
      <td>
        <form method="post" action="{{route('users.destroy', $user->id)}}">
          @csrf
          @method('DELETE')
          <input type="submit" value="удалить">
        </form>
      </td>
    </tr>
  </tbody>
</table>
@endsection