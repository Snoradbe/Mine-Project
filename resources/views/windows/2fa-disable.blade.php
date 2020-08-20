@extends('layouts.windows')

@section('title', __('titles.account.2fa.disable'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.2fa.disable.title')</h4>
    <form class="form page-window__form" method="post" action="{{route('account.2fa.disable')}}">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.2fa.disable.form.password')</label>
            <input type="password" class="input" name="password">
        </div>

        @if(!$loggedByKey)
            <div class="form__item">
                <label class="form__label">@lang('site.account.windows.2fa.disable.form.code')</label>
                <input type="text" class="input" name="code">
            </div>
        @endif

        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.2fa.disable.form.button')</button>
        </div>
    </form>
@endsection
