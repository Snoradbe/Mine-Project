@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.discounts.index', 'Скидки'],
        ['', 'Редактирование скидки'],
    ]
])

@section('title', 'Редактирование скидки')
@section('title-icon', 'fa fa-plus')

@section('content')

    <div class="card">
        <div class="card-header">
            Редактирование скидки
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.discounts.update', $discount)}}" method="post" enctype="multipart/form-data">
                @csrf @method('put')

                <div class="form-group">
                    <label>Название</label>
                    <input type="text" class="form-control" name="name" max="255" value="{{old('name', $discount->name)}}" required autofocus>
                </div>

                @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                    @if ($lang != 'ru')
                        <div class="form-group">
                            <label>Название {{strtoupper($lang)}}</label>
                            <input type="text" class="form-control" name="name_{{$lang}}" max="255" value="{{old('name_' . $lang, $discount->getAttribute('name_' . $lang))}}" required>
                        </div>
                    @endif
                @endforeach

                <div class="form-group">
                    <label class="d-block">Скидка</label>
                    <input type="number" class="form-control" name="discount" value="{{old('discount', $discount->discount)}}" min="1" max="99">
                </div>

                <div class="form-group">
                    <label class="d-block">Дата начала</label>
                    <input type="date" class="form-control" name="date_start" value="{{old('date_start', $discount->date_start->format('Y-m-d'))}}">
                </div>

                <div class="form-group">
                    <label class="d-block">Дата окончания</label>
                    <input type="date" class="form-control" name="date_end" value="{{old('date_end', $discount->date_end->format('Y-m-d'))}}">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Сохранить изменения</button>
                </div>
            </form>
        </div>
    </div>

@endsection
