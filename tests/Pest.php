<?php

use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function App(array $roots = [], array $options = [], string $domain = 'example.com', array $users = []): Kirby
{
    Kirby::$enableWhoops = false;

    return new Kirby([
        'roots' => array_merge([
            'index' => '/dev/null',
            'base' => $base = dirname(__DIR__),
            'tests' => $tests = $base.'/tests',
            'fixtures' => $fixtures = $tests.'/Fixtures',
            'media' => $fixtures.'/media',
            'site' => $site = $fixtures.'/site',
            'content' => $site.'/content',
            'storage' => $fixtures.'/storage',
        ], $roots),
        'server' => [
            'SERVER_NAME' => $domain,
        ],
        'options' => array_merge([
            'beebmx.kirby-courier' => require dirname(__DIR__).'/extensions/options.php',
        ], $options),
        'users' => [$users],
        'authChallenges' => require dirname(__DIR__).'/extensions/authChallenges.php',
        'commands' => require dirname(__DIR__).'/extensions/commands.php',
        'snippets' => require dirname(__DIR__).'/extensions/snippets.php',
        'templates' => require dirname(__DIR__).'/extensions/templates.php',
        'translations' => require dirname(__DIR__).'/extensions/translations.php',
    ]);
}

function fixtures(string $path): string
{
    return dirname(__DIR__).'/tests/Fixtures/'.$path;
}

function attachment(string $file): string
{
    return fixtures('attachments/'.$file);
}

function storage(string $path): string
{
    return fixtures('storage/'.$path);
}

function directoriesFor(string $path): array
{
    return Dir::dirs(Kirby::instance()->roots()->templates().'/'.$path, [], true);
}

function filesFor(string $path): array
{
    return Dir::files(Kirby::instance()->roots()->templates().'/'.$path, ['.gitignore'], true);
}

function deleteFor(string $path): void
{
    foreach (filesFor($path) as $file) {
        F::remove($file);
    }

    foreach (directoriesFor($path) as $dir) {
        Dir::remove($dir);
    }
}
