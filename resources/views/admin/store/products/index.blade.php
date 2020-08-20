@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['', 'Товары']
    ]
])

@section('title', 'Товары')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список товаров
            <a href="{{route('admin.store.products.create')}}" class="btn btn-outline-primary btn-sm ml-auto"><i class="fa fa-plus"></i> Добавить</a>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Категория</th>
                    <th>Сервер</th>
                    <th>Название</th>
                    @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                        @if($lang != 'ru')
                            <th>Название {{strtoupper($lang)}}</th>
                        @endif
                    @endforeach
                    <th class="text-center">Включен</th>
                    <th>Количество покупок</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($products as $product)
                    <tr>
                        <td>{{$product->id}}</td>
                        <td>{{$product->category->name}}</td>
                        <td>{{$product->servername}}</td>
                        <td>{{$product->name}}</td>
                        @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                            @if($lang != 'ru')
                                <td>{{$product->getAttribute('name_' . $lang)}}</td>
                            @endif
                        @endforeach
                        <td class="text-center"><i class="fa {{!$product->enabled ? 'fa-times-circle text-danger' : 'fa-check-circle text-success'}}"></i></td>
                        <td>{{$product->count_buys}}</td>
                        <td>
                            <a href="{{route('admin.store.products.edit', $product)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                        </td>
                        <td>
                            <form action="{{route('admin.store.products.destroy', $product)}}" method="post" onsubmit="return confirm('Удалить товар №{{$product->id}}?')">
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
