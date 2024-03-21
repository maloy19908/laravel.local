@extends('layouts.app')
@section('content')
<h1 class="text-center">Список всех городов пользователя</h1>
@include('layouts.alerts')
  <form method="post" enctype="multipart/form-data" action="{{ route('towns.import')}}">
    @csrf
    <input type="file" name="xlsx">
    <input id="import" class="btn btn-success" type="submit" value="Загрузить">
  </form>
<div class="row">
    <div class="col-5">
        <table class="table table-sm table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя</th>
                    <th scope="col">дети</th>
                    <th scope="col" colspan="2">действие</th>
                </tr>
            </thead>
            @foreach ($towns as $town)
            <tr>
                <th scope="row">{{$town->id}}</th>
                <td><a href="{{route('towns.show',[
                    'town'=>$town,
                    'parent_id'=>$town->id,
                ])}}">{{$town->name}}</a></td>
                <td>{{$town->childrens_count}}</td>
                <td><a class="btn btn-warning btn-sm" href="{{route('towns.edit',[
                    'parent_id'=>$town->parent_id,
                    'town'=>$town->id,
                ])}}"><i class="bi bi-pencil-fill"></i></a></td>
                <td>
                    <form method="POST" action="{{route('towns.destroy',[
                    'client'=>$client->id,
                    'town'=>$town->id,
                ])}}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" type="submit" value="удалить"><i
                                class="bi bi-trash3"></i></button>
                    </form>
                </td>
        
            </tr>
            
            @endforeach
        </table>
        <a class="btn btn-primary" href="{{route('towns.create',$client->id)}}">Добавить</a>
    </div>

</div>
@endsection