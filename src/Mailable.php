<?php

namespace Beebmx\KirbyCourier;

use Beebmx\KirbyCourier\Contracts\Sendable;
use Kirby\Cms\App;
use Kirby\Cms\File as KirbyFile;
use Kirby\Email\Email;
use Kirby\Filesystem\Asset;
use Kirby\Filesystem\File;
use Kirby\Toolkit\Collection;

abstract class Mailable implements Sendable
{
    /**
     * The preset for the message.
     */
    protected array|string $preset = [];

    /**
     * The data for the message.
     */
    protected array $data = [];

    /**
     * The "from" information for the message.
     */
    public array|string $from = [];

    /**
     * The "from" information for the message.
     */
    public array|string $to = [];

    /**
     * The "cc" information for the message.
     */
    public array|string $cc = [];

    /**
     * The "bcc" information for the message.
     */
    public array|string $bcc = [];

    /**
     * The "reply to" recipients of the message.
     */
    public array|string $replyTo = [];

    /**
     * The subject of the mail.
     */
    public string $subject;

    /**
     * The template to be rendered.
     */
    public string $template = 'mail';

    /**
     * The current theme being used when generating emails.
     */
    public string $theme = 'default';

    /**
     * The attachments for the message.
     */
    public array $attachments = [];

    /**
     * The callbacks for the message.
     */
    public Asset|string|null $logo = null;

    /**
     * The callbacks for the message.
     */
    public array $callbacks = [];

    public function __construct()
    {
        $this->subject = App::instance()->option('beebmx.kirby-courier.message.subject', 'Message from courier');
        $this->logo = $this->getLogo();

        $this->boot();
    }

    abstract public function boot(): void;

    /**
     * Set the preset from your email.presets configuration
     */
    public function preset(?string $preset = null): static
    {
        $this->preset = in_array(
            $preset,
            array_keys($this->getPresets())
        ) ? $preset
          : [];

        return $this;
    }

    /**
     * Determine if the mailable has preset or the preset has a given property.
     */
    public function hasPreset(?string $property = null): bool
    {
        return ! empty($this->preset) && $property !== null
            ? property_exists($this->getPreset(), $property)
            : ! empty($this->preset);
    }

