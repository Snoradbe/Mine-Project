@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.promo.index', 'Промо-коды'],
        ['', 'Редактирование промо-кода #' . $promo->id],
    ]
])

@section('title', 'Редактирование промо-кода')
@section('title-icon', 'fa fa-edit')

@section('content')

    <div class="card">
        <div class="card-header">
            Редактирование промо-кода
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.promo.update', $promo)}}" method="post">
                @csrf @method('put')

                <div class="form-group">
                    <label>Код</label>
                    <input type="text" class="form-control" name="code" value="{{old('code', $promo->code)}}" autofocus>
                </div>

                <div class="form-group">
                    <label class="d-block">Скидка</label>
                    <input type="number" class="form-control" name="discount" value="{{old('discount', $promo->discount)}}" min="1" max="99">
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Сохранить</button>
                </div>
            </form>
        </div>
    </div>

@endsection
