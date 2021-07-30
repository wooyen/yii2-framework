<?php
namespace frontend\controllers;

use Yii;
use common\models\LoginForm;
use frontend\models\ContactForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\RegisterEmailForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\VerifyEmailForm;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Site controller
 */
class SiteController extends Controller {
	/**
	 * {@inheritdoc}
	 */
	public function behaviors() {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['logout', 'signup'],
				'rules' => [
					[
						'actions' => ['signup'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actions() {
		return [
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	/**
	 * Displays homepage.
	 *
	 * @return mixed
	 */
	public function actionIndex() {
		return $this->render('index');
	}

	/**
	 * Logs in a user.
	 *
	 * @return mixed
	 */
	public function actionLogin() {
		if (!Yii::$app->user->isGuest) {
			return $this->goHome();
		}

		$model = new LoginForm();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			return $this->goBack();
		} else {
			$model->password = '';

			return $this->render('login', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Logs out the current user.
	 *
	 * @return mixed
	 */
	public function actionLogout() {
		Yii::$app->user->logout();

		return $this->goHome();
	}

	/**
	 * Displays contact page.
	 *
	 * @return mixed
	 */
	public function actionContact() {
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
				Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
			} else {
				Yii::$app->session->setFlash('error', 'There was an error sending your message.');
			}

			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays about page.
	 *
	 * @return mixed
	 */
	public function actionAbout() {
		return $this->render('about');
	}

	public function actionRegisterEmail() {
		$model = new VerifyEmailForm;
		$req = Yii::$app->request;
		if ($req->isPost && $model->load($req->post()) && $model->sendEmail()) {
			return $this->redirect('pending-verify-email');
		}
		return $this->render('register-email', [
			'model' => $model,
		]);
	}

	public function actionPendingVerifyEmail() {
		return $this->render('pending-verify-email');
	}

	/**
	 * Signs user up.
	 *
	 * @return mixed
	 */
	public function actionSignup() {
		$session = Yii::$app->session;
		if (!$session->has('email')) {
			return $this->redirect('register-email');
		}
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post()) && $model->signup($session->get('email'))) {
			$session->setFlash('success', Yii::t('app', 'Thank you for registration.'));
			$session->remove('email');
			return $this->goHome();
		}

		return $this->render('signup', [
			'model' => $model,
		]);
	}

	/**
	 * Requests password reset.
	 *
	 * @return mixed
	 */
	public function actionRequestPasswordReset() {
		$model = new PasswordResetRequestForm();
		if ($model->load(Yii::$app->request->post()) && $model->validate()) {
			if ($model->sendEmail()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Check your email for further instructions.'));

				return $this->goHome();
			} else {
				Yii::$app->session->setFlash('error', Yii::t('app', 'Sorry, we are unable to reset password for the provided email address.'));
			}
		}

		return $this->render('requestPasswordResetToken', [
			'model' => $model,
		]);
	}

	/**
	 * Resets password.
	 *
	 * @param string $token
	 * @return mixed
	 * @throws BadRequestHttpException
	 */
	public function actionResetPassword($token) {
		$model = new ResetPasswordForm;
		$model->token = $token;

		if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
			Yii::$app->session->setFlash('success', 'New password saved.');

			return $this->goHome();
		}

		return $this->render('resetPassword', [
			'model' => $model,
		]);
	}

	/**
	 * Verify email address
	 *
	 * @param string $token
	 * @throws BadRequestHttpException
	 * @return yii\web\Response
	 */
	public function actionVerifyEmail() {
		$model = new VerifyEmailForm(['scenario' => 'verify']);
		$model->load(Yii::$app->request->get(), '');
		if (!$model->validate()) {
			Yii::$app->session->setFlash('error', $model->firstErrors);
			return $this->redirect('register-email');
		}
		$session = Yii::$app->session;
		$session->setFlash('success', Yii::t('app', "Your email {email} has been verified.", ['email' => $model->email]));
		if (Yii::$app->user->isGuest) {
			$session->set('email', $model->email);
			return $this->redirect('signup');
		}
		$user = Yii::$app->user->identity;
		$user->email = $model->email;
		if (!$user->save()) {
			$session->setFlash('error', $user->firstErrors);
		} else {
			$session->setFlash('success', Yii::t('app', "Your email has been updated."));
		}

		return $this->goHome();
	}
}