    /**
     * Set the from address for the mail message.
     */
    public function from(string $address, ?string $name = null): static
    {
        return $this->setAddress($address, $name, 'from');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     */
    public function hasFrom(array|string $address, ?string $name = null): bool
    {
        return $this->hasRecipient($address, $name, 'from');
    }

    /**
     * Set the recipients of the message.
     */
    public function to(array|string $address, ?string $name = null): static
    {
        return $this->setAddress($address, $name, 'to');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     */
    public function hasTo(array|string $address, ?string $name = null): bool
    {
        return $this->hasRecipient($address, $name, 'to');
    }

    /**
     * Set the recipients of the message.
     */
    public function cc(array|string $address, ?string $name = null): static
    {
        return $this->setAddress($address, $name, 'cc');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     */
    public function hasCc(array|string $address, ?string $name = null): bool
    {
        return $this->hasRecipient($address, $name, 'cc');
    }

    /**
     * Set the recipients of the message.
     */
    public function bcc(array|string $address, ?string $name = null): static
    {
        return $this->setAddress($address, $name, 'bcc');
    }

    /**
     * Determine if the given recipient is set on the mailable.
     */
    public function hasBcc(array|string $address, ?string $name = null): bool
    {
        return $this->hasRecipient($address, $name, 'bcc');
    }

    /**
     * Set the "reply to" address of the message.
     */
    public function replyTo(array|string $address, ?string $name = null): static
    {
        return $this->setAddress($address, $name, 'replyTo');
    }

    /**
     * Determine if the given replyTo is set on the mailable.
     */
    public function hasReplyTo(array|string $address, ?string $name = null): bool
    {
        return $this->hasRecipient($address, $name, 'replyTo');
    }

    /**
     * Set the subject of the notification.
     */
    public function subject(string $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Determine if the mailable has the given subject.
     */
    public function hasSubject(string $subject): bool
    {
        return ! empty($this->subject) && $this->subject === $subject;
    }

    /**
     * Set the theme to use with the template.
     */
    public function theme(?string $theme = null): static
    {
        if ($theme) {
            $this->theme = $theme;
        } else {
            $this->theme = 'default';
        }

        return $this;
    }

    /**
     * Set the data to use with the mailable.
     */
    public function data(array $data = []): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Returns the data with the default mailable data.
     */
    public function mergeData(): array
    {
        return array_merge($this->toArray(), $this->data);
    }

    /**
     * Attach a file to the message.
     */
    public function attach(File|KirbyFile|string $file): static
    {
        if ($file instanceof File || $file instanceof KirbyFile) {
            $this->attachments[] = $file;
        }

        if (is_string($file) && file_exists($file)) {
            $this->attachments[] = $this->toAttachment($file);
        }

        return $this;
    }

    public function attachments(): array
    {
        return $this->attachments;
    }

    /**
     * Determine if the mailable has the given attachment.
     */
    public function hasAttachment(File|KirbyFile|string $file): bool
    {
        return (new Collection($this->attachments))
            ->filter(
                fn (File|KirbyFile|string $attachment) => is_string($file)
                    ? $attachment->filename() === $file
                    : $attachment->filename() === $file->filename() && $attachment->sha1() === $file->sha1()
            )->count() >= 1;
    }

    /**
     * Attach multiple files to the message.
     */
    public function attachMany(array $files): static
    {
        foreach ($files as $file) {
            $this->attach($file);
        }

        return $this;
    }

    public function render(): Content
    {
        return (new Parse($this->template, $this->theme))
            ->data($this->mergeData())
            ->render();
    }

    public function send($debug = false): Email
    {
        return App::instance()->email($this->preset, array_merge([
            'debug' => $debug,
            'body' => [
                'html' => $this->render()->toHtml(),
            ],
        ], $this->build()));
    }

    /**
     * Returns the build structure for email
     */
    public function build(): array
    {
        return array_merge(
            $this->buildSubject(),
            $this->buildFrom(),
            $this->buildRecipients(),
            $this->buildAttachments(),
        );
    }

    /**
     * Set the recipients of the message.
     *
     * All recipients are stored internally as [['name' => ?, 'address' => ?]]
     */
    protected function setAddress(array|string $address, ?string $name = null, string $property = 'to'): static
    {
        if (empty($address)) {
            return $this;
        }

        foreach ($this->addressesToArray($address, $name) as $recipient) {
            $recipient = $this->normalizeRecipient($recipient);

            $this->{$property}[] = [
                'name' => $recipient->name ?? null,
                'address' => $recipient->address,
            ];
        }

        return $this;
    }

    /**
     * Convert the given recipient arguments to an array.
     */
    protected function addressesToArray(array|string $address, ?string $name): array|string
    {
        if (! is_array($address)) {
            $address = is_string($name) ? [['name' => $name, 'address' => $address]] : [$address];
        }

        return $address;
    }

    /**
     * Convert the given recipient into an object.
     */
    protected function normalizeRecipient(mixed $recipient): object
    {
        if (is_array($recipient)) {
            if (array_values($recipient) === $recipient) {
                return (object) array_map(function ($address) {
                    return compact('address');
                }, $recipient);
            }

            return (object) $recipient;
        } elseif (is_string($recipient)) {
            return (object) ['address' => $recipient];
        }

        return $recipient;
    }

    /**
     * Determine if the given recipient is set on the mailable.
     */
    protected function hasRecipient(array|string $address, ?string $name = null, string $property = 'to'): bool
    {
        if (empty($address)) {
            return false;
        }

        $expected = $this->normalizeRecipient(
            $this->addressesToArray($address, $name)[0]
        );

        $expected = [
            'name' => $expected->name ?? null,
            'address' => $expected->address,
        ];

        return (new Collection($this->{$property}))->filter(function ($current) use ($expected) {
            if (! isset($expected['name'])) {
                return $current['address'] === $expected['address'];
            }

            return $current == $expected;
        })->count() === 1;
    }

    protected function toAttachment(string $file): File
    {
        return new File($file);
    }

    protected function buildSubject(): array
    {
        if ($this->hasPreset('subject')) {
            return $this->buildFromPreset('subject');
        }

        return [
            'subject' => $this->subject,
        ];
    }

    protected function buildFrom(): array
    {
        if ($this->hasPreset()) {
            return $this->buildFromPreset(['from', 'fromName']);
        }

        if (empty($this->from)) {
            $this->setAddress(
                App::instance()->option('beebmx.kirby-courier.from.address', 'hello@example.com'),
                App::instance()->option('beebmx.kirby-courier.from.name', 'Example'),
                'from'
            );
        }

        return [
            'from' => $this->from[0]['address'],
            'fromName' => $this->from[0]['name'],
        ];
    }

    protected function buildRecipients(): array
    {
        $recipients = [
            'to' => [],
            'cc' => [],
            'bcc' => [],
            'replyTo' => [],
        ];

        foreach ($recipients as $type => $value) {
            foreach ($this->{$type} as $recipient) {
                $recipients[$type][] = match (true) {
                    isset($recipient['address']) && isset($recipient['name']) => [$recipient['address'], $recipient['name']],
                    default => $recipient['address']
                };
            }
        }

        $recipients = array_merge_recursive(
            $recipients,
            $this->buildFromPreset(['to', 'cc', 'bcc', 'replyTo'])
        );

        return array_map(
            fn ($recipent) => count($recipent) === 1 && is_string($recipent[0])
                ? $recipent[0]
                : $recipent,
            array_filter($recipients)
        );
    }

    protected function buildAttachments(): array
    {
        return [
            'attachments' => $this->attachments(),
        ];
    }

    protected function buildFromPreset(array|string $property = 'from'): array
    {
        if ($this->hasPreset()) {
            $preset = $this->getPreset();

            $properties = [];

            if (is_string($property)) {
                $properties[$property] = $preset?->{$property};
            } elseif (is_array($property)) {
                foreach ($property as $item) {
                    $properties[$item] = property_exists($preset, $item)
                        ? $preset?->{$item}
                        : null;
                }
            }

            return array_filter($properties);
        }

        return [];
    }

    protected function getPresets(): array
    {
        return App::instance()->option('email.presets', []);
    }

    protected function getPreset(): object
    {
        if (! empty($this->preset)) {
            return (object) $this->getPresets()[$this->preset];
        }

        return (object) [];
    }

    protected function getLogo(): Asset|string|null
    {
        $logo = App::instance()->option('beebmx.kirby-courier.logo');

        return match (true) {
            $logo instanceof Asset => $logo,
            default => null,
        };
    }

    /**
     * Get an array representation of the message.
     */
    public function toArray(): array
    {
        return [
            'kirby' => App::instance(),
            'logo' => $this->logo,
            'subject' => $this->subject,
        ];
    }
}
