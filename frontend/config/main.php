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
	'id' => 'app-frontend',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log'],
	'controllerNamespace' => 'frontend\controllers',
	'components' => [
		'request' => [
			'csrfParam' => '_csrf-frontend',
			'cookieValidationKey' => $cvk,
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
