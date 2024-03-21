@extends('layouts.app')
@section('content')
    <div class="row justify-content-center">
        <h1 class="text-center">Покупатели</h1>
        <div class="col-md-8">
            @isset($clients)
            <table class="table">
                <thead class="thead-dark">
                    <tr class="bg-primary">
                        <th scope="col">Имя</th>
                        <th scope="col">Распознаватель пользователя</th>
                        <th scope="col">Емаил</th>
                        <th scope="col">Тел</th>
                        <th scope="col">действие</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)                    
                    <tr>
                        <td>@if($client->user_id==$userId) <i class="bi bi-person-circle text-danger"></i> @endif{{$client->name}}</td>
                        <td>{{$client->recognize}}</td>
                        <td>{{$client->email}}</td>
                        <td>{{$client->phone_personal}}</td>
                        <td><a class="btn btn-primary btn-sm" href="{{route('clients.show',$client)}}" role="button"><i class="bi bi-eye-fill"></i> Открыть</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endisset
            <a class="btn btn-primary btn-sm" href="{{route('clients.create')}}">Создать Клиента</a>
        </div>
    </div>
@endsection