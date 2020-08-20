@extends('layouts.windows')

@section('title', __('titles.account.2fa.confirm'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.2fa.confirm.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
        @lang('site.account.windows.2fa.confirm.subtitle')
    </div>
    <div class="page-window__username bordered-status bordered-status_circle">{{$user->playername}}</div>
    <form class="form page-window__form" method="post" action="{{route('2fa.auth')}}">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.2fa.confirm.form.code')</label>
            <input type="text" class="input" name="code" required autofocus>
        </div>
        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.2fa.confirm.form.button')</button>
        </div>
    </form>
@endsection
