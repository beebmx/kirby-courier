<?php

use Beebmx\KirbyCourier\Actions\SendTestEmail;
use Kirby\Cms\App;

return fn (App $kirby) => [
    [
        'pattern' => 'courier/email/test',
        'method' => 'POST',
        'action' => fn () => (new SendTestEmail)($kirby),
    ],
];
