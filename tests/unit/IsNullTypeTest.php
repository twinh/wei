<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsNullTypeTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForNull
     * @param mixed $input
     */
    public function testNull($input)
    {
        $this->assertTrue($this->isNullType($input));
    }

    /**
     * @dataProvider providerForNotNull
     * @param mixed $input
     */
    public function testNotNull($input)
    {
        $this->assertFalse($this->isNullType($input));
    }

    public static function providerForNull()
    {
        return [
            [null],
        ];
    }

    public static function providerForNotNull()
    {
        return [
            [''],
            [false],
            [0],
            [0.0],
            [[]],
            ['0'],
        ];
    }
}
