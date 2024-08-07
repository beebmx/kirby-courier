<?php snippet('courier/message', slots: true) ?>
<?php slot('body') ?>
# Simple courier template

The body of your message.

Thanks,
<?= site()->title() ?>
<?php endslot() ?>
<?php endsnippet() ?>
