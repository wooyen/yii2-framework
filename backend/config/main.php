<?php
$params = array_merge(
	require __DIR__ . '/../../common/config/params.php',
	require __DIR__ . "/../../common/config/params-{$yii_env['ENV']}.php",
	require __DIR__ . '/params.php',
	require __DIR__ . "/params-{$yii_env['ENV']}.php"
);
if (($cvk = file_get_contents(__DIR__ . '/../runtime/cookieValidationKey')) === false) {
	$cvk = strtr(base64_encode(openssl_random_pseudo_bytes(24)), '+/=', '_-.');
	file_put_contents(__DIR__ . '/../runtime/cookieValidationKey', $cvk);
}

return [
	'id' => 'app-backend',
	'basePath' => dirname(__DIR__),
	'controllerNamespace' => 'backend\controllers',
	'bootstrap' => ['log'],
	'modules' => [],
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-backend',
			'cookieValidationKey' => $cvk,
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
