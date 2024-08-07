<?php

use Beebmx\KirbyCourier\Mail\Message as Mail;
use Beebmx\KirbyCourier\Notification\Message as Notification;

it('can return an instance of mail message')
    ->expect(courier('mail'))
    ->toBeInstanceOf(Mail::class);

it('can return an instance of notification message')
    ->expect(courier('notification'))
    ->toBeInstanceOf(Notification::class);

it('can return null if a courier message is invalid')
    ->expect(courier('other'))
    ->toBeNull();
