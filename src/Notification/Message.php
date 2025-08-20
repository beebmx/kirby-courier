<?php

namespace Beebmx\KirbyCourier\Notification;

use Beebmx\KirbyCourier\Action;
use Beebmx\KirbyCourier\Mailable;
use Kirby\Cms\App;

class Message extends Mailable
{
    /**
     * The template to be rendered.
     */
    public string $template = 'notification';

    /**
     * The current theme being used when generating emails.
     */
    public string $theme = 'default';

    /**
     * The "level" of the notification (info, success, error).
     */
    public string $level = 'info';

    /**
     * The notification's greeting.
     */
    public ?string $greeting = null;

    /**
     * The notification's salutation.
     */
    public ?string $salutation = null;

    /**
     * The "intro" lines of the notification.
     */
    public array $introLines = [];

    /**
     * The code of the notification.
     */
    public ?string $code = null;

    /**
     * The "outro" lines of the notification.
     */
    public array $outroLines = [];

    public ?Action $action = null;

    public function boot(): void
    {
        $this->greeting = App::instance()->option('beebmx.courier.message.greeting', 'Hello!');
    }

    /**
     * Indicate that the notification gives information about a successful operation.
     */
    public function success(): static
    {
        $this->level = 'success';

        return $this;
    }

    /**
     * Indicate that the notification gives information about an error.
     */
    public function error(): static
    {
        $this->level = 'error';

        return $this;
    }

    /**
     * Set the "level" of the notification (success, error, etc.).
     */
    public function level(string $level): static
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Set the greeting of the notification.
     */
    public function greeting(?string $greeting): static
    {
        $this->greeting = $greeting;

        return $this;
    }

    /**
     * Set the salutation of the notification.
     */
    public function salutation(?string $salutation): static
    {
        $this->salutation = $salutation;

        return $this;
    }

    /**
     * Add a line of text to the notification.
     */
    public function line(mixed $line): static
    {
        return $this->with($line);
    }

    /**
     * Add a code of text to the notification.
     */
    public function code(mixed $code): static
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Add a line of text to the notification.
     */
    public function with(mixed $line): static
    {
        if ($this->action === null && $this->code === null) {
            $this->introLines[] = $this->formatLine($line);
        } else {
            $this->outroLines[] = $this->formatLine($line);
        }

        return $this;
    }

    /**
     * Add a line of text to the notification if the given condition is true.
     */
    public function lineIf(bool $boolean, mixed $line): static
    {
        if ($boolean) {
            return $this->line($line);
        }

        return $this;
    }

    /**
     * Add lines of text to the notification.
     */
    public function lines(iterable $lines): static
    {
        foreach ($lines as $line) {
            $this->line($line);
        }

        return $this;
    }

    /**
     * Add lines of text to the notification if the given condition is true.
     */
    public function linesIf(bool $boolean, iterable $lines): static
    {
        if ($boolean) {
            return $this->lines($lines);
        }

        return $this;
    }

    /**
     * Configure the "call to action" button.
     */
    public function action(string $text, string $url): static
    {
        $this->action = new Action($text, $url);

        return $this;
    }

    protected function formatLine(array|string $line): string
    {
        if (is_array($line)) {
            return implode(' ', array_map('trim', $line));
        }

        return trim(implode(' ', array_map('trim', preg_split('/\\r\\n|\\r|\\n/', $line ?? ''))));
    }

    /**
     * Get an array representation of the message.
     */
    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'actionText' => $this->action?->text,
            'actionUrl' => $this->action?->url,
            'code' => $this->code,
            'displayableActionUrl' => str_replace(['mailto:', 'tel:'], '', $this->action?->url ?? ''),
            'greeting' => $this->greeting,
            'introLines' => $this->introLines,
            'level' => $this->level,
            'outroLines' => $this->outroLines,
            'salutation' => $this->salutation,
        ]);
    }
}
