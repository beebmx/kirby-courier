<?php

use Beebmx\KirbyCourier\CourierChallenge;
use Kirby\Cms\App;
use Kirby\Email\Email;

beforeEach(function () {
    Email::$debug = true;
    Email::$emails = [];

    App(users: [
        'email' => 'john@doe.co',
    ]);

    $this->kirby = App::instance();
});

it('challenge is available', function () {
    $user = $this->kirby->user('john@doe.co');

    expect(CourierChallenge::isAvailable($user, 'login'))
        ->toBeTrue();
});

it('verify challenge', function () {
    $user = $this->kirby->user('john@doe.co');

    expect(CourierChallenge::verify($user, '123 456'))
        ->toBeFalse();

    $this->kirby->session()->set('kirby.challenge.code', 'test');

    expect(CourierChallenge::verify($user, '123 456'))
        ->toBeFalse();

    $this->kirby->session()->set('kirby.challenge.code', password_hash('123456', PASSWORD_DEFAULT));

    expect(CourierChallenge::verify($user, '123456'))
        ->toBeTrue()
        ->and(CourierChallenge::verify($user, '12 34 56'))
        ->toBeTrue()
        ->and(CourierChallenge::verify($user, '654321'))
        ->toBeFalse();
});

it('creates a login code', function () {
    $user = $this->kirby->user('john@doe.co');
    $options = ['mode' => 'login', 'timeout' => 7.3 * 60];

    $code = CourierChallenge::create($user, $options);

    expect($code)
        ->toBeString()
        ->toMatch('/^\d{6}$/')
        ->and(Email::$emails)
        ->toHaveCount(1)
        ->and(Email::$emails[0])
        ->from()->toEqual('noreply@example.com')
        ->fromName()->toEqual('Site Title')
        ->to()->toEqual(['john@doe.co' => ''])
        ->subject()->toEqual('Your login code')
        ->and(Email::$emails[0]->body()->html())
        ->toContain('code-content')
        ->toContain('login code')
        ->toContain('john@doe.co')
        ->toContain('7 minutes')
        ->toContain($code);

    $other = CourierChallenge::create($user, $options);

    expect($code)
        ->not->toEqual($other);
});

it('creates a pasword-reset code', function () {
    $user = $this->kirby->user('john@doe.co');
    $options = ['mode' => 'password-reset', 'timeout' => 7.3 * 60];

    $code = CourierChallenge::create($user, $options);

    expect($code)
        ->toBeString()
        ->toMatch('/^\d{6}$/')
        ->and(Email::$emails)
        ->toHaveCount(1)
        ->and(Email::$emails[0])
        ->from()->toEqual('noreply@example.com')
        ->fromName()->toEqual('Site Title')
        ->to()->toEqual(['john@doe.co' => ''])
        ->subject()->toEqual('Your password reset code')
        ->and(Email::$emails[0]->body()->html())
        ->toContain('code-content')
        ->toContain('password reset code')
        ->toContain('john@doe.co')
        ->toContain('7 minutes')
        ->toContain($code);

    $other = CourierChallenge::create($user, $options);

    expect($code)
        ->not->toEqual($other);
});
