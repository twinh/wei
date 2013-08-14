<?php

namespace WidgetTest\Validator;

use WidgetTest\Fixtures\UserEntity;

class EntityExistsTest extends TestCase
{
    protected $inputTestOptions = array(
        'entityClass' => 'WidgetTest\Fixtures\UserEntity'
    );

    public function setUp()
    {
        if (!class_exists(('\Doctrine\ORM\EntityManager'))) {
            $this->markTestSkipped('doctrine\orm is required');
        }

        parent::setUp();

        $widget = $this->widget;

        $widget->setConfig('entityManager', array(
            'config' => array(
                'proxyDir' => './',
                'proxyNamespace' => 'Proxy',
                'useSimpleAnnotationReader' => true,
                'annotationDriverPaths' => array('./')
            )
        ));

        /* @var $em \Doctrine\ORM\EntityManager */
        $em = $widget->entityManager();

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

        $metadata = $em->getClassMetadata('WidgetTest\Fixtures\UserEntity');

        // Create table from User entity
        $tool->dropSchema(array($metadata));
        $tool->createSchema(array($metadata));

        // Insert some test data
        $user1 = new UserEntity();
        $user1->setName('twin');
        $user1->setEmail('twin@test.com');
        $em->persist($user1);

        $user2 = new UserEntity();
        $user2->setName('test');
        $user2->setEmail('test@test.com');
        $em->persist($user2);

        $em->flush();
    }

    public function tearDown()
    {
        if (!class_exists(('\Doctrine\ORM\EntityManager'))) {
            return;
        }

        /* @var $em \Doctrine\ORM\EntityManager */
        /*$em = $this->entityManager();

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropDatabase();*/

        parent::tearDown();
    }

    public function testEntityExists()
    {
        $this->assertTrue($this->isEntityExists('1', 'WidgetTest\Fixtures\UserEntity'));

        $this->assertTrue($this->isEntityExists('twin', 'WidgetTest\Fixtures\UserEntity', 'name'));
    }

    public function testCriteria()
    {
        $entityExists = new \Widget\Validator\EntityExists(array(
            'widget' => $this->widget,
            'entityClass' => 'WidgetTest\Fixtures\UserEntity',
            'criteria' => array(
                'name' => 'twin',
                'email' => 'twin@test.com'
            )
        ));

        $this->assertNull($entityExists->getEntity());
        $this->assertTrue($entityExists());
        $this->assertInstanceOf('WidgetTest\Fixtures\UserEntity', $entityExists->getEntity());
    }
}