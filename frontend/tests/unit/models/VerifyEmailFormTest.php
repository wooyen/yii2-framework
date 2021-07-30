<?php

namespace frontend\tests\unit\models;

use Yii;
use common\fixtures\UserFixture;
use frontend\models\VerifyEmailForm;

class VerifyEmailFormTest extends \Codeception\Test\Unit {
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

	public function testSendEmailWrong() {
		$model = new VerifyEmailForm;
		$model->email = 'example.com';
		expect('wrong email address.', $model->sendEmail())->false();
		expect($model->getFirstError('email'))->equals(Yii::t('yii', '{attribute} is not a valid email address.', ['attribute' => Yii::t('app', 'Email')]));
	}

	public function testSendEmailCorrect() {
		$model = new VerifyEmailForm;
		$model->email = 'somebody@example.com';
		expect('send email succesfully.', $model->sendEmail())->true();
		$this->tester->seeEmailIsSent();
		$mail = $this->tester->grabLastSentEmail();
		expect($mail)->isInstanceOf('yii\mail\MessageInterface');
		expect($mail->getTo())->hasKey('somebody@example.com');
		$token = Yii::$app->session->get($model->email);
		expect($mail->toString())->stringContainsString($token);
	}

	public function testWrongEmail() {
		$model = new VerifyEmailForm(['scenario' => 'verify']);
		$model->email = 'not_exist@example.com';
		$model->token = 'not_exist_token';
		expect($model->validate())->false();
		expect($model->getFirstError('email'))->equals(Yii::t('app', "The email is not found. Maybe your token has expired"));
	}

	public function testWrongToken() {
		Yii::$app->session->set('somebody@example.com', 'valid_token');
		$model = new VerifyEmailForm(['scenario' => 'verify']);
		$model->email = 'somebody@example.com';
		$model->token = 'invalid_token';
		expect($model->validate())->false();
		expect($model->getFirstError('token'))->equals(Yii::t('app', "The token is invalid."));
	}
	
	public function testCorrectToken() {
		Yii::$app->session->set('somebody@example.com', 'valid_token');
		$model = new VerifyEmailForm(['scenario' => 'verify']);
		$model->email = 'somebody@example.com';
		$model->token = 'valid_token';
		expect($model->validate())->true();
	}
	
}
