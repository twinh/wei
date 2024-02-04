<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsBoolableTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForBoolVal
     * @param mixed $input
     */
    public function testBoolVal($input)
    {
        $this->assertTrue($this->isBoolable($input));
    }

    /**
     * @dataProvider providerForNotBoolVal
     * @param mixed $input
     */
    public function testNotBoolVal($input)
    {
        $this->assertFalse($this->isBoolable($input));
    }

    public static function providerForBoolVal()
    {
        return [
            [true],
            [1],
            ['1'],
            [false],
            [''],
            ['0'],
            [0],
            ['on'],
            ['On'],
            ['off'],
            ['yes'],
            ['no'],
        ];
    }

    public static function providerForNotBoolVal()
    {
        return [
            ['123'],
            [123],
            [1.01],
            ['y'],
            ['n'],
        ];
    }
}
