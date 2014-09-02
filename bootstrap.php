<?php

require_once __DIR__.'/vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);

if (!file_exists(__DIR__ . '/config/config.php')) {
	die("Config file not found: config/config.php");
}

$config = include('config/config.php');

chdir(__DIR__);
mb_internal_encoding('UTF-8');