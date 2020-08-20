@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Доступ к админ-панели']
    ]
])

@section('title', 'Доступ к админ-панели')
@section('title-icon', 'fa fa-lock')

@section('content')

    <div class="row">
        <div class="col-8">
            <div class="card">
                <div class="card-header">Список админов</div>
                <div class="card-body">
                    <table class="table">
                        @foreach($admins as $admin)
                            <tr>
                                <td>{{$admin}}</td>
                                <td>
                                    <form action="{{route('admin.settings.admins.remove', $admin)}}" method="post">
                                        @csrf

                                        <button type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash-alt"></i> Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-4">
            <div class="card">
                <div class="card-header">Добавление админа</div>
                <div class="card-body">
                    <form action="{{route('admin.settings.admins.add')}}" method="post">
                        @csrf

                        <div class="form-group">
                            <label>Ник</label>
                            <input type="text" class="form-control" name="nickname" required autofocus>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary">Добавить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('bottom')
    <script type="text/javascript">
        function addAdminForm(e) {
            ($('#admins-form').append('<div class="form-group"><label>Ник</label><input type="text" class="form-control" name="admins[]"></div>'))
        }

        $(function() {
            let admins = @json($admins);
            $container = $('#admins-form');
            for(let admin of admins)
            {
                $container.append('<div class="form-group"><label>Ник</label><input type="text" class="form-control" name="admins[]" value="'+admin+'"></div>')
            }
        });
    </script>
@endsection
