@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.promo.index', 'Промо-коды'],
        ['', 'Промо-код #' . $promo->id . ' (' . $promo->code . ')'],
    ]
])

@section('title', 'Промо-код #' . $promo->id . ' (' . $promo->code . ')')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Активации промо-кода
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Игрок</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
                @foreach($activations as $activation)
                    <tr>
                        <td>{{$activation->id}}</td>
                        <td>{{$activation->user->playername}}</td>
                        <td>{{$activation->created_at->format('d.m.Y H:i')}}</td>
                        <td>
                            <form action="{{route('admin.store.promo.user-activation.delete', $activation)}}" method="post" onsubmit="return confirm('Удалить активацию промо-кода пользователем {{$activation->user->playername}}?')">
                                @csrf

                                <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i> Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{$activations->render()}}
        </div>
    </div>
@endsection
