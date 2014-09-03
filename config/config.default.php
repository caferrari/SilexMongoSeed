<?php

return [
    'db_name' => 'my_database',
    'site_title' => 'Silex Skel',
    'twig' => [
        'auto_reload'       => $app['debug'],
        'debug'             => $app['debug'],
        'strict_variables'  => $app['debug'],
        'cache'             => getcwd() . '/var/cache/twig'
    ],
    'debug' => $app['debug']
];
