<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsPresentTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForPresent
     * @param mixed $input
     */
    public function testPresent($input)
    {
        $this->assertTrue($this->isPresent($input));
    }

    /**
     * @dataProvider providerForNotPresent
     * @param mixed $input
     */
    public function testNotPresent($input)
    {
        $this->assertFalse($this->isPresent($input));
    }

    public static function providerForPresent()
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
        ];
    }

    public static function providerForNotPresent()
    {
        return [
            [''],
            [false],
            [[]],
            [null],
        ];
    }
}
