<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsUTinyIntTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForUTinyInt
     * @param mixed $input
     */
    public function testUTinyInt($input)
    {
        $this->assertTrue($this->isUTinyInt($input));
    }

    /**
     * @dataProvider providerForNotUTinyInt
     * @param mixed $input
     */
    public function testNotUTinyInt($input)
    {
        $this->assertFalse($this->isUTinyInt($input));
    }

    public function providerForUTinyInt()
    {
        return [
            [1],
            [0],
            [255],
        ];
    }

    public function providerForNotUTinyInt()
    {
        return [
            ['1.0'],
            [0 - 1],
            [255 + 1],
        ];
    }
}
