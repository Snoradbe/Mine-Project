<?php

return [

    'responses' => [
        'errors' => [
            'invalid_2fa_code' => 'Неправильный код',
            'not_guard_keys' => 'Нет ключей для скачивания',
            'invalid_password' => 'Неправильный пароль',
        ],
        'success' => [
            '2fa_set' => 'Двухфакторная авторизация включена',
            '2fa_removed' => 'Двухфакторная авторизация отключена',
            'playtime_reset' => 'Игровое время обнулено',
        ],
    ],



    'nav' => [
        'account' => [
            'logged' => 'You logged as',
            'login' => 'Log in',

            'admin' => 'Admin Panel',
            'my_account' => 'My Account',
            'perks' => 'Perks & Payments',
            'settings' => 'Settings',
            'logout' => 'Logout',
        ],

        'home' => 'Home',
        'store' => 'Store',
        'support' => 'Support',
        'our_servers' => 'Our Servers',
        'rules_and_guidelines' => 'Rules & Guidelines',

        'servers_status' => 'Servers Status',
        'wiki' => 'Wiki',
        'players_statistics' => 'Players Statistics',
        'our_staff' => 'Our Staff',
        'donate' => 'Donate',
        'discord' => 'Discord',
        'bug_tracker' => 'Bug Tracker',

        'for_developers' => 'For Developers',
        'recruitment' => 'Recruitment',
        'branding' => 'Branding',
        'other_projects' => 'Other Projects',
        'faq' => 'FAQ',
        'technical_support' => 'Technical Support',
        'meet_the_team' => 'Meet the Team',
    ],

    'footer' => [
        'title' => 'Get all the features with your personal account!',
        'subtitle' => 'Just join the server and get credentials to login.',
        'text' => 'Manage your account security, view personal game statistics, upload custom skins, use mine Store® and more!',

        'btn_login' => 'Account Login',
        'btn_support' => 'Get Support'
    ],

    'account' => [
        'login' => [
            'welcome' => 'Welcome back, we’ve glad to see you!',

            'form' => [
                'login' => 'Username',
                'password' => 'Password',
                'sign_in' => 'Sign in'
            ],

            'forgot' => 'Forgot password?',
            'restore' => 'Restore password',

            'too_many' => [
                'title' => 'Whoa! Slow Down',
                'text' => 'Your account has too many login attempts. We have temporarily restricted access to your account. Try this step in a few minutes.'
            ]
        ],

        'profile' => [
            '2fa' => [
                'true' => 'Enabled',
                'false' => 'Disabled'
            ]
        ],

        'settings' => [
            'id' => [
                'subtitle' => 'Account unique identificatior.',
                'your_id' => 'Your ID',
                'button' => 'Copy'
            ],
            'email' => [
                'subtitle' => 'Current email address.',
                'button' => 'Change'
            ],
            'password_update' => [
                'subtitle' => 'Last password update.',
                'button' => 'Change'
            ],
            '2fa' => [
                'enabled' => [
                    'title' => '2FA enabled',
                    'subtitle' => 'Two factor authentication status.',
                    'button' => 'Disable'
                ],
                'disabled' => [
                    'title' => '2FA disabled',
                    'subtitle' => 'Two-factor authentication is an additional way to secure your account. Needs Google Authentificator or other app.',
                    'button' => 'Setup'
                ],

                'keys' => [
                    'title' => 'Get Backup Keys',
                    'subtitle' => 'One-time access keys that can be used instead of the code generated with the Google Authenticator.',
                    'button' => 'Show',
                ]
            ],
            'playtime' => [
                'subtitle' => 'Your total playtime on our servers.',
                'button' => 'Reset',
            ],
            'language' => [
                'title' => 'Language',
                'subtitle' => 'Website interface language.',
                'button' => 'Reset',
                'langs' => [
                    'en' => 'English',
                    'ru' => 'Russian'
                ]
            ],
        ],

        'windows' => [
            'email' => [
                'assigment' => [
                    'title' => 'Email Assignment',
                    'subtitle' => 'Before you sign in to your account, you need to enter your email address.',

                    'form' => [
                        'email' => 'Email address',
                        'button' => 'Next'
                    ],

                    'verify' => [
                        'text' => 'We’ve sent the verification letter on your email. Check your Inbox and follow instructions in our letter.',
                        'sent' => 'Sent to'
                    ]
                ],
                'change' => [
                    'title' => 'Email Change',

                    'form' => [
                        'new' => 'New email address',
                        'repeat' => 'Repeat new email',
                        'button' => 'Change Email'
                    ],
                ],
                'change_confirm' => [
                    'title' => 'Подтверждение изменения почты',
                    'subtitle' => 'Мы отправили подтверждение на вашу старую почту. Откройте письмо и следуйте дальнейшим инструкциям.',
                    'sent' => 'Sent to'
                ],
                'confirm_detach' => [
                    'title' => 'Подтверждение новой почты',
                    'subtitle' => 'Мы отправили подтверждение на указанную почту. Откройте письмо и следуйте дальнейшим инструкциям.',
                    'sent' => 'Sent to'
                ],
                'confirm' => [
                    'title' => 'Need Verification',
                    'subtitle' => 'To access this feature or section, we must make sure that you are the owner of the account.',
                    'form' => [
                        'password' => 'Current password',
                        'button' => 'Enter'
                    ],
                ]
            ],
            '2fa' => [
                'confirm' => [
                    'title' => 'Two Factor Authentication',
                    'subtitle' => 'Enter code from Google Authenticator',

                    'form' => [
                        'code' => 'Enter code',
                        'button' => 'Sign in'
                    ],
                ],
                'setup' => [
                    'title' => '2FA Setup',
                    'attention' => 'ATTENTION! DO NOT GIVE KEYS OR QR-CODE TO ANYONE!',

                    'app' => [
                        'title' => 'DOWNLOAD AUTHENTIFICATOR APP',
                        'desc' => 'Download Google Authentificator app on your mobile device.',
                        'links' => [
                            'title' => 'LINKS',
                            'google' => 'Google Play',
                            'appstore' => 'AppStore',
                        ],
                    ],
                    'codes' => [
                        'title' => 'ADD CODE GENERATOR',
                        'desc' => 'Open Google Authentificator and scan this QR-code right. If you have troubles, you can try enter key below manually.',
                        'code' => '2FA CODE',
                    ],
                    'install' => [
                        'title' => 'FINISH SETUP',
                        'desc' => 'Enter the code from the Google Authentificator.',
                    ],

                    'form' => [
                        'button' => 'Activate'
                    ],
                ],
                'disable' => [
                    'title' => 'Disable 2FA',
                    'form' => [
                        'password' => 'Current password',
                        'code' => 'Code from Google Authenticator',
                        'button' => 'Disable 2FA',
                    ],
                ],
                'keys' => [
                    'title' => 'Get 2FA Backup keys',
                    'attention' => 'ATTENTION! DO NOT GIVE BACKUP KEYS TO ANYONE!',

                    'quest' => 'WHAT IS BACKUP CODES?',
                    'answ' => 'One-time access keys that can be used instead of the code generated with the Google Authenticator.',
                    'warning' => 'Use them only if you cannot use codes generated with Google Authentificator.',

                    'buttons' => [
                        'download' => 'Download',
                        'close' => 'Close'
                    ]
                ],
            ],
            'captcha' => [
                'title' => 'Are you washing machine?',
                'subtitle' => 'Enter captcha below',
            ],
            'password' => [
                'restore' => [
                    'title' => 'Restore Password',
                    'form' => [
                        'email' => 'Email',
                        'button' => 'Next'
                    ]
                ],
                'reset' => [
                    'title' => 'Reset Password',
                    'form' => [
                        'email' => 'Email',
                        'password' => 'New Password',
                        'password2' => 'Confirm New Password',
                        'button' => 'Next'
                    ]
                ],
                'change' => [
                    'title' => 'Change Password',
                    'form' => [
                        'current' => 'Current Password',
                        'new' => 'New Password',
                        'repeat' => 'Repeat new password',
                        'button' => 'Change Password'
                    ]
                ],
            ],
            'playtime_reset' => [
                'title' => 'Attention!',
                'text' => 'All your playing time on all servers will be reset. Are you sure you want to continue? This action cannot be undone.',
                'button' => 'Yes, reset my playtime',
            ],
        ]
    ],

];
