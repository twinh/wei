<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class InTest extends TestCase
{
    /**
     * @dataProvider providerForIn
     */
    public function testIn($input, $array, $case = false)
    {
        $this->assertTrue($this->isIn($input, $array, $case));
    }

    /**
     * @dataProvider providerForNotIn
     */
    public function testNotIn($input, $array, $case = false)
    {
        $this->assertFalse($this->isIn($input, $array, $case));
    }

    public function providerForIn()
    {
        return array(
            array('apple', array('apple', 'pear')),
            array('apple', new \ArrayObject(array('apple', 'pear'))),
            array('', array(null)),
        );
    }
    
    public function providerForNotIn()
    {
        return array(
            array('', array(null), true),
        );
    }
}
