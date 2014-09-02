<?php

use \Symfony\Component\HttpFoundation\Request;
use \Silex\Application;

require_once 'bootstrap.php';

header('Content-type: text/html; charset=UTF-8');
/* Silex */
$app = new Application();
$app['debug'] = true; // (getenv('APPLICATION_ENV') == 'development');
$app['uri'] = $_SERVER['REQUEST_URI'];
$app['config'] = $config;

$app['dm'] = include('doctrine.php');

/* Providers */
$app->register(new \Silex\Provider\LocaleServiceProvider());
$app->register(new \Silex\Provider\TranslationServiceProvider(), ['locale_fallbacks' => ['pt_BR']]);
$app->register(new \Silex\Provider\SessionServiceProvider());
$app->register(new \Silex\Provider\FormServiceProvider());
$app->register(new \Caf\Slugify\Bridge\Silex\SlugifyServiceProvider());
$app->register(new \Silex\Provider\ValidatorServiceProvider());

require_once 'events.php';

/* Mounts do site */
$app->mount('/', new \Site\Controller\HomeController());

/* Mounts do Admin */
$app->mount('/admin', new \Admin\Controller\DashboardController());

$app->run();