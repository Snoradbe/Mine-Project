@extends('layouts.app')

@section('title', __('titles.status.home'))

@section('head')
    <link rel="stylesheet" href="{{asset('css/status.css')}}?v={{time()}}">
@endsection

@section('content')
    <div id="status">
        <status
            node-domain="{{$domain}}:2096"
            :node-options="{transports: ['websocket']}"
            :settings="{{json_encode($settings)}}"
            :is-auth="@auth true @else false @endauth"
            :servers-sorting="{{json_encode(config('servers-sorting', []))}}"
        ></status>
    </div>
@endsection

@section('bottom')
    <script type="text/javascript">
		loading(true);
        var Lang = {!! json_encode($lang) !!};
        var Types = {!! json_encode($types) !!}
    </script>
    <script src="{{asset('js/client/status/status.js')}}?v={{time()}}"></script>
@endsection
