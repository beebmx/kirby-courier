<?php

use Beebmx\KirbyCourier\Mail\Message;
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

    it('set a email with default from recipent', function () {
        $message = (new Message);

        expect($message->send(true))
            ->from()
            ->toEqual('hello@example.com');
    });

    it('can set from recipent', function () {
        $message = (new Message)
            ->from('john@doe.co');

        expect($message)
            ->hasFrom('john@doe.co')
            ->toBeTrue();
    });

    it('send a email with from recipent', function () {
        $message = (new Message)
            ->from('john@doe.co');

        expect($message->send(true))
            ->from()
            ->toEqual('john@doe.co');
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
        $this->message = new Message;
    });

    it('can attach a file as string', function () {
        $this->message->attach(
            attachment('empty.txt')
        );

        expect($this->message)
            ->attachments()
            ->toHaveCount(1);
    });

    it('can send an attachment', function () {
        $this->message->attach(
            attachment('empty.txt')
        )->send(true);
    })->throwsNoExceptions();

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

describe('mail', function () {
    beforeEach(function () {
        App();
        $this->message = new Message;
    });

    it('can set the a template', function () {
        $this->message
            ->template('simple');

        expect($this->message)
            ->render()
            ->toHtml()
            ->toContain('Simple courier template');
    });

    it('reset the template if null is given', function () {
        $this->message
            ->template();

        expect($this->message)
            ->render()
            ->toHtml()
            ->toContain('Hello Courier');
    });
});

describe('advance', function () {
    it('can update the logo with an asset', function () {
        App(options: [
            'beebmx.kirby-courier.logo' => fn () => asset('media/plugins/beebmx/kirby-courier/logo-700.png'),
        ]);

        expect(new Message)
            ->render()
            ->toHtml()
            ->toContain('logo-700.png');
    });

    it('can update the logo with a string url', function () {
        App(options: [
            'beebmx.kirby-courier.logo' => 'media/plugins/beebmx/kirby-courier/logo-500.png',
        ]);

        expect(new Message)
            ->render()
            ->toHtml()
            ->toContain('logo-500.png');
    });
});
