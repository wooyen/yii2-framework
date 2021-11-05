<?php
require __DIR__ . '/env.php';

return yii\helpers\ArrayHelper::merge(
	require __DIR__ . '/main.php',
	require __DIR__ . "/main-{$yii_env['ENV']}.php",
	require __DIR__ . '/test.php',
	require __DIR__ . "/test-{$yii_env['ENV']}.php",
	[
		'components' => [
			'request' => [
				'cookieValidationKey' => strtr(base64_encode(openssl_random_pseudo_bytes(24)), '+/=', '_-.'),
			],
		],
	]
);
