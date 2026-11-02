<?php

declare(strict_types=1);

/*
 * No queues in this phase; Laravel's default `database` connection would use
 * the ImpressCMS database.
 */
return [
    'default' => env('QUEUE_CONNECTION', 'sync'),
];
