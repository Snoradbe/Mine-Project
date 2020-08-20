<!doctype html>
<html lang="{{ str_replace('_', '-', \App\Lang::locale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>mine - @lang('titles.offline')</title>

    <link rel="stylesheet" href="https://mine.org/fonts/unisans/stylesheet.css">
    <link rel="stylesheet" href="https://mine.org/fonts/whitney/stylesheet.css">
    <style>
        * {
            -webkit-user-select: none;
            -moz-user-select: none;
            -webkit-user-drag: none;
            -moz-user-drag: none;
        }
        body {
            background: #f6f6f7;
            margin: 0;
            padding: 0;
        }

        #page {
            min-height: 500px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            margin: 100px 0 10px 0;
        }

        .logosvg {
            width: 160px;
        }

        .logowrap {
            position: absolute;
            left: 0;
            bottom: 15px;
            text-align: center;
            width: 100%;
        }

        .text-section {
            font-family: 'Uni Sans Heavy CAPS';
            font-weight: 900;
            font-style: normal;
            font-size: 36px;
            text-align: center;
            margin: 0;
        }

        .text-section {
            color: #23272A;
            background-image: url(https://cdn.mine.org/interface/txt-backround.gif);
            background-size: cover;
            color: transparent;
            -moz-background-clip: text;
            -webkit-background-clip: text;
            text-transform: uppercase;
        }

        .links i {
            position: center;
            text-decoration: none;
            padding: 0 12px 0 12px;
            color: #23272A;
            background-image: url(https://cdn.mine.org/interface/txt-backround.gif);
            background-size: cover;
            color: transparent;
            -moz-background-clip: text;
            -webkit-background-clip: text;
            text-transform: uppercase;
        }

        .fa-vk:hover {
            color: #45668e;
            transition: 0.3s;
        }

        .fa-discord:hover {
            color:#7289DA;
            transition: 0.3s;
        }
    </style>
</head>
<body>

<div id="page">
    <div class="text-section">
        <div class="animation">
            <h1>Coming soon in 2020</h1>
        </div>
        <div class="links">
            <a href="https://vk.com/mine" target="_blank"><i class="fab fa-vk" aria-hidden="true"></i></a>
            <a href="https://mine.org/discord" target="_blank"><i class="fab fa-discord" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="logowrap">
        <a href="https://www.mine.org">
            <img alt="Главная" class="logosvg" src="https://cdn.mine.org/interface/logo_gray.svg">
        </a>
    </div>

</div>
<link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-shims.min.css" media="all">
<link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free-v4-font-face.min.css"
      media="all">
<link rel="stylesheet" href="https://kit-free.fontawesome.com/releases/latest/css/free.min.css" media="all">

</body>
</html>
