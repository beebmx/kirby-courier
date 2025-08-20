<?php

use Beebmx\KirbyCourier\Notification\Message;
use Kirby\Cms\App;
use Kirby\Email\Email;
use Kirby\Filesystem\File;

describe('basic', function () {
    beforeEach(function () {
        App();
    });

    it('returns an instance of Email when send a message', function () {
        $message = (new Message);

        expect($message->send(true))
            ->toBeInstanceOf(Email::class);
    });

    it('can set single recipent', function () {
        $message = (new Message)
            ->to('john@doe.co');

        expect($message)
            ->hasTo('john@doe.co')
            ->toBeTrue();
    });

    it('can set multiples recipents', function () {
        $message = (new Message)
            ->to('john@doe.co', 'John Doe')
            ->to('jane@doe.co');

        expect($message)
            ->hasTo('john@doe.co')
            ->toBeTrue()
            ->hasTo('jane@doe.co')
            ->toBeTrue();
    });

    it('can set cc recipent', function () {
        $message = (new Message)
            ->to('john@doe.co')
            ->cc('jane@doe.co');

        expect($message)
            ->hasCc('jane@doe.co')
            ->toBeTrue();
    });

    it('can set bcc recipent', function () {
        $message = (new Message)
            ->to('john@doe.co')
            ->bcc('jane@doe.co');

        expect($message)
            ->hasBcc('jane@doe.co')
            ->toBeTrue();
    });

    it('can set replyTo recipent', function () {
        $message = (new Message)
            ->to('john@doe.co')
            ->replyTo('jane@doe.co');

        expect($message)
            ->hasReplyTo('jane@doe.co')
            ->toBeTrue();
    });

    it('can set a subject', function () {
        $message = (new Message)
            ->to('john@doe.co')
            ->subject('My message');

        expect($message)
            ->hasSubject('My message')
            ->toBeTrue();
    });

    it('renders the default template', function () {
        expect((new Message))
            ->render()
            ->toHtml()
            ->toContain('-apple-system');
    });

    it('has footer rights', function () {
        expect((new Message))
            ->render()
            ->toHtml()
            ->toContain('All rights reserved.');
    });

    it('can set a different theme', function () {
        $message = (new Message)
            ->theme('black');

        expect($message)
            ->render()
            ->toHtml()
            ->toContain('color: black');
    });

    it('reset the theme if null is given', function () {
        $message = (new Message)
            ->theme();

        expect($message)
            ->render()
            ->toHtml()
            ->toContain('-apple-system');
    });
});

describe('attachments', function () {
    beforeEach(function () {
        App();
        $this->message = new \Beebmx\KirbyCourier\Mail\Message;
    });

    it('can attach a file as string', function () {
        $this->message->attach(
            attachment('empty.txt')
        );

        expect($this->message)
            ->attachments()
            ->toHaveCount(1);
    });

    it('can determine if an attachment exists with filename', function () {
        $this->message->attach(
            attachment('empty.txt')
        );

        expect($this->message)
            ->hasAttachment('empty.txt')
            ->toBeTrue()
            ->hasAttachment('other.txt')
            ->toBeFalse();
    });

    it('can attach a file', function () {
        $this->message->attach(
            new File(attachment('empty.txt'))
        );

        expect($this->message)
            ->attachments()
            ->toHaveCount(1);
    });

    it('can determine if an attachment exists with file', function () {
        $this->message->attach(
            new File(attachment('empty.txt'))
        );

        expect($this->message)
            ->hasAttachment(new File(attachment('empty.txt')))
            ->toBeTrue()
            ->hasAttachment(new File(attachment('other.txt')))
            ->toBeFalse();
    });

    it('can attach a page file', function () {
        $home = App::instance()->site()->homePage();
        $this->message->attach(
            $home->file('empty.jpg')
        );

        expect($this->message)
            ->attachments()
            ->toHaveCount(1);
    });

    it('can determine if an attachment exists with page file', function () {
        $home = App::instance()->site()->homePage();
        $this->message->attach(
            $home->file('empty.jpg')
        );

        expect($this->message)
            ->hasAttachment($home->file('empty.jpg'))
            ->toBeTrue()
            ->hasAttachment($home->file('more.jpg'))
            ->toBeFalse();
    });

    it('can determine if an attachment exists with a string and file', function () {
        $this->message->attach(
            new File(attachment('empty.txt'))
        );

        expect($this->message)
            ->hasAttachment('empty.txt')
            ->toBeTrue();
    });

    it('can attach files with multiple calls', function () {
        $this->message
            ->attach(attachment('empty.txt'))
            ->attach(attachment('extra.jpg'));

        expect($this->message)
            ->attachments()
            ->toHaveCount(2);
    });

    it('can able to attachMany files', function () {
        $home = App::instance()->site()->homePage();
        $this->message
            ->attachMany([
                attachment('empty.txt'),
                new File(attachment('other.jpg')),
                $home->file('more.jpg'),
            ]);

        expect($this->message)
            ->attachments()
            ->toHaveCount(3);
    });
});

