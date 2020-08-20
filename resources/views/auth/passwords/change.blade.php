@extends('layouts.windows')

@section('title', __('titles.auth.passwords.change'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.account.windows.password.change.title')</h4>
    <form class="form page-window__form" action="{{route('password.change.update')}}" method="post">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.change.form.current')</label>
            <input type="password" class="input" name="current_password">
        </div>

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.change.form.new')</label>
            <input type="password" class="input" name="password">
        </div>

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.change.form.repeat')</label>
            <input type="password" class="input" name="password_confirmation">
        </div>

        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.password.change.form.button')</button>
        </div>
    </form>
@endsection
