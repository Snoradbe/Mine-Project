<?php


namespace App\Console\Cron;


use App\Models\PlayTime;
use App\Models\PlaytimeRating;
use App\Models\Server;
use Illuminate\Console\Command;

class PlaytimeRatingCron extends Command
{
    protected $signature = 'cron:playtime:rating';

    public function handle()
    {
        PlaytimeRating::truncate();

        $playtime = new PlayTime();
        $servers = Server::getAllFromCache(true)->map(function (Server $server) {
            return $server->name;
        })->toArray();
        $users = [];
        $globalRatings = $playtime->getRatingUsers();

        foreach ($globalRatings as $i => $globalRating)
        {
            $users[$globalRating->username] = [
                'username' => $globalRating->username,
                'global' => $i + 1
            ];
        }

        foreach ($servers as $server)
        {
            $serverRatings = $playtime->getRatingUsers($server);

            foreach ($serverRatings as $i => $serverRating)
            {
                $users[$serverRating->username][$server] = $i + 1;
                $users[$serverRating->username]['username'] = $serverRating->username;
            }
        }

        PlaytimeRating::insert($users);
    }
}
