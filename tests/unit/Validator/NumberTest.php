<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class NumberTest extends TestCase
{
    /**
     * @dataProvider providerForNumber
     * @param mixed $input
     */
    public function testNumber($input)
    {
        $this->assertTrue($this->isNumber($input));
    }

    /**
     * @dataProvider providerForNotNumber
     * @param mixed $input
     */
    public function testNotNumber($input)
    {
        $this->assertFalse($this->isNumber($input));
    }

    public function providerForNumber()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['1234567'],
            ['123456789'],
            ['1.1'],
            [2.0],
        ];
    }

    public function providerForNotNumber()
    {
        return [
            ['012345-1234567890'],
            ['not number'],
            ['0.1a'],
        ];
    }
}
