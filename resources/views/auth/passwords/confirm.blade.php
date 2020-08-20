@extends('layouts.windows')

@section('title', __('titles.auth.passwords.confirm'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.windows.confirm.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
        @lang('site.windows.confirm.subtitle')
    </div>
    <form class="form page-window__form">
        <div class="form__item">
            <label class="form__label">@lang('site.windows.confirm.form.password')</label>
            <input type="password" class="input" name="password">
        </div>
        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.windows.confirm.form.button')</button>
        </div>
    </form>
@endsection
