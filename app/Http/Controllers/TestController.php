<?php


namespace App\Http\Controllers;


use App\Models\LuckPerms\Player;
use App\Models\News;
use App\Models\PlayTime;
use App\Models\User;
use App\Services\Groups\GroupsManager;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function a()
    {
        $redis = Redis::connection();
        $redis->publish('test1', 'm1' . rand(1, 444));
        dd($redis->keys('*'), $redis->get('test1'), $redis->hgetall('Server'));
        /*(new News([
            'image_urls' => [
                [
                    'type' => 'm',
                    'url' => 'http://fff'
                ]
            ],
            'title' => 'title',
            'content' => 'content',
            'url' => 'http://vk.com/mine....'
        ]))->save();*/

        dd(URL::route('home', [], false));
        dd(News::all()->first()->image_urls);
    }

    public function b()
    {
        dd(home_route('login'), route('login'));
    }
}
