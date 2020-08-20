@extends('layouts.windows')

@section('title', __('titles.account.captcha'))

@section('content')
    <h4 class="page-window__title page-window__title_no-margin">@lang('site.account.windows.captcha.title')</h4>
    <div class="page-window__subtitle page-window__subtitle_expanded">
        @lang('site.account.windows.captcha.subtitle')
    </div>
    <div class="page-window__text page-window__text_center">
        <form action="" method="post" id="grecaptchaForm">
            @csrf
            @foreach($request as $key => $value)
                <input type="hidden" name="{{$key}}" value="{{$value}}">
            @endforeach

            {!! NoCaptcha::display(['data-theme' => 'dark', 'data-callback' => 'grecaptchaGetResponse']) !!}
        </form>
    </div>
@endsection
@section('bottom')

    {!! NoCaptcha::renderJs(str_replace('_', '-', \App\Lang::locale())) !!}
    <script type="text/javascript">
        function grecaptchaGetResponse(e) {
            $('#grecaptchaForm').submit()
        }
    </script>
@endsection
