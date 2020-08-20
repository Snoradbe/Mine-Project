@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Пользователи'],
        ['', 'Список пользователей'],
    ]
])

@section('title', 'Список пользователей')
@section('title-icon', 'fa fa-users')

@section('content')

    <div class="card">
        <div class="card-header">
            Список пользователей
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <tr>
                    <th>ID</th>
                    <th>Ник</th>
                    <th>Почта</th>
                    <th>Дата регистрации</th>
                    <th>Дата входа</th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>{{$user->id}}</td>
                        <td><a href="{{route('admin.users.show', $user)}}">{{$user->playername}}</a></td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->regdate->format('d.m.Y H:i')}}</td>
                        <td>{{$user->lastlogin->format('d.m.Y H:i')}}</td>
                    </tr>
                @endforeach
            </table>
            {{$users->render()}}
        </div>
    </div>

@endsection
