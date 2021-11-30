<?php
require __DIR__ . '/env.php';

return yii\helpers\ArrayHelper::merge(
	require __DIR__ . '/main.php',
	require __DIR__ . "/main-{$yii_env['ENV']}.php",
	require __DIR__ . "/codeception-{$yii_env['ENV']}.php",
	[
		'components' => [
			'request' => [
				'cookieValidationKey' => "common-codeception_" . @$yii_env['COOKIE_VALIDATION_KEY'],
			],
		],
	]
);
