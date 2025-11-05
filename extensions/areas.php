<?php

use Kirby\Cms\App;

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
                        'transport' => $kirby->option('email.transport'),
                        'plus' => $kirby->option('beebmx.email-plus.type'),
                        'courier' => array_merge(
                            ['logo' => $kirby->option('beebmx.courier.logo')],
                            ['from' => $kirby->option('beebmx.courier.from')],
                        ),
                    ],
                ],
            ]],
        ];
    },
];
