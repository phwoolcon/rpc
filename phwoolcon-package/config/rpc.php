<?php

use Phwoolcon\Rpc\Procedures\Hello;

return [
    'services' => [
        'hello-world' => [
            'listen' => 'ws://0.0.0.0:2080/hello',
            'front-config' => [
                'url' => '',
                'debug' => false,
            ],
            'procedures' => [
                'hello' => [
                    'instance' => Hello::class,
                ],
            ],
        ],
    ],
];
