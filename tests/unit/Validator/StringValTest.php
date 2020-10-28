<?php

namespace WeiTest\Validator;

use Wei\Validator\DateTime;

/**
 * @internal
 */
final class StringValTest extends TestCase
{
    /**
     * @dataProvider providerForStringVal
     * @param mixed $input
     */
    public function testStringVal($input)
    {
        $this->assertTrue($this->isStringVal($input));
    }

    /**
     * @dataProvider providerForNotStringVal
     * @param mixed $input
     */
    public function testNotStringVal($input)
    {
        $this->assertFalse($this->isStringVal($input));
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
