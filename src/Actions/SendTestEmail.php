<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier\Actions;

use Beebmx\KirbyCourier\Notification\Message;
use Exception;
use Kirby\Cms\App;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;

class SendTestEmail
{
    public function __invoke(App $kirby): array
    {
        $success = false;

        if (! V::email($email = $kirby->request()->get('email'))) {
            return [
                'success' => $success,
                'message' => I18n::translate('error.validation.email', 'Please enter a valid email address'),
            ];
        }

        try {
            $success = (new Message)
                ->to('delivered@resend.dev')
                ->subject(I18n::translate('beebmx.courier.panel.email.subject', 'Test Email'))
                ->line(I18n::translate('beebmx.courier.panel.email.message', 'This is a test email.'))
                ->action('Panel', $kirby->url($kirby->option('panel.slug')))
                ->salutation(I18n::translate('beebmx.courier.panel.email.salutation', 'Good bye!'))
                ->send()
                ->isSent();

            $message = I18n::translate('beebmx.courier.panel.email.sent', 'The test email has been sent successfully.');
        } catch (Exception $e) {
            $message = I18n::translate('beebmx.courier.panel.email.sent.failed', 'Something went wrong while sending the test email.');
        }

        return [
            'success' => $success,
            'message' => $message,
        ];
    }
}
