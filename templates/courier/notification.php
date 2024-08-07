<?php snippet('courier/message', ['logo' => $logo], slots: true) ?>

<?php slot('body') ?>
<?php /*-- Greeting --*/ ?>
<?php if (! empty($greeting)): ?>
# <?= $greeting ?>
<?php endif ?>

<?php /*-- Intro Lines --*/ ?>
<?php foreach ($introLines as $line): ?>
<?= $line ?>


<?php endforeach; ?>

<?php /*-- Action Button --*/ ?>
<?php if ($actionText): ?>
<?php $color = match ($level) {
    'success', 'error' => $level,
    default => 'primary',
};
?>
<?php snippet('courier/button', ['url' => $actionUrl, 'color' => $color], slots: true) ?>
<?= $actionText ?>
<?php endsnippet() ?>
<?php endif ?>

<?php /*-- Outro Lines --*/ ?>
<?php foreach ($outroLines as $line): ?>
<?= $line ?>


<?php endforeach; ?>

<?php /*-- Salutation --*/ ?>
<?php if (! empty($salutation)): ?>
<?= $salutation ?>
<?php else: ?>
<?= option('beebmx.kirby-courier.message.salutation', 'Regards') ?>,
<?= option('beebmx.kirby-courier.message.brand_name', site()->title()->or('Courier')) ?>
<?php endif ?>
<?php endslot() ?>

<?php /*-- Subcopy --*/ ?>
<?php if ($actionText): ?>
<?php slot('subcopy') ?>
<?= option('beebmx.kirby-courier.message.notify', 'If you\'re having trouble clicking the button, copy and paste the URL below into your web browser') ?>

<span class="break-all">[<?= $displayableActionUrl ?>](<?= $actionUrl ?>)</span>
<?php endslot() ?>
<?php endif ?>

<?php endsnippet() ?>
