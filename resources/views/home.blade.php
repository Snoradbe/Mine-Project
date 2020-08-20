@extends('layouts.app')

@section('title', __('titles.home'))

@section('head')
    <link rel="stylesheet" href="{{asset('css/slick.css')}}">
@endsection

@section('content')
    <section class="section-about space-header">
        <div class="container section-about__inner">
            <h1 class="section-about__title">
                @lang('home-page.top.title_1')
                <br>
                <span class="section-about__title_white">@lang('home-page.top.title_2')</span>
            </h1>
            <div class="section-about__desc">
                @foreach(__('home-page.top.subtitles') as $subtitle)
                    <p>{{$subtitle}}</p>
                @endforeach
            </div>
            <div class="about-stats section-about__stats">
                <div class="about-stats__item">
                    <div class="about-stats__count">{{$countAdmins}}</div>
                    <div class="about-stats__title">@lang('home-page.top.stats.staff_members', ['members' => \App\Helpers\Str::declensionNumber(intval($countAdmins), ...__('words.members'))])</div>
                </div>
                <div class="about-stats__item">
                    <div class="about-stats__count">{{\App\Helpers\Number::readable($countPlayers, 0)}}</div>
                    <div class="about-stats__title">@lang('home-page.top.stats.registered_players', ['players' => \App\Helpers\Str::declensionNumber(intval($countPlayers), ...__('words.players'))])</div>
                </div>
                <div class="about-stats__item">
                    <div class="about-stats__count">{{\App\Helpers\Number::readable($totalOnline, 0)}}</div>
                    <div class="about-stats__title">@lang('home-page.top.stats.hours_played', ['hours' => \App\Helpers\Str::declensionNumber(intval($totalOnline), ...__('words.time.hours'))])</div>
                </div>
            </div>
            <div class="section-about__scroll">
                {{--<i class="icon material-icons section-about__mouse">mouse</i> @lang('home-page.top.scroll')--}}
            </div>
        </div>
    </section>

    @if(!$news->isEmpty())
    <div class="section-content">
        <div class="container section-content__inner">

            <div class="news" id="newsSlider">
                @foreach($news as $article)
                    <div class="news__item">
                        <div class="news__picture">
                            <img src="{{asset('uploads/news/' . $article->image)}}" alt="mine {!! $article->title !!}" class="news__img img-loading">
                        </div>
                        <article class="news__content">
                            <div class="news__header">
                                <h1 class="news__title">{!! $article->title !!}</h1>
                            </div>
                            <div class="news__body">
                                {!! $article->short_content !!}
                            </div>
                            <div class="news__footer">
                                <div class="news__date">
                                    <i class="fa fa-clock"></i> {{$article->created_at->format('d.m.Y H:i')}}
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <section class="how-start section-content__section-start">
                <div class="how-start__item how-start__desc">
                    <h1 class="how-start__title">@lang('home-page.start.title')</h1>
                    <div class="how-start__text">@lang('home-page.start.subtitle')</div>
                </div>
                <div class="start-info how-start__item">
                    <img src="{{asset('img/mc_logo.svg')}}" alt="mine Project" class="icon start-info__icon">
                    <h4 class="start-info__title">@lang('home-page.start.cards.join.title')</h4>
                    <h5 class="start-info__subtitle">@lang('home-page.start.cards.join.subtitle')</h5>
                    <button class="btn btn_warning start-info__btn">@lang('home-page.start.cards.join.button')</button>
                </div>
                <div class="start-info how-start__item">
                    <i class="icon material-icons start-info__icon">bookmark</i>
                    <h4 class="start-info__title">@lang('home-page.start.cards.read.title')</h4>
                    <h5 class="start-info__subtitle">@lang('home-page.start.cards.read.subtitle')</h5>
                    <a href="#" class="btn btn_warning-outline start-info__btn">@lang('home-page.start.cards.read.button')</a>
                </div>
            </section>

            <div class="monitoring section-content__section-monitoring">

                @foreach($servers as $server)
                    <div class="monitoring-server monitoring__item">
                        <h2 class="monitoring-server__name">{{$server->name}}</h2>
                        <div class="monitoring-server__info">
                            <div class="monitoring-server__status">@lang('home-page.servers.status.' . ($server->isOnline() ? 'online' : 'offline'))</div>
                            @if($server->isOnline())
                                <div class="monitoring-server__online">{{$server->players}}/{{$server->slots}}</div>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>

        </div>
    </div>
    @endif

    @guest
        <section class="section-manage">
            <div class="container section-manage__inner">
                <div class="section-manage__titles">
                    <h1 class="section-manage__title">@lang('home-page.footer.title')</h1>
                    <h3 class="section-manage__subtitle">@lang('home-page.footer.subtitle')</h3>
                </div>
                <div class="section-manage__info">
                    <div class="section-manage__text">@lang('home-page.footer.text')</div>
                    <div class="section-manage__buttons">
                        <a href="{{route('login')}}" class="btn btn_warning">@lang('home-page.footer.buttons.login')</a>
                        <a href="#" class="btn btn_warning-outline">@lang('home-page.footer.buttons.support')</a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection

@section('bottom')
    <script src="{{asset('js/slick.min.js')}}" type="text/javascript"></script>
    <script type="text/javascript">
        let slider = $("#newsSlider");

        slider.slick({
            infinite: false,
            slidesToShow: 1,
            slidesToScroll: 1,
            fade: true,
            arrows: true,
            loader: true,
            dots: false
        });
    </script>
@endsection
