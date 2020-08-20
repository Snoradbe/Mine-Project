<?php

return [

    'error' => 'Whoops!',

    'greeting' => 'Hello, :player.',
    'regards' => 'Regards',
    'footer' => '© :year :app. All rights reserved.',

    'troubles' => "If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
        'into your web browser: [:displayableActionUrl](:actionURL)',

    'email' => [
        'verify' => [
            'subject' => 'Verify Email Address',
            'text_1' => 'Please click the button below to verify your email address.',
            'button' => 'Verify Email Address',
            'text_2' => 'If you did not create request an confirm account email, no further action is required.',
        ],
        'change' => [
            'subject' => 'Изменение почты',
            'text_1' => 'Вы получили это письмо, потому что мы получили запрос на изменение адреса почты аккаунта.',
            'text_2' => 'Новая почта: :new',
            'text_3' => 'При подтверждении запроса, мы вышлем письмо с подтверждением на новую почту.',
            'button' => 'Подтвердить изменение'
        ]
    ],

    'password' => [
        'reset' => [
            'subject' => 'Verify Password Reset',
            'text_1' => 'You are receiving this email because we received a password reset request for your account.',
            'button' => 'Reset Password',
            'text_2' => 'This password reset link will expire in :count :minutes.',
            'text_3' => 'If you did not request a password reset, no further action is required.',
        ],
    ],

];
