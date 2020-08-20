@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Сортировка серверов'],
    ]
])

@section('title', 'Сортировка серверов')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    Список отсортированных серверов
                </div>
                <div class="card-body">
                    <form action="{{route('admin.servers-sorting.save')}}" method="post">
                        @csrf

                        @foreach($sorted as $server => $weight)
                            <div class="form-group">
                                <label>{{$server}}</label>
                                <input type="number" class="form-control" name="servers[{{$server}}]" min="0" value="{{$weight}}">
                            </div>
                        @endforeach
                        <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Сохранить</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    Добавление сервера
                </div>
                <div class="card-body">
                    <form method="post" action="{{route('admin.servers-sorting.add')}}">
                        @csrf

                        <div class="form-group">
                            <label>Название сервера</label>
                            <input type="text" class="form-control" name="server" value="{{old('server')}}">
                        </div>

                        <div class="form-group">
                            <label>Вес</label>
                            <input type="number" class="form-control" name="weight" min="1" value="{{old('weight', 0)}}">
                        </div>

                        <button class="btn btn-success" type="submit"><i class="fa fa-plus"></i> Добавить сервер</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
