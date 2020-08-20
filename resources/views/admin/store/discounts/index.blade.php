@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['', 'Скидки'],
    ]
])

@section('title', 'Скидки')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список скидок
            <div class="">
                <a href="{{route('admin.store.discounts.create')}}" class="btn btn-outline-primary btn-sm ml-auto"><i class="fa fa-plus"></i> Добавить</a>
                <form action="{{route('admin.store.discounts.remove-discounts')}}" class="d-inline-block" method="post" onsubmit="return confirm('Вы уверены что хотите удалить все скидки со всех товаров?')">
                    @csrf

                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Убрать скидки со всех товаров</button>
                </form>
            </div>
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
                    <th>Скидка</th>
                    <th>С</th>
                    <th>До</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                @foreach($discounts as $discount)
                    <tr>
                        <td>{{$discount->id}}</td>
                        <td>{{$discount->name}}</td>
                        @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                            @if($lang != 'ru')
                                <td>{{$discount->getAttribute('name_' . $lang)}}</td>
                            @endif
                        @endforeach
                        <td>{{$discount->discount}}%</td>
                        <td>{{$discount->date_start->format('d.m.Y H:i')}}</td>
                        <td>{{$discount->date_end->format('d.m.Y H:i')}}</td>
                        <td>
                            <a href="{{route('admin.store.discounts.show', $discount)}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>
                            <a href="{{route('admin.store.discounts.edit', $discount)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>
                        </td>
                        <td>
                            <form action="{{route('admin.store.discounts.destroy', $discount)}}" method="post" onsubmit="return confirm('Удалить скидку №{{$discount->id}}?')">
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
