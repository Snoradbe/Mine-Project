@extends('layouts.windows')

@section('title', __('titles.account.email.assigment'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.email.assigment.title')</h4>
    <div class="page-window__subtitle">
        @lang('site.account.windows.email.assigment.subtitle')
    </div>
    <div class="page-window__username bordered-status bordered-status_circle">{{$user->playername}}</div>
    <form class="form page-window__form" method="post" action="{{route('account.email.assigment.make')}}">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.email.assigment.form.email')</label>
            <input type="text" class="input" name="email">
        </div>
        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.email.assigment.form.button')</button>
        </div>
    </form>
@endsection
