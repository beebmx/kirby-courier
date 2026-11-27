<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier\Actions;

use Beebmx\KirbyCourier\Notification\Message;
use Kirby\Cms\App;
use Kirby\Exception\PermissionException;
use Kirby\Toolkit\I18n;
use Kirby\Toolkit\V;
use Throwable;

class SendTestEmail
{
    /**
     * @throws PermissionException If the Courier area is disabled or the user can't access it
     */
    public function __invoke(App $kirby): array
    {
        $this->authorize($kirby);

        $email = $kirby->request()->get('email');

        if (! V::email($email)) {
            return [
                'success' => false,
                'message' => I18n::translate('error.validation.email', 'Please enter a valid email address'),
            ];
        }

        try {
            $success = (new Message)
                ->to($email)
                ->subject(I18n::translate('beebmx.courier.panel.email.subject', 'Test Email'))
                ->line(I18n::translate('beebmx.courier.panel.email.message', 'This is a test email.'))
                ->action('Panel', $kirby->url('panel'))
                ->salutation(I18n::translate('beebmx.courier.panel.email.salutation', 'Good bye!'))
                ->send()
                ->isSent();
        } catch (Throwable) {
            $success = false;
        }

        return [
            'success' => $success,
            'message' => $success
                ? I18n::translate('beebmx.courier.panel.email.sent', 'The test email has been sent successfully.')
                : I18n::translate('beebmx.courier.panel.email.sent.failed', 'Something went wrong while sending the test email.'),
        ];
    }

    /**
     * Applies the same rules as the Panel area, so the route
     * can't be used when the area is disabled or forbidden
     *
     * @throws PermissionException
     */
    protected function authorize(App $kirby): void
    {
        if ($kirby->option('beebmx.courier.panel') === false) {
            throw new PermissionException(key: 'access.view');
        }

        $kirby->panel()->access()->area('courier', throws: true);
    }
}
