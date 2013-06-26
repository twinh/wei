<?php

namespace WidgetTest;

class ArrTest extends TestCase
{
    public function testArray()
    {
        $array = array(
            'key' => 'value',
            1 => '10',
            11,
        );

        $arrayObject = new \ArrayObject($array);

        foreach ($array as $key => $value) {
            $this->assertEquals($value, $this->arr->attr($array, $key));
            $this->assertEquals($value, $this->arr->attr($arrayObject, $key));
        }
    }

    public function testPropertyAndGetter()
    {
        $this->assertEquals('pv', $this->arr->attr($this, 'propertyValue'));
        $this->assertEquals('value', $this->arr->attr($this, 'value'));
        $this->assertEquals(null, $this->arr->attr($this, 'no this property'));
    }

    /**
     * test fixture for testPropertyAndGetter()
     */
    public $propertyValue = 'pv';
    public function getValue()
    {
        return 'value';
    }

    public function testSort()
    {
        $widget = $this->object;

        $array = array(
            0 => array(
                'id' => 1,
                'order' => 2,
            ),
            1 => array(
                'id' => 2,
                'order' => 1,
            ),
        );

        $this->assertEquals(array(
            0 => array(
                'id' => 2,
                'order' => 1,
            ),
            1 => array(
                'id' => 1,
                'order' => 2,
            ),
        ), $widget->arr->sort($array, 'id', SORT_DESC));

        $this->assertEquals(array(
            0 => array(
                'id' => 2,
                'order' => 1,
            ),
            1 => array(
                'id' => 1,
                'order' => 2,
            ),
        ), $widget->arr->sort($array, 'order', SORT_ASC));

        $this->assertEquals(array(), $widget->arr->sort(array()), 'empty array');
    }

    public function testIndexBy()
    {
        $array = array(
            0 => array(
                'id' => 1,
                'order' => 2,
            ),
            1 => array(
                'id' => 2,
                'order' => 1,
            ),
        );

        $newArray = $this->arr->indexBy($array, 'id');

        $this->assertEquals(array(
            1 => array(
                'id' => 1,
                'order' => 2,
            ),
            2 => array(
                'id' => 2,
                'order' => 1,
            ),
        ), $newArray);
    }
}
