<?php

function fixKirbyHelpersForTesting(): void
{
    $helpers = __DIR__.'/../kirby/config/helpers.php';

    if (file_exists($helpers)) {
        $content = file_get_contents($helpers);
        if (str_contains($content, 'function dump(') && str_contains($content, 'function e(')) {
            $content = str_replace('function dump(', 'function __dump(', $content);
            $content = str_replace('function e(', 'function __e(', $content);
            file_put_contents($helpers, $content);
        }
    }
}

fixKirbyHelpersForTesting();
