<?php

use Beebmx\KirbyCourier\Mail\Message as Mail;
use Beebmx\KirbyCourier\Notification\Message as Notification;

if (! function_exists('courier')) {
    function courier(string $type): Mail|Notification|null
    {
        return match ($type) {
            'notification' => new Notification,
            'mail' => new Mail,
            default => null
        };
    }
}
