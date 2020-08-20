@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['', 'Промо-коды'],
    ]
])

@section('title', 'Промо-коды')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список промо-кодов
            <a href="{{route('admin.store.promo.create')}}" class="btn btn-outline-primary btn-sm ml-auto"><i class="fa fa-plus"></i> Добавить</a>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Код</th>
                    <th>Скидка</th>
                    <th style="width: 50px"></th>
                    <th style="width: 50px"></th>
                    <th style="width: 50px"></th>
                </tr>
                @foreach($promos as $promo)
                    <tr>
                        <td>{{$promo->id}}</td>
                        <td>{{$promo->code}}</td>
                        <td>{{$promo->discount}}%</td>
                        <td>
                            <a href="{{route('admin.store.promo.show', $promo)}}" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>
                            <a href="{{route('admin.store.promo.edit', $promo)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                        </td>
                        <td>
                            <form action="{{route('admin.store.promo.destroy', $promo)}}" method="post" onsubmit="return confirm('Удалить промо-код №{{$promo->id}}?')">
                                @csrf @method('delete')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
