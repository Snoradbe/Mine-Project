@extends('layouts.app')

@section('title', __('titles.store.home'))

@section('head')
    <link rel="stylesheet" href="{{asset('css/store.css')}}?v={{time()}}">
    <link rel="stylesheet" href="{{asset('fonts/unisansac.css')}}?v={{time()}}">
@endsection

@if(!empty($userData))
    @section('content')
        <div id="store">
            <marketplace
                :categories="{{$categories->toJson()}}"
                :servers="{{$servers->toJson()}}"
                :discounts="{{$discounts->toJson()}}"
                :popular="{{$popular->toJson()}}"
                :settings="{{json_encode($settings)}}"
                :user-data="{{json_encode($userData)}}"
                :selected-cat="{{$selectedCategory}}"
            ></marketplace>
        </div>
    @endsection

    @section('bottom')
        <script type="text/javascript">
            var StoreLang = '{{\App\Lang::locale()}}';
            var Lang = {!! json_encode($lang) !!}
        </script>
        <script src="{{asset('/js/client/store/store.js')}}?={{time()}}"></script>
        <script src="{{asset('/js/client/store/marketplace.js')}}?={{time()}}"></script>
    @endsection
@else
    @section('content')
        <div class="section-store space-header">
            <div class="container section-store__container store-auth-container">
                @if (isset($errors) && count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <div class="alert alert_danger">
                            <div class="alert__text">
                                <i class="icon material-icons text-white alert__icon">warning</i>
                                {{ $error  }}
                            </div>
                            <a href="#" class="close close_light">×</a>
                        </div>
                    @endforeach
                @endif

                <div class="store-auth">
                    <div class="store-auth__title">
                        PLEASE LOGIN
                    </div>
                    <form class="store-auth__form" action="{{route('store.login')}}" method="post">
                        @csrf

                        <div class="store-auth__inputs">
                            <label class="store-auth__label">Login with nickname</label>
                            <input type="text" class="input store-auth__input" name="nickname" placeholder="Enter nickname">
                        </div>

                        <div class="store-auth__info">
                            <h6 class="store-auth__info-title">LIMITATIONS WITH NICKNAME AUTH</h6>
                            <p class="store-auth__info-text">You cannot to use shopping cart and buy game items, cases and exchange roubles to mine Coins until you sign in with mine Account.</p>
                        </div>

                        <div class="store-auth__buttons">
                            <button type="submit" class="store-auth__btn btn btn_warning">ENTER WITH NICKNAME</button>
                            <a href="{{route('login')}}" class="store-auth__link-login">Login with account <span class="material-icons">arrow_forward</span></a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endsection
@endif
