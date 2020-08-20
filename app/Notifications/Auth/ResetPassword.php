<?php


namespace App\Notifications\Auth;


use App\Helpers\Str;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPassword extends BaseResetPassword
{
    /**
     * @inheritDoc
     */
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $count = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->greeting(Lang::get('mails.greeting', ['player' => $notifiable->playername]))
            ->subject(Lang::get('mails.password.reset.subject'))
            ->line(Lang::get('mails.password.reset.text_1'))
            ->action(Lang::get('mails.password.reset.button'), url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line(Lang::get('mails.password.reset.text_2', ['count' => $count, 'minutes' => Str::declensionNumber($count, ...Lang::get('words.time.minutes'))]))
            ->line(Lang::get('mails.password.reset.text_3'));
    }
}
