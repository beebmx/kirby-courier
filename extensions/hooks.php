<?php

use Beebmx\KirbyCourier\Blade;
use Kirby\Cms\App;

return [
    'system.loadPlugins:after' => function () {
        // Blade is optional: it's only set up when beebmx/kirby-blade is installed and enabled
        if (Blade::isEnabled($kirby = App::instance())) {
            Blade::boot($kirby);
        }
    },
];
