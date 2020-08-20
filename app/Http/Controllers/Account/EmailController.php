<?php


namespace App\Http\Controllers\Account;


use App\Helpers\Str;
use App\Http\Controllers\Controller;
use App\Http\Requests\Account\Email\AssigmentRequest;
use App\Http\Requests\Account\Email\UpdateRequest;
use App\Models\EmailToken;
use App\Models\User;
use App\Notifications\Auth\ChangeEmail;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * Вывести форму для указания почты.
     *
     * @return \Illuminate\View\View
     */
    public function assigment()
    {
        return $this->view('account.email.assigment', [
            'user' => $this->user()
        ]);
    }

    /**
     * Установить почту и отправить письмо с подтверждением.
     * В случае успеха, показывает страницу с уведомлением.
     *
     * @param AssigmentRequest $request
     * @return \Illuminate\View\View
     */
    public function makeAssigment(AssigmentRequest $request)
    {
        $user = $this->user();

        $user->email = $request->post('email');
        $user->sendEmailVerificationNotification();

        return $this->view('account.email.assigment_verify', [
            'email' => $user->email
        ]);
    }

    /**
     * Вывести форму изменения пароля.
     *
     * @return \Illuminate\View\View
     */
    public function change()
    {
        return $this->view('account.email.change', [
            //
        ]);
    }

    /**
     * Отправить подтверждение на старую почту.
     *
     * @param UpdateRequest $request
     * @return \Illuminate\View\View
     */
    public function update(UpdateRequest $request)
    {
        $user = $this->user();

        $token = new EmailToken([
            'email' => $user->email,
            'token' => Str::random(8)
        ]);

        $user->notify(new ChangeEmail($request->post('email'), $token->token));
        $token->save();

        return $this->view('account.email.change_confirm', [
            'email' => $user->email
        ]);
    }

    /**
     * Проверить подтверждение старой почты и отправить подтверждение на новую почту.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function confirmDetach(Request $request)
    {
        $user = $this->user();

        if (! hash_equals((string) $request->route('id'), (string) $user->getKey())) {
            throw new AuthorizationException;
        }

        if (! hash_equals((string) $request->route('hash'), sha1( $user->id . $request->route('new') . $user->playername))) {
            throw new AuthorizationException;
        }

        $token = EmailToken::findByToken($request->route('token'))->firstOrFail();
        if ($token->email != $user->email) {
            throw new AuthenticationException;
        }

        $email = $request->route('new');
        if (!is_null(User::where('email', $email)->first())) {
            throw new AuthorizationException(__('validation.unique', ['attribute' => 'email']));
        }

        $user->email = $email;
        $user->sendEmailVerificationNotification();

        $token->delete();

        return $this->view('account.email.confirm_detach', [
            'email' => $user->email
        ]);
    }
}
