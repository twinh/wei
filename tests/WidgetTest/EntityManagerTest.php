<?php

namespace WidgetTest;

class EntityManagerTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\ORM\EntityManager'))) {
            $this->markTestSkipped('doctrine\orm is required');
            return;
        }

        parent::setUp();
    }


    public function testInvoker()
    {
        $this->assertInstanceOf('\Doctrine\ORM\EntityManager', $this->entityManager());
    }

    public function testStringAsCacheConfiguration()
    {
        $config = $this->widget->getConfig('entityManager');
        $emWidget = new \Widget\EntityManager(array(
            'config' => array(
                'cache' => 'ArrayCache'
            ) + $config['config']
        ));

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emWidget();

        $cacheImpl = $em->getConfiguration()->getMetadataCacheImpl();

        $this->assertInstanceOf('\Doctrine\Common\Cache\ArrayCache', $cacheImpl);
    }


    public function testObjectAsCacheConfiguration()
    {
        $config = $this->widget->getConfig('entityManager');
        $emWidget = new \Widget\EntityManager(array(
            'config' => array(
                'cache' => new \Doctrine\Common\Cache\ArrayCache
            ) + $config['config']
        ));

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emWidget();

        $cacheImpl = $em->getConfiguration()->getMetadataCacheImpl();

        $this->assertInstanceOf('\Doctrine\Common\Cache\ArrayCache', $cacheImpl);
    }

    public function testEntityNamespacesConfig()
    {
        $config = $this->widget->getConfig('entityManager');
        $emWidget = new \Widget\EntityManager(array(
            'config' => array(
                'entityNamespaces' => array(
                    'App' => 'AppModule'
                )
            ) + $config['config']
        ));

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emWidget();

        $ns = $em->getConfiguration()->getEntityNamespaces();
        $this->assertEquals(array('App' => 'AppModule'), $ns);

        $name = $em->getConfiguration()->getEntityNamespace('App');
        $this->assertEquals('AppModule', $name);
    }

    public function testAutoGenerateProxyClassesConfig()
    {
        $config = $this->widget->getConfig('entityManager');
        $emWidget = new \Widget\EntityManager(array(
            'config' => array(
                'autoGenerateProxyClasses' => true
            ) + $config['config']
        ));

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $emWidget();

        $this->assertTrue($em->getConfiguration()->getAutoGenerateProxyClasses());
    }
}