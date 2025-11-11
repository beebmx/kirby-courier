<?php

use Kirby\Cms\App;

return [
    'system.loadPlugins:after' => function () {
        if (App::instance()->option('beebmx.kirby-blade.bootstrap', false) && class_exists('Beebmx\\KirbyBlade\\Facades\\Blade')) {
            \Beebmx\KirbyBlade\Facades\Blade::viewPath(dirname(__DIR__).'/templates');
            \Beebmx\KirbyBlade\Facades\Blade::anonymousComponentPath(dirname(__DIR__).'/components/html', 'courier');
        }
    },
];
