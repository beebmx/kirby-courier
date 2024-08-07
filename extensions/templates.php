<?php

use Kirby\Cms\App;

if (App::instance()->option('beebmx.kirby-blade.bootstrap', false) && class_exists('Beebmx\\KirbyBlade\\Facades\\Blade')) {
    return [
        'courier/mail' => dirname(__DIR__).'/templates/courier/mail.blade.php',
        'courier/notification' => dirname(__DIR__).'/templates/courier/notification.blade.php',
    ];
}

return [
    'courier/mail' => dirname(__DIR__).'/templates/courier/mail.php',
    'courier/notification' => dirname(__DIR__).'/templates/courier/notification.php',
];
