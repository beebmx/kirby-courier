<?php

use Kirby\Cms\App;

@include_once __DIR__.'/vendor/autoload.php';

App::plugin('beebmx/courier', [
    'authChallenges' => require_once __DIR__.'/extensions/authChallenges.php',
    'commands' => require_once __DIR__.'/extensions/commands.php',
    'hooks' => require_once __DIR__.'/extensions/hooks.php',
    'options' => require_once __DIR__.'/extensions/options.php',
    'snippets' => require_once __DIR__.'/extensions/snippets.php',
    'templates' => require_once __DIR__.'/extensions/templates.php',
    'translations' => require_once __DIR__.'/extensions/translations.php',
]);
