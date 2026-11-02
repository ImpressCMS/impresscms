<?php

declare(strict_types=1);

/*
 * The ImpressCMS database, owned by Laravel and shared with the legacy layer
 * (design L7). Host, name, credentials, charset and prefix come from
 * mainfile.php when the connection is first opened (IcmsServiceProvider).
 */
return [
    'default' => 'icms',

    'connections' => [
        'icms' => [
            'driver' => 'mysql',
            // the attributes ImpressCMS' own connection has (PDO's defaults):
            // emulated prepares, so values come back as strings
            'options' => [
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_STRINGIFY_FETCHES => false,
            ],
            // leave the session as legacy expects it: no `strict`, `modes`,
            // `timezone`, `isolation_level` or `charset` (which would send SET
            // statements); the charset goes into the DSN instead
            'use_db_after_connecting' => false,
        ],
    ],
];
