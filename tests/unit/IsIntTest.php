<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIntVal
     * @param mixed $input
     * @param int|null $min
     * @param int|null $max
     */
    public function testIntVal($input, ?int $min = null, ?int $max = null)
    {
        $this->assertTrue($this->isInt($input, $min, $max));
    }

    /**
     * @dataProvider providerForNotIntVal
     * @param mixed $input
     * @param int|null $min
     * @param int|null $max
     */
    public function testNotIntVal($input, ?int $min = null, ?int $max = null)
    {
        $this->assertFalse($this->isInt($input, $min, $max));
    }

    public static function providerForIntVal()
    {
        return [
            [1],
            [-1],
            [\PHP_INT_MAX],
            ['-1'],
            ['123'],
            ['0'],
            [0],
            [1, 1],
            [1, null, 1],
            [1, 1, 2],
        ];
    }

    public static function providerForNotIntVal()
    {
        return [
            [true],
            [false],
            [null],
            [1.1],
            ['1.0'],
            [1, 2],
            [1, null, 0],
            [2, 3, 4],
        ];
    }
}
