<?php


namespace App\Http\Controllers\Account;


use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Models\GuardKey;
use App\Services\Auth\TwoFactor\TwoFactorManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class Guard2FAController extends Controller
{
    /*public function setup(TwoFactorManager $manager)
    {
        if ($this->user()->has2fa()) {
            return redirect()->route('account.home');
        }

        $secret = $manager->generateSecret();

        return $this->view('account.2fa.setup', [
            'secret' => $secret,
            'qr' => $manager->generateQR($this->user()->playername, $secret, 100)
        ]);
    }*/

    public function setup(TwoFactorManager $manager)
    {
        if ($this->user()->has2fa()) {
            abort(403);
        }

        $secret = $manager->generateSecret();

        return new JsonResponse([
            'secret' => $secret,
            'secret_word' => Str::separateWord($secret, 4),
            'qr' => $manager->generateQR($this->user()->playername, $secret, 100)
        ]);
    }

    /**
     * Установить двухфакторную авторизацию.
     *
     * @param Request $request
     * @param TwoFactorManager $manager
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function set(Request $request, TwoFactorManager $manager)
    {
        $this->validate($request, [
            'code' => 'required|string',
            'secret' => 'required|string'
        ]);

        if ($this->user()->has2fa()) {
            return redirect()->route('account.home');
        }

        $code = $request->post('code');
        $secret = $request->post('secret');
        if ($manager->checkCode($secret, $code)) {
            $user = $this->user();
            $user->g2fa_details = $secret;
            $user->save();

            GuardKey::regenerateKeys($user);

            $request->session()->put('2fa', true);

            return $this->redirectSuccess('account.settings', __('site.responses.success.2fa_set'));
        }

        return $this->backError(__('site.responses.errors.invalid_2fa_code'));
    }

    /*public function confirmDisable(Request $request)
    {
        return $this->view('windows.2fa-disable', [
            'needPassword' => true,
            'loggedByKey' => $request->session()->get('2fa.key', false)
        ]);
    }*/

    /**
     * Отключить двухфакторную авторизацию.
     * Если пользователь залогинился с одноразовым ключом, то проверки ключа не будет.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function disable(Request $request)
    {
        $loggedByKey = $request->session()->get('2fa.key', false);

        $user = $this->user();
        if (!$user->has2fa()) {
            return redirect()->route('account.home');
        }

        $this->validate($request, [
            'password' => 'required|string'
        ]);

        if (!Hash::check($request->post('password'), $user->password)) {
            return $this->backError(__('site.responses.errors.invalid_password'));
        }

        //если пользователь авторизован по ключу, то не просим гугл код
        if (!$loggedByKey) {
            if (!$this->check2FACode($request)) {
                return $this->backError(__('site.responses.errors.invalid_2fa_code'));
            }
        }

        $user->g2fa_details = null;
        $user->save();

        GuardKey::deleteAllFromUser($user);

        $request->session()->put('2fa.key', false);

        return $this->redirectSuccess('account.settings', __('site.responses.success.2fa_removed'));
    }

    /**
     * Вывести список одноразовых ключей.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function keys()
    {
        $user = $this->user();
        if (!$user->has2fa()) {
            return redirect()->route('account.home');
        }

        return $this->view('account.2fa.keys', [
            'keys' => $user->guardKeys
        ]);
    }

    /**
     * Скачать одноразовые ключи.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function downloadKeys()
    {
        $user = $this->user();
        if (!$user->has2fa()) {
            return redirect()->route('account.home');
        }

        $keys = $user->guardKeys->map(function (GuardKey $guardKey) {
            return $guardKey->key;
        })->toArray();

        if (empty($keys)) {
            return $this->backError(__('site.responses.errors.not_guard_keys'));
        }

		$content = "2FA BACKUP CODES FOR mine NETWORK

##################################
DO NOT GIVE BACKUP KEYS TO ANYONE!
##################################\n
";
        $content .= implode("\n", $keys);
        $content .= "\n\nUse backup codes only if you cannot use codes generated with Google Authentificator.";
        $filename = 'keys.txt';

        $headers = [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            'Content-Length' => Str::length($content)
        ];

        return response($content, 200, $headers);
    }
}
