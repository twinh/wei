<?php

namespace WeiTest;

use Wei\IsDateTime;

/**
 * @internal
 */
final class IsStringTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForStringVal
     * @param mixed $input
     */
    public function testStringVal($input)
    {
        $this->assertTrue($this->isString($input));
    }

    /**
     * @dataProvider providerForNotStringVal
     * @param mixed $input
     */
    public function testNotStringVal($input)
    {
        $this->assertFalse($this->isString($input));
    }

    public function providerForStringVal()
    {
        return [
            [''],
            ['123'],
            [true],
            [false],
            [1],
            [1.2],
            [$this],
        ];
    }

    public function providerForNotStringVal()
    {
        return [
            [new \ArrayObject()],
            [new \stdClass()],
        ];
    }

    public function __toString()
    {
        return 'test';
    }
}
