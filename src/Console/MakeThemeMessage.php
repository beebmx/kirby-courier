<?php

namespace Beebmx\KirbyCourier\Console;

use Kirby\CLI\CLI;
use Kirby\Cms\App;

class MakeThemeMessage
{
    public function __invoke(CLI $cli): void
    {
        $kirby = $cli->kirby();

        $fake = $cli->arg('fake') === true;
        $name = $cli->argOrPrompt('theme', 'Enter a courier theme name:');
        $file = $this->path($kirby).'/themes/'.$name.'.css';

        if (! $fake) {
            $cli->make($file, __DIR__.'/stubs/theme.stub');
        }

        $cli->success('Courier theme was created.');
    }

    protected function path(App $kirby): string
    {
        return $kirby->roots()->templates().'/'.$this->courier($kirby);
    }

    protected function courier(App $kirby): string
    {
        return $kirby->option('beebmx.courier.path', 'courier');
    }
}
