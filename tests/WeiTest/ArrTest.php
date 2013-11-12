<?php

namespace WeiTest;

class ArrTest extends TestCase
{
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
        $wei = $this->object;

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
        ), $wei->arr->sort($array, 'id', SORT_DESC));

        $this->assertEquals(array(
            0 => array(
                'id' => 2,
                'order' => 1,
            ),
            1 => array(
                'id' => 1,
                'order' => 2,
            ),
        ), $wei->arr->sort($array, 'order', SORT_ASC));

        $this->assertEquals(array(), $wei->arr->sort(array()), 'empty array');
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
