<?php snippet('courier/message', slots: true) ?>
<?php slot('body') ?>
# Simple emails template

The body of your message.

<?php snippet('courier/button', ['url' => ''], slots: true) ?>
Button
<?php endsnippet() ?>

Thanks,
<?= site()->title() ?>
<?php endslot() ?>
<?php endsnippet() ?>
