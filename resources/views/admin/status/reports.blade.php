@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Репорты мониторинга'],
    ]
])

@section('title', 'Репорты мониторинга')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Список репортов
        </div>
        <div class="card-body">
            <table class="table table-striped align-middle">
                <tr>
                    <th>ID</th>
                    <th>Игрок</th>
                    <th>Сервер</th>
                    <th>Тип репорта</th>
                    <th>Дата</th>
                </tr>
                @foreach($reports as $report)
                    <tr>
                        <td>{{$report->id}}</td>
                        <td>{{$report->user->playername}}</td>
                        <td>{{$report->servername}}</td>
                        <td>{{__('status.reports.types.' . $report->type)}}</td>
                        <td data-toggle="tooltip" title="{{$report->created_at->format('d.m.Y H:i:s')}}">
                            @if($now - $report->created_at->getTimestamp() < 86400)
                                {{$report->created_at->diffForHumans()}}
                            @else
                                {{$report->created_at->format('d.m.Y H:i:s')}}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
            {{$reports->render()}}
        </div>
    </div>
@endsection
