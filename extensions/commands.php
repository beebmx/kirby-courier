<?php

use Beebmx\KirbyCourier\Console\AliasMakeMailMessage;
use Beebmx\KirbyCourier\Console\MakeMailMessage;
use Beebmx\KirbyCourier\Console\MakeThemeMessage;

return [
    'make:courier' => [
        'description' => 'Make your own Courier mail',
        'args' => [
            'courier' => [
                'description' => 'The name of the courier mail message',
            ],
            'blade' => [
                'prefix' => 'b',
                'longPrefix' => 'blade',
                'description' => 'Create a courier mail message for Blade templates',
                'noValue' => true,
            ],
            'fake' => [
                'prefix' => 'f',
                'longPrefix' => 'fake',
                'description' => 'Fake the creation of a courier mail message',
                'noValue' => true,
            ],
        ],
        'command' => new MakeMailMessage,
    ], 'courier:make' => [
        'description' => 'Alias of make:courier',
        'args' => [
            'courier' => [
                'description' => 'The name of the courier mail message',
            ],
            'blade' => [
                'prefix' => 'b',
                'longPrefix' => 'blade',
                'description' => 'Create a courier mail message for Blade templates',
                'noValue' => true,
            ],
            'fake' => [
                'prefix' => 'f',
                'longPrefix' => 'fake',
                'description' => 'Fake the creation of a courier mail message',
                'noValue' => true,
            ],
        ],
        'command' => new AliasMakeMailMessage,
    ], 'courier:theme' => [
        'description' => 'Make your own Courier theme',
        'args' => [
            'theme' => [
                'description' => 'The name of the courier theme',
            ],
            'fake' => [
                'prefix' => 'f',
                'longPrefix' => 'fake',
                'description' => 'Fake the creation of a courier theme',
                'noValue' => true,
            ],
        ],
        'command' => new MakeThemeMessage,
    ],
];
