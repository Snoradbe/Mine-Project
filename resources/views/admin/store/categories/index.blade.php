@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['', 'Категории'],
    ]
])

@section('title', 'Категории')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список категорий
            <a href="{{route('admin.store.categories.create')}}" class="btn btn-outline-primary btn-sm ml-auto"><i class="fa fa-plus"></i> Добавить</a>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                        @if($lang != 'ru')
                            <th>Название {{strtoupper($lang)}}</th>
                        @endif
                    @endforeach
                    <th class="text-center">Для авторизованных</th>
                    <th class="text-center">Включена</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($categories as $category)
                    <tr>
                        <td>{{$category->id}}</td>
                        <td>{{$category->name}}</td>
                        @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                            @if($lang != 'ru')
                                <td>{{$category->getAttribute('name_' . $lang)}}</td>
                            @endif
                        @endforeach
                        <td class="text-center"><i class="fa {{!$category->need_auth ? 'fa-times-circle text-danger' : 'fa-check-circle text-success'}}"></i></td>
                        <td class="text-center"><i class="fa {{!$category->enabled ? 'fa-times-circle text-danger' : 'fa-check-circle text-success'}}"></i></td>
                        <td>
                            <a href="{{route('admin.store.categories.edit', $category)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Редактировать</a>
                        </td>
                        <td>
                            <form action="{{route('admin.store.categories.destroy', $category)}}" method="post" onsubmit="return confirm('Удалить категорию №{{$category->id}}?')">
                                @csrf @method('delete')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i> Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
