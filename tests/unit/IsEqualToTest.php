<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsEqualToTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForEquals
     * @param mixed $input
     * @param mixed $equals
     */
    public function testEquals($input, $equals)
    {
        $this->assertTrue($this->isEqualTo($input, $equals));
    }

    /**
     * @dataProvider providerForNotEquals
     * @param mixed $input
     * @param mixed $equals
     */
    public function testNotEquals($input, $equals)
    {
        $this->assertFalse($this->isEqualTo($input, $equals));
    }

    public function providerForEquals()
    {
        return [
            ['abc', 'abc'],
            [0, null],
            [0, \PHP_MAJOR_VERSION >= 8 ? 0 : ''],
            [null, null],
            [new \stdClass(), new \stdClass()],
        ];
    }

    public function providerForNotEquals()
    {
        return [
            ['abc', 'bbc'],
            ['', []],
            [new \stdClass(), new \ArrayObject()],
            [0, \PHP_MAJOR_VERSION >= 8 ? '' : 1],
        ];
    }
}
