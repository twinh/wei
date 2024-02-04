<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsObjectTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForObjectVal
     * @param mixed $input
     */
    public function testObjectVal($input, ?int $length = null)
    {
        $this->assertTrue($this->isObject($input, $length));
    }

    /**
     * @dataProvider providerForNotObjectVal
     * @param mixed $input
     */
    public function testNotObjectVal($input, ?int $length = null)
    {
        $this->assertFalse($this->isObject($input, $length));
    }

    public static function providerForObjectVal()
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
        ];
    }

    public static function providerForNotObjectVal()
    {
        return [
            ['123'],
            [123],
            [1.01],
            [[]],
            [(object) ['a' => 'b'], 8],
            [(object) ['a' => '我'], 8],
        ];
    }
}
