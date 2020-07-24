<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class DigitTest extends TestCase
{
    /**
     * @dataProvider providerForDigit
     * @param mixed $input
     */
    public function testDigit($input)
    {
        $this->assertTrue($this->isDigit($input));
    }

    /**
     * @dataProvider providerForNotDigit
     * @param mixed $input
     */
    public function testNotDigit($input)
    {
        $this->assertFalse($this->isDigit($input));
    }

    public function providerForDigit()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['123456'],
            ['0123456'],
        ];
    }

    public function providerForNotDigit()
    {
        return [
            ['0.123'],
            ['a bcdefg'],
            ['1 23456'],
            ['string'],
        ];
    }
}
