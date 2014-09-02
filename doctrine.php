<?php

use \Doctrine\ODM\MongoDB\Mapping\Driver\AnnotationDriver;

require_once 'bootstrap.php';

$connection = new \Doctrine\MongoDB\Connection();
$doctrineConfig = new Doctrine\ODM\MongoDB\Configuration();
$doctrineConfig->setProxyDir(__DIR__ . '/data/proxy');
$doctrineConfig->setHydratorDir(__DIR__ . '/data/hydrator');
$doctrineConfig->setProxyNamespace('Proxies');
$doctrineConfig->setAutoGenerateProxyClasses(true);
$doctrineConfig->setHydratorNamespace('Hydrators');
$doctrineConfig->setMetadataDriverImpl(AnnotationDriver::create(__DIR__ . '/src/Application/Document'));
$doctrineConfig->setDefaultDB($config['db_name']);

AnnotationDriver::registerAnnotationClasses();

return \Doctrine\ODM\MongoDB\DocumentManager::create($connection, $doctrineConfig);