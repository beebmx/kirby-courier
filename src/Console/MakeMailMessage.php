<?php

namespace Beebmx\KirbyCourier\Console;

use Kirby\CLI\CLI;
use Kirby\Cms\App;

class MakeMailMessage
{
    public function __invoke(CLI $cli): void
    {
        $kirby = $cli->kirby();

        $fake = $cli->arg('fake') === true;
        $name = $cli->argOrPrompt('courier', 'Enter a courier mail message name:');
        $stub = $cli->arg('blade') === true
            ? __DIR__.'/stubs/mail.blade.stub'
            : __DIR__.'/stubs/mail.stub';

        $extension = $cli->arg('blade') === true
            ? '.blade.php'
            : '.php';

        $file = $this->path($kirby).'/'.$name.$extension;

        if (! $fake) {
            $cli->make($file, $stub);
        }

        $cli->success('Courier mail message was created.');
    }

    protected function path(App $kirby): string
    {
        return $kirby->roots()->templates().'/'.$this->courier($kirby);
    }

    protected function courier(App $kirby): string
    {
        return $kirby->option('beebmx.kirby-courier.path', 'courier');
    }
}
