@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['', 'Покупки'],
    ]
])

@section('title', 'Покупки')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список покупок
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Игрок</th>
                    <th>Сумма</th>
                    <th>Валюта</th>
                    <th>Дата</th>
                </tr>
                @foreach($purchases as $purchase)
                    <tr>
                        <td>{{$purchase->id}}</td>
                        <td>{{$purchase->user->playername}}</td>
                        <td>{{$purchase->cost}}</td>
                        <td>{{$purchase->currency}}</td>
                        <td>{{$purchase->completed_at->format('d.m.Y H:i')}}</td>
                    </tr>
                @endforeach
            </table>
            {{$purchases->render()}}
        </div>
    </div>
@endsection
