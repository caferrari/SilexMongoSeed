<?php

require_once 'bootstrap.php';

/* Mounts do site */
$app->mount('/', new \Site\Controller\HomeController());

/* Mounts do Admin */
$app->mount('/admin', new \Admin\Controller\DashboardController());

// Let's go!
$app->run();