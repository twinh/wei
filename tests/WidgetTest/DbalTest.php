<?php

namespace WidgetTest;

class DbalTest extends TestCase
{
    public function setUp()
    {
        if (!class_exists(('\Doctrine\DBAL\DriverManager'))) {
            $this->markTestSkipped('doctrine\dbal is required');
            return;
        }

        parent::setUp();
    }

    public function testWidget()
    {
        $this->assertInstanceOf('\Doctrine\DBAL\Connection', $this->dbal());
    }
}