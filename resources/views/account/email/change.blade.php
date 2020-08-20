@extends('layouts.windows')

@section('title', __('titles.account.email.change'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.email.change.title')</h4>
    <form class="form page-window__form" method="post" action="{{route('account.email.update')}}">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.email.change.form.new')</label>
            <input type="email" class="input" name="email">
        </div>
        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.email.change.form.repeat')</label>
            <input type="email" class="input" name="email_confirmation">
        </div>
        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.email.change.form.button')</button>
        </div>
    </form>
@endsection
