@extends('layouts.windows')

@section('title', __('titles.auth.passwords.reset'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.account.windows.password.reset.title')</h4>
    <form class="form page-window__form" action="{{route('password.update')}}" method="post">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <input type="hidden" class="input" name="email" value="{{ $email ?? old('email') }}" readonly>

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.reset.form.password')</label>
            <input type="password" class="input" name="password">
        </div>

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.reset.form.password2')</label>
            <input type="password" class="input" name="password_confirmation">
        </div>

        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.password.restore.form.button')</button>
        </div>
    </form>
@endsection
