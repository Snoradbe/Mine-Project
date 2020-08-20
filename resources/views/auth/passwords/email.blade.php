@extends('layouts.windows')

@section('title', __('titles.auth.passwords.restore'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.account.windows.password.restore.title')</h4>
    <h5 class="page-window__subtitle page-window__subtitle_expanded">@lang('site.account.windows.password.restore.subtitle')</h4>
    <form class="form page-window__form" action="{{route('password.email')}}" method="post">
        @csrf

        <div class="form__item">
            <label class="form__label">@lang('site.account.windows.password.restore.form.email')</label>
            <input type="email" class="input" name="email">
        </div>

        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.password.restore.form.button')</button>
        </div>
    </form>
@endsection
