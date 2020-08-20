@extends('layouts.admin', [
    'breadcrumbs' => [
        ['admin.home', 'Главная'],
        ['', 'Магазин'],
        ['admin.store.products.index', 'Товары'],
        ['', 'Создание товара'],
    ]
])

@section('title', 'Создание товара')
@section('title-icon', 'fa fa-plus')

@section('content')

    <div class="card">
        <div class="card-header">
            Создание товара
        </div>
        <div class="card-body">
            <form action="{{route('admin.store.products.store')}}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label>Название</label>
                    <input type="text" class="form-control" name="name" max="255" value="{{old('name')}}" required autofocus>
                </div>

                @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                    @if ($lang != 'ru')
                        <div class="form-group">
                            <label>Название {{strtoupper($lang)}}</label>
                            <input type="text" class="form-control" name="name_{{$lang}}" max="255" value="{{old('name_' . $lang)}}" required>
                        </div>
                    @endif
                @endforeach

                <div class="form-group">
                    <label>Описание</label>
                    <textarea class="form-control" name="desc" rows="5">{{old('desc')}}</textarea>
                </div>

                @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                    @if ($lang != 'ru')
                        <div class="form-group">
                            <label>Описание {{strtoupper($lang)}}</label>
							<textarea class="form-control" name="desc_{{$lang}}" rows="5">{{old('desc_' . $lang)}}</textarea>
                        </div>
                    @endif
                @endforeach

                <div class="form-group">
                    <label class="d-block">Категория</label>
                    <select name="category" class="form-control" id="selectCategory">
                        <option value="">Выберите категорию</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}" @if(old('category') == $category->id) selected @endif>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="d-block">Сервер</label>
                    <select name="server" class="form-control">
                        <option value="">--- Все сервера ---</option>
                        @foreach($servers as $server)
                            <option @if(old('server') == $server->name) selected @endif>{{$server->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label id="productDataTitle">Data</label>
                    <input type="text" class="form-control" name="data" value="{{old('data')}}">
                </div>

                <div class="form-group">
                    <label>Цена в рублях</label>
                    <input type="number" class="form-control" name="price_rub" min="0" value="{{old('price_rub', 0)}}">
                    <code>0 - отключено</code>
                </div>

                <div class="form-group">
                    <label>Цена в монетах</label>
                    <input type="number" class="form-control" name="price_coins" min="0" value="{{old('price_coins', 0)}}">
                    <code>0 - отключено</code>
                </div>

                <div class="form-group">
                    <label>Количество</label>
                    <input type="number" class="form-control" name="amount" min="1" value="{{old('amount', 1)}}">
                </div>

                <div class="form-group">
                    <label class="d-block">Включен?</label>
                    <input type="checkbox" name="enabled" @if(old('enabled', false)) checked @endif>
                </div>

                <div class="form-group">
                    <label class="d-block">Изображение</label>
                    <input type="file" class="d-block" name="image">
                </div>

                <div class="form-group">
                    <label class="d-block">Список дополнительных товаров в формате id:количество (через запятую)</label>
                    <input type="text" class="form-control" name="additionals" value="{{old('additionals')}}">
                    <code>Необязательно</code>
                </div>

                <div class="form-group">
                    <label class="d-block">Скидка</label>
                    <select name="server" class="form-control">
                        <option value="">--- Без скидки ---</option>
                        @foreach($discounts as $discount)
                            <option value="{{$discount->id}}" @if(old('discount') == $discount->id) selected @endif>{{$discount->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-plus"></i> Создать товар</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('bottom')
    <script type="text/javascript">
        $(function () {
            function productDataTitle(category) {
                let $productDataTitle = $('#productDataTitle')
                if (!category) {
                    $productDataTitle.html('Data')
                } else {
                    let title = ''
                    switch (category) {
                        case '1':
                            title = 'Игровой ID предмета'
                            break;
                        case '2':
                            title = 'Название группы'
                            break;
                        case '3':
                            title = 'ID кейса'
                            break;
                        case '4':
                        default:
                            title = 'Data'
                    }
                    $productDataTitle.html(title)
                }
            }

            $('#selectCategory').on('change', function (event) {
                productDataTitle(event.target.value)
            })
        })
    </script>
@endsection
