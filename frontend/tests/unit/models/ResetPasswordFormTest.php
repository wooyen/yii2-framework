<?php

namespace frontend\tests\unit\models;

use Yii;
use common\fixtures\UserFixture;
use common\models\User;
use frontend\models\ResetPasswordForm;

class ResetPasswordFormTest extends \Codeception\Test\Unit{
	/**
	 * @var \frontend\tests\UnitTester
	 */
	protected $tester;


	public function _before() {
		$this->tester->haveFixtures([
			'user' => [
				'class' => UserFixture::className(),
				'dataFile' => codecept_data_dir() . 'user.php'
			],
		]);
		Yii::$app->cache->set('right_token', 1, 3600);
		Yii::$app->cache->set('token_with_wrong_user_id', 999, 3600);
	}

	public function _after() {
		Yii::$app->cache->delete('right_token');
		Yii::$app->cache->delete('token_with_wrong_user_id');
	}

	public function testResetWrongToken() {
		$model = new ResetPasswordForm;
		$model->token = '';
		$model->password = '12345678';
		$model->password_repeat = '12345678';
		expect('Wrong token', $model->validate())->false();
		expect('Wrong token', $model->hasErrors('token'))->true();
		$model->token = 'notexistingtoken';
		$model->clearErrors();
		expect('Wrong token', $model->validate())->false();
		expect('Wrong token', $model->hasErrors('token'))->true();
		$model->token = 'token_with_wrong_user_id';
		$model->clearErrors();
		expect('Wrong token', $model->validate())->false();
		expect('Wrong token', $model->hasErrors('token'))->true();
	}

	public function testResetCorrectToken() {
		$password = '12345678';
		$user = $this->tester->grabFixture('user', 0);
		$model = new ResetPasswordForm;
		$model->token = 'right_token';
		$model->password = $password;
		$model->password_repeat = $password;
		expect_that($model->resetPassword());
		expect('password has changed', User::findIdentity($user['id'])->validatePassword($password))->true();
	}

}
