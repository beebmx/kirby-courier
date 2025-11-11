<?php if ($header = $slots->header()): ?>
<?= strip_tags($header ?? '') ?>
<?php endif ?>

<?= strip_tags(kirbytext($slots->body())) ?>

<?php if ($subcopy = $slots->subcopy()):  ?>
<?= strip_tags($subcopy) ?>
<?php endif ?>

<?php if ($footer = $slots->footer()): ?>
<?= strip_tags($footer ?? '') ?>
<?php endif ?>
