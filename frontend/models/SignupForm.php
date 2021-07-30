<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model {
	use PasswordTrait;
	public $username;


	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return array_merge(PasswordTrait::rules(), [
			['username', 'trim'],
			['username', 'required'],
			['username', 'unique', 'targetClass' => User::class, 'message' => 'This username has already been taken.'],
			['username', 'string', 'min' => 2, 'max' => 255],

		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return array_merge(PasswordTrait::attributeLabels(), [
			'username' => Yii::t('app', 'User name'),
		]);
	}

	/**
	 * Signs user up.
	 *
	 * @param string $email The email address bind to the user.
	 * @return \common\models\User|false The user created or false.
	 */
	public function signup($email) {
		if (!$this->validate()) {
			return false;
		}
		
		$user = new User();
		$user->username = $this->username;
		$user->email = $email;
		$user->setPassword($this->password);
		$user->generateAuthKey();
		if ($user->save()) {
			return $user;
		}
		foreach ($user->firstErrors as $error) {
			$this->addError('*', $error);
		}
		return false;

	}

}
