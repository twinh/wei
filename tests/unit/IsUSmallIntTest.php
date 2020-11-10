<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUSmallIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUSmallInt
     * @param mixed $input
     */
    public function testUSmallInt($input)
    {
        $this->assertTrue($this->isUSmallInt($input));
    }

    /**
     * @dataProvider providerForNotUSmallInt
     * @param mixed $input
     */
    public function testNotUSmallInt($input)
    {
        $this->assertFalse($this->isUSmallInt($input));
    }

    public function providerForUSmallInt()
    {
        return [
            [1],
            [0],
            [65535],
        ];
    }

    public function providerForNotUSmallInt()
    {
        return [
            ['1.0'],
            [0 - 1],
            [65535 + 1],
        ];
    }
}
