@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.discounts.index', 'Скидки'],
        ['', 'Скидка #' . $discount->id]
    ]
])

@section('title', 'Скидка')
@section('title-icon', 'fa fa-list')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            Скидка на все | категорию | сервер
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.discounts.set-discount', $discount)}}" method="post">
                @csrf
                <input type="hidden" name="type" value="1">

                <div class="form-group">
                    <select name="category" class="form-control">
                        <option value="">--- Выберите категорию ---</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <select name="server" class="form-control">
                        <option value="">--- Выберите сервер ---</option>
                        @foreach($servers as $server)
                            <option>{{$server->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Выдать скидку</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            Скидка на товар
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.discounts.set-discount', $discount)}}" method="post">
                @csrf
                <input type="hidden" name="type" value="2">

                <div class="form-group">
                    <label>Выберите товары</label>
                    <div class="" style="height: 500px; overflow-y: scroll">
                        <table class="table table-sm">
                            @foreach($products as $product)
                                <tr>
                                    <td><input type="checkbox" name="products[]" value="{{$product->id}}"></td>
                                    <td><img src="{{asset('img/store/products/' . ($product->img ?: 'default.png'))}}" width="32" alt="mine Product"></td>
                                    <td>{{$product->name}} (#{{$product->id}})</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Выдать скидку на выбранные товары</button>
                </div>
            </form>
        </div>
    </div>
@endsection
