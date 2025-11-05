<?php

return [
    'logo' => null,
    'path' => 'courier',
    'from' => [
        'address' => 'hello@example.com',
        'name' => 'Example',
    ],
    'message' => [
        'greeting' => 'Hello!',
        'rights' => 'All rights reserved.',
        'salutation' => 'Regards',
        'subject' => 'Message from courier',
        'notify' => 'If you\'re having trouble clicking the button, copy and paste the URL below into your web browser',
        'brand_name' => null,
    ],
    'challenge' => [
        'theme' => 'default',
        'greeting' => null,
        'email' => [
            'login' => [
                'before' => null,
                'after' => null,
            ],
            'password-reset' => [
                'before' => null,
                'after' => null,
            ],
        ],
    ],
    'panel' => [
        'label' => 'Courier',
        'icon' => 'email',
    ],
];
