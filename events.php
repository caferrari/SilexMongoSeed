<?php

use \Symfony\Component\HttpFoundation\Request;
use \Silex\Application;

// Cria a variável que determina se a URL atual é de admin
$app->before(function(Request $request) use ($app) {
    $app['is_admin_url'] = ('/admin' == substr($request->getPathInfo(), 0, 6));
});

// Verifica se o usuário pode acessar a URL
$app->before(function(Request $request) use ($app) {
    $user = $app['session']->get('user');
    if ($app['is_admin_url'] && ($user == null || $user->isGuest())) {
        $app['session']->set('redirectTo', $request->getPathInfo());
        return $app->redirect('/login');
    }
});

// Define o local para fazer redirect quando logar
$app->before(function(Request $request) use ($app) {
    if ('GET' !== $request->getMethod()) {
        return;
    }
    $dontSave = ['/login', '/logout', '/register'];
    if (in_array($request->getPathInfo(), $dontSave)) {
        return;
    }
    $app['session']->set('redirectTo', $request->getPathInfo());
});

// Define o path da view dependendo da URL
$app->before(function(Request $request) use ($app) {
    $app->register(
        new Silex\Provider\TwigServiceProvider(),  
        [
            'twig.path' => __DIR__.'/view',
            'twig.options' => isset($app['config']['twig']) ? 
                                    $app['config']['twig'] : []
        ]
    );
    
    if ($app['is_admin_url']) {
        $app['twig.loader.filesystem']->addPath(__DIR__.'/view/admin');
        return;
    }
    
    $app['twig.loader.filesystem']->addPath(__DIR__.'/view/site');
});

// Set the site name from the config
$app->before(function(Request $request) use ($app) {
    $app['twig']->addGlobal('site_title', $app['config']['site_title']);
});

