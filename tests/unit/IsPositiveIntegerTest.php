<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsPositiveIntegerTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForPositiveInteger
     * @param mixed $input
     */
    public function testPositiveInteger($input)
    {
        $this->assertTrue($this->isPositiveInteger($input));
    }

    /**
     * @dataProvider providerForNotPositiveInteger
     * @param mixed $input
     */
    public function testNotPositiveInteger($input)
    {
        $this->assertFalse($this->isPositiveInteger($input));
    }

    public static function providerForPositiveInteger()
    {
        return [
            [1],
            ['11'],
            ['100'],
            ['+1'],
            [+1],
        ];
    }

    public static function providerForNotPositiveInteger()
    {
        return [
            ['0'],
            [0],
            ['0.1'],
            ['a bcdefg'],
            ['1 23456'],
            ['string'],
            [-1],
            ['-1'],
        ];
    }
}
