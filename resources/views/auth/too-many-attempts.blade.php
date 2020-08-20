@extends('layouts.windows')

@section('title', __('titles.auth.too_many_attempts'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.login.too_many.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
        @lang('site.account.login.too_many.text')
    </div>
@endsection
