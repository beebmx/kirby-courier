<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

use Closure;
use Kirby\Cms\File;
use Kirby\Content\Field;
use Kirby\Filesystem\Asset;

/**
 * Resolves the `beebmx.courier.logo` option to a URL
 */
final class Logo
{
    /**
     * Returns the URL of the logo, which can be set as:
     * - a URL string
     * - a closure that returns a URL string, an Asset, a File or a Field with a file
     */
    public static function url(mixed $logo): ?string
    {
        if ($logo instanceof Closure) {
            $logo = $logo();
        }

        $url = match (true) {
            is_string($logo) => $logo,
            $logo instanceof Asset, $logo instanceof File => $logo->url(),
            $logo instanceof Field => $logo->toFile()?->url(),
            default => null,
        };

        return is_string($url) && trim($url) !== '' ? $url : null;
    }
}
