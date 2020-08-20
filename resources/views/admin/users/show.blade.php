@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Пользователи'],
        ['', 'Список пользователей'],
    ]
])

@section('title', 'Список пользователей')
@section('title-icon', 'fa fa-users')

@php
 /* @var \App\Models\User $user */
@endphp

@section('content')

    <div class="row">
        <div class="col-7">
            <div class="card">
                <div class="card-header">
                    Пользователь {{$user->playername}}
                </div>
                <div class="card-body">
                    <table class="table table-striped align-middle">
                        <tr>
                            <td>ID:</td>
                            <td colspan="2">{{$user->id}}</td>
                        </tr>
                        <tr>
                            <td>Ник:</td>
                            <td colspan="2">{{$user->playername}}</td>
                        </tr>
                        <tr>
                            <td>Почта:</td>
                            @if($user->hasEmail())
                                <td>{{$user->email}}</td>
                                <td>
                                    <form action="{{route('admin.users.remove.email', $user)}}" method="post" onsubmit="return confirm('Удалить почту?')">
                                        @csrf

                                        <button type="submit" class="btn btn-warning btn-sm"><i class="fa fa-trash"></i> Удалить</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2">
                                    <span class="text-danger"><i class="fa fa-times"></i> Отключена</span>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>Двухфакторная авторизация:</td>
                            @if($user->has2fa())
                                <td>
                                    <span class="text-success"><i class="fa fa-check"></i> Включена</span>
                                </td>
                                <td>
                                    <form action="{{route('admin.users.remove.2fa', $user)}}" method="post" onsubmit="return confirm('Удалить двухфакторную авторизацию?')">
                                        @csrf

                                        <button class="btn btn-warning btn-sm"><i class="fa fa-trash"></i> Отключить</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2">
                                    <span class="text-danger"><i class="fa fa-times"></i> Отключена</span>
                                </td>
                            @endif
                        </tr>
                        <tr>
                            <td>Группа:</td>
                            <td>{{$user->getPrimaryGroup()->getGroup()->getScreenName()}}</td>
                            <td>до: {{$userGroup->getExpiry() == 0 ? 'навсегда' : date('d.m.Y H:i', $userGroup->getExpiry())}}</td>
                        </tr>
                        <tr>
                            <td>Дата регистрации:</td>
                            <td colspan="2">{{$user->regdate->format('d.m.Y H:i')}}</td>
                        </tr>
                        <tr>
                            <td>IP регистрации:</td>
                            <td colspan="2">{{$user->ip_address}}</td>
                        </tr>
                        <tr>
                            <td>Дата последнего входа:</td>
                            <td colspan="2">{{$user->lastlogin->format('d.m.Y H:i')}}</td>
                        </tr>
                        <tr>
                            <td>IP последнего входа:</td>
                            <td colspan="2">{{$user->last_ip}}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card">
                <div class="card-header">Баланс монет</div>
                <div class="card-body">
                    <form action="{{route('admin.users.set.coins', $user)}}" method="post" onsubmit="return confirm('Изменить баланс монет?')">
                        @csrf

                        <div class="form-group">
                            <label>Количество</label>
                            <input type="number" class="form-control" name="amount" value="{{$user->coins->coins}}">
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Сохранить</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">Выдача группы</div>
                <div class="card-body">
                    <form action="{{route('admin.users.set.group', $user)}}" method="post" onsubmit="return confirm('Изменить группу?')">
                        @csrf

                        <div class="form-group">
                            <label>Группа</label>
                            <select name="group" class="form-control" required>
                                @foreach($groups as $group)
                                    <option value="{{$group->getName()}}">{{$group->getScreenName()}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Срок окончания</label>
                            <input type="date" class="form-control" name="date">
                            <code>Если не выбрать, то будет навсегда</code>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Выдать группу</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
