<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Verify Email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verify-email">
	<h1><?= Html::encode($this->title); ?></h1>
	<p>Please input your email address</p>
	<div class="row">
		<div class="col-lg-5">
<?php
$form = ActiveForm::begin(['id' => 'form-verify-email']);
?>
			<?= $form->field($model, 'email')->textInput(['autofocus' => true]); ?>
			<div class="form-group">
				<?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'verify-email-button']); ?>
			</div>
<?php
ActiveForm::end();
?>
		</div>
	</div>
</div>
