<?php

namespace WidgetTest\Validator;

class CharLengthTest extends TestCase
{
    /**
     * @dataProvider providerForLength
     */
    public function testLength($input, $option1, $option2)
    {
        $this->assertTrue($this->isCharLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForNotLength
     */
    public function testNotLength($input, $option1, $option2)
    {
        $this->assertFalse($this->isCharLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForSpecifiedLength
     */
    public function testSpecifiedLength($input, $length)
    {
        $this->assertTrue($this->is('charLength', $input, $length));
    }

    /**
     *
     * @dataProvider providerForSpecifiedLengthNotPass
     */
    public function testSpecifiedLengthNotPass($input, $length)
    {
        $this->assertFalse($this->is('charLength', $input, $length));
    }

    public function providerForSpecifiedLength()
    {
        return array(
            array('i♥u4', 4),
        );
    }

    public function providerForSpecifiedLengthNotPass()
    {
        return array(
            array('i♥u4', 5),
        );
    }

    public function providerForLength()
    {
        return array(
            array('i♥u4', 0, 4),
            array('i♥u4', 2, 5),
        );
    }

    public function providerForNotLength()
    {
        return array(
            array('i♥u4', 5, 6),
            array('i♥u4', -2, -1),
        );
    }
}