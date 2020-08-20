@extends('layouts.windows')

@section('title', __('titles.auth.login'))

@section('content')
    <div class="logo">
        <img src="{{asset('img/logo.svg')}}" alt="mine Project" class="logo__img">
    </div>
    <h4 class="page-window__title page-window__title_welcome">@lang('site.account.login.welcome')</h4>
    <form class="form page-window__form" method="POST" action="{{route('login')}}">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.login.form.login')</label>
            <input type="text" class="input" name="playername">
        </div>
        <div class="form__item">
            <label class="form__label">@lang('site.account.login.form.password')</label>
            <input type="password" class="input" name="password">
        </div>
        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.login.form.sign_in')</button>
        </div>
        <div class="form__item page-window__forgot-password">
            @lang('site.account.login.forgot')
            <a href="{{route('password.request')}}" class="page-window__restore-link text-warning">@lang('site.account.login.restore')</a>
        </div>
    </form>
@endsection
