@extends('layouts.windows')

@section('title', __('titles.account.email.confirm_detach'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.email.confirm_detach.title')</h4>
    <div class="page-window__subtitle">
        @lang('site.account.windows.email.confirm_detach.subtitle')
    </div>
    <div class="page-window__text">
        @lang('site.account.windows.email.confirm_detach.sent') <span class="text-warning">{{$email}}</span>
    </div>
@endsection
