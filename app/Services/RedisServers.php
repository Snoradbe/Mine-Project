<?php


namespace App\Services;


use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class RedisServers
{
    private function __construct()
    {
    }

    public static function getServers(): array
    {
        return Cache::remember('redis_servers', 300, function () {
            $servers = Redis::keys('*');
            if (empty($servers) || !is_array($servers)) {
                return [];
            }

            return $servers;
        });
    }
}
