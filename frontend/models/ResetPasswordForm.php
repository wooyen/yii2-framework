<?php
namespace frontend\models;

use Yii;
use common\models\User;
use yii\base\Model;

/**
 * Password reset form
 */
class ResetPasswordForm extends Model {
	use PasswordTrait;
	public $token;

	/**
	 * @var \common\models\User
	 */
	private $_user;

	/**
	 * {@inheritdoc}
	 */
	public function rules() {
		return array_merge(PasswordTrait::rules(), [
			['token', 'required'],
			['token', 'string'],
			['token', 'verifyToken'],
		]);
	}

	/**
	 * Verify token
	 *
	 * @param $attribute string The attribute name.
	 * @param $value mix The value of the attribute.
	 * @return bool the result of the validation.
	 */
	public function verifyToken($attribute, $params) {
		$id = Yii::$app->cache->get($this->$attribute);
		if ($id === false || empty($this->_user = User::findIdentity($id))) {
			$this->addError($attribute, Yii::t('app', 'The token is invalid'));
			return false;
		}
		return true;
	}
	/**
	 * Resets password.
	 *
	 * @return bool if password was reset.
	 */
	public function resetPassword() {
		if (!$this->validate()) {
			return false;
		}
		$user = $this->_user;
		$user->setPassword($this->password);
		Yii::$app->cache->delete($this->token);
		$user->generateAuthKey();

		return $user->save(false);
	}
}
