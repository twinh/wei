<?php

namespace WidgetTest;

class ArrayWidgetTest extends TestCase
{
    public function setUp()
    {
        $this->object = new \WidgetTest\Fixtures\ArrayWidget(array(
            'widget' => $this->widget
        ));
    }
    
    public function testOffsetExists() {
        $widget = $this->object;

        $widget['key'] = 'value';

        $this->assertTrue(isset($widget['key']));
    }

    public function testOffsetGet() {
        $widget = $this->object;

        $widget['key'] = 'value1';

        $this->assertEquals('value1', $widget['key']);
    }

    public function testOffsetUnset() {
        $widget = $this->object;

        unset($widget['key']);

        $this->assertNull($widget['key']);
    }

    public function testFromArray() {
        $widget = $this->object;

        $widget['key2'] = 'value2';

        $widget->fromArray(array(
            'key1' => 'value1',
            'key2' => 'value changed',
        ));

        $this->assertEquals('value1', $widget['key1']);

        $this->assertEquals('value changed', $widget['key2']);
    }

    public function testToArray()
    {
        $widget = $this->object;

        $widget->fromArray(array(
            'key' => 'other value',
        ));

        $arr = $widget->toArray();

        $this->assertContains('other value', $arr);
    }
    
    public function testCount()
    {
        $widget = $this->object;

        $widget->fromArray(range(1, 10));
        
        $this->assertCount(10, $widget);
    }
    
    public function testGetIterator()
    {
        $this->assertInstanceOf('\ArrayIterator', $this->object->getIterator());
    }
    
    public function testKeys()
    {
        $widget = $this->object;

        $widget->fromArray(array(
            'string' => 'value',
            1 => 2
        ));
        
        $this->assertEquals(array('string', 1), $this->object->keys());
    }
    
    public function testInvoker()
    {
        $widget = $this->object;

        $widget->fromArray(array(
            'string' => 'value',
            1 => 2
        ));
        
        $this->assertEquals('value', $widget('string'));
        
        $this->assertEquals('custom', $widget('no this key', 'custom'));
    }
}

