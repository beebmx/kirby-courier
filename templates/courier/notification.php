<?php
/**
 * @var Kirby\Cms\App $kirby
 * @var array $introLines
 * @var array $outroLines
 * @var string $actionText
 * @var string $actionUrl
 * @var string $code
 * @var string $displayableActionUrl
 * @var string $greeting
 * @var string $level
 * @var string $logo
 * @var string $salutation
 * @var string $subject
 */
?>
<?php snippet('courier/message', ['logo' => $logo ?? null], slots: true) ?>
<?php slot('body') ?>
<?php /*-- Greeting --*/ ?>
<?php if (! empty($greeting)): ?>
# <?= $greeting ?>
<?php endif ?>

<?php /*-- Intro Lines --*/ ?>
<?php foreach ($introLines as $line): ?>
<?= $line ?>


<?php endforeach; ?>

<?php /*-- Code --*/ ?>
<?php if (! empty($code)): ?>
<?php snippet('courier/code', slots: true) ?>
<?= $code ?>
<?php endsnippet() ?>
<?php endif ?>

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
<?= option('beebmx.courier.message.salutation', 'Regards') ?>,
<?= option('beebmx.courier.message.brand_name', site()->title()->or('Courier')) ?>
<?php endif ?>
<?php endslot() ?>

<?php /*-- Subcopy --*/ ?>
<?php if ($actionText): ?>
<?php slot('subcopy') ?>
<?= option('beebmx.courier.message.notify', 'If you\'re having trouble clicking the button, copy and paste the URL below into your web browser') ?>

<span class="break-all">[<?= $displayableActionUrl ?>](<?= $actionUrl ?>)</span>
<?php endslot() ?>
<?php endif ?>

<?php endsnippet() ?>
