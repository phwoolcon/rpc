<?php

use Phwoolcon\Rpc\Methods\Hello;

return [
    'services' => [
        'default' => [
            'listen' => 'ws://0.0.0.0:2080',
            'methods' => [
                Hello::class,
            ],
        ],
    ],
];
