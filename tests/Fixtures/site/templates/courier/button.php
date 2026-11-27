<?php snippet('courier/message', slots: true) ?>
<?php slot('body') ?>
<?php snippet('courier/button', ['url' => $url, 'color' => $color, 'align' => $align], slots: true) ?>
Send
<?php endsnippet() ?>
<?php endslot() ?>
<?php endsnippet() ?>
