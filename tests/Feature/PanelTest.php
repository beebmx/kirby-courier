<?php

use Beebmx\KirbyCourier\Actions\SendTestEmail;
use Kirby\Cms\App;
use Kirby\Email\Email;
use Kirby\Exception\PermissionException;

beforeEach(function () {
    Email::$debug = true;
    Email::$emails = [];
});

function courierView(App $kirby): array
{
    $area = (require dirname(__DIR__, 2).'/extensions/areas.php')['courier']($kirby);

    return $area['views'][0]['action']();
}

describe('area', function () {
    it('is not available for guests', function () {
        $area = (require dirname(__DIR__, 2).'/extensions/areas.php')['courier'](App());

        expect($area)->toBe([]);
    });

    it('can be disabled', function () {
        $kirby = App(options: ['beebmx.courier.panel' => false], users: ['email' => 'john@doe.co', 'role' => 'admin']);
        $kirby->impersonate('john@doe.co');

        expect((require dirname(__DIR__, 2).'/extensions/areas.php')['courier']($kirby))->toBe([]);
    });

    it('renders the courier view', function () {
        $kirby = App(users: ['email' => 'john@doe.co', 'role' => 'admin']);
        $kirby->impersonate('john@doe.co');

        expect(courierView($kirby))
            ->component->toBe('k-courier-view')
            ->title->toBe('Courier')
            ->props->toHaveKeys(['user', 'transport', 'plus', 'courier']);
    });

    it('never exposes the transport credentials', function () {
        $kirby = App(
            options: ['email' => ['transport' => [
                'type' => 'smtp',
                'host' => 'smtp.example.com',
                'port' => 587,
                'security' => 'tls',
                'auth' => true,
                'username' => 'courier',
                'password' => 'secret-password',
            ]]],
            users: ['email' => 'john@doe.co', 'role' => 'admin'],
        );
        $kirby->impersonate('john@doe.co');

        expect(courierView($kirby)['props']['transport'])
            ->toBe([
                'type' => 'smtp',
                'host' => 'smtp.example.com',
                'port' => 587,
                'security' => 'tls',
                'auth' => true,
                'username' => 'courier',
            ])
            ->not->toHaveKey('password');
    });
});

function testEmail(array $email = ['email' => 'john@doe.co'], array $options = [], string $role = 'admin', array $roles = [], bool $login = true): array
{
    $kirby = App(
        options: $options,
        users: ['email' => 'jane@doe.co', 'role' => $role],
        request: ['query' => $email],
        roles: $roles,
    );

    if ($login) {
        $kirby->impersonate('jane@doe.co');
    }

    return (new SendTestEmail)($kirby);
}

describe('test email', function () {
    it('validates the email address', function () {
        expect(testEmail(['email' => 'invalid']))
            ->success->toBeFalse()
            ->and(Email::$emails)->toHaveCount(0);
    });

    it('sends a test email with a link to the panel', function () {
        $response = testEmail();

        // in debug mode the email is captured but never sent
        expect($response)
            ->success->toBe(Email::$emails[0]->isSent())
            ->message->toBe('Something went wrong while sending the test email.')
            ->and(Email::$emails)->toHaveCount(1)
            ->and(Email::$emails[0])
            ->to()->toEqual(['john@doe.co' => ''])
            ->subject()->toBe('Test Email')
            ->and(Email::$emails[0]->body()->html())
            ->toContain('This is a test email.', 'http://example.com/panel');
    });

    it('can not be sent by guests', function () {
        testEmail(login: false);
    })->throws(PermissionException::class);

    it('can not be sent when the panel area is disabled', function () {
        testEmail(options: ['beebmx.courier.panel' => false]);
    })->throws(PermissionException::class);

    it('can not be sent by roles without access to the courier area', function () {
        testEmail(role: 'editor', roles: [
            ['name' => 'admin'],
            ['name' => 'editor', 'permissions' => ['access' => ['courier' => false]]],
        ]);
    })->throws(PermissionException::class);

    it('can not be sent by roles without access to the panel', function () {
        testEmail(role: 'client', roles: [
            ['name' => 'admin'],
            ['name' => 'client', 'permissions' => ['access' => ['panel' => false]]],
        ]);
    })->throws(PermissionException::class);

    it('can be sent by roles with access to the courier area', function () {
        testEmail(role: 'editor', roles: [
            ['name' => 'admin'],
            ['name' => 'editor', 'permissions' => ['access' => ['courier' => true]]],
        ]);

        expect(Email::$emails)->toHaveCount(1);
    });

    it('does not send anything when it is not allowed', function () {
        try {
            testEmail(options: ['beebmx.courier.panel' => false]);
        } catch (PermissionException) {
        }

        expect(Email::$emails)->toHaveCount(0);
    });
});
