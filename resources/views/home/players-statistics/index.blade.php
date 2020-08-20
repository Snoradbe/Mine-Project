@extends('layouts.app')

@section('title', __('titles.players-statistics.home'))

@section('head')
    <link rel="stylesheet" href="{{asset('fonts/unisansac.css')}}?v={{time()}}">
    <link rel="stylesheet" href="{{asset('css/players-statistics.css')}}?v={{time()}}">
@endsection

@section('content')
    <div class="players-statistics space-header">
        <div class="container players-statistics__container">
            <h1 class="players-statistics__title">@lang('players-statistics.title')</h1>
            <div class="player-statistic">
                <div class="player-statistic__playtime">
                    <div class="players-statistics__ava player-statistic__ava" style="background-image:url('{{asset($user->getAvatar())}}');"></div>
                    <div class="player-statistic__info">
                        <h6 class="player-statistic__title">@lang('players-statistics.global_ranking')</h6>
                        <h5 class="player-statistic__playername">{{$user->playername}}</h5>
                    </div>
                    <div class="player-statistic__servers">
                        @foreach($servers as $server)
                            <div class="player-statistic__server">
                                <h4 class="player-statistic__servername">{{$server->getAbbr()}}</h4>
                                <div class="player-statistic__time">
                                    {{floor($user->playtime->getOnlineOnServer($server->name) / 3600)}}
                                    {{\App\Helpers\Str::declensionNumber(floor($user->playtime->getOnlineOnServer($server->name) / 3600), ...__('words.time.hours'))}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="players-statistics__line"></div>
                <table class="players-statistics__table" cellpadding="0" cellspacing="0">
                    @if(!is_null($nearRanks[0]))
                        <tr>
                            <td>#{{$nearRanks[0]->rank}}</td>
                            <td>
                                <div class="players-statistics__table-player">
                                    <div class="players-statistics__ava" style="background-image:url('{{asset($nearRanks[0]->avatar)}}');"></div>
                                    {{$nearRanks[0]->username}}
                                </div>
                            </td>
                            <td></td>
                            <td class="player-statistic-global-rank">
                                {{$nearRanks[0]->total}} {{\App\Helpers\Str::declensionNumber($nearRanks[0]->total, ...__('words.time.hours'))}}
                            </td>
                        </tr>
                    @endif
                    <tr class="selected">
                        <td>#{{$accountTime->rank}}</td>
                        <td>
                            <div class="players-statistics__table-player">
                                <div class="players-statistics__ava" style="background-image:url('{{asset($accountTime->avatar)}}');"></div>
                                {{$accountTime->username}}
                            </div>
                        </td>
                        <td></td>
                        <td class="player-statistic-global-rank">
                            {{$accountTime->total}} {{\App\Helpers\Str::declensionNumber($accountTime->total, ...__('words.time.hours'))}}
                            <div>@lang('players-statistics.your_global_rank')</div>
                        </td>
                    </tr>
                        @if(!is_null($nearRanks[1]))
                            <tr>
                                <td>#{{$nearRanks[1]->rank}}</td>
                                <td>
                                    <div class="players-statistics__table-player">
                                        <div class="players-statistics__ava" style="background-image:url('{{asset($nearRanks[1]->avatar)}}');"></div>
                                        {{$nearRanks[1]->username}}
                                    </div>
                                </td>
                                <td></td>
                                <td class="player-statistic-global-rank">
                                    {{$nearRanks[1]->total}} {{\App\Helpers\Str::declensionNumber($nearRanks[1]->total, ...__('words.time.hours'))}}
                                </td>
                            </tr>
                        @endif
                </table>
            </div>

            <div class="players-statistics__search">
                <form method="get" action="">
                    <input type="text" class="input" name="name" placeholder="@lang('players-statistics.search_player')" @if($search) value="{{$search}}" @endif>
                    @if(!$search)
                        <div>
                            <span>@lang('players-statistics.enter_to_search')</span>
							<button type="submit"><span class="material-icons">search</span></button>
                        </div>
                    @else
                        <div>
                            <span>@lang('players-statistics.enter_to_search')</span>
                            <a href="{{home_route('home.players-statistics')}}">
                                <span class="material-icons">cancel</span>
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <div class="players-statistics__top">
                <div class="players-statistics__tabs">
                    <div class="players-statistics__tab active" data-tab="#server-global">@lang('players-statistics.global_ranking')</div>
                    @foreach($servers as $server)
                        <div class="players-statistics__tab" data-tab="#server-{{$server->name}}">{{$server->name}}</div>
                    @endforeach
                </div>
                @if($search && $topTotals->isEmpty())
                    <div class="players-statistics__tabs-content">
                        <div class="players-statistics__not-found">
                            <span class="icon material-icons">search_off</span>
                            <h1>@lang('players-statistics.not_found.title', ['name' => $search])</h1>
                            <h6>@lang('players-statistics.not_found.subtitle')</h6>
                        </div>
                    </div>
                @else
                    <div class="players-statistics__tabs-content js-tabs">
                        <div id="server-global" style="display: block">
                            @include('home.players-statistics.partials.tab-content', ['rows' => $topTotals])
                        </div>
                        @foreach($topServers as $topServer => $topServerData)
                            <div id="server-{{$topServer}}">
                                @include('home.players-statistics.partials.tab-content', ['rows' => $topServerData])
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
