<?php

use \Symfony\Component\HttpFoundation\Request;
use \Silex\Application;

define('DS', DIRECTORY_SEPARATOR);
chdir(__DIR__);
mb_internal_encoding('UTF-8');
header('Content-type: text/html; charset=UTF-8');

require_once __DIR__.'/vendor/autoload.php';

$app = new Application();
$app['debug'] 	= (getenv('APPLICATION_ENV') !== 'production');

$config = include('config/config.default.php');

if (file_exists(__DIR__ . '/config/config.php')) {
	$config = array_merge($config, include('config/config.php'));
}

/* Silex */

$app['uri'] 	= $_SERVER['REQUEST_URI'];
$app['config'] 	= $config;
$app['dm'] = include('doctrine.php');

/* Providers */
$app->register(new \Silex\Provider\LocaleServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider(), ['locale_fallbacks' => ['pt_BR']]);
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());

require_once 'events.php';