@extends('layouts.app')

@section('title', __('titles.store.cart'))

@section('head')
    <link rel="stylesheet" href="{{asset('css/store.css')}}?v={{time()}}">
@endsection

@section('content')
    <div id="cart">
        <cart
            :items="{{$items->toJson()}}"
            :recommended="{{$recommended->toJson()}}"
            :settings="{{json_encode($settings)}}"
            :user-data="{{json_encode($userData)}}"
        ></cart>
    </div>
@endsection

@section('bottom')
    <script type="text/javascript">
        var StoreLang = '{{\App\Lang::locale()}}';
        var Lang = {!! json_encode($lang) !!}
    </script>
    <script src="{{asset('/js/client/store/store.js')}}?={{time()}}"></script>
    <script src="{{asset('/js/client/store/cart.js')}}?={{time()}}"></script>
@endsection
