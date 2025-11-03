<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier;

class Action
{
    /**
     * Create a new action instance.
     */
    public function __construct(public string $text, public string $url) {}
}
