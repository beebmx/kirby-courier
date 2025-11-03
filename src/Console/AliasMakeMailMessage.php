<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier\Console;

use Kirby\CLI\CLI;

class AliasMakeMailMessage
{
    public function __invoke(CLI $cli): void
    {
        $args = [];
        foreach ($cli->args()->toArray() as $key => $value) {
            if ($key === 'courier') {
                $args[] = $value;
            }
            if ($key === 'blade' && $value === true) {
                $args[] = '--blade';
            }
            if ($key === 'fake' && $value === true) {
                $args[] = '--fake';
            }
        }

        $cli->command('make:courier', ...$args);
    }
}
