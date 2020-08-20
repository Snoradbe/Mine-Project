<!doctype html>
<html lang="{{ str_replace('_', '-', \App\Lang::locale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link rel="shortcut icon" href="{{asset('img/favicon.ico')}}" type="image/x-icon">
    <title>{{config('app.name', 'mine')}}@hasSection('title') - @yield('title') @endif</title>

    <!-- Styles -->
    <link href="{{asset('css/app.css')}}?v={{time()}}" rel="stylesheet">
    @yield('head')
</head>
<body>
<div id="app">
    <header class="header">
        <div class="container header__inner">
            <div class="logo">
                <a href="/"><img src="{{asset('img/logo.svg')}}" alt="mine Project" class="logo__img"></a>
                </div>
                <nav class="nav header__nav">
                    <div class="nav__top">
                        <a href="{{route('status.index')}}" class="nav__item">@lang('site.nav.servers_status')</a>
                        <a href="#" class="nav__item">@lang('site.nav.wiki')</a>
                        <a href="{{home_route('home.players-statistics')}}" class="nav__item">@lang('site.nav.players_statistics')</a>
                        <a href="#" class="nav__item">@lang('site.nav.our_staff')</a>
                        <a href="#" class="nav__item">@lang('site.nav.donate')</a>
                        <a href="#" class="nav__item">@lang('site.nav.discord')</a>
                        <a href="#" class="nav__item">@lang('site.nav.bug_tracker')</a>
                    </div>
                    <div class="nav__main">
                        <a href="{{home_route()}}" class="nav__item">@lang('site.nav.home')</a>
                        <a href="{{route('store.home')}}" class="nav__item">@lang('site.nav.store')</a>
                        <a href="#" class="nav__item">@lang('site.nav.support')</a>
                        <a href="#" class="nav__item">@lang('site.nav.our_servers')</a>
                        <a href="#" class="nav__item">@lang('site.nav.rules_and_guidelines')</a>
                    </div>
                </nav>
                <div class="header__user-panel header__user-balance">
                    @auth
                        <div class="header__logged">@lang('site.nav.account.balance')</div>
                        <div class="header__user">
                            <a href="#" class="header__user-link bordered-status cur cur_left cur_coins">{{auth()->user()->coins->coins}}</a>
                        </div>
                    @endif
                </div>
                <div class="header__user-panel">
                    @auth
                        <div class="header__logged">@lang('site.nav.account.logged')</div>
                        <div class="header__user user-dropdown">
                            <a href="#" class="header__user-link bordered-status bordered-status_circle user-dropdown__toggler">{{auth()->user()->playername}}</a>
                            <div class="user-dropdown__menu">
                                @if(auth()->user()->isAdmin())
                                    <a href="{{route('admin.home')}}" class="user-dropdown__link">@lang('site.nav.account.admin') <i class="icon material-icons">verified_user</i></a>
                                @endif
                                <a href="{{route('account.home')}}" class="user-dropdown__link">@lang('site.nav.account.my_account') <i class="icon material-icons">person</i></a>
                                <a href="#" class="user-dropdown__link">@lang('site.nav.account.perks') <i class="icon material-icons">offline_bolt</i></a>
                                <a href="{{route('account.settings')}}" class="user-dropdown__link">@lang('site.nav.account.settings') <i class="icon material-icons">settings</i></a>
                                <div class="user-dropdown__divider"></div>
                                <form method="post" action="{{route('logout')}}">
                                    @csrf

                                    <a href="{{route('logout')}}" class="user-dropdown__link user-dropdown__link_danger" onclick="event.preventDefault(); $(this).parent().submit()">
                                        @lang('site.nav.account.logout') <i class="icon material-icons">exit_to_app</i>
                                    </a>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="header__user">
                            <a href="{{route('login')}}" class="header__user-link bordered-status">@lang('site.nav.account.login')</a>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        @yield('content')

        <footer class="footer">
            <div class="container footer__inner">
                <div class="logo footer__logo">
                    <a href="/"><img src="{{asset('img/logo.svg')}}" alt="mine Project" class="logo__img"></a>
                </div>
                <nav class="nav footer__nav">
                    <div class="nav__main">
                        <a href="#" class="nav__item">@lang('site.nav.our_servers')</a>
                        <a href="#" class="nav__item">@lang('site.nav.rules_and_guidelines')</a>
                        <a href="#" class="nav__item">@lang('site.nav.for_developers')</a>
                        <a href="#" class="nav__item">@lang('site.nav.recruitment')</a>
                    </div>
                    <div class="nav__bottom">
                        <a href="{{'https://status.' . config('site.domain')}}" class="nav__item">@lang('site.nav.servers_status')</a>
                        <a href="#" class="nav__item">@lang('site.nav.branding')</a>
                        <a href="#" class="nav__item">@lang('site.nav.other_projects')</a>
                        <a href="#" class="nav__item">@lang('site.nav.faq')</a>
                        <a href="#" class="nav__item">@lang('site.nav.technical_support')</a>
                        <a href="#" class="nav__item">@lang('site.nav.meet_the_team')</a>
                    </div>
                </nav>
            </div>
        </footer>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" type="text/javascript"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        function loading(status) {
            if (status) {
                $('body').addClass('body-overflow').append('<div class="pre-loader"><div class="pre-loader__loading"></div></div>')
            } else {
                $('body').removeClass('body-overflow');
                $('.pre-loader').remove()
            }
        }

        $(function () {
            $(document).on('click', function (event) {
                let $element = $('.user-dropdown__menu');
                if (!$element.is(':hidden')) {
                    $element.fadeOut(200)
                }
            });

            $('.user-dropdown__toggler').on('click', function(event) {
                event.preventDefault();
                event.stopPropagation();
                let $element = $(this).next('.user-dropdown__menu');
                if ($element.is(':hidden')) {
                    $element.fadeIn(200)
                } else {
                    $element.fadeOut(200)
                }
            });

            $('[data-modal]').on('click', function(event) {
                event.preventDefault();
                $($(this).data('modal')).modal('show')
            });

            $('.alert .close').on('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                $(this).parent().fadeOut(100)
            });

            $('.img-loading').on('load', function (event) {
                $(this).css('background', 'none');
            });

			$('[data-tab]').on('click', function (event) {
				let $this = $(this);
				$('.js-tabs > div').css('display', 'none');
				$($this.data('tab')).fadeIn(200)
				$('[data-tab]').removeClass('active');
				$this.addClass('active');
			});
        });

		$('[data-tooltip]').tooltip({
			classes: {
				"ui-tooltip": "ui-tooltip_triangle-left",
			},
			position: { my: "left+10 center", at: "right center" },
			content: function() {
				return $(this).prop('title')
			}
		});

        (function($) {
            $.fn.modal = function(type) {

                function show($modal) {
                    $('body').addClass('body-overflow');
                    $modal.fadeIn(300);
                    $modal.find('[data-close]').on('click', function (event) {
                        event.preventDefault();
                        hide($modal)
                    })
                }

                function hide($modal) {
                    $modal.fadeOut(200, () => {
                        $('body').removeClass('body-overflow')
                    })
                }

                show($(this));

                return this;
            };
        })(jQuery);
    </script>
    @yield('bottom')
</body>
</html>
