<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsJsonTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForJson
     * @param mixed $input
     */
    public function testJson($input, int $length = null)
    {
        $this->assertTrue($this->wei->isJson($input, $length));
    }

    /**
     * @dataProvider providerForNotJson
     * @param mixed $input
     */
    public function testNotJson($input, int $length = null)
    {
        $this->assertFalse($this->wei->isJson($input, $length));
    }

    public function providerForJson()
    {
        return [
            [(object) []],
            [(object) null],
            [(object) 1],
            [(object) '1'],
            [new \stdClass()],
            [new \ArrayObject()],
            [wei()],
            [(object) ['a' => 'b'], 9],
            [(object) ['a' => '我'], 9],
            [[]],
            [[1, 2]],
            [['a' => 'b', 'c' => 'd']],
        ];
    }

    public function providerForNotJson()
    {
        return [
            ['123'],
            [123],
            [1.01],
            [(object) ['a' => 'b'], 8],
            [(object) ['a' => '我'], 8],
        ];
    }
}
