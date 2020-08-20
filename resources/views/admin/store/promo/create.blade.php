@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.promo.index', 'Промо-коды'],
        ['', 'Создание промо-кода'],
    ]
])

@section('title', 'Создание промо-кода')
@section('title-icon', 'fa fa-plus')

@section('content')

    <div class="card">
        <div class="card-header">
            Создание промо-кода
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.promo.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Код</label>
                    <input type="text" class="form-control" name="code" max="16" value="{{old('code')}}" autofocus>
                    <code>Если пусто, то будет сгенерирован автоматически</code>
                </div>

                <div class="form-group">
                    <label class="d-block">Скидка</label>
                    <input type="number" class="form-control" name="discount" value="{{old('discount', 1)}}" min="1" max="99">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Создать промо-код</button>
                </div>
            </form>
        </div>
    </div>

@endsection
