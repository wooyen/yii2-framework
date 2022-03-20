<?php
function parse_ini_file_ex($file, &$loaded_files) {
	if (empty($loaded_files)) {
		$loaded_files = [];
	}
	$file = realpath($file);
	if (in_array($file, $loaded_files)) {
		return [];
	}
	$arr = parse_ini_file($file);
	if ($arr === false) {
		error_log("Failed to parse ini file $file.");
		return false;
	}
	$loaded_files[] = $file;
	if (!array_key_exists('INI_DIR', $arr)) {
		return $arr;
	}
	$ini_dirs = (array)$arr['INI_DIR'];
	unset($arr['INI_DIR']);
	foreach ($ini_dirs as $ini_dir) {
		if ($ini_dir[0] != '/') {
			$ini_dir = dirname($file) . '/' . $ini_dir;
		}
		$dir = dir($ini_dir);
		if ($dir === false) {
			error_log("Can not open directory $ini_dir.");
			continue;
		}
		while (($entry = $dir->read()) !== false) {
			if (substr($entry, -4) != '.ini') {
				continue;
			}
			$res = parse_ini_file_ex("$ini_dir/$entry", $loaded_files);
			if ($res !== false) {
				$arr = array_merge_recursive($arr, $res);
			}
		}
	}
	return $arr;
}
$default_ini_file = '.yii2.ini';
$ini_file = getenv('YII2_INI_FILE');
$loaded_ini_files = [];
if (empty($ini_file)) {
	if (!empty($home = getenv('HOME')) && is_file("$home/$default_ini_file")) {
		$ini_file = "$home/$default_ini_file";
	} else if (is_file("/etc/$default_ini_file")) {
		$ini_file = "/etc/$default_ini_file";
	}
}
$yii_env = empty($ini_file) ? [] : parse_ini_file_ex($ini_file, $loaded_ini_files);
if (!array_key_exists('ENV', $yii_env)) {
	$yii_env['ENV'] = 'dev';
}
if (!array_key_exists('DEBUG', $yii_env)) {
	$yii_env['DEBUG'] = $yii_env['ENV'] != 'prod';
}
defined('YII_DEBUG') or define('YII_DEBUG', $yii_env['DEBUG']);
defined('YII_ENV') or define('YII_ENV', $yii_env['ENV']);
