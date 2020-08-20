<?php

namespace App\Http\Controllers;

use App\Models\LuckPerms\PlayerPermission;
use App\Models\News;
use App\Models\PlayTime;
use App\Models\Server;
use App\Models\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $news = News::latest('id')->take(3)->get();

        $countPlayers = User::count();

        return $this->view('home', [
            'servers' => Server::onlyGame()->get(),
            'news' => $news,
            'countAdmins' => PlayerPermission::getCountAdmins(),
            'countPlayers' => $countPlayers,
            'totalOnline' => round(app(PlayTime::class)->getAllTotalOnline() / 3600, 0)
        ]);
    }

    /**
     * Перенаправить пользователя на страницу авторизации.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectLogin()
    {
        return redirect()->route('login');
    }
}
