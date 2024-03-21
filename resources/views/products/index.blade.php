@extends('layouts.app')

@section('content')
@include('layouts.alerts')
<div class="row justify-content-center">
  <div class="col">
    @livewire('products')
  </div>
</div>
@endsection