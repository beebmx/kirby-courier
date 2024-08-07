<?php

use Kirby\Cms\App as Kirby;

@include_once __DIR__.'/vendor/autoload.php';

Kirby::plugin('beebmx/kirby-courier', [
    'commands' => require_once __DIR__.'/extensions/commands.php',
    'hooks' => require_once __DIR__.'/extensions/hooks.php',
    'snippets' => require_once __DIR__.'/extensions/snippets.php',
    'templates' => require_once __DIR__.'/extensions/templates.php',
]);
