<?php

require 'vendor/autoload.php';
require '../../lib/Widget/Widget.php';

// Get widget container
$widget = widget(array(
    'widget' => array(
        'autoloadMap' => array(
            'WidgetExtension' => 'src',
            'Entity'          => 'src',
        ),
        'aliases' => array(
            'dbal' => 'WidgetExtension\Dbal',
            'orm' => 'WidgetExtension\Orm'
        )
    ),
    // Doctrine DBAL widget configuration
    'dbal' => array(
        'driver' => 'pdo_sqlite',
        'memory' => true
    ),
    // Doctrine ORM widget configuration
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
$dbal = $widget->dbal();

var_dump($dbal->fetchArray("SELECT MAX(1, 2)"));

/** @var $orm \Doctrine\ORM\EntityManager */
$orm = $widget->orm();

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
var_dump($user);exit;