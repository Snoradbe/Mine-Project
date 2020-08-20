<?php

namespace App\Http\Controllers\Account\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\RedirectHome;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, RedirectHome;

    /**
     * Количество попыток входа.
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * Количество минут, которое будет невозможно войти в аккаунт, при неправильных попытках.
     *
     * @var int
     */
    protected $decayMinutes = 5;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('need.captcha')->only('login');
    }

    /**
     * @inheritDoc
     */
    public function username()
    {
        return 'playername';
    }

    /**
     * @inheritDoc
     */
    public function login(Request $request)
    {
        $this->checkCaptcha($request);
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            //если у пользователя включена двухфакторная авторизация,
            //то делаем пометку, что авторизация пройдена не до конца.
            if ($this->guard()->user()->has2fa()) {
                $request->session()->put('2fa', false);
            } else {
                $request->session()->put('2fa', true);
            }
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * @inheritDoc
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->loggedOut($request) ?: redirect($this->redirectTo());
    }

    /**
     * @inheritDoc
     */
    public function sendLockoutResponse(Request $request)
    {
        return $this->view('auth.too-many-attempts');
    }
}
