<?php


namespace App\Http\Controllers\Account\Auth;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    /**
     * Вывести форму изменения пароля.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return $this->view('auth.passwords.change');
    }

    /**
     * Изменить пароль.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function change(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'password' => 'required|string|confirmed|my_password|min:6'
        ]);

        $user = $this->user();
        if (!Hash::check($request->post('current_password'), $user->password)) {
            return $this->backError(__('auth.failed'));
        }

        $user->password = Hash::make($request->post('password'));
        $user->save();

        Auth::logout();

        return $this->redirectSuccess('login', __('site.responses.success.password_changed'));
    }
}
