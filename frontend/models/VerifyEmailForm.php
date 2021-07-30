<?php

namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;
use yii\helpers\Url;

class VerifyEmailForm extends Model {
	/**
	 * @var string
	 */
	public $email;

	/**
	 * @var string
	 */
	public $token;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return [
			['email', 'trim'],
			['email', 'required'],
			['email', 'email'],
			['email', 'string', 'max' => 255],
			['email', 'unique', 'targetClass' => User::class, 'message' => Yii::t('app', 'This email address has already been taken.')],
			['token', 'required', 'on' => 'verify'],
			['token', 'string', 'on' => 'verify'],
			[['email', 'token'], 'verifyEmail', 'skipOnError' => true, 'on' => 'verify'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function attributeLabels() {
		return [
			'email' => Yii::t('app', 'Email'),
			'token' => Yii::t('app', 'Token'),
		];
	}

	/**
	 * generate and send verify token to the email
	 * @return boolean
	 */
	public function sendEmail() {
		if (!$this->validate()) {
			return false;
		}
		$token = Yii::$app->security->generateRandomString();
		Yii::$app->session->set($this->email, $token);
		$res = Yii::$app->mailer->compose([
			'html' => 'emailVerify-html',
			'text' => 'emailVerify-text',
		], [
			'link' => Url::toRoute(['/site/verify-email', 'email' => $this->email, 'token' => $token], true),
		])
		->setFrom(Yii::$app->params['mailFrom'])
		->setTo($this->email)
		->setSubject(Yii::t('app', 'Verify your email address ') . Yii::$app->name)
		->send();
		if ($res == false) {
			$this->addError('email', Yii::t('app', 'Failed to send email'));
			return false;
		}
		return true;
	}
	
	/**
	 * Verify email
	 *
	 * @return boolean the result of the validation.
	 */
	public function verifyEmail($attribute, $params) {
		$session = Yii::$app->session;
		switch ($attribute) {
		case 'email':
			if (!$session->has($this->$attribute)) {
				$this->addError($attribute, Yii::t('app', "The email is not found. Maybe your token has expired."));
				return false;
			}
			break;
		case 'token':
			$token = $session->get($this->email);
			if ($token != $this->$attribute) {
				$this->addError($attribute, Yii::t('app', "The token is invalid."));
				return false;
			}
			$session->remove($this->email);
			break;
		}
		return true;
	}

}
