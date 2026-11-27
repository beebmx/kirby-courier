<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

use Beebmx\KirbyCourier\Notification\Message;
use Kirby\Auth\Challenge\EmailChallenge;
use Kirby\Toolkit\I18n;

/**
 * Sends the Kirby login and password reset codes
 * with a Courier notification message
 */
class CourierChallenge extends EmailChallenge
{
    /**
     * Uses the same label as the core email challenge
     */
    public function label(): string
    {
        return static::i18n('login.challenge.email.label');
    }

    /**
     * Sends the email with the code to the user
     */
    protected function send(string $code): void
    {
        // use the login templates for 2FA
        $mode = match ($this->mode) {
            '2fa' => 'login',
            default => $this->mode
        };

        $language = $this->user->language();
        $name = $this->user->nameOrEmail()->value();

        $greeting = $this->kirby->option(
            'beebmx.courier.challenge.greeting',
            I18n::template('beebmx.courier.challenge.email.greeting', null, [
                'name' => $name,
            ], $language)
        );

        $before = $this->kirby->option(
            'beebmx.courier.challenge.email.'.$mode.'.before',
            I18n::template('beebmx.courier.challenge.email.'.$mode.'.before', null, [
                'name' => $name,
                'site' => $this->kirby->system()->title(),
                'timeout' => round($this->timeout / 60),
            ], $language)
        );

        $after = $this->kirby->option(
            'beebmx.courier.challenge.email.'.$mode.'.after',
            I18n::template('beebmx.courier.challenge.email.'.$mode.'.after', null, [
                'name' => $name,
            ], $language)
        );

        (new Message)
            ->theme($this->kirby->option('beebmx.courier.challenge.theme', 'default'))
            ->from($this->senderFrom(), $this->senderFromName())
            ->to($this->user->email())
            ->subject($this->subject($mode))
            ->greeting($greeting)
            ->line($before)
            ->code($code)
            ->line($after)
            ->salutation((string) $this->kirby->site()->title())
            ->send();
    }
}
