<?php

use Kirby\Cms\App;
use Kirby\Cms\File;
use Kirby\Content\Field;
use Kirby\Filesystem\Asset;

return [
    'courier' => function (App $kirby): array {
        if (! $kirby->user() || $kirby->option('beebmx.courier.panel') === false) {
            return [];
        }

        $logo = match (true) {
            is_string($kirby->option('beebmx.courier.logo')) => $kirby->option('beebmx.courier.logo'),
            is_callable($kirby->option('beebmx.courier.logo')) && $kirby->option('beebmx.courier.logo')() instanceof Asset => $kirby->option('beebmx.courier.logo')()->url(),
            is_callable($kirby->option('beebmx.courier.logo')) && $kirby->option('beebmx.courier.logo')() instanceof File => $kirby->option('beebmx.courier.logo')()->url(),
            is_callable($kirby->option('beebmx.courier.logo')) && $kirby->option('beebmx.courier.logo')() instanceof Field => $kirby->option('beebmx.courier.logo')()->toFile()?->url(),
            default => null,
        };

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
                            ['logo' => $logo],
                            ['from' => $kirby->option('beebmx.courier.from')],
                        ),
                    ],
                ],
            ]],
        ];
    },
];
