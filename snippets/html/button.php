<?php $align = $align ?? 'center' ?>
<table class="action" align="<?= esc($align, 'attr') ?>" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="<?= esc($align, 'attr') ?>">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="<?= esc($align, 'attr') ?>">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td>
<a href="<?= esc($url, 'attr') ?>" class="button button-<?= esc($color ?? 'primary', 'attr') ?>" target="_blank" rel="noopener"><?= $slot ?></a>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
