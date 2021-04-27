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
    public function testObjectVal($input)
    {
        $this->assertTrue($this->isObject($input));
    }

    /**
     * @dataProvider providerForNotObjectVal
     * @param mixed $input
     */
    public function testNotObjectVal($input)
    {
        $this->assertFalse($this->isObject($input));
    }

    public function providerForObjectVal()
    {
        return [
            [(object) []],
            [(object) null],
            [(object) 1],
            [(object) '1'],
            [new \stdClass()],
            [new \ArrayObject()],
            [wei()],
        ];
    }

    public function providerForNotObjectVal()
    {
        return [
            ['123'],
            [123],
            [1.01],
            [[]],
        ];
    }
}
