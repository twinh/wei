<?php

namespace WidgetTest\Validator;

use WidgetTest\TestCase;

class RangeTest extends TestCase
{
    /**
     * @dataProvider providerForRange
     */
    public function testRange($input, $options)
    {
        $this->assertTrue($this->isRange($input, $options));
    }

    /**
     * @dataProvider providerForNotRange
     */
    public function testNotRange($input, $options)
    {
        $this->assertFalse($this->isRange($input, $options));
    }

    public function providerForRange()
    {
        return array(
            array(20, array(
                'min' => 10,
                'max' => 30,
            )),
            array('2013-01-13', array(
                'min' => '2013-01-01',
                'max' => '2013-01-31'
            )),
            array(1.5, array(
                'min' => 0.8,
                'max' => 3.2
            ))
        );
    }

    public function providerForNotRange()
    {
        return array(
            array(20, array(
                'min' => 30,
                'max' => 40
            ))
        );
    }
}
