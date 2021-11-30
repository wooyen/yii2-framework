<?php
$default_ini_file = '.yii2.ini';
$ini_file = getenv('YII2_INI_FILE');
if (empty($ini_file)) {
	if (!empty($home = getenv('HOME')) && is_file("$home/$default_ini_file")) {
		$ini_file = "$home/$default_ini_file";
	} else if (is_file("/etc/$default_ini_file")) {
		$ini_file = "/etc/$default_ini_file";
	}
} else if (!is_file($ini_file)) {
	die("The specified ini file $ini_file does not exist or is not readable.");
}
if (!empty($ini_file)) {
	$yii_env = parse_ini_file($ini_file);
	if ($yii_env === false) {
		die("Can not parse the ini file $ini_file.");
	}
	$loaded_ini_files = [$ini_file];
}
if (array_key_exists('INI_DIR', $yii_env)) {
	$ini_dir = $yii_env['INI_DIR'];
	if (substr($ini_idr, 0, 1) != '/') {
		$ini_dir = dirname($ini_file) . '/' . $ini_dir;
	}
	$dir = dir($ini_dir);
	if ($dir === false) {
		die("Can not open directory $ini_dir.");
	}
	while (($entry = $dir->read()) !== false) {
		if (preg_match('/^[^\.].*\.ini$/', $entry) > 0) {
			$file = "$ini_dir/$entry";
			$loaded_ini_files[] = $file;
			$res = parse_ini_file($file);
			if ($res === false) {
				die("Can not parse the ini file $file.");
			}
			$yii_env = array_merge($yii_env, $res);
		}
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
