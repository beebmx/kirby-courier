<?php

arch()->preset()->php();

arch()->preset()->security();

arch('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();
