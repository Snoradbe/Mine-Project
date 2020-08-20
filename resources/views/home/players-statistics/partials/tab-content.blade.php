@if(!$search)
    <div class="players-statistics__places">
        @if(isset($rows[0]))
            <div class="players-statistics__place">
                <div class="players-statistics__ava players-statistics__place-ava" style="background-image:url('{{asset($rows[0]->avatar)}}');">
                    <span class="icon material-icons">star</span>
                </div>
                <div class="players-statistics__place-info">
                    <h4 class="players-statistics__place-playername">{{$rows[0]->username}}</h4>
                    <h5 class="players-statistics__place-position">@lang('players-statistics.places.1')</h5>
                    <div class="players-statistics__place-time">
                        <span class="icon material-icons">schedule</span>
                        {{$rows[0]->total}} {{\App\Helpers\Str::declensionNumber($rows[0]->total, ...__('words.time.hours'))}}
                    </div>
                </div>
            </div>
        @endif
        @if(isset($rows[1]))
            <div class="players-statistics__place">
                <div class="players-statistics__ava players-statistics__place-ava" style="background-image:url('{{asset($rows[1]->avatar)}}');">
                    <span class="icon material-icons">star</span>
                </div>
                <div class="players-statistics__place-info">
                    <h4 class="players-statistics__place-playername">{{$rows[1]->username}}</h4>
                    <h5 class="players-statistics__place-position">@lang('players-statistics.places.2')</h5>
                    <div class="players-statistics__place-time">
                        <span class="icon material-icons">schedule</span>
                        {{$rows[1]->total}} {{\App\Helpers\Str::declensionNumber($rows[1]->total, ...__('words.time.hours'))}}
                    </div>
                </div>
            </div>
        @endif
        @if(isset($rows[2]))
            <div class="players-statistics__place">
                <div class="players-statistics__ava players-statistics__place-ava" style="background-image:url('{{asset($rows[2]->avatar)}}');">
                    <span class="icon material-icons">star</span>
                </div>
                <div class="players-statistics__place-info">
                    <h4 class="players-statistics__place-playername">{{$rows[2]->username}}</h4>
                    <h5 class="players-statistics__place-position">@lang('players-statistics.places.3')</h5>
                    <div class="players-statistics__place-time">
                        <span class="icon material-icons">schedule</span>
                        {{$rows[2]->total}} {{\App\Helpers\Str::declensionNumber($rows[2]->total, ...__('words.time.hours'))}}
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="players-statistics__line"></div>
@endif
@if(!$search)
    <div class="players-statistics__table-block">
        <table class="players-statistics__table" cellspacing="0" cellpadding="0">
            @foreach($rows as $i => $row)
                <tr>
                    <td>#{{($i + 1)}}</td>
                    <td>
                        <div class="players-statistics__table-player">
                            <div class="players-statistics__ava" style="background-image:url('{{asset($row->avatar)}}');"></div> {{$row->username}}
                        </div>
                    </td>
                    <td></td>
                    <td class="players-statistics__colored">{{$row->total}} {{\App\Helpers\Str::declensionNumber($row->total, ...__('words.time.hours'))}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@else
    <div class="players-statistics__table-block">
        <table class="players-statistics__table" cellspacing="0" cellpadding="0">
            @foreach($rows as $row)
                <tr>
                    <td>#{{($row->rank)}}</td>
                    <td>
                        <div class="players-statistics__table-player">
                            <div class="players-statistics__ava" style="background-image:url('{{asset($row->avatar)}}');"></div> {{$row->username}}
                        </div>
                    </td>
                    <td></td>
                    <td class="players-statistics__colored">{{$row->total}} {{\App\Helpers\Str::declensionNumber($row->total, ...__('words.time.hours'))}}</td>
                </tr>
            @endforeach
        </table>
    </div>
@endif
