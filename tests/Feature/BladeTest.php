<?php

use Beebmx\KirbyCourier\Blade;
use Beebmx\KirbyCourier\Mail\Message as Mail;
use Beebmx\KirbyCourier\Notification\Message as Notification;
use Beebmx\KirbyCourier\Parse;
use Kirby\Email\Email;

/*
|--------------------------------------------------------------------------
| Blade
|--------------------------------------------------------------------------
|
| `beebmx/kirby-blade` is optional, so these tests are only registered when
| it's installed (the `blade` job in the GitHub workflow installs it).
| Without it, they don't run and aren't reported as skipped.
|
*/

if (! Blade::isAvailable()) {
    return;
}

pest()->group('blade');

describe('integration', function () {
    it('detects kirby-blade', function () {
        expect(Blade::isAvailable())->toBeTrue();
    });

    it('is enabled by default when kirby-blade is available', function () {
        expect(Blade::isEnabled(App(blade: null)))->toBeTrue();
    });

    it('can be disabled with the kirby-blade bootstrap option', function () {
        expect(Blade::isEnabled(App(blade: false)))->toBeFalse()
            ->and(Blade::isEnabled(App(blade: true)))->toBeTrue();
    });

    it('detects blade templates', function () {
        expect(Blade::isTemplate('courier/mail.blade.php'))->toBeTrue()
            ->and(Blade::isTemplate('courier/mail.php'))->toBeFalse()
            ->and(Blade::isTemplate(null))->toBeFalse();
    });

    it('returns the blade templates', function () {
        expect(Blade::templates())
            ->{'courier/mail'}->toEndWith('templates/courier/mail.blade.php')
            ->{'courier/notification'}->toEndWith('templates/courier/notification.blade.php');
    });

    it('registers the php templates by default', function () {
        expect(require dirname(__DIR__, 2).'/extensions/templates.php')
            ->{'courier/mail'}->toEndWith('templates/courier/mail.php')
            ->{'courier/notification'}->toEndWith('templates/courier/notification.php');
    });

    it('switches to the blade templates from the hook when kirby-blade is enabled', function () {
        expect(App(blade: true)->template('courier/mail')->file())
            ->toEndWith('templates/courier/mail.blade.php')
            ->and(App(blade: true)->template('courier/notification')->file())
            ->toEndWith('templates/courier/notification.blade.php');
    });

    it('keeps the php templates when kirby-blade is disabled', function () {
        expect(App(blade: false)->template('courier/mail')->file())
            ->toEndWith('templates/courier/mail.php')
            ->and(App(blade: false)->template('courier/notification')->file())
            ->toEndWith('templates/courier/notification.php');
    });
});

describe('mail', function () {
    beforeEach(function () {
        App(blade: true);
    });

    it('renders the default blade template', function () {
        expect((new Mail)->render()->toHtml())
            ->toContain('Hello Courier', 'All rights reserved.', '-apple-system', 'button-primary');
    });

    it('renders the default blade template as plain text', function () {
        expect((new Mail)->renderText()->toHtml())
            ->toContain('Hello Courier', 'All rights reserved.')
            ->not->toContain('<html', '<body', '<table', '<td');
    });

    it('renders a custom blade template with data', function () {
        expect((new Mail)->template('blade')->data(['title' => 'Kirby'])->render()->toHtml())
            ->toContain('Blade courier template for: Kirby', 'Blade button', 'https://example.com', 'button-success')
            ->toContain('panel-content', 'Blade panel', '<table', 'Blade row');
    });

    it('renders a custom blade template with data as plain text', function () {
        expect((new Mail)->template('blade')->data(['title' => 'Kirby'])->renderText()->toHtml())
            ->toContain('Blade courier template for: Kirby', 'Blade button', 'Blade panel', 'Blade row')
            ->not->toContain('<table', 'panel-content');
    });

    it('can switch between html and text components', function () {
        $message = (new Mail)->template('blade');

        expect($message->render()->toHtml())->toContain('<table')
            ->and($message->renderText()->toHtml())->not->toContain('<table')
            ->and($message->render()->toHtml())->toContain('<table');
    });

    it('can change the theme', function () {
        expect((new Mail)->theme('black')->render()->toHtml())
            ->toContain('color: black');
    });

    it('sends a blade message', function () {
        $email = (new Mail)->to('john@doe.co')->send(true);

        expect($email)
            ->toBeInstanceOf(Email::class)
            ->and($email->body()->html())->toContain('Hello Courier')
            ->and($email->body()->text())->toContain('Hello Courier')->not->toContain('<table');
    });
});

describe('notification', function () {
    beforeEach(function () {
        App(blade: true);
    });

    it('renders the default blade notification', function () {
        $message = (new Notification)
            ->greeting('Hello friend!')
            ->line('Intro line')
            ->code('000001')
            ->error()
            ->action('Send', 'https://example.com')
            ->line('Outro line')
            ->salutation('Thank you!');

        expect($message->render()->toHtml())
            ->toContain(
                'Hello friend!', 'Intro line', '000001', 'code-content', 'button-error',
                'https://example.com', 'Outro line', 'Thank you!', 'If you\'re having trouble clicking the button',
            );
    });

    it('renders the default blade notification as plain text', function () {
        $message = (new Notification)
            ->greeting('Hello friend!')
            ->line('Intro line')
            ->action('Send', 'https://example.com')
            ->salutation('Thank you!');

        expect($message->renderText()->toHtml())
            ->toContain('Hello friend!', 'Intro line', 'Send', 'https://example.com', 'Thank you!')
            ->not->toContain('<html', '<body', '<table', '<td');
    });

    it('links the subcopy to the action url only once', function () {
        $html = (new Notification)
            ->action('Send', 'https://example.com/?a=1&b=2')
            ->render()
            ->toHtml();

        preg_match_all('/href="([^"]+)"/', $html, $links);

        expect(array_count_values($links[1]))
            ->toHaveKey('https://example.com/?a=1&amp;b=2', 2)
            ->and($html)->not->toContain('&amp;amp;');
    });

    it('shows the plain values in the text version', function () {
        expect((new Notification)->line('Use <b> & "quotes"')->renderText()->toHtml())
            ->toContain('Use <b> & "quotes"')
            ->not->toContain('&lt;', '&amp;', '&quot;');
    });

    it('escapes the logo', function () {
        App(blade: true, options: ['beebmx.courier.logo' => 'https://example.com/logo.png" onerror="alert(1)']);

        expect((new Notification)->render()->toHtml())
            ->not->toMatch('/<img[^>]*\sonerror="/');
    });

    it('escapes the notification lines', function () {
        expect((new Notification)->line('<script>alert(1)</script>')->render()->toHtml())
            ->not->toContain('<script>alert(1)</script>');
    });
});

describe('php templates', function () {
    it('keeps rendering php templates while kirby-blade is enabled', function () {
        App(blade: true);

        expect((new Parse('simple'))->render()->toHtml())
            ->toContain('Simple courier template')
            ->and((new Parse('simple'))->renderText()->toHtml())
            ->toContain('Simple courier template')
            ->not->toContain('<table');
    });
});
