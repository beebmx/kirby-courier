<?php

use Beebmx\KirbyCourier\CourierChallenge;
use Kirby\Auth\Challenge\EmailChallenge;
use Kirby\Auth\Pending;
use Kirby\Cms\App;
use Kirby\Cms\User;
use Kirby\Email\Email;

beforeEach(function () {
    Email::$debug = true;
    Email::$emails = [];

    App(users: [
        'email' => 'john@doe.co',
    ]);

    $this->kirby = App::instance();
    $this->user = $this->kirby->user('john@doe.co');
});

function challenge(User $user, string $mode = 'login', int $timeout = 438): CourierChallenge
{
    return new CourierChallenge($user, 'courier', $mode, $timeout);
}

function sentCode(): string
{
    preg_match('/\d{3} \d{3}/', Email::$emails[0]->body()->text(), $matches);

    return $matches[0];
}

it('extends the core email challenge', function () {
    expect(challenge($this->user))
        ->toBeInstanceOf(EmailChallenge::class)
        ->type()->toBe('courier')
        ->label()->toBe((new EmailChallenge($this->user, 'email', 'login', 438))->label());
});

it('is registered as an auth challenge', function () {
    expect($this->kirby->extensions('authChallenges'))
        ->toHaveKey('courier', CourierChallenge::class);
});

it('challenge is available', function () {
    expect(challenge($this->user, 'login')->isAvailable())
        ->toBeTrue()
        ->and(challenge($this->user, 'password-reset')->isAvailable())
        ->toBeTrue();
});

it('verify challenge', function () {
    $challenge = challenge($this->user);

    expect($challenge->verify('123 456', new Pending))
        ->toBeFalse()
        ->and($challenge->verify('123 456', new Pending(secret: 'test')))
        ->toBeFalse();

    $pending = new Pending(secret: password_hash('123456', PASSWORD_DEFAULT));

    expect($challenge->verify('123456', $pending))
        ->toBeTrue()
        ->and($challenge->verify('12 34 56', $pending))
        ->toBeTrue()
        ->and($challenge->verify(123456, $pending))
        ->toBeTrue()
        ->and($challenge->verify('654321', $pending))
        ->toBeFalse();
});

it('creates a login code', function () {
    $pending = challenge($this->user, 'login')->create();
    $code = sentCode();

    expect($pending)
        ->toBeInstanceOf(Pending::class)
        ->and(challenge($this->user)->verify($code, $pending))
        ->toBeTrue()
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

    Email::$emails = [];
    challenge($this->user, 'login')->create();

    expect(sentCode())
        ->not->toEqual($code);
});

it('creates a pasword-reset code', function () {
    $pending = challenge($this->user, 'password-reset')->create();
    $code = sentCode();

    expect(challenge($this->user)->verify($code, $pending))
        ->toBeTrue()
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
});

it('uses the login messages for two factor authentication', function () {
    challenge($this->user, '2fa')->create();

    expect(Email::$emails[0])
        ->subject()->toEqual('Your login code')
        ->and(Email::$emails[0]->body()->html())
        ->toContain('login code');
});

it('can customize the messages per mode', function () {
    App(
        options: [
            'beebmx.courier.challenge.email.password-reset.before' => 'Custom before reset',
            'beebmx.courier.challenge.email.password-reset.after' => 'Custom after reset',
        ],
        users: ['email' => 'john@doe.co'],
    );

    challenge(App::instance()->user('john@doe.co'), 'password-reset')->create();

    expect(Email::$emails[0]->body()->html())
        ->toContain('Custom before reset', 'Custom after reset');
});
