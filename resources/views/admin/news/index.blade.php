@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Новости']
    ]
])

@section('title', 'Новости')
@section('title-icon', 'fa fa-newspaper')

@section('content')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список новостей
            <a href="{{route('admin.news.create')}}" class="btn btn-outline-primary btn-sm ml-auto"><i class="fa fa-plus"></i> Добавить</a>
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Заголовок</th>
                    <th>Дата публикации</th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($news as $article)
                    <tr>
                        <td>{{$article->id}}</td>
                        <td>{{$article->title}}</td>
                        <td>{{$article->created_at->format('d.m.Y H:i')}}</td>
                        <td>
                            <a href="{{route('admin.news.edit', $article)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Редактировать</a>
                        </td>
                        <td>
                            <form action="{{route('admin.news.destroy', $article)}}" method="post" onsubmit="return confirm('Удалить новость №{{$article->id}}?')">
                                @csrf @method('delete')

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i> Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{$news->render()}}
        </div>
    </div>

@endsection
