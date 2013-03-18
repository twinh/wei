<?php

namespace WidgetTest;

class RequestTest extends TestCase
{
    public function testInvoke()
    {
        $widget = $this->object;

        $name = $widget->request('name');
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : null;

        $this->assertEquals($name, $source);

        $default = 'default';
        $name2 = $widget->request('name', $default);
        $source = isset($_REQUEST['name']) ? $_REQUEST['name'] : $default;

        $this->assertEquals($name2, $default);
    }

    public function testSet()
    {
        $widget = $this->object;

        $widget->set('key', 'value');

        $this->assertEquals('value', $widget->request('key'), 'string param');

        $widget->fromArray(array(
            'key1' => 'value1',
            'key2' => 'value2',
        ));

        $this->assertEquals('value2', $widget->request('key2'), 'array param');
    }

    public function testRemove()
    {
        $widget = $this->object;

        $widget->set('remove', 'just a moment');

        $this->assertEquals('just a moment', $widget->request('remove'));

        $widget->remove('remove');

        $this->assertNull($widget->request('remove'));
    }
    
    public function testMethod()
    {
        foreach (array('GET', 'POST') as $method) {
            $request = new \Widget\Request(array(
                'widget' => $this->widget,
                'fromGlobal' => false,
                'servers' => array(
                    'REQUEST_METHOD' => $method
                )
            ));

            $this->assertTrue($request->{'in' . $method}());
            $this->assertTrue($request->inMethod($method));

            $method = strtolower($method);
            $method[0] = strtoupper($method[0]);
            $this->{'in' . $method}->setOption('request', $request);
            $this->assertTrue($this->{'in' . $method}());
        }
    }
    
    /**
     * @expectedException \Widget\Exception\InvalidArgumentException
     */
    public function testInvalidParameterReference()
    {
        $this->request->getParameterReference('exception');
    }
}