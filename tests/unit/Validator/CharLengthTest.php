<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class CharLengthTest extends TestCase
{
    /**
     * @dataProvider providerForLength
     * @param mixed $input
     * @param mixed $option1
     * @param mixed $option2
     */
    public function testLength($input, $option1, $option2)
    {
        $this->assertTrue($this->isCharLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForNotLength
     * @param mixed $input
     * @param mixed $option1
     * @param mixed $option2
     */
    public function testNotLength($input, $option1, $option2)
    {
        $this->assertFalse($this->isCharLength($input, $option1, $option2));
    }

    /**
     * @dataProvider providerForSpecifiedLength
     * @param mixed $input
     * @param mixed $length
     */
    public function testSpecifiedLength($input, $length)
    {
        $this->assertTrue($this->isCharLength($input, $length));
    }

    /**
     * @dataProvider providerForSpecifiedLengthNotPass
     * @param mixed $input
     * @param mixed $length
     */
    public function testSpecifiedLengthNotPass($input, $length)
    {
        $this->assertFalse($this->isCharLength($input, $length));
    }

    public function providerForSpecifiedLength()
    {
        return [
            ['i♥u4', 4],
        ];
    }

    public function providerForSpecifiedLengthNotPass()
    {
        return [
            ['i♥u4', 5],
        ];
    }

    public function providerForLength()
    {
        return [
            ['i♥u4', 0, 4],
            ['i♥u4', 2, 5],
        ];
    }

    public function providerForNotLength()
    {
        return [
            ['i♥u4', 5, 6],
            ['i♥u4', -2, -1],
        ];
    }
}
