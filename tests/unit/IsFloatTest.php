<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsFloatTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForFloatVal
     * @param mixed $input
     */
    public function testFloatVal($input)
    {
        $this->assertTrue($this->isFloat($input));
    }

    /**
     * @dataProvider providerForNotFloatVal
     * @param mixed $input
     */
    public function testNotFloatVal($input)
    {
        $this->assertFalse($this->isFloat($input));
    }

    public function providerForFloatVal()
    {
        return [
            [0],
            [1],
            [99],
            [1.0],
            [1.1],
            [-1],
            ['1'],
            ['1.0'],
            ['0.1'],
            ['-1'],
            ['99'],
        ];
    }

    public function providerForNotFloatVal()
    {
        return [
            ['a'],
            [null],
            [true],
            [false],
            ['1.1.1'],
        ];
    }
}
