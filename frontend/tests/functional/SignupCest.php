<?php

namespace frontend\tests\functional;

use Yii;
use frontend\tests\FunctionalTester;

class SignupCest {
	protected $formId = '#form-signup';

	private $blankUsernameMsg;
	private $blankPasswordMsg;

	public function _before(FunctionalTester $I) {
		Yii::$app->session->set('email', 'somebody@example.com');
		$I->amOnRoute('site/signup');
		$this->blankUsernameMsg = Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'User name')]);
		$this->blankPasswordMsg = Yii::t('yii', '{attribute} cannot be blank.', ['attribute' => Yii::t('app', 'Password')]);
	}

	public function signupWithEmptyFields(FunctionalTester $I) {
		$I->see('Signup', 'h1');
		$I->see('Please fill out the following fields to signup:');
		$I->submitForm($this->formId, []);
		$I->seeValidationError($this->blankUsernameMsg);
		$I->seeValidationError($this->blankPasswordMsg);

	}

	public function signupWithNotConfirmedPassword(FunctionalTester $I) {
		$I->submitForm(
			$this->formId, [
			'SignupForm[username]' => 'tester',
			'SignupForm[password]' => 'tester_password',
			'SignupForm[password_repeat]' => 'tester_password2',
		]
		);
		$I->dontSee($this->blankUsernameMsg, '.help-block');
		$I->dontSee($this->blankPasswordMsg, '.help-block');
		$I->see(Yii::t('yii', '{attribute} must be equal to "{attribute2}".', ['attribute' => Yii::t('app', 'Confirm password'), 'attribute2' => Yii::t('app', 'Password')]));
	}

	public function signupSuccessfully(FunctionalTester $I) {
		$I->submitForm($this->formId, [
			'SignupForm[username]' => 'tester',
			'SignupForm[password]' => 'tester_password',
			'SignupForm[password_repeat]' => 'tester_password',
		]);

		$I->seeRecord('common\models\User', [
			'username' => 'tester',
			'email' => 'somebody@example.com',
			'status' => \common\models\User::STATUS_ACTIVE
		]);

		$I->see(Yii::t('app', 'Thank you for registration.'));
	}
}
