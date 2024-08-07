<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?= site()->title()->or('Courier') ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="color-scheme" content="light">
<meta name="supported-color-schemes" content="light">
<style>
@media only screen and (max-width: 640px) {
.inner-body {
width: 100% !important;
border-radius: 0 !important;
}

.footer {
width: 100% !important;
}
}

@media only screen and (max-width: 500px) {
.button {
width: 100% !important;
}
}
</style>
</head>
<body>

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="center">
<table class="content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<!-- Email Header -->
<?php if ($header = $slots->header()): ?>
<?= $header ?>
<?php endif ?>

<!-- Email Body -->
<tr>
<td class="body" width="100%" cellpadding="0" cellspacing="0" style="border: hidden !important;">
<table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<!-- Body content -->
<tr>
<td class="content-cell">
<?= kirbytext($slots->body()) ?>

<!-- Body subcopy -->
<?php if ($subcopy = $slots->subcopy()):  ?>
<?= $subcopy ?>
<?php endif ?>
</td>
</tr>
</table>
</td>
</tr>

<!-- Email Footer -->
<?php if ($footer = $slots->footer()): ?>
<?= $footer ?>
<?php endif ?>
</table>
</td>
</tr>
</table>
</body>
</html>
