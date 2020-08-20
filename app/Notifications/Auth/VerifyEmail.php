<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class VerifyEmail extends BaseVerifyEmail
{
    /**
     * Имеется ли старая почта у аккаунта.
     *
     * @var bool
     */
    protected $hasOldEmail;

    /**
     * VerifyEmail constructor.
     *
     * @param bool $hasOldEmail
     */
    public function __construct(bool  $hasOldEmail)
    {
        $this->hasOldEmail = $hasOldEmail;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  \App\Models\User  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->greeting(Lang::get('mails.greeting', ['player' => $notifiable->playername]))
            ->subject(Lang::get('mails.email.verify.subject'))
            ->line(Lang::get('mails.email.verify.text_1'))
            ->action(Lang::get('mails.email.verify.button'), $verificationUrl)
            ->line(Lang::get('mails.email.verify.text_2'));
    }

    /**
     * Получить ссылку на подтверждение почты.
     *
     * @param \App\Models\User $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification() . $notifiable->playername),
                'email' => $notifiable->getEmailForVerification(),
                'old' => $this->hasOldEmail
            ]
        );
    }
}
