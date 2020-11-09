<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIdenticalToTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIdenticalTo
     * @param mixed $input
     * @param mixed $equals
     */
    public function testEquals($input, $equals)
    {
        $this->assertTrue($this->isIdenticalTo($input, $equals));
    }

    /**
     * @dataProvider providerForNotIdenticalTo
     * @param mixed $input
     * @param mixed $equals
     */
    public function testNotEquals($input, $equals)
    {
        $this->assertFalse($this->isIdenticalTo($input, $equals));
    }

    public function providerForIdenticalTo()
    {
        $input = $equals = new \stdClass();
        return [
            ['abc', 'abc'],
            [$input, $equals],
            [1 + 1, 2],
            [[], []],
            [0, 0],
            [false, false],
        ];
    }

    public function providerForNotIdenticalTo()
    {
        $input = new \stdClass();
        $equals = new \stdClass();
        return [
            ['abc', 'bbc'],
            [$input, $equals],
            [0, false],
            [0, null],
            [0, ''],
            [0, 0.0],
            [false, null],
            [false, ''],
            [false, 0.0],
            [null, ''],
            [null, 0.0],
        ];
    }
}
