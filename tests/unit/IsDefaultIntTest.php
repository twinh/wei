<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsDefaultIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForDefaultInt
     * @param mixed $input
     */
    public function testDefaultInt($input)
    {
        $this->assertTrue($this->isDefaultInt($input));
    }

    /**
     * @dataProvider providerForNotDefaultInt
     * @param mixed $input
     */
    public function testNotDefaultInt($input)
    {
        $this->assertFalse($this->isDefaultInt($input));
    }

    public function providerForDefaultInt()
    {
        return [
            [0],
            [1],
            [-1],
            [-2147483648],
            [2147483647],
        ];
    }

    public function providerForNotDefaultInt()
    {
        return [
            ['1.0'],
            [-2147483648 - 1],
            [2147483647 + 1],
        ];
    }
}
