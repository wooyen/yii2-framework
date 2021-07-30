<?php
# REPLACEMENT PLACEHOLDERS:
# SENSITIVE:DB_PASSWORD
# END
return [
	'components' => [
		'db' => [
			'dsn' => 'mysql:host=localhost;dbname=yii2advanced',
			'username' => 'yii2',
			'password' => 'DB_PASSWORD',
		],
		'mailer' => [
			'useFileTransport' => true,
		],
		'log' => [
			'traceLevel' => 3,
		],
	],
	'bootstrap' =>[
		'debug',
		'gii',
	],
	'modules' => [
		'debug' => [
			'class' => 'yii\debug\Module',
			'allowedIPs' => [
				'127.0.0.1',
				'::1',
				'192.168.*',
			],
		],
		'gii' => [
			'class' => 'yii\gii\Module',
			'allowedIPs' => [
				'127.0.0.1',
				'::1',
				'192.168.*',
			],
			'generators' => [
				'model' => [
					'class' => 'yii\gii\generators\model\Generator',
					'templates' => [
						'tab' => '@common/giiTemplates/model/default',
					],
				],
			],
		],
	],
];
