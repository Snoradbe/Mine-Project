<?php


namespace App\Http\Controllers\Account;


use App\Http\Controllers\Controller;
use App\Lang;
use App\Models\PlaytimeRating;
use App\Models\Server;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Вывести страницу с данными пользователя.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = $this->user();
        $playtime = $user->playtime;
        $totalOnline = $playtime->getTotalOnline();
        $onlineRankPosition = PlaytimeRating::byUser($user->playername)->first()->global;

        $precision = 0; //количество чисел после запятой при округлении
		$hundred = 80; //количество % от 100% с учетом вычета ширины числа с процентами
        $servers = Server::getAllFromCache();
        $serversOnline = [];
        foreach ($servers as $server)
        {
            $serverOnline = $playtime->getOnlineOnServer($server->name);
            $serversOnline[$server->name] = [
                'online' => $serverOnline == 0 ? 0 : round($serverOnline / 3600, $precision),
                'percent_width' => $totalOnline == 0 ? 0 : round(($serverOnline / $totalOnline) * $hundred, $precision),
                'percent' => $totalOnline == 0 ? 0 : round(($serverOnline / $totalOnline) * 100, $precision)
            ];
        }

        $serversOnline = collect($serversOnline)->sort(function ($serv1, $serv2) {
            return $serv1['percent'] < $serv2['percent'];
        });

        $totalOnline = round($totalOnline / 3600, $precision);

        $primaryGroup = $user->getPrimaryGroup(true);
        $expiresGroup = $primaryGroup->getGroup()->isDefault() ? 0 : $primaryGroup->getExpiry() - time();
        if ($expiresGroup < 0) {
            $expiresGroup = 0;
        }
        $expiresGroup = floor($expiresGroup / 86400);
        $primaryGroup = $primaryGroup->getGroup();
        $staffGroup = optional($user->getStaffGroup())->getGroup();
        $skinSize = config('site.skin.size', 2);

        return $this->view(
            'account.index',
            compact(
                'user',
                'playtime', 'totalOnline', 'onlineRankPosition', 'serversOnline',
                'primaryGroup',
                'expiresGroup',
                'staffGroup',
                'skinSize'
            )
        );
    }

    /**
     * Вывести страницу с настройками игрока.
     *
     * @return \Illuminate\View\View
     */
    public function settings(Request $request)
    {
        $user = $this->user();
        $totalOnline = $user->playtime->getTotalOnline();

        return $this->view('account.settings', [
            'user' => $user,
            'userLang' => $request->cookie('lang', Lang::DEFAULT),
            'totalOnline' => $totalOnline === 0 ? 0 : floor($totalOnline / 3600),
            'keys' => $user->guardKeys,
            'loggedByKey' => $request->session()->get('2fa.key', false)
        ]);
    }

    /**
     * Вывести форму подтверждения обнуления плейтайма.
     *
     * @return \Illuminate\View\View
     */
    public function resetPlayTimeConfirmation()
    {
        return $this->view('windows.playtime-reset');
    }

    /**
     * Обнулить плейтайм.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPlayTime()
    {
        $this->user()->playtime
            ->resetPlayTime()
            ->update();

        return $this->redirectSuccess('account.settings', __('site.responses.success.playtime_reset'));
    }

    /**
     * Установить язык.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setLang(Request $request)
    {
        try {
            $this->validate($request, [
                'lang' => 'required|string|in:' . implode(',', Lang::ALLOWED_LANGS)
            ]);

            $lang = $request->post('lang');
            Cookie::queue(Cookie::make('lang', $lang));
        } catch (ValidationException $exception) {
        }

        return redirect('/');
    }
}
