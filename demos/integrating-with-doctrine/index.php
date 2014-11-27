<?php

require 'vendor/autoload.php';
require '../../lib/Wei/Wei.php';

// Get wei container
$wei = wei(array(
    'wei' => array(
        'autoloadMap' => array(
            'WeiExtension' => 'src',
            'Entity'          => 'src',
        ),
        'aliases' => array(
            'dbal' => 'WeiExtension\Dbal',
            'orm' => 'WeiExtension\Orm'
        )
    ),
    // Doctrine DBAL wei configuration
    'dbal' => array(
        'driver' => 'pdo_sqlite',
        'memory' => true
    ),
    // Doctrine ORM wei configuration
    'orm' => array(
        'config' => array(
            'proxyDir' => './',
            'proxyNamespace' => 'Proxy',
            'useSimpleAnnotationReader' => true,
            'annotationDriverPaths' => array('./src/Entity')
        )
    ),
));

/** @var $dbal \Doctrine\DBAL\Connection */
$dbal = $wei->dbal();

var_dump($dbal->fetchArray("SELECT MAX(1, 2)"));

/** @var $orm \Doctrine\ORM\EntityManager */
$orm = $wei->orm();

$tool = new \Doctrine\ORM\Tools\SchemaTool($orm);

$metadata = $orm->getClassMetadata('Entity\User');

// Create table from User entity
$tool->dropSchema(array($metadata));
$tool->createSchema(array($metadata));

// Insert some test data
$user1 = new \Entity\User();
$user1->setName('twin');
$user1->setEmail('twin@test.com');

// Save to database
$orm->persist($user1);
$orm->flush();

// Receive user data from database
$user = $orm->find('Entity\User', 1);

// Display user data
var_dump($user);