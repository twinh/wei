<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsEmptyTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForEmpty
     * @param mixed $input
     */
    public function testEmpty($input)
    {
        $this->assertTrue(wei()->isEmpty($input));
    }

    /**
     * @dataProvider providerForNotEmpty
     * @param mixed $input
     */
    public function testNotEmpty($input)
    {
        $this->assertFalse(wei()->isEmpty($input));
    }

    public static function providerForEmpty()
    {
        return [
            [''],
            [null],
        ];
    }

    public static function providerForNotEmpty()
    {
        return [
            ['0'],
            [0],
            [0.0],
            ['string'],
            [' '],
            ["\r\n"],
            ["\n"],
            ["\r"],
            [false],
            [[]],
        ];
    }
}
