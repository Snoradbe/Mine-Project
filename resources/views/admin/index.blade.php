@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Главная страница панели управления'],
    ]
])

@section('title', 'Главная')
@section('title-icon', 'fa fa-home')

@section('content')

    <div class="card">
        <div class="card-header">
            Базовая информация
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td>Режим работы сайта:</td>
                    <td>
                        @if($enabled)
                            <span class="text-success">Включен</span>
                        @else
                            <span class="text-danger">Отключен</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Зарегистрировано пользователей:</td>
                    <td>{{$countUsers}}</td>
                </tr>
                <tr>
                    <td>Размер свободного места на диске:</td>
                    <td>{{$diskSpace}}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer text-right">
            <form action="{{route('admin.cache.config.clear')}}" method="post">
                @csrf

                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i> Очистить кэш конфига</button>
            </form>
        </div>
    </div>

@endsection
