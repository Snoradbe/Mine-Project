<?php

return [
	'title' => 'Minecraft Servers Status',
	'subtitle' => 'Realtime statistics updating every second',
	'report' => 'Report Problem',
	'dashboard' => 'Dashboard',
	'primary_services' => 'Primary Services',
	'proxy_gateway' => 'Proxy Gateway',
	'normal_operating' => 'Normal Operating',
	'secondary_services' => 'Secondary Services',
	'not_responding' => 'Not Responding',
	'game_servers' => 'Game Servers',
	'degraded_performance' => 'Degraded Performance',
	'too_low_performance' => 'Too Low Performance',
	'offline' => 'Offline',
	'under_maintenance' => 'Under Maintenance',
	'players_online' => 'Players Online',
	'service_servers' => 'Service Servers',
	'tps_performance' => 'TPS Performance',
	'avg' => 'AVG',
	'uptime' => 'UPTIME',
	'online' => 'Online',
	'core' => 'Core',

    //аптайм
    'd' => 'd',
    'h' => 'h',
    'm' => 'm',
    's' => 's',

    'reports' => [
        'modal' => [
            'title' => 'Report Problem',
            'subtitle' => 'Something went wrong? Let us know!',
            'pick_server' => 'PICK SERVER',
            'pick_type' => 'PICK REASON',
            'btns' => [
                'cancel' => 'Cancel',
                'send' => 'Send'
            ]
        ],

        'responses' => [
            'cooldown' => 'Отправлять репорт можно 1 раз в :cooldown сек.',
            'ok' => 'Репорт отправлен'
        ],

        'types' => [
            'cant_connect' => 'I can’t connect to the server',
            'server_unavailable' => 'Server unavailable',
            'low_performance' => 'Lagging, glitching, low perfomance',
            'unreliable' => 'Unreliable connection',
            'other' => 'Other',
        ],
    ]
];
