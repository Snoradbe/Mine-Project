<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ПУ - @yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/css/all.min.css">
    <link rel="stylesheet" href="{{asset('css/admin/app.css')}}">
    <style>
        table.align-middle th, table.align-middle td {
            vertical-align: middle;
        }
    </style>
    @yield('head')
</head>
<body>

<header class="header">
    <div class="header__brand"><a href="{{route('admin.home')}}" class="header-nav__link">Панель Управления</a></div>
    <nav class="header-nav header__nav">
        <div class="header-nav__item">
            <a href="{{home_route('home')}}" class="header-nav__link" title="Просмотр сайта"><i class="fa fa-globe"></i></a>
        </div>
    </nav>
</header>

<main class="main">
    <aside class="sidebar main__side">
        <div class="sidebar__items main__side-items">
            <div class="sidebar__item">
                <a href="{{route('admin.home')}}" class="sidebar__link"><i class="fa fa-globe sidebar__icon"></i> Главная</a>
            </div>
            <div class="sidebar__item">
                <a href="{{route('admin.news.index')}}" class="sidebar__link"><i class="fa fa-newspaper sidebar__icon"></i> Новости</a>
            </div>
            <div class="sidebar__item sidebar-dropdown">
                <a href="#" class="sidebar__link sidebar-dropdown__toggle"><i class="fa fa-wrench sidebar__icon"></i> Настройки</a>
                <div class="sidebar-dropdown__menu">
                    <a href="{{route('admin.settings.admins')}}" class="sidebar__link sidebar-dropdown__item">Доступ к админке</a>
                    {{--<a href="{{route('admin.settings.base')}}" class="sidebar__link sidebar-dropdown__item">Настройки системы</a>--}}
                </div>
            </div>
            <div class="sidebar__item sidebar-dropdown">
                <a href="#" class="sidebar__link sidebar-dropdown__toggle"><i class="fa fa-users sidebar__icon"></i> Пользователи</a>
                <div class="sidebar-dropdown__menu">
                    <a href="{{route('admin.users.index')}}" class="sidebar__link sidebar-dropdown__item">Список пользователей</a>
                </div>
            </div>
            <div class="sidebar__item sidebar-dropdown">
                <a href="#" class="sidebar__link sidebar-dropdown__toggle"><i class="fa fa-store sidebar__icon"></i> Магазин</a>
                <div class="sidebar-dropdown__menu">
                    <a href="{{route('admin.store.categories.index')}}" class="sidebar__link sidebar-dropdown__item">Категории</a>
                    <a href="{{route('admin.store.products.index')}}" class="sidebar__link sidebar-dropdown__item">Товары</a>
                    <a href="{{route('admin.store.discounts.index')}}" class="sidebar__link sidebar-dropdown__item">Скидки</a>
                    <a href="{{route('admin.store.promo.index')}}" class="sidebar__link sidebar-dropdown__item">Промо-коды</a>
                    <a href="{{route('admin.store.purchases.index')}}" class="sidebar__link sidebar-dropdown__item">Покупки</a>
                </div>
            </div>
            <div class="sidebar__item">
                <a href="{{route('admin.status.reports.index')}}" class="sidebar__link"><i class="fa fa-tv sidebar__icon"></i> Репорты мониторинга</a>
            </div>
            <div class="sidebar__item">
                <a href="{{route('admin.servers-sorting.index')}}" class="sidebar__link"><i class="fa fa-sort-numeric-down-alt sidebar__icon"></i> Сортировка серверов</a>
            </div>
        </div>
    </aside>
    <div class="content main__content">
        <div class="content__header">
            <h3 class="content__title"><i class="@yield('title-icon')"></i> @yield('title')</h3>
        </div>
        @isset($breadcrumbs)
            <div class="breadcrumbs">
                <div class="breadcrumbs__items">
                    @foreach($breadcrumbs as $breadcrumb)
                        <a @empty($breadcrumb[0]) @else href="{{route($breadcrumb[0])}}" @endif class="breadcrumbs__item">{{$breadcrumb[1]}}</a>
                    @endforeach
                </div>
            </div>
        @endif
        <div class="content__body">

            @include('admin.partials.result')

            @yield('content')

        </div>
    </div>
</main>


<script src="{{asset('js/admin/app.js')}}"></script>
<script>
    $(function() {
        $('.sidebar-dropdown > a').on('click', function(event) {
            event.preventDefault();
            let $element = $(this).next('.sidebar-dropdown__menu').first();
            if ($element.is(':hidden')) {
                $element.fadeIn(200)
            } else {
                $element.fadeOut(200)
            }

            $(this).parent().toggleClass('sidebar-dropdown_active');
        });

    });
</script>
@yield('bottom')

</body>
</html>
