<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $link string */

?>
<div class="password-reset">
	<p>Hello <?= Html::encode($name) ?>,</p>

	<p>Follow the link below to reset your password:</p>

	<p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