describe('presets', function () {
    beforeEach(function () {
        App(options: [
            'email' => [
                'presets' => [
                    'contact' => [
                        'from' => 'no-reply@supercompany.com',
                        'subject' => 'Thank you for your contact request',
                        'cc' => 'marketing@supercompany.com',
                        'bcc' => 'admin@supercompany.com',
                    ],
                    'marketing' => [
                        'from' => 'marketing@supercompany.com',
                        'fromName' => 'Marketing',
                        'cc' => 'admin@supercompany.com',
                        'replyTo' => 'support@supercompany.com',
                    ],
                ],
            ],
        ]);
        $this->message = new Message;
    });

    it('can set a preset', function () {
        $this->message
            ->preset('contact');

        expect($this->message)
            ->hasPreset()
            ->toBeTrue();
    });

    it('can not be added an invalid preset', function () {
        $this->message
            ->preset('team');

        expect($this->message)
            ->hasPreset()
            ->toBeFalse();
    });

    it('set from value from preset', function () {
        $this->message
            ->preset('contact');

        expect($this->message)
            ->build()
            ->toHaveKey('from', 'no-reply@supercompany.com')
            ->not->toHaveKey('fromName');
    });

    it('set fromName value from preset', function () {
        $this->message
            ->preset('marketing');

        expect($this->message)
            ->build()
            ->toHaveKey('from', 'marketing@supercompany.com')
            ->toHaveKey('fromName', 'Marketing');
    });

    it('set cc value from preset', function () {
        $this->message
            ->preset('contact');

        expect($this->message)
            ->build()
            ->toHaveKey('cc', 'marketing@supercompany.com');
    });

    it('set bcc value from preset', function () {
        $this->message
            ->preset('contact');

        expect($this->message)
            ->build()
            ->toHaveKey('bcc', 'admin@supercompany.com');
    });

    it('set replyTo value from preset', function () {
        $this->message
            ->preset('marketing');

        expect($this->message)
            ->build()
            ->toHaveKey('replyTo', 'support@supercompany.com');
    });

    it('set subject value from preset', function () {
        $this->message
            ->preset('contact');

        expect($this->message)
            ->build()
            ->toHaveKey('subject', 'Thank you for your contact request');
    });
});

describe('notification', function () {
    beforeEach(function () {
        App();
        $this->message = new Message;
    });

    it('can add a greeting', function () {
        $this->message
            ->greeting('Hello friend!');

        expect($this->message->render()->toHtml())
            ->toContain('Hello friend!');
    });

    it('can add intro lines', function () {
        $this->message
            ->line('My')
            ->line('world');

        expect($this->message->render()->toHtml())
            ->toContain('My', 'world');
    });

    it('can add an action', function () {
        $this->message
            ->action('Send', 'https://example.com');

        expect($this->message->render()->toHtml())
            ->toContain('Send', 'https://example.com');
    });

    it('can add outro lines', function () {
        $this->message
            ->action('Send', 'https://example.com')
            ->line('My')
            ->line('world');

        expect($this->message->render()->toHtml())
            ->toContain('My', 'world');
    });

    it('can add lines and action', function () {
        $this->message
            ->line('My')
            ->action('Send', 'https://example.com')
            ->line('world');

        expect($this->message->render()->toHtml())
            ->toContain('My', 'Send', 'world');
    });

    it('can add a salutation', function () {
        $this->message
            ->salutation('Thank you!');

        expect($this->message->render()->toHtml())
            ->toContain('Thank you!');
    });

    it('display a notification if an action button was set', function () {
        $this->message
            ->action('Send', 'https://example.com');

        expect($this->message->render()->toHtml())
            ->toContain('If you\'re having trouble clicking the button');
    });

    it('can add conditional lines', function () {
        $this->message
            ->lineIf(false, 'My')
            ->lineIf(true, 'world');

        expect($this->message->render()->toHtml())
            ->not->toContain('My')
            ->toContain('world');
    });

    it('can multiple lines', function () {
        $this->message
            ->lines(['My', 'world']);

        expect($this->message->render()->toHtml())
            ->toContain('My', 'world');
    });

    it('can multiple conditional lines', function () {
        $this->message
            ->linesIf(false, ['My', 'world'])
            ->linesIf(true, ['Other', 'space']);

        expect($this->message->render()->toHtml())
            ->not->toContain('My', 'world')
            ->toContain('Other', 'space');
    });

    it('can set success level', function () {
        $this->message
            ->success()
            ->action('Send', 'https://example.com');

        expect($this->message->render()->toHtml())
            ->toContain('button-success');
    });

    it('can set error level', function () {
        $this->message
            ->error()
            ->action('Send', 'https://example.com');

        expect($this->message->render()->toHtml())
            ->toContain('button-error');
    });

    it('can set any level', function () {
        $this->message
            ->level('info')
            ->action('Send', 'https://example.com');

        expect($this->message->render()->toHtml())
            ->toContain('button-primary');
    });

    it('can add a code block', function () {
        $this->message
            ->code('000001');

        expect($this->message->render()->toHtml())
            ->toContain('000001');
    });
});

describe('advance', function () {
    it('can update the logo with an asset', function () {
        App(options: [
            'beebmx.courier.logo' => fn () => asset('media/plugins/beebmx/kirby-courier/logo-700.png'),
        ]);

        expect(new Message)
            ->render()
            ->toHtml()
            ->toContain('logo-700.png');
    });

    it('can update the logo with a string url', function () {
        App(options: [
            'beebmx.courier.logo' => 'media/plugins/beebmx/kirby-courier/logo-500.png',
        ]);

        expect(new Message)
            ->render()
            ->toHtml()
            ->toContain('logo-500.png');
    });
});
