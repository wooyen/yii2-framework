<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $link string */

?>
<div class="verify-email">
	<p>Please follow the link below to verify your email:</p>
	<p><?= Html::a(Html::encode($link), $link) ?></p>
</div>
