<?php

namespace frontend\tests\functional;

use Yii;
use frontend\tests\FunctionalTester;

class PreSignupCest {
	protected $formId = '#form-signup';

	public function _before(FunctionalTester $I) {
		$I->amOnRoute('site/signup');
	}

	public function signupWithEmptyFields(FunctionalTester $I) {
		$I->see('Verify Email', 'h1');
		$I->see('Please input your email address');
	}

}
