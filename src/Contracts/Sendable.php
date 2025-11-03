<?php

declare(strict_types=1);

namespace Beebmx\KirbyCourier\Contracts;

interface Sendable
{
    public function data();

    public function send();

    public function toArray();
}
