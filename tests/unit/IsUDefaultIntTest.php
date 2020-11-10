<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUDefaultIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUDefaultInt
     * @param mixed $input
     */
    public function testUDefaultInt($input)
    {
        $this->assertTrue($this->isUDefaultInt($input));
    }

    /**
     * @dataProvider providerForNotUDefaultInt
     * @param mixed $input
     */
    public function testNotUDefaultInt($input)
    {
        $this->assertFalse($this->isUDefaultInt($input));
    }

    public function providerForUDefaultInt()
    {
        return [
            [1],
            [0],
            [4294967295],
        ];
    }

    public function providerForNotUDefaultInt()
    {
        return [
            ['1.0'],
            [0 - 1],
            [4294967295 + 1],
        ];
    }
}
