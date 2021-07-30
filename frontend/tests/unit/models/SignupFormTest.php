<?php
namespace frontend\tests\unit\models;

use Yii;
use common\fixtures\UserFixture;
use frontend\models\SignupForm;

class SignupFormTest extends \Codeception\Test\Unit {
	/**
	 * @var \frontend\tests\UnitTester
	 */
	protected $tester;


	public function _before() {
		$this->tester->haveFixtures([
			'user' => [
				'class' => UserFixture::className(),
				'dataFile' => codecept_data_dir() . 'user.php'
			]
		]);
	}

	public function testCorrectSignup() {
		$model = new SignupForm([
			'username' => 'some_username',
			'password' => 'some_password',
			'password_repeat' => 'some_password',
		]);

		$user = $model->signup('some_user@example.com');
		expect_that($user);
		expect($user->username)->equals('some_username');
		expect($user->validatePassword('some_password'))->true();
		expect($user->email)->equals('some_user@example.com');
		expect($user->status)->equals(\common\models\User::STATUS_ACTIVE);
	}

	public function testNotCorrectSignup() {
		$email = 'nicolas.dianna@hotmail.com';
		$model = new SignupForm([
			'username' => 'troy.becker',
			'password' => 'some_password',
			'password_repeat' => 'some_password',
		]);
		expect_not($model->signup($email));
		expect($model->getFirstError('username'))->equals(Yii::t('app', 'This username has already been taken.'));

		$model->username = 'new_user';
		expect_not($model->signup($email));
		expect($model->getFirstError('*'))->equals(Yii::t('app', 'Email "{attribute}" has already been taken.', ['attribute' => $email]));
	}
}
