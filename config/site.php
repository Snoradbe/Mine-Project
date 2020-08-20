<?php

return [

    'domain' => env('DOMAIN', 'mine.org'),

    //включен/отключен сайт
    'enabled' => true,

    //папка public
    'public_path' => 'public',

    //ip для подключения к игре
    'play_ip' => 'play.mine.org',

    //группы
    'groups' => [
        [
            'name' => 'default',
            'rank' => 0,
            'is_default' => true
        ],
        [
            'name' => 'vip',
            'rank' => 1,
        ],
        [
            'name' => 'premium',
            'rank' => 2,
        ],
        [
            'name' => 'builder',
            'screen_name' => 'Builder',
            'rank' => 10,
            'is_admin' => true
        ],
        [
            'name' => 'builder_lead',
            'screen_name' => 'Builder Team Lead',
            'rank' => 15,
            'is_admin' => true
        ],
        [
            'name' => 'moderator',
            'screen_name' => 'Moderator',
            'rank' => 20,
            'is_admin' => true
        ],
        [
            'name' => 'moderator_lead',
            'screen_name' => 'Moderator Team Lead',
            'rank' => 25,
            'is_admin' => true
        ],
        [
            'name' => 'tester',
            'screen_name' => 'Tester',
            'rank' => 30,
            'is_admin' => true
        ],
        [
            'name' => 'tester_lead',
            'screen_name' => 'Tester Team Lead',
            'rank' => 35,
            'is_admin' => true
        ],
        [
            'name' => 'dev',
            'screen_name' => 'Developer',
            'rank' => 40,
            'is_admin' => true
        ],
        [
            'name' => 'dev_lead',
            'screen_name' => 'Developer Team Lead',
            'rank' => 45,
            'is_admin' => true
        ],
        [
            'name' => 'managers',
            'screen_name' => 'Project Manager',
            'rank' => \App\Services\Groups\Group::ADMIN_RANK,
            'is_admin' => true
        ],
    ],

    //двухфакторная авторизация
    'two_factor' => [
        //драйвер
        //по-умолчанию: google_auth
        'driver' => 'google_auth',

        //количество запасных ключей
        'count_keys' => 10,
    ],

    'skin' => [
        //максимальный вес файла в мегабайтах
        'size' => 2,
    ],

    //мониторинг серверов
    'status' => [
        //список типов репортов
        //из lang.status.reports.types
        'report_types' => [
            'cant_connect',
            'server_unavailable',
            'low_performance',
            'unreliable',
            'other',
        ],

        //через сколько сек. можно создавать новый репорт
        'report_cooldown' => 60,
    ],

    //магазин
    'store' => [
        'distributors' => [
            \App\Services\Store\Distributors\ItemsDistributor::class,
            \App\Services\Store\Distributors\GroupsDistributor::class,
            \App\Services\Store\Distributors\CoinsDistributor::class,
        ],

        //методы оплаты
        'payers' => [
            \App\Services\Store\Purchasing\Payers\UnitpayPayer::class
        ],

        //основной метод оплаты
        'payer' => \App\Services\Store\Purchasing\Payers\UnitpayPayer::class,

        //настройки платежей
        'payment' => [
            'unitpay' => [
                //id кассы
                'id' => '123',
                //секретный ключ
                'secret' => 'fdsfsd'
            ]
        ],

		//отключенные серверы перков (для отображения)
		'disabled_servers_perks' => [
			'Event'
		],

        //сколько товаров на 1 странице
        'limit' => 12,

        //сколько транзакций можно совершать в час
        'count_transactions' => 1,
    ],
	
	'players_statistics' => [
		'limit' => 100,
	],

];
