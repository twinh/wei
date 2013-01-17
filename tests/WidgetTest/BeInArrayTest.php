<?php

namespace WidgetTest;

class BeInArrayTest extends TestCase
{
    public function testInArray()
    {
        $result = $this->beInArray('apple', array('pear', 'apple', 'banana'));

        $this->assertEquals('apple', $result, 'apple found in array');

        $result = $this->beInArray('apple', array('pear', 'banana'));

        $this->assertEquals('pear', $result, 'apple ont found, so pear instead');
    }
}
