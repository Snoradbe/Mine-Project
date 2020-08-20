@extends('layouts.app')

@section('title', __('titles.account.settings'))

@section('content')
    <div class="section-settings space-header">
        <div class="container section-settings__container">
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
            <div class="section-settings__head">
                <div class="card">
                    <div class="card__header account-header">
                        <img src="{{asset($user->getAvatar())}}" alt="" class="account-header__avatar">
                        <h4 class="account-header__nickname">{{$user->playername}}</h4>
                    </div>
                    <div class="card__body">
                        <div class="card-table card-table_space-between">
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h4 class="card__title">ID {{$user->id}}</h4>
                                    <h5 class="card__subtitle">@lang('site.account.settings.id.subtitle')</h5>
                                </div>
                                <div class="card-table__col-2">
                                    <button type="button" class="btn btn_outline btn_sm" onclick="prompt('@lang('site.account.settings.id.your_id'):', '{{$user->id}}')">@lang('site.account.settings.id.button')</button>
                                </div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h4 class="card__title">
                                        {{$user->email}}
                                        <i class="icon material-icons text-success" data-tooltip title="@lang('site.account.settings.email.badge')">check_circle</i>
                                    </h4>
                                    <h5 class="card__subtitle">@lang('site.account.settings.email.subtitle')</h5>
                                </div>
                                <div class="card-table__col-2">
                                    <a href="#" class="btn btn_outline btn_sm" data-modal="#change-email">@lang('site.account.settings.email.button')</a>
                                </div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h4 class="card__title">NaN days ago</h4>
                                    <h5 class="card__subtitle">@lang('site.account.settings.password_update.subtitle')</h5>
                                </div>
                                <div class="card-table__col-2">
                                    <a href="#" class="btn btn_outline btn_sm" data-modal="#change-password">@lang('site.account.settings.password_update.button')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if(!$user->has2fa())
                    <div class="card">
                        <div class="card__body">
                            <div class="card-table card-table_space-between">
                                <div class="card-table__row">
                                    <div class="card-table__col-6">
                                        <h4 class="card__title">
                                            @lang('site.account.settings.2fa.disabled.title')
                                            <i class="icon material-icons text-warning text-warning_light" data-tooltip title="@lang('site.account.settings.2fa.disabled.badge')">warning</i>
                                        </h4>
                                        <h5 class="card__subtitle">
                                            @lang('site.account.settings.2fa.disabled.subtitle')
                                        </h5>
                                    </div>
                                    <div class="card-table__col-2">
                                        <a href="#" class="btn btn_outline btn_sm" id="2fa-setup-open">@lang('site.account.settings.2fa.disabled.button')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="card">
                        <div class="card__body">
                            <div class="card-table card-table_space-between">
                                <div class="card-table__row">
                                    <div class="card-table__col-6">
                                        <h4 class="card__title">
                                            @lang('site.account.settings.2fa.enabled.title')
                                            <i class="icon material-icons text-success">check_circle</i>
                                        </h4>
                                        <h5 class="card__subtitle">
                                            @lang('site.account.settings.2fa.enabled.subtitle')
                                        </h5>
                                    </div>
                                    <div class="card-table__col-2">
                                        <a href="#" class="btn btn_outline btn_sm" data-modal="#change-2fa-disable">@lang('site.account.settings.2fa.enabled.button')</a>
                                    </div>
                                </div>
                                <div class="card-table__row">
                                    <div class="card-table__col-6">
                                        <h4 class="card__title">@lang('site.account.settings.2fa.keys.title')</h4>
                                        <h5 class="card__subtitle">
                                            @lang('site.account.settings.2fa.keys.subtitle')
                                        </h5>
                                    </div>
                                    <div class="card-table__col-2">
                                        <a href="#" class="btn btn_outline btn_sm" data-modal="#2fa-keys">@lang('site.account.settings.2fa.keys.button')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card">
                    <div class="card__body">
                        <div class="card-table card-table_space-between">
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h4 class="card__title">{{$totalOnline === 0 ? 0 : ($totalOnline < 1 ? '< 1' : $totalOnline)}} {{\App\Helpers\Str::declensionNumber($totalOnline === 0 ? 0 : $totalOnline, ...__('words.time.hours'))}}</h4>
                                    <h5 class="card__subtitle">
                                        @lang('site.account.settings.playtime.subtitle')
                                    </h5>
                                </div>
                                <div class="card-table__col-2">
                                    <a href="#" class="btn btn_outline btn_sm" data-modal="#reset-playtime">@lang('site.account.settings.playtime.button')</a>
                                </div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h4 class="card__title">@lang('site.account.settings.language.title')</h4>
                                    <h5 class="card__subtitle">
                                        @lang('site.account.settings.language.subtitle')
                                    </h5>
                                </div>
                                <div class="card-table__col-2">
                                    <form method="post" action="{{route('account.settings.set-lang')}}">
                                        @csrf

                                        <select class="select select_outline select_sm" name="lang" onchange="$(this).parent().submit()">
                                            @foreach(\App\Lang::ALLOWED_LANGS as $lang)
                                                <option value="{{$lang}}" @if($lang == $userLang) selected @endif>@lang('site.account.settings.language.langs.' . $lang)</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('bottom')
    @if(!$user->has2fa())
        <div class="modal" id="2fa-setup">
			<div class="modal__dialog">
				<div class="modal__container">
					<div class="modal__header">
						<div>
							<h4 class="modal__title">@lang('site.account.windows.2fa.setup.title')</h4>
							<h5 class="modal__attention">@lang('site.account.windows.2fa.setup.attention')</h5>
						</div>
						<a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
					</div>
					<div class="modal__body">
						<div class="tfa-enable modal__tfa-enable">
							<div class="tfa-enable__download">
								<div class="tfa-enable__authenticator">
									<img src="{{asset('img/google-authenticator-2.svg')}}" alt="" class="tfa-enable__authenticator-img img-loading">
								</div>
								<div class="tfa-enable__apps">
									<div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.app.title')</div>
									<div class="tfa-enable__desc">@lang('site.account.windows.2fa.setup.app.desc')</div>
									<div class="tfa-enable__links">
										<div class="tfa-enable__links-title">@lang('site.account.windows.2fa.setup.app.links.title')</div>
										<a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" class="tfa-enable__link text-warning" target="_blank">@lang('site.account.windows.2fa.setup.app.links.google') <i class="icon material-icons tfa-enable__link-icon">launch</i></a>
										<a href="https://apps.apple.com/ru/app/google-authenticator/id388497605" class="tfa-enable__link text-warning" target="_blank">@lang('site.account.windows.2fa.setup.app.links.appstore') <i class="icon material-icons tfa-enable__link-icon">launch</i></a>
									</div>
								</div>
							</div>
							<div class="tfa-enable__codes">
								<div class="tfa-enable__qr">
									<img src="" alt="" class="tfa-enable__qr-img img-loading" id="2fa-qr">
								</div>
								<div class="tfa-enable__install">
									<div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.codes.title')</div>
									<div class="tfa-enable__desc">@lang('site.account.windows.2fa.setup.codes.desc')</div>
									<div class="tfa-enable__code">
										<div class="tfa-enable__title tfa-enable__code-title">@lang('site.account.windows.2fa.setup.codes.code')</div>
										<span id="2fa-secret"><!--0000 0000 0000 0000--></span>
									</div>
									<div class="tfa-enable__finish">
										<div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.install.title')</div>
										@lang('site.account.windows.2fa.setup.install.desc')
									</div>

									<form class="form tfa-enable__form" method="post" action="{{route('account.2fa.set')}}">
										@csrf

										<input type="hidden" name="secret" value="" id="2fa-secret-input">
										<input type="text" class="input" name="code" required autofocus>
										<button type="submit" class="btn btn_warning btn_sm">@lang('site.account.windows.2fa.setup.form.button')</button>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    @else
        <div class="modal" id="2fa-keys">
            <div class="modal__dialog">
                <div class="modal__container">
                    <div class="modal__header">
                        <div>
                            <h4 class="modal__title">@lang('site.account.windows.2fa.keys.title')</h4>
                            <h5 class="modal__attention">@lang('site.account.windows.2fa.keys.attention')</h5>
                        </div>
                        <a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
                    </div>
                    <div class="modal__body">
                        <div class="tfa-keys page-window__tfa-keys">
                            <div class="tfa-keys__list">
                                @foreach($keys as $key)
                                    <div class="tfa-keys__key">{{\App\Helpers\Str::separateWord((string) $key->key, 4)}}</div>
                                @endforeach
                            </div>
                            <div class="tfa-keys__content">
                                <div class="tfa-keys__desc">
                                    <p class="tfa-keys__about-quest">@lang('site.account.windows.2fa.keys.quest')</p>
                                    <p class="tfa-keys__about-answ">@lang('site.account.windows.2fa.keys.answ')</p>
                                    <p class="tfa-keys__about-warning text-warning">@lang('site.account.windows.2fa.keys.warning')</p>
                                </div>

                                <div class="tfa-keys__buttons">
                                    <form class="tfa-keys__form" action="{{route('account.2fa.keys.download')}}" method="post">
                                        @csrf

                                        <button type="submit" class="btn btn_outline btn_sm tfa-keys__button">@lang('site.account.windows.2fa.keys.buttons.download')</button>
                                    </form>
                                    <button class="btn btn_warning btn_sm tfa-keys__button" data-close>@lang('site.account.windows.2fa.keys.buttons.close')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="change-2fa-disable">
            <div class="modal__dialog modal__dialog_lg">
                <div class="modal__container">
                    <div class="modal__header">
                        <h4 class="modal__title">@lang('site.account.windows.2fa.disable.title')</h4>
                        <a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
                    </div>
                    <div class="modal__body">
                        <form class="form page-window__form" action="{{route('account.2fa.disable')}}" method="post">
                            @csrf

                            <div class="form__item">
                                <label class="form__label">@lang('site.account.windows.2fa.disable.form.password')</label>
                                <input type="password" class="input" name="password" required>
                            </div>

                            @if(!$loggedByKey)
                                <div class="form__item">
                                    <label class="form__label">@lang('site.account.windows.2fa.disable.form.code')</label>
                                    <input type="text" class="input" name="code" required>
                                </div>
                            @endif

                            <div class="form__item page-window__item-button">
                                <button type="submit" class="btn btn_warning">@lang('site.account.windows.2fa.disable.form.button')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modal" id="reset-playtime">
        <div class="modal__dialog modal__dialog_lg">
            <div class="modal__container">
                <div class="modal__header">
                    <h4 class="modal__title">@lang('site.account.windows.playtime_reset.title')</h4>
                    <a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
                </div>
                <div class="modal__body">
                    @lang('site.account.windows.playtime_reset.text')
                    <form class="form page-window__form" action="{{route('account.playtime.reset.confirm')}}" method="post">
                        @csrf

                        <div class="form__item page-window__item-button">
                            <button type="submit" class="btn btn_warning">@lang('site.account.windows.playtime_reset.button')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="change-password">
        <div class="modal__dialog modal__dialog_lg">
            <div class="modal__container">
                <div class="modal__header">
                    <h4 class="modal__title">@lang('site.account.windows.password.change.title')</h4>
                    <a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
                </div>
                <div class="modal__body">
                    <form class="form page-window__form" action="{{route('account.password.change.update')}}" method="post">
                        @csrf

                        <div class="form__item">
                            <label class="form__label">@lang('site.account.windows.password.change.form.current')</label>
                            <input type="password" class="input" name="current_password" required>
                        </div>

                        <div class="form__item">
                            <label class="form__label">@lang('site.account.windows.password.change.form.new')</label>
                            <input type="password" class="input" name="password" required>
                        </div>

                        <div class="form__item">
                            <label class="form__label">@lang('site.account.windows.password.change.form.repeat')</label>
                            <input type="password" class="input" name="password_confirmation" required>
                        </div>

                        <div class="form__item page-window__item-button">
                            <button type="submit" class="btn btn_warning">@lang('site.account.windows.password.change.form.button')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="change-email">
        <div class="modal__dialog modal__dialog_lg">
            <div class="modal__container">
                <div class="modal__header">
                    <h4 class="modal__title">@lang('site.account.windows.email.change.title')</h4>
                    <a href="#" class="close close_light" data-close><i class="icon material-icons">close</i></a>
                </div>
                <div class="modal__body">
                    <form class="form page-window__form" action="{{route('account.email.update')}}" method="post">
                        @csrf

                        <div class="form__item">
                            <label class="form__label">@lang('site.account.windows.email.change.form.new')</label>
                            <input type="email" class="input" name="email" required>
                        </div>

                        <div class="form__item">
                            <label class="form__label">@lang('site.account.windows.email.change.form.repeat')</label>
                            <input type="email" class="input" name="email_confirmation" required>
                        </div>

                        <div class="form__item page-window__item-button">
                            <button type="submit" class="btn btn_warning">@lang('site.account.windows.email.change.form.button')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        {{--todo скрипт в файл--}}
        $('#2fa-setup-open').on('click', function(event) {
            event.preventDefault();
            loading(true);
            $.post('{{route('account.2fa.setup')}}', {'_token': '{{csrf_token()}}'})
                .then((res) => {
                    loading(false);
                    let $modal = $('#2fa-setup');
                    $modal.find('#2fa-qr').attr('src', res.qr);
                    $modal.find('#2fa-secret').text(res.secret_word);
                    $modal.find('#2fa-secret-input').attr('value', res.secret);
                    loading(false);
                    $modal.modal('show')
                })
                .catch(err => {
                    alert('Error!');
                    loading(false)
                });
        });
    </script>
@endsection
