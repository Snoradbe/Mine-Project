<?php

namespace App\Http\Controllers;

use App\Lang;
use App\Models\GuardKey;
use App\Services\Auth\TwoFactor\TwoFactorManager;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Получить авторизованного пользователя.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|\App\Models\User|null
     */
    protected function user()
    {
        return Auth::user();
    }

    /**
     * Добавить сообщение к ответу.
     *
     * @param RedirectResponse $response
     * @param string $message
     * @return RedirectResponse
     */
    private function addSuccessMessage(RedirectResponse $response, string $message)
    {
        return $response->with('success_message', $message);
    }

    /**
     * Перенаправить назад с сообщением об успехе.
     *
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function backSuccess(string $message)
    {
        return $this->addSuccessMessage(back(), $message);
    }

    /**
     * Перенаправить на указанный маршрут с сообщением об успехе.
     *
     * @param string $routeName
     * @param string $message
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function redirectSuccess(string $routeName, string $message)
    {
        return $this->addSuccessMessage(redirect()->route($routeName), $message);
    }

    /**
     * Перенаправить назад с ошибкой.
     *
     * @param $error
     * @return RedirectResponse
     */
    protected function backError($error)
    {
        return back()->withErrors($error);
    }

    /**
     * Получить указанное представление с учетом языка или без.
     *
     * @param string $view
     * @param array $data
     * @param bool $lang
     * @return \Illuminate\View\View
     */
    protected function view(string $view, array $data = [], bool $lang = false)
    {
        if ($lang) {
            $view = Lang::locale() . '.' . $view;
        }

        return view($view, $data);
    }

    /**
     * Проверить код двухфакторной авторизации.
     *
     * @param Request $request
     * @return bool
     * @throws \Exception
     */
    protected function check2FACode(Request $request): bool
    {
        try {
            $this->validate($request, [
                'code' => 'required|string'
            ]);

            $guardKey = GuardKey::findKey($this->user(), $request->post('code'))->first();
            if (!is_null($guardKey)) {
                $guardKey->delete();

                if ($this->user()->guardKeys->isEmpty()) {
                    GuardKey::regenerateKeys($this->user());
                }

                return true;
            }

            return app(TwoFactorManager::class)->checkCode($this->user()->g2fa_details, $request->post('code'));
        } catch (ValidationException $e) {
            return false;
        }
    }

    /**
     * Проверить каптчу.
     *
     * @param Request $request
     * @throws ValidationException
     */
    protected function checkCaptcha(Request $request): void
    {
        $this->validate($request, [
            'g-recaptcha-response' => 'required|captcha'
        ]);
    }
}
