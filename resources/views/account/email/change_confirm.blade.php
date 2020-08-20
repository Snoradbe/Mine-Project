@extends('layouts.windows')

@section('title', __('titles.account.email.change_confirm'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.email.change_confirm.title')</h4>
    <div class="page-window__subtitle">
        @lang('site.account.windows.email.change_confirm.subtitle')
    </div>
    <div class="page-window__text">
        @lang('site.account.windows.email.change_confirm.sent') <span class="text-warning">{{$email}}</span>
    </div>
@endsection
