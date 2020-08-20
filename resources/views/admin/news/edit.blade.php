@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['admin.news.index', 'Новости'],
        ['', 'Редактирование новости #' . $article->id . ' (' . \App\Helpers\Str::limit($article->title, 10) . ')'],
    ]
])

@section('title', 'Редактирование новости')
@section('title-icon', 'fa fa-edit')

@section('content')

    <div class="card">
        <div class="card-header">
            Создание новости
        </div>
        <div class="card-body">
            <form action="{{route('admin.news.update', $article)}}" method="post" enctype="multipart/form-data">
                @csrf @method('put')

                <div class="form-group">
                    <label>Заголовок</label>
                    <input type="text" class="form-control" name="title" max="255" value="{{old('title', $article->title)}}" required autofocus>
                </div>

                <div class="form-group">
                    <label>Краткий текст</label>
                    <textarea class="form-control js-ckeditor" name="short_content" cols="30" rows="3" maxlength="255">{{old('short_content', $article->short_content)}}</textarea>
                </div>

                <div class="form-group">
                    <label>Полный текст</label>
                    <textarea class="form-control js-ckeditor" name="content" cols="30" rows="10" maxlength="65535" required>{{old('content', $article->content)}}</textarea>
                </div>

                <div class="form-group">
                    <label>Картинка</label>
                    <input type="file" class="form-control" name="image">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>

@endsection
@section('head')
    <style>
        .ck-editor__editable {
            min-height: 200px;
        }
    </style>
@endsection

@section('bottom')
    <script type="text/javascript" src="{{asset('vendor/ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('vendor/ckeditor/translations/ru.js')}}"></script>
    <script>
        let allEditors = document.querySelectorAll('.js-ckeditor');
        for (let editor of allEditors)
        {
            ClassicEditor
                .create( editor, {
                    language: 'ru'
                } )
                .catch( error => {
                    console.error( error );
                } );
        }
    </script>
@endsection
