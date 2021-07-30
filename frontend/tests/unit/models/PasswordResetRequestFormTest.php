<?php

namespace frontend\tests\unit\models;

use Yii;
use frontend\models\PasswordResetRequestForm;
use common\fixtures\UserFixture as UserFixture;
use common\models\User;

class PasswordResetRequestFormTest extends \Codeception\Test\Unit {
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

	public function testSendMessageWithWrongEmailAddress() {
		$model = new PasswordResetRequestForm();
		$model->email = 'not-existing-email@example.com';
		expect_not($model->sendEmail());
	}

	public function testSendEmailSuccessfully() {
		$userFixture = $this->tester->grabFixture('user', 0);
		
		$model = new PasswordResetRequestForm();
		$model->email = $userFixture['email'];

		expect_that($model->sendEmail());
		expect('The user id is stored in cache', Yii::$app->cache->get($model->token))->equals($userFixture['id']);

		$emailMessage = $this->tester->grabLastSentEmail();
		expect('valid email is sent', $emailMessage)->isInstanceOf('yii\mail\MessageInterface');
		expect($emailMessage->getTo())->hasKey($model->email);
		expect($emailMessage->getFrom())->equals(Yii::$app->params['mailFrom']);
	}
}
