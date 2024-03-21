<form action="{{ route('switch.user', ['userId' => $user->id]) }}" method="POST">
  @csrf
  <button type="submit">Switch to {{ $user->name }}</button>
</form>
@foreach ($users as $user)
  <form action="{{ route('switch.user', ['userId' => $user->id]) }}" method="POST">
    @csrf
    <button type="submit">{{ $user->name }}</button>
  </form>
@endforeach
