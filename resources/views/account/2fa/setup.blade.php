@extends('layouts.windows')

@section('title', __('titles.account.2fa.setup'))

@section('window-class', 'page-window_sm')

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.2fa.setup.title')</h4>
    <div class="page-window__attention">@lang('site.account.windows.2fa.setup.attention')</div>
    <div class="tfa-enable page-window__tfa-enable">
        <div class="tfa-enable__download">
            <div class="tfa-enable__authenticator">
                <img src="{{asset('img/google-authenticator-2.svg')}}" alt="" class="tfa-enable__authenticator-img img-loading">
            </div>
            <div class="tfa-enable__apps">
                <div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.app.title')</div>
                <div class="tfa-enable__desc">@lang('site.account.windows.2fa.setup.app.desc')</div>
                <div class="tfa-enable__links">
                    <div class="tfa-enable__links-title">@lang('site.account.windows.2fa.setup.app.links.title')</div>
                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" class="tfa-enable__link text-warning">@lang('site.account.windows.2fa.setup.app.links.google') <i class="icon material-icons tfa-enable__link-icon">launch</i></a>
                    <a href="https://apps.apple.com/ru/app/google-authenticator/id388497605" class="tfa-enable__link text-warning">@lang('site.account.windows.2fa.setup.app.links.appstore') <i class="icon material-icons tfa-enable__link-icon">launch</i></a>
                </div>
            </div>
        </div>
        <div class="tfa-enable__codes">
            <div class="tfa-enable__qr">
                <img src="{{$qr}}" alt="" class="tfa-enable__qr-img img-loading">
            </div>
            <div class="tfa-enable__install">
                <div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.codes.title')</div>
                <div class="tfa-enable__desc">@lang('site.account.windows.2fa.setup.codes.desc')</div>
                <div class="tfa-enable__code">
                    <div class="tfa-enable__title tfa-enable__code-title">@lang('site.account.windows.2fa.setup.codes.code')</div>
                    {{\App\Helpers\Str::separateWord($secret, 4)}}
                </div>
                <div class="tfa-enable__finish">
                    <div class="tfa-enable__title">@lang('site.account.windows.2fa.setup.install.title')</div>
                    @lang('site.account.windows.2fa.setup.install.desc')
                </div>

                <form class="form tfa-enable__form" method="post" action="{{route('account.2fa.set')}}">
                    @csrf
                    <input type="hidden" name="secret" value="{{$secret}}">

                    <input type="text" class="input" name="code" required autofocus>
                    <button type="submit" class="btn btn_warning btn_sm">@lang('site.account.windows.2fa.setup.form.button')</button>
                </form>
            </div>
        </div>
    </div>
@endsection
