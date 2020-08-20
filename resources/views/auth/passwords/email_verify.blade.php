@extends('layouts.windows')

@section('title', __('titles.account.email.assigment_verify'))

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.password.restore.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
		@lang('site.account.windows.password.restore.verify.text')
    </div>
    <div class="page-window__text">
        @lang('site.account.windows.password.restore.verify.sent') <span class="text-warning">{{$email}}</span>
    </div>
@endsection
