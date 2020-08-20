<?php


namespace App\Http\Controllers\Account\Auth;


use App\Http\Controllers\Controller;
use App\Models\GuardKey;
use App\Services\Auth\TwoFactor\TwoFactorManager;
use Illuminate\Http\Request;

class Guard2FAController extends Controller
{
    /**
     * Guard2FAController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Вывести форму подтверждения кода.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function confirm()
    {
        if (!$this->user()->has2fa()) {
            return redirect()->route('account.home');
        }

        return $this->view('auth.2fa', [
            'user' => $this->user()
        ]);
    }

    /**
     * Авторизовать пользователя, если код правильный.
     *
     * @param Request $request
     * @param TwoFactorManager $manager
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function auth(Request $request, TwoFactorManager $manager)
    {
		$this->validate($request, [
			'code' => 'required|string'
		]);
		
        if (!$this->user()->has2fa()) {
            return redirect()->route('account.home');
        }

        $code = $request->post('code');
        $guardKey = GuardKey::findKey($this->user(), $code)->first();
        if (!is_null($guardKey)) {
            $guardKey->delete();
            $request->session()->put('2fa', true);

            //делаем пометку, что пользователь авторизовался с помощью секретного ключа
            $request->session()->put('2fa.key', true);

            return redirect()->route('home');
        }

        if ($manager->checkCode($this->user()->g2fa_details, $code)) {
            $request->session()->put('2fa', true);

            return redirect()->route('home');
        }

        return $this->backError(__('site.responses.errors.invalid_2fa_code'));
    }
}
