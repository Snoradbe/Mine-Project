<?php

namespace App\Http\Controllers\Account\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');
        $this->middleware('need.captcha')->only('sendResetLinkEmail');
    }

    /**
     * @inheritDoc
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->checkCaptcha($request);
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

    /**
     * @inheritDoc
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
		return $this->view('auth.passwords.email_verify', [
			'email' => $request->post('email')
		]);
        return redirect()->route('password.request')->with('status', trans($response));
    }

    /**
     * @inheritDoc
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return redirect()->route('password.request')
            ->withInput($request->only('email'))
            ->withErrors(['email' => trans($response)]);
    }
}
