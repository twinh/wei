<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class ContainsTest extends TestCase
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

    public function providerForContains()
    {
        return [
            [123, 1],
            ['abc', 'a'],
            ['@#$', '@'],
            ['ABC', '/a/i', true],
        ];
    }

    public function providerForNotContains()
    {
        return [
            ['123', '4'],
            ['ABC', '/a/', true],
        ];
    }
}
