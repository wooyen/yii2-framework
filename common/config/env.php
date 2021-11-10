<?php
$env_name = 'YII2_INI_FILE';
$default_ini_file = '.yii2.ini';
$ini_file = getenv('YII2_INI_FILE');
if (empty($ini_file)) {
	if (!empty($home = getenv('HOME')) && is_readable("$home/$default_ini_file")) {
		$ini_file = "$home/$default_ini_file";
	} else if (is_readable("/etc/$default_ini_file")) {
		$ini_file = "/etc/$default_ini_file";
	}
} else if (!is_readable($ini_file)) {
	die("The specified ini file $ini_file does not exist or is not readable.");
}
$yii_env = @parse_ini_file($ini_file);
if ($yii_env === false) {
	$yii_env = [];
}
if (!array_key_exists('ENV', $yii_env)) {
	$yii_env['ENV'] = 'dev';
}
if (!array_key_exists('DEBUG', $yii_env)) {
	$yii_env['DEBUG'] = $yii_env['ENV'] != 'prod';
}
defined('YII_DEBUG') or define('YII_DEBUG', $yii_env['DEBUG']);
defined('YII_ENV') or define('YII_ENV', $yii_env['ENV']);
