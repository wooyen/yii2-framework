<?php
namespace frontend\models;

use Yii;

/**
 * Password trait
 */
trait PasswordTrait {
	public $password;
	public $password_repeat;

	public function rules() {
		return [
			['password', 'required'],
			['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
			['password_repeat', 'compare', 'compareAttribute' => 'password'],
		];
	}

	public function attributeLabels() {
		return [
			'password' => Yii::t('app', 'Password'),
			'password_repeat' => Yii::t('app', 'Confirm password'),
		];
	}
}
