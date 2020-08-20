<!doctype html>
<html lang="{{ str_replace('_', '-', \App\Lang::locale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>{{ config('app.name', 'mine') }}@hasSection('title') - @yield('title') @endif</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('head')
</head>
<body>
<div id="app">
    <section class="page-window @yield('window-class') page-window_bg-{{rand(1, 5)}}">
        <div class="page-window__container">

            @if(session()->has('success_message'))
                <div class="alert alert_success">
                    <div class="alert__text">
                        <i class="icon material-icons text-white alert__icon">check_circle</i>
                        {{ session()->get('success_message') }}
                    </div>
                    <a href="#" class="close close_light">×</a>
                </div>
            @endif

            @if (isset($errors) && count($errors) > 0)
                @foreach ($errors->all() as $error)
                    <div class="alert alert_danger">
                        <div class="alert__text">
                            <i class="icon material-icons text-white alert__icon">warning</i>
                            {{ $error  }}
                        </div>
                        <a href="#" class="close close_light">×</a>
                    </div>
                @endforeach
            @endif

            <div class="page-window__inner">
                @yield('content')
            </div>
        </div>
    </section>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<script>
    $('.close').on('click', function (event) {
        event.preventDefault();
        $(this).parent().fadeOut(100)
    });
	$('img').on('load', function (event) {
		$(this).css('background', 'none');
	});
</script>
@yield('bottom')
</body>
</html>
