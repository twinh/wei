<?php

namespace WidgetTest;

class SessionTest extends TestCase
{
    /**
     * @var \Widget\Session
     */
    protected $object;
    
    protected function tearDown()
    {
        // FIXME why sometime $this->obejct is NULL
        if ($this->object) {
            $this->object->destroy();
        }

        parent::tearDown();
    }
    
    public function testIsStarted()
    {
        $session = $this->object;

        $this->assertTrue($session->isStarted());
    }
    
    public function testSet()
    {
        $session = $this->object;

        $session->set('action', 'test');

        $this->assertEquals('test', $session->get('action'));
    }

    public function testClear()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->clear();

        $this->assertEquals(null, $session->get('action'));
    }

    public function testDestroy()
    {
        $session = $this->object;

        $session->set('action', 'clear');

        $session->destroy();

        $this->assertEquals(null, $session->get('action'));
    }
}