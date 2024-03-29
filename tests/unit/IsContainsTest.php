<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsContainsTest extends BaseValidatorTestCase
{
    protected $inputTestOptions = [
        'search' => 'test',
    ];

    /**
     * @dataProvider providerForContains
     * @param mixed $input
     * @param mixed $search
     * @param mixed $regex
     */
    public function testContains($input, $search, $regex = false)
    {
        $this->assertTrue($this->isContains($input, $search, $regex));
    }

    /**
     * @dataProvider providerForNotContains
     * @param mixed $input
     * @param mixed $search
     * @param mixed $regex
     */
    public function testNotContains($input, $search, $regex = false)
    {
        $this->assertFalse($this->isContains($input, $search, $regex));
    }

    public static function providerForContains()
    {
        return [
            [123, 1],
            ['abc', 'a'],
            ['@#$', '@'],
            ['ABC', '/a/i', true],
        ];
    }

    public static function providerForNotContains()
    {
        return [
            ['123', '4'],
            ['ABC', '/a/', true],
        ];
    }
}
