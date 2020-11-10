<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsTinyIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForTinyInt
     * @param mixed $input
     */
    public function testTinyInt($input)
    {
        $this->assertTrue($this->isTinyInt($input));
    }

    /**
     * @dataProvider providerForNotTinyInt
     * @param mixed $input
     */
    public function testNotTinyInt($input)
    {
        $this->assertFalse($this->isTinyInt($input));
    }

    public function providerForTinyInt()
    {
        return [
            [0],
            [1],
            [-1],
            [-128],
            [127],
        ];
    }

    public function providerForNotTinyInt()
    {
        return [
            ['1.0'],
            [-128 - 1],
            [127 + 1],
        ];
    }
}
