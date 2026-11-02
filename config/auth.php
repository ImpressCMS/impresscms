<?php

declare(strict_types=1);

/*
 * The ImpressCMS user is the Laravel user (design L11): a request guard over
 * icms::$user. Login, logout and autologin stay in ImpressCMS, so there is no
 * user provider and no password broker.
 */
return [
    'defaults' => [
        'guard' => 'icms',
    ],

    'guards' => [
        'icms' => [
            'driver' => 'icms',
        ],
    ],
];
