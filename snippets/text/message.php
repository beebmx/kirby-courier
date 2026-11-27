<?php
/**
 * @var string $logo
 */
?>
<?php snippet('courier/layout', slots: true) ?>

<?php /*-- Header --*/ ?>
<?php slot('header') ?>
<?php snippet('courier/header', ['url' => site()->url()], slots: true) ?>
<?php if(is_string($logo ?? null)): ?>
<img src="<?= esc($logo, 'attr') ?>" class="logo" alt="<?= esc(site()->title(), 'attr') ?>">
<?php else: ?>
<?= site()->title() ?>
<?php endif ?>
<?php endsnippet() ?>
<?php endslot() ?>

<?php /*-- Body --*/ ?>
<?php slot('body') ?>
<?= $slots->body() ?>
<?php endslot() ?>

<?php /*-- Subcopy --*/ ?>
<?php if ($subcopy = $slots->subcopy()): ?>
<?php slot('subcopy') ?>
<?php snippet('courier/subcopy', slots: true) ?>
<?= $subcopy ?>
<?php endsnippet() ?>
<?php endslot() ?>
<?php endif ?>

<?php /*-- Footer --*/ ?>
<?php slot('footer') ?>
<?php snippet('courier/footer', slots: true) ?>
© <?= date('Y') ?> <?= site()->title() ?>. <?= option('beebmx.courier.message.rights', 'All rights reserved.') ?>
<?php endsnippet() ?>
<?php endslot() ?>

<?php endsnippet() ?>
