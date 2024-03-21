@extends('layouts.app')

@section('content')
<h1>404::{{$exception->getMessage()}}</h1>
@endsection