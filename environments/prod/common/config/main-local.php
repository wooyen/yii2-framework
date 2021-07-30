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
		'log' => [
			'traceLevel' => 0,
		],
	],
];
