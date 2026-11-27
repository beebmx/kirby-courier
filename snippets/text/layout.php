<?php if ($header = $slots->header()): ?>
<?= html_entity_decode(strip_tags($header ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
<?php endif ?>

<?= html_entity_decode(strip_tags(kirbytext($slots->body())), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>

<?php if ($subcopy = $slots->subcopy()):  ?>
<?= html_entity_decode(strip_tags($subcopy), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
<?php endif ?>

<?php if ($footer = $slots->footer()): ?>
<?= html_entity_decode(strip_tags($footer ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8') ?>
<?php endif ?>
