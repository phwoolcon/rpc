<?php

use Phwoolcon\Rpc\Methods\Hello;

return [
    'services' => [
        'hello-world' => [
            'listen' => 'ws://0.0.0.0:2080/hello',
            'procedures' => [
                'hello' => [
                    'instance' => Hello::class,
                ],
            ],
        ],
    ],
];
