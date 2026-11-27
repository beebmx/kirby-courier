<?php

/**
 * Loaded through `auto_prepend_file` (see the `test` composer script),
 * so Kirby skips its own `e()` and `dump()` helpers before Composer
 * loads the `illuminate/support` ones with the same name.
 */
define('KIRBY_HELPER_E', false);
define('KIRBY_HELPER_DUMP', false);
