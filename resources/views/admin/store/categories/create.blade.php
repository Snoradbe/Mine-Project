@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.categories.index', 'Категории'],
        ['', 'Создание категории'],
    ]
])

@section('title', 'Создание категории')
@section('title-icon', 'fa fa-plus')

@section('content')

    <div class="card">
        <div class="card-header">
            Создание категории
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.categories.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Название</label>
                    <input type="text" class="form-control" name="name" max="255" value="{{old('name')}}" required autofocus>
                </div>

                @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                    @if ($lang != 'ru')
                        <div class="form-group">
                            <label>Название {{strtoupper($lang)}}</label>
                            <input type="text" class="form-control" name="name_{{$lang}}" max="255" value="{{old('name_' . $lang)}}" required>
                        </div>
                    @endif
                @endforeach

                <div class="form-group">
                    <label class="d-block">Только для авторизованных?</label>
                    <input type="checkbox" name="need_auth" @if(old('need_auth', false)) checked @endif>
                </div>

                <div class="form-group">
                    <label class="d-block">Включена?</label>
                    <input type="checkbox" name="enabled" @if(old('enabled', false)) checked @endif>
                </div>

                <div class="form-group">
                    <label class="d-block">Дистрибьютор</label>
                    <select name="distributor" class="form-control">
                        @foreach($distributors as $distributor)
                            <option @if(old('distributor') == $distributor) selected @endif>{{$distributor}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="d-block">Изображение</label>
                    <input type="file" class="d-block" name="image">
                    <code>Только .svg</code>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Создать категорию</button>
                </div>
            </form>
        </div>
    </div>

@endsection
