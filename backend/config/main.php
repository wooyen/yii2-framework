<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . "/../../common/config/params-{$yii_env['ENV']}.php",
	require __DIR__ . '/params.php',
	require __DIR__ . "/params-{$yii_env['ENV']}.php"
);

return [
	'id' => 'app-backend',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log'],
	'modules' => [],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-backend',
			'cookieValidationKey' => "app-backend_" . @$yii_env['COOKIE_VALIDATION_KEY'],
		],
		'user' => [
			'identityClass' => 'common\models\User',
			'enableAutoLogin' => true,
			'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
		],
		'session' => [
			'name' => 'advanced-backend',
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
