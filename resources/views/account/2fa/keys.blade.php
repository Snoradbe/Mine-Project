@extends('layouts.windows')

@section('title', __('titles.account.2fa.setup'))

@section('window-class', 'page-window_sm')

@section('content')
    <h4 class="page-window__title">@lang('site.account.windows.2fa.keys.title')</h4>
    <div class="page-window__attention">@lang('site.account.windows.2fa.keys.attention')</div>
    <div class="tfa-keys page-window__tfa-keys">
        <div class="tfa-keys__list">
            @foreach($keys as $key)
                <div class="tfa-keys__key">{{\App\Helpers\Str::separateWord((string) $key->key, 4)}}</div>
            @endforeach
        </div>
        <div class="tfa-keys__content">
            <div class="tfa-keys__desc">
                <p class="tfa-keys__about-quest">@lang('site.account.windows.2fa.keys.quest')</p>
                <p class="tfa-keys__about-answ">@lang('site.account.windows.2fa.keys.answ')</p>
                <p class="tfa-keys__about-warning text-warning">@lang('site.account.windows.2fa.keys.warning')</p>
            </div>

            <div class="tfa-keys__buttons">
                <form class="tfa-keys__form" action="{{route('account.2fa.keys.download')}}" method="post">
                    @csrf

                    <button type="submit" class="btn btn_outline btn_sm tfa-keys__button">@lang('site.account.windows.2fa.keys.buttons.download')</button>
                </form>
                <a href="{{route('account.settings')}}" class="btn btn_warning btn_sm tfa-keys__button">@lang('site.account.windows.2fa.keys.buttons.close')</a>
            </div>
        </div>
    </div>
@endsection
