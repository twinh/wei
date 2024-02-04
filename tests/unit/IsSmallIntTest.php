<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsSmallIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForSmallInt
     * @param mixed $input
     */
    public function testSmallInt($input)
    {
        $this->assertTrue($this->isSmallInt($input));
    }

    /**
     * @dataProvider providerForNotSmallInt
     * @param mixed $input
     */
    public function testNotSmallInt($input)
    {
        $this->assertFalse($this->isSmallInt($input));
    }

    public static function providerForSmallInt()
    {
        return [
            [0],
            [1],
            [-1],
            [-32768],
            [32767],
        ];
    }

    public static function providerForNotSmallInt()
    {
        return [
            ['1.0'],
            [-32768 - 1],
            [32767 + 1],
        ];
    }
}
