<?php


namespace App\Notifications\Auth;


use App\Helpers\Str;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;

class ChangeEmail extends Notification
{
    /**
     * Новая почта.
     *
     * @var string
     */
    protected $newEmail;

    /**
     * Токен.
     *
     * @var string
     */
    protected $token;

    /**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * ChangeEmail constructor.
     *
     * @param string $newEmail
     * @param string $token
     */
    public function __construct(string  $newEmail, string $token)
    {
        $this->newEmail = $newEmail;
        $this->token = $token;
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  \App\Models\User  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->greeting(Lang::get('mails.greeting', ['player' => $notifiable->playername]))
            ->subject(Lang::get('mails.email.change.subject'))
            ->line(Lang::get('mails.email.change.text_1'))
            ->line(Lang::get('mails.email.change.text_2', ['new' => $this->newEmail]))
            ->line(Lang::get('mails.email.change.text_3'))
            ->action(Lang::get('mails.email.change.button'), $link);
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
            'account.email.confirm',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1( $notifiable->getKey() . $this->newEmail . $notifiable->playername),
                'token' => $this->token,
                'new' => $this->newEmail
            ]
        );
    }
}
