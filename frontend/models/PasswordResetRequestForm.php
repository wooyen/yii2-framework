<?php
namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\helpers\Url;

/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model {
	public $email;

	private $_token;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'exist',
				'targetClass' => User::class,
				'filter' => ['status' => User::STATUS_ACTIVE],
				'message' => 'There is no user with this email address.'
			],
		];
	}

	public function getToken() {
		return $this->_token;
	}

	/**
	 * Sends an email with a link, for resetting the password.
	 *
	 * @return bool whether the email was send
	 */
	public function sendEmail() {
		/* @var $user User */
		$user = User::findOne([
			'status' => User::STATUS_ACTIVE,
			'email' => $this->email,
		]);

		if (!$user) {
			return false;
		}
		do {
			$this->_token = Yii::$app->security->generateRandomString();
		} while (!Yii::$app->cache->add($this->_token, $user->id, 86400));

		return Yii::$app
			->mailer
			->compose([
				'html' => 'passwordResetToken-html',
				'text' => 'passwordResetToken-text'
			], [
				'link' => Url::toRoute(['/site/reset-password', 'token' => $this->_token], true),
				'name' => $user->username,
			])
			->setFrom(Yii::$app->params['mailFrom'])
			->setTo($this->email)
			->setSubject('Password reset for ' . Yii::$app->name)
			->send();
	}
}
