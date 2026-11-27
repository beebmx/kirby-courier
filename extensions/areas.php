<?php

use Beebmx\KirbyCourier\Logo;
use Kirby\Cms\App;
use Kirby\Toolkit\A;

return [
    'courier' => function (App $kirby): array {
        if (! $kirby->user() || $kirby->option('beebmx.courier.panel') === false) {
            return [];
        }

        return [
            'label' => $kirby->option('beebmx.courier.panel.label', 'Courier'),
            'icon' => $kirby->option('beebmx.courier.panel.icon', 'email'),
            'menu' => true,
            'link' => 'courier',
            'views' => [[
                'pattern' => 'courier',
                'action' => fn () => [
                    'component' => 'k-courier-view',
                    'title' => $kirby->option('beebmx.courier.panel.label', 'Courier'),
                    'props' => [
                        'user' => $kirby->user()->toArray(),
                        // never expose credentials like the SMTP password to the Panel
                        'transport' => A::get(
                            (array) $kirby->option('email.transport', []),
                            ['type', 'host', 'port', 'security', 'auth', 'username']
                        ),
                        'plus' => $kirby->option('beebmx.email-plus.type'),
                        'courier' => array_merge(
                            ['logo' => Logo::url($kirby->option('beebmx.courier.logo'))],
                            ['from' => $kirby->option('beebmx.courier.from')],
                        ),
                    ],
                ],
            ]],
        ];
    },
];
