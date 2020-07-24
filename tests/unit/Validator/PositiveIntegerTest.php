<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class PositiveIntegerTest extends TestCase
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

    public function providerForPositiveInteger()
    {
        return [
            [1],
            ['11'],
            ['100'],
            ['+1'],
            [+1],
        ];
    }

    public function providerForNotPositiveInteger()
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
