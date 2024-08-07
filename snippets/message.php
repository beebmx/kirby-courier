<?php snippet('courier/layout', slots: true) ?>

<?php /*-- Header --*/ ?>
<?php slot('header') ?>
<?php snippet('courier/header', ['url' => site()->url()], slots: true) ?>
<?php $logo = option('beebmx.kirby-courier.logo') ?>
<?php if($logo instanceof \Closure): ?>
<img src="<?= $logo()->url() ?>" class="logo" alt="<?= site()->title() ?>">
<?php elseif(is_string($logo)): ?>
<img src="<?= $logo ?>" class="logo" alt="<?= site()->title() ?>">
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
© <?= date('Y') ?> <?= site()->title() ?>. <?= option('beebmx.kirby-courier.message.rights', 'All rights reserved.') ?>
<?php endsnippet() ?>
<?php endslot() ?>

<?php endsnippet() ?>
