<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsBoolValTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForBoolVal
     * @param mixed $input
     */
    public function testBoolVal($input)
    {
        $this->assertTrue($this->isBoolVal($input));
    }

    /**
     * @dataProvider providerForNotBoolVal
     * @param mixed $input
     */
    public function testNotBoolVal($input)
    {
        $this->assertFalse($this->isBoolVal($input));
    }

    public function providerForBoolVal()
    {
        return [
            [true],
            [1],
            ['1'],
            [false],
            [''],
            ['0'],
            [0],
        ];
    }

    public function providerForNotBoolVal()
    {
        return [
            ['123'],
            [123],
            [1.01],
            ['y'],
            ['n'],
            ['on'],
            ['On'],
            ['off'],
            ['yes'],
            ['no'],
        ];
    }
}
