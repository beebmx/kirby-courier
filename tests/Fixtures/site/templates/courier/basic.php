<?php snippet('courier/message', slots: true) ?>
<?php slot('body') ?>
# Basic courier template for: <?= $title ?>

The body of your message.

Thanks,
<?= site()->title() ?>
<?php endslot() ?>
<?php endsnippet() ?>
