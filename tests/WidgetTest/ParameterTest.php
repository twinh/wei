<?php

namespace WidgetTest;

class ParameterTest extends TestCase
{
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
}