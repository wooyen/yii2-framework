<?php
$pattern = '/^YII2_(.+)$/';
$yii_env = [];
foreach ($_SERVER as $k => $v) {
	if (preg_match($pattern, $k, $matches) > 0 && getenv($k) == $v) {
		$yii_env[$matches[1]] = $v;
	}
}
if (array_key_exists('INI_FILE', $yii_env)) {
	$ini = @parse_ini_file($yii_env['INI_FILE']);
	if ($ini !== false) {
		$yii_env = array_merge($yii_env, $ini);
	}
}
if (!array_key_exists('ENV', $yii_env)) {
	$yii_env['ENV'] = 'dev';
}
if (!array_key_exists('DEBUG', $yii_env)) {
	$yii_env['DEBUG'] = $yii_env['ENV'] != 'prod';
}
defined('YII_DEBUG') or define('YII_DEBUG', $yii_env['DEBUG']);
defined('YII_ENV') or define('YII_ENV', $yii_env['ENV']);
