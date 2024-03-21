@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-6 bg-white shadow-sm p-3 rounded">
    <h1 class="text-center">Категории</h1>
    <a class="btn btn-primary" href="{{ route('category.create') }}">Создать категорию</a>
    @foreach($categories as $i => $category_perent)
    <ul class="list-group">
      <li class="list-group-item">
        <div class="d-flex flex-row">
          {{$category_perent->id}}--{{$category_perent->name}}
          <a class="btn btn-link btn-sm" href="{{ route('category.edit',$category_perent)}}"><i class="bi bi-pencil-fill"></i></a>
{{--           <form method="POST" action="{{route('category.destroy',$category_perent)}}">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm"><i class="bi bi-trash3"></i></button>
          </form> --}}
        </div>
        @if(count($category_perent->children))
        @include('category.childrens',['childrens' => $category_perent->children])
        @endif
      </li>
    </ul>
    @endforeach
  </div>

</div>

@endsection
