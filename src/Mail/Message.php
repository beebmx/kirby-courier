<?php

namespace Beebmx\KirbyCourier\Mail;

use Beebmx\KirbyCourier\Mailable;

class Message extends Mailable
{
    /**
     * The template to be rendered.
     */
    public string $template = 'mail';

    /**
     * The current theme being used when generating emails.
     */
    public string $theme = 'default';

    public function boot(): void
    {
        //
    }

    public function template(?string $template = null): static
    {
        if ($template) {
            $this->template = $template;
        } else {
            $this->template = 'mail';
        }

        return $this;
    }

    /**
     * Get an array representation of the message.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            //
        ]);
    }
}
