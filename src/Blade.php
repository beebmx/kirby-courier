<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

use Beebmx\KirbyBlade\Template as BladeTemplate;
use Kirby\Cms\App;

/**
 * Optional integration with `beebmx/kirby-blade` (^7.0).
 *
 * Courier doesn't require kirby-blade: this class is only used when it's
 * installed and its `beebmx.kirby-blade.bootstrap` option is not disabled.
 */
final class Blade
{
    /**
     * The extension of Blade templates.
     */
    public const string EXTENSION = '.blade.php';

    /**
     * The prefix of the anonymous components (`<x-courier::button>`).
     */
    public const string PREFIX = 'courier';

    /**
     * Determine if kirby-blade is installed.
     */
    public static function isAvailable(): bool
    {
        return class_exists(BladeTemplate::class);
    }

    /**
     * Determine if Courier should use the Blade templates by default.
     */
    public static function isEnabled(?App $kirby = null): bool
    {
        return self::isAvailable()
            && ($kirby ?? App::instance())->option('beebmx.kirby-blade.bootstrap', true) !== false;
    }

    /**
     * Determine if the given file is a Blade template.
     */
    public static function isTemplate(?string $file): bool
    {
        return is_string($file) && str_ends_with($file, self::EXTENSION);
    }

    /**
     * Sets up the Blade templates and components of Courier.
     *
     * Called from the `system.loadPlugins:after` hook,
     * only when kirby-blade is installed and enabled.
     */
    public static function boot(App $kirby): void
    {
        $kirby->extend(['templates' => self::templates()]);

        self::components('html');
    }

    /**
     * Returns the Blade versions of the default Courier templates.
     *
     * @return array<string, string>
     */
    public static function templates(): array
    {
        $root = dirname(__DIR__).'/templates/courier';

        return [
            'courier/mail' => $root.'/mail'.self::EXTENSION,
            'courier/notification' => $root.'/notification'.self::EXTENSION,
        ];
    }

    /**
     * Points the anonymous `courier::` components to the given type (html or text).
     *
     * The component namespace is hashed by its prefix, so compiled templates keep
     * working while the components are swapped between the html and text versions.
     */
    public static function components(string $type): void
    {
        $blade = BladeTemplate::boot();
        $compiler = $blade->compiler();
        $path = dirname(__DIR__).'/components/'.$type;

        $registered = in_array(
            self::PREFIX,
            array_column($compiler->getAnonymousComponentPaths(), 'prefix'),
            true
        );

        $registered
            ? $compiler->replaceAnonymousComponentPath($path, self::PREFIX)
            : $compiler->anonymousComponentPath($path, self::PREFIX);

        $blade->container()->get('view')->flushFinderCache();
    }
}
