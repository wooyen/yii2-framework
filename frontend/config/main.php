<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . "/../../common/config/params-{$yii_env['ENV']}.php",
	require __DIR__ . '/params.php',
	require __DIR__ . "/params-{$yii_env['ENV']}.php"
);
return [
	'id' => 'app-frontend',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-frontend',
			'cookieValidationKey' => "app-frontend_" . @$yii_env['COOKIE_VALIDATION_KEY'],
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
		],
		'session' => [
			'name' => 'advanced-frontend',
		],
		'errorHandler' => [
			'errorAction' => 'site/error',
		],
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			],
		],
	],
	'params' => $params,
];
