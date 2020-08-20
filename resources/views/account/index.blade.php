@extends('layouts.app')

@section('title', __('titles.account.home'))

@section('content')
    <div class="section-account space-header">
        <div class="container section-account__container">
            <div class="section-account__head">
                <div class="card">
                    <div class="card__header account-header">
                        <img src="{{asset($user->getAvatar())}}" alt="mine {{$user->playername}}" class="account-header__avatar">
                        <h4 class="account-header__nickname">{{$user->playername}}</h4>
                    </div>
                    <div class="card__body">
                        <h5 class="section-account__latest-online">@lang('account-page.latest_online')</h5>
                        <h4 class="section-account__latest-date">{{$user->lastlogin->format('d F Y (H:i)')}}</h4>
                    </div>
                </div>
            </div>

            <div class="section-account__perks">
                @if(!is_null($staffGroup))
                    <div class="account-perk">
                        <div class="account-perk__info">
                            <img src="{{asset('img/staff_logo_profile.svg')}}" class="icon account-perk__icon account-perk__icon_staff">
                            <div class="account-perk__info-content">
                                <h4 class="account-perk__name">{{$staffGroup->getScreenName()}}</h4>
                                <h5 class="account-perk__expires">@lang('account-page.perk.staff.title')</h5>
                            </div>
                        </div>
                    </div>
                @else
                    @if (!$primaryGroup->isDefault())
                        <div class="account-perk">
                            <div class="account-perk__info">
                                <i class="icon material-icons account-perk__icon">flash_on</i>
                                <div class="account-perk__info-content">
                                    <h4 class="account-perk__name">{{$primaryGroup->getScreenName()}} @lang('account-page.perk.activated.title')</h4>
                                    <h5 class="account-perk__expires">@lang('account-page.perk.activated.expires', ['amount' => $expiresGroup, 'days' => \App\Helpers\Str::declensionNumber($expiresGroup, ...__('words.time.days'))])</h5>
                                </div>
                            </div>
                            <div class="account-perk__buttons">
                                <button class="btn btn_white-outline btn_sm">@lang('account-page.perk.activated.button')</button>
                            </div>
                        </div>
                    @else
                        <div class="account-perk account-perk_empty">
                            <div class="account-perk__info">
                                <i class="icon material-icons account-perk__icon">notification_important</i>
                                <div class="account-perk__info-content">
                                    <h4 class="account-perk__name">@lang('account-page.perk.no_activated.title')</h4>
                                </div>
                            </div>
                            <div class="account-perk__buttons">
                                <button class="btn btn_warning btn_sm">@lang('account-page.perk.no_activated.button')</button>
                            </div>
                        </div>
                    @endif
                @endif
            </div>

            <div class="section-account__info">
                <div class="card">
                    <div class="card__header">
                        <h5 class="card__title">@lang('account-page.account_information')</h5>
                        <a href="{{route('account.settings')}}"><i class="icon icon_link material-icons card__icon">settings</i></a>
                    </div>
                    <div class="card__body">
                        <div class="card-table">
                            <div class="card-table__row">
                                <div class="card-table__col-4">@lang('account-page.email')</div>
                                <div class="card-table__col-8">
                                    {{$user->email}}
                                    <i class="icon material-icons text-success" data-tooltip title="@lang('account-page.email_badge')">check_circle</i>
                                </div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-4">@lang('account-page.registration_ip')</div>
                                <div class="card-table__col-8">{{$user->ip_address}}</div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-4">@lang('account-page.registration_date')</div>
                                <div class="card-table__col-8">{{$user->lastlogin->format('d.m.Y (H:i') . ' MSK)'}}</div>
                            </div>
                            <div class="card-table__row">
                                <div class="card-table__col-4">@lang('account-page.twofactor_auth')</div>
                                <div class="card-table__col-8">
                                    @if($user->has2fa())
                                        @lang('site.account.profile.2fa.true')
                                        <i class="icon material-icons text-success">check_circle</i>
                                    @else
                                        @lang('site.account.profile.2fa.false')
                                        <i class="icon material-icons text-warning" data-tooltip title="@lang('site.account.settings.2fa.disabled.badge')">error</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__header section-account__header-stats">
                        <div class="card-table">
                            <div class="card-table__row">
                                <div class="card-table__col-4">
                                    <h5 class="card__title">@lang('account-page.total_playtime')</h5>
                                </div>
                                <div class="card-table__col-3">
                                    <h5 class="card__title">{{floor($totalOnline)}} {{\App\Helpers\Str::declensionNumber(floor($totalOnline), ...__('words.time.hours'))}}</h5>
                                </div>
                                <div class="card-table__col-5">
                                    <div class="card__title bordered-status bordered-status_circle section-account__ranking-place">{{$onlineRankPosition}} @lang('account-page.ranking_place')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card__body">
                        <div class="card-table">
                            @foreach($serversOnline as $serverName => $serverOnline)
                                <div class="card-table__row">
                                    <div class="card-table__col-4">{{$serverName}}</div>
                                    <div class="card-table__col-3">{{$serverOnline['online'] < 1 ? ($serverOnline['online'] === 0 ? 0 : '< 1') : floor($serverOnline['online'])}} {{\App\Helpers\Str::declensionNumber($serverOnline['online'] < 1 ? ($serverOnline['online'] > 0 ? 1 : 0) : floor($serverOnline['online']), ...__('words.time.hours'))}}</div>
                                    <div class="card-table__col">
                                        <div class="section-account__online-progress">
                                            <div class="progress-line" style="width: {{$serverOnline['percent_width']}}%; @if($serverOnline['percent_width'] < 1) display: none; @endif">
                                                <div class="progress-line__filled"></div>
                                            </div>
                                            <span class="section-account__online-percents">{{$serverOnline['percent']}}%</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card__header card__header_bg-body">
                        <div class="card-table">
                            <div class="card-table__row">
                                <div class="card-table__col-6">
                                    <h5 class="card__title">@lang('account-page.skin.title')</h5>
                                    <h6 class="card__subtitle">@lang('account-page.skin.subtitle', ['size' => $skinSize])</h6>
                                </div>
                                <div class="card-table__col-6 section-account__skin-buttons">
                                    <a href="#" class="btn btn_outline btn_sm section-account__skin-button">@lang('account-page.skin.buttons.upload')</a>
                                    <a href="#" class="btn btn_outline btn_sm section-account__skin-button">@lang('account-page.skin.buttons.reset')</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card__body">
                        <div class="section-account__skin-models">
                            <img src="{{asset('img/skin-model.svg')}}" alt="mine Skin" class="section-account__skin-model">
                            <img src="{{asset('img/skin-model.svg')}}" alt="mine Skin" class="section-account__skin-model">
                            <img src="{{asset('img/skin-model-side.svg')}}" alt="mine Skin" class="section-account__skin-model">
                            <img src="{{asset('img/skin-model-side.svg')}}" alt="mine Skin" class="section-account__skin-model">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
