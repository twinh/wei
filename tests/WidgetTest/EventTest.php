<?php

namespace WidgetTest;

class EventTest extends TestCase
{
    /**
     * @var \Widget\Event
     */
    protected $object;
    

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //$this->object = Qwin::getInstance()->event;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
    
    /**
     * @covers \Widget\EventManager::__construct
     * @covers \Widget\EventManager::add
     * @covers \Widget\EventManager::splitNamespace
     * @covers \Widget\Event::__construct
     * @covers \Widget\Event::getType
     * @covers \Widget\Event::setType
     * @covers \Widget\Event::setNamespaces
     * @covers \Widget\Off::__invoke
     * @covers \Widget\Trigger::__invoke
     */
    public function testGeter()
    {
        $that = $this;
        
        $this->off('test')
            ->on('test.ns.ns2', function(\Widget\Event $event) use($that) {
                $that->assertEquals('test', $event->getType());
                $this->assertEquals('ns.ns2', $event->getNamespace());
                $this->assertEquals(array('ns', 'ns2'), $event->getNamespaces());
            })
            ->trigger('test.ns.ns2');
            
        
    }
    
    public function testAddHandler()
    {
        $this->on('test', function(){});
        
        $this->assertTrue($this->eventManager->has('test'));
        
        $this->setExpectedException('\Widget\Exception', 'Parameter 2 should be a valid callback');
        
        $this->on('test', 'not callback');
    }

    /**
     * @covers \Widget\EventManager::__invoke
     * @covers \Widget\Event::stopPropagation
     * @covers \Widget\Event::isPropagationStopped
     */
    public function testTriggerHandler()
    {
        $event = $this->off('test')
            ->on('test', function(){
                return false;
            })
            ->trigger('test');
        $this->assertEquals(false, $event->getResult());
        
        $event = $this->event('test');
        $this->off('test')
            ->on('test', function(){
                return 'result';
            })
            ->trigger($event);
        $this->assertEquals('result', $event->getResult());
        
        $event = $this->off('test')
            ->on('test', function($event){
                $event->stopPropagation();
                return 'first';
            })
            // won't be triggered
            ->on('test', function(){
                return 'second';
            })
            ->trigger('test');
        $this->assertEquals('first', $event->getResult());
    }

    /**
     * @covers \Widget\EventManager::has
     */
    public function testHasHandler()
    {
        $fn = function(){};
        $em = $this->eventManager;
        
        $this->off('test')
            ->on('test', $fn)
            ->on('test.ns1', $fn)
            ->on('test.ns2', $fn)
            ->on('test.ns1.ns2', $fn);
        
        $this->assertTrue($em->has('test'));
        
        $this->assertTrue($em->has('.ns1'));
        
        $this->assertTrue($em->has('test.ns1'));
        
        $this->assertTrue($em->has('.ns1.ns2'));
        
        $this->assertFalse($em->has('test2'));
        
        $this->assertFalse($em->has('.ns3'));
        
        $this->assertFalse($em->has('test2.ns3'));
    }
    
    /**
     * @covers \Widget\EventManager::remove
     */
    public function testRemoveHandler()
    {
        $that = $this;
        $em = $this->eventManager;

        $init = function() use($that) {
            $fn = function(){};
            $that->off('test')
                ->on('test', $fn)
                ->on('test.ns1', $fn)
                ->on('test.ns2', $fn)
                ->on('test.ns1.ns2', $fn);
        };
        
        $this->assertTrue($em->has('test'));
        $this->off('test');
        $this->assertFalse($em->has('test'));
        $init();
        
        $this->assertTrue($em->has('test.ns1'));
        $this->off('test.ns1');
        $this->assertFalse($em->has('test.ns1'));
        $init();

        $this->assertTrue($em->has('test.ns1.ns2'));
        $this->off('test.ns1.ns2');
        $this->assertFalse($em->has('test.ns1.ns2'));
        $init();
        
        $this->assertTrue($em->has('.ns1'));
        $this->off('.ns1');
        $this->assertFalse($em->has('test.ns1'));
        $this->assertFalse($em->has('test.ns1.ns2'));
        $init();
    }
}
