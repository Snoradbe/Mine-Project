@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.discounts.index', 'Скидки'],
        ['', 'Создание скидки'],
    ]
])

@section('title', 'Создание скидки')
@section('title-icon', 'fa fa-plus')

@section('content')

    <div class="card">
        <div class="card-header">
            Создание скидки
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.discounts.store')}}" method="post" enctype="multipart/form-data">
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
                    <label class="d-block">Скидка</label>
                    <input type="number" class="form-control" name="discount" value="{{old('discount', 1)}}" min="1" max="99">
                </div>

                <div class="form-group">
                    <label class="d-block">Дата начала</label>
                    <input type="date" class="form-control" name="date_start" value="{{old('date_start')}}">
                </div>

                <div class="form-group">
                    <label class="d-block">Дата окончания</label>
                    <input type="date" class="form-control" name="date_end" value="{{old('date_end')}}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Создать скидку</button>
                </div>
            </form>
        </div>
    </div>

@endsection
