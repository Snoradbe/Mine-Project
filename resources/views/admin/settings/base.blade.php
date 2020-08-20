@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Базовые настройки сайта'],
    ]
])

@section('title', 'Базовые настройки сайта')
@section('title-icon', 'fa fa-cog')

@section('content')

    <div class="card">
        <div class="card-header">
            Базовая информация
        </div>
        <div class="card-body">

            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" title="Общие настройки сайта" data-toggle="tooltip">
                    <a class="nav-link active" data-toggle="tab" href="#common"><i class="fa fa-cog"></i> Общие</a>
                </li>
            </ul>

            <div class="tab-content tabcontent-border">
                <div class="tab-pane fade active show" id="common" role="tabpanel">
                    <div class="p-t-15">

                        <table class="table table-striped">
                            <tr>
                                <td>Название проекта:</td>
                                <td>
                                    <input type="text" class="form-control form-control-sm " name="cms[project_name]" value="">
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection
