<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsStringTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForStringVal
     * @param mixed $input
     * @param int|null $minLength
     * @param int|null $maxLength
     */
    public function testStringVal($input, ?int $minLength = null, ?int $maxLength = null)
    {
        $this->assertTrue($this->isString($input, $minLength, $maxLength));
    }

    /**
     * @dataProvider providerForNotStringVal
     * @param mixed $input
     * @param int|null $minLength
     * @param int|null $maxLength
     */
    public function testNotStringVal($input, ?int $minLength = null, ?int $maxLength = null)
    {
        $this->assertFalse($this->isString($input, $minLength, $maxLength));
    }

    public static function providerForStringVal()
    {
        return [
            [''],
            ['123'],
            [true],
            [false],
            [1],
            [1.2],
            [new class {
                public function __toString()
                {
                    return 'test';
                }
            }],
            ['1', 1],
            ['1', null, 1],
            ['1', 1, 2],
            ['我', 3],
            ['我', null, 3],
            ['我', 2, 3],
            ['😊', 4],
            ['😊', null, 4],
            ['😊', 3, 4],
        ];
    }

    public static function providerForNotStringVal()
    {
        return [
            [new \ArrayObject()],
            [new \stdClass()],
            ['12', 3],
            ['12', null, 1],
            ['12', 3, 4],
            ['我', 4],
            ['我', null, 2],
            ['我', 4, 5],
            ['😊', 5],
            ['😊', null, 3],
            ['😊', 6, 7],
        ];
    }
}
