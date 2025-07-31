<?php

namespace Beebmx\KirbyCourier;

use Beebmx\KirbyCourier\Notification\Message;
use Kirby\Cms\Auth\EmailChallenge;
use Kirby\Cms\User;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\Str;

class CourierChallenge extends EmailChallenge
{
    public static function create(User $user, array $options): string
    {
        $code = Str::random(6, 'num');

        $mode = match ($options['mode']) {
            '2fa' => 'login',
            default => $options['mode']
        };

        $kirby = $user->kirby();
        $timeout = round($options['timeout'] / 60);

        $from = $kirby->option(
            'auth.challenge.email.from',
            'noreply@'.$kirby->url('index', true)->host()
        );

        $formName = $kirby->option(
            'auth.challenge.email.fromName',
            $kirby->site()->title()
        );

        $subject = $kirby->option(
            'auth.challenge.email.subject',
            I18n::translate('login.email.'.$mode.'.subject', null, $user->language())
        );

        $greeting = $kirby->option(
            'beebmx.kirby-courier.challenge.greeting',
            I18n::template('beebmx.kirby-courier.challenge.email.greeting', null, [
                'name' => $user->nameOrEmail(),
            ], $user->language())
        );

        $before = $kirby->option(
            'beebmx.kirby-courier.challenge.email.login.before',
            I18n::template('beebmx.kirby-courier.challenge.email.'.$mode.'.before', null, [
                'name' => $user->nameOrEmail(),
                'site' => $kirby->system()->title(),
                'timeout' => $timeout,
            ], $user->language())
        );

        $after = $kirby->option(
            'beebmx.kirby-courier.challenge.email.login.after',
            I18n::template('beebmx.kirby-courier.challenge.email.'.$mode.'.after', null, [
                'name' => $user->nameOrEmail(),
            ], $user->language())
        );

        (new Message)
            ->theme($kirby->option('beebmx.kirby-courier.challenge.theme', 'default'))
            ->from($from, $formName)
            ->to($user->email())
            ->subject($subject)
            ->greeting($greeting)
            ->line($before)
            ->code($code)
            ->line($after)
            ->salutation($kirby->site()->title())
            ->send();

        return $code;
    }
}
