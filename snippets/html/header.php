<tr>
<td class="header">
<a href="<?= $url ?>" style="display: inline-block;">
<?php if (trim($slot) === 'Site Title' || trim($slot) === 'Courier'): ?>
<img src="<?= asset('media/plugins/beebmx/kirby-courier/logo-400.png')->url() ?>" class="logo" alt="Kirby Courier">
<?php else: ?>
<?= $slot ?>
<?php endif ?>
</a>
</td>
</tr>
