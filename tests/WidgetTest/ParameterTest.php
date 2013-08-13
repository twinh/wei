<?php

namespace WidgetTest;

class ParameterTest extends TestCase
{
    /**
     * @var \Widget\Stdlib\Parameter
     */
    protected $object;

    public function setUp()
    {
        $this->object = new \WidgetTest\Fixtures\Parameter(array(
            'widget' => $this->widget
        ));
    }

    public function createParameterObject($type, $class)
    {
        // create request widget from custom parameter
        $request = new \Widget\Request(array(
            'widget' => $this->widget,
            'fromGlobal' => false,
            $type => array(
                'key' => 'value',
                'key2' => 'value2',
                'int' => '5',
                'array' => array(
                    'item' => 'value'
                )
            )
        ));

        if ('request' === $class) {
            return $request;
        } else {
            // inject request widget
            $class = '\Widget\\' . ucfirst($class);
            $parameter = new $class(array(
                'widget' => $this->widget,
                'request' => $request
            ));
            return $parameter;
        }
    }

    public function testGetter()
    {
        $parameters = array(
            'gets' => 'query',
            'posts' => 'post',
            'cookies' => 'cookie',
            'servers' => 'server',
            'data' => 'request'
        );

        foreach ($parameters as $type => $class) {
            $parameter = $this->createParameterObject($type, $class);

            $this->assertEquals('value', $parameter->get('key'));

            $this->assertEquals(5, $parameter->getInt('int'));

            $this->assertEquals(array('5'), $parameter->getArray('int'));

            $this->assertEquals('', $parameter->get('notFound'));

            // int => 5, not in specified array
            $this->assertEquals('firstValue', $parameter->getInArray('int', array(
                'firstKey' => 'firstValue',
                'secondKey' => 'secondValue'
            )));

            // int => 5
            $this->assertEquals(6, $parameter->getInt('int', 6));

            $this->assertEquals(6, $parameter->getInt('int', 6, 10));

            $this->assertEquals(4, $parameter->getInt('int', 1, 4));
        }
    }

    public function testArrayAsSetParameter()
    {
        $array = array(
            'key' => 'value',
            'key1' => 'value1'
        );

        $this->query->set($array);

        $this->assertIsSubset($array, $this->query->toArray());
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
        $parameter = $this->object;

        $parameter->fromArray(array(
            'string' => 'value',
            1 => 2
        ));

        $this->assertEquals('value', $parameter('string'));

        $this->assertEquals('custom', $parameter('no this key', 'custom'));
    }
}