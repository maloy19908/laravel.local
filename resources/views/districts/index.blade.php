@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('layouts.alerts')
            @if(count($districts))
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col">Имя</th>
                        <th scope="col">Изменить</th>
                        <th scope="col">удалить</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($districts as $district)
                    <tr>
                        <th scope="row">{{$district->id}}</th>
                        <td>{{$district->name}}</td>
                        <td><a class="btn btn-warning" href="{{route('districts.edit',$district)}}">Изменить</a></td>
                        <td><form method="POST" action="{{route('districts.destroy',$district)}}">
                            @csrf
                            @method('DELETE')
                            <input class="btn btn-danger" type="submit" value="удалить">
                        </form></td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                @else
                Нет ничего
                @endif
                <a class="btn btn-primary" href="{{route('districts.create')}}">Добавить</a>
        </div>
    </div>
</div>
@endsection
