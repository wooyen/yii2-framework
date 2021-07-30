<?php

use yii\helpers\Html;

$this->title = 'Verify Email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-pending-verify-email">
	<h1><?= Html::encode($this->title) ?></h1>
	<p>Please follow the instructions in the email to verify your email.</p>
<?php
if (!Yii::$app->user->isGuest) {
?>
	<p>Do not logout before you verifying your email address.</p>
<?php
}
?>
</div>
