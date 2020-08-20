@extends('layouts.windows')

@section('title', __('titles.account.playtime_reset'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.account.windows.playtime_reset.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
        @lang('site.account.windows.playtime_reset.text')
    </div>
    <form class="form page-window__form" action="{{route('account.playtime.reset.confirm')}}" method="post">
        @csrf

        <div class="form__item page-window__item-button">
            <button type="submit" class="btn btn_warning">@lang('site.account.windows.playtime_reset.button')</button>
        </div>
    </form>
@endsection
