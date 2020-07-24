<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class IdenticalToTest extends TestCase
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
        $a = $b = new \stdClass();
        return [
            ['abc', 'abc'],
            [$a, $b],
            [1 + 1, 2],
            [[], []],
            [0, 0],
            [false, false],
        ];
    }

    public function providerForNotIdenticalTo()
    {
        $a = new \stdClass();
        $b = new \stdClass();
        return [
            ['abc', 'bbc'],
            [$a, $b],
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
