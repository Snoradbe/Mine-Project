<?php


namespace App\Http\Controllers;


use App\Models\PlayTime;
use App\Models\PlaytimeRating;
use App\Models\Server;
use App\Services\Avatar;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PlayersStatisticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if (!empty($name = trim($request->get('name', '')))) {
            return $this->search($name);
        }

        return $this->all();
    }

    private function all()
    {
        $limit = config('site.players_statistics.limit', 100);
        $servers = Server::getAllFromCache();

        [$accountTime, $prevRank, $nextRank] = $this->getAccountAndNearRanks();

        $topTotalOnline = $this->modifyRows(
            PlaytimeRating::with('playtime')->oldest('global')->take($limit)->get()
        );
        $topServersOnline = [];
        foreach ($servers as $server)
        {
            $topServersOnline[$server->name] = $this->modifyRows(
                PlaytimeRating::with('playtime')->oldest($server->name)->take($limit)->get(),
                $server->name
            );
        }

        return $this->view('home.players-statistics.index', [
            'user' => $this->user(),
            'servers' => $servers,
            'accountTime' => $accountTime,
            'nearRanks' => [
                $prevRank, $nextRank
            ],
            'topTotals' => $topTotalOnline,
            'topServers' => $topServersOnline,
            'search' => false
        ]);
    }

    private function search(string $name)
    {
        $servers = Server::getAllFromCache();

        [$accountTime, $prevRank, $nextRank] = $this->getAccountAndNearRanks();

        $topTotalOnline = $this->modifyRows(
            PlaytimeRating::with('playtime')->byUser($name)->get()
        );

        $topServersOnline = [];
        foreach ($servers as $server)
        {
            $topServersOnline[$server->name] = $this->modifyRows(
                PlaytimeRating::with('playtime')->byUser($name)->get(),
                $server->name
            );
        }

        return $this->view('home.players-statistics.index', [
            'user' => $this->user(),
            'servers' => $servers,
            'accountTime' => $accountTime,
            'nearRanks' => [
                $prevRank, $nextRank
            ],
            'topTotals' => $topTotalOnline,
            'topServers' => $topServersOnline,
            'search' => $name
        ]);
    }

    private function getAccountAndNearRanks(): array
    {
        $accountTime = $this->modifyRows(
            PlaytimeRating::with('playtime')->byUser($this->user()->playername)->get()
        )->first();

        $nearRanks = $this->modifyRows(
            PlaytimeRating::with('playtime')->nearRanks(null, $accountTime->rank)->get()
        );

        $prevRank = $nextRank = null;
        if (!$nearRanks->isEmpty()) {
            if ($nearRanks[0]->rank < $accountTime->rank) {
                $prevRank = $nearRanks[0];
            } else {
                $nextRank = $nearRanks[0];
            }

            if (isset($nearRanks[1])) {
                $nextRank = $nearRanks[1];
            }
        }

        return [$accountTime, $prevRank, $nextRank];
    }

    private function modifyRows(Collection $rows, ?string $server = null): Collection
    {
        foreach ($rows as $row)
        {
            $row->avatar = Avatar::get($row->username);
            $row->total = floor($this->getTotalOnline($row->playtime, $server) / 3600);
            $row->rank = is_null($server) ? $row->global : $row->$server;
        }

        return $rows;
    }

    private function getTotalOnline(PlayTime $playTime, ?string $server = null): int
    {
        $online = 0;
        if (is_null($server)) {
            foreach ($playTime->getAttributes() as $key => $attribute)
            {
                if ($key != 'username') {
                    $online += $attribute;
                }
            }
        } else {
            $online = $playTime->$server;
        }

        return $online;
    }
}
