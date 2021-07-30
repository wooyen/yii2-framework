<?php

namespace frontend\tests\functional;

use Yii;
use common\fixtures\UserFixture;
use frontend\tests\FunctionalTester;

class UserVerifyEmailCest {
	/**
	 * Load fixtures before db transaction begin
	 * Called in _before()
	 * @see \Codeception\Module\Yii2::_before()
	 * @see \Codeception\Module\Yii2::loadFixtures()
	 * @return array
	 */
	public function _fixtures() {
		return [
			'user' => [
				'class' => UserFixture::className(),
				'dataFile' => codecept_data_dir() . 'user.php',
			],
		];
	}

	public function _before(FunctionalTester $I) {
		Yii::$app->user->login($I->grabFixture('user', 0));
		Yii::$app->session->setFlash('somebody@example.com', 'a_random_token');
	}

	public function checkEmptyEmail(FunctionalTester $I) {
		$I->amOnRoute('site/verify-email', ['email' => '', 'token' => 'dummy']);
		$I->canSee(Yii::t('yii', '{attribute} cannot be blank', ['attribute' => Yii::t('app', 'Email')]));
	}

	public function checkEmptyToken(FunctionalTester $I) {
		$I->amOnRoute('site/verify-email', ['email' => 'a@b.cc', 'token' => '']);
		$I->canSee(Yii::t('yii', '{attribute} cannot be blank', ['attribute' => Yii::t('app', 'Token')]));
	}

	public function checkInvalidEmail(FunctionalTester $I) {
		$I->amOnRoute('site/verify-email', ['email' => 'wrong@example.com', 'token' => 'a_random_token']);
		$I->canSee(Yii::t('app', "The email is not found. Maybe your token has expired."));
	}

	public function checkInvalidToken(FunctionalTester $I) {
		$I->amOnRoute('site/verify-email', ['email' => 'somebody@example.com', 'token' => 'wrong_token']);
		$I->canSee(Yii::t('app', "The token is invalid."));
	}

	public function checkEmailDuplicate(FunctionalTester $I) {
		Yii::$app->session->setFlash('test@mail.com', 'a_random_token');
		$I->amOnRoute('site/verify-email', ['email' => 'test@mail.com', 'token' => 'a_random_token']);
		$I->canSee(Yii::t('app', "This email address has already been taken."));
	}

	public function checkSuccessVerification(FunctionalTester $I) {
		$I->amOnRoute('site/verify-email', ['email' => 'somebody@example.com', 'token' => 'a_random_token']);
		$I->canSee(Yii::t('app', "Your email has been updated."));
		$I->seeRecord('common\models\User', [
			'username' => 'okirlin',
			'email' => 'somebody@example.com',
		]);
	}
}
