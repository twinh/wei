<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsNaturalNumberTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForNaturalNumber
     * @param mixed $input
     */
    public function testNaturalNumber($input)
    {
        $this->assertTrue($this->isNaturalNumber($input));
    }

    /**
     * @dataProvider providerForNotNaturalNumber
     * @param mixed $input
     */
    public function testNotNaturalNumber($input)
    {
        $this->assertFalse($this->isNaturalNumber($input));
    }

    public static function providerForNaturalNumber()
    {
        return [
            ['0'],
            [0],
            [1],
            ['11'],
            ['100'],
            ['+1'],
            [+1],
        ];
    }

    public static function providerForNotNaturalNumber()
    {
        return [
            ['0.1'],
            ['a bcdefg'],
            ['1 23456'],
            ['string'],
            [-1],
            ['-1'],
        ];
    }
}
