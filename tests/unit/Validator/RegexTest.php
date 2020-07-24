<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class RegexTest extends TestCase
{
    protected $inputTestOptions = [
        'pattern' => '(.+?)',
    ];

    /**
     * @dataProvider providerForRegex
     * @param mixed $input
     * @param mixed $regex
     */
    public function testRegex($input, $regex)
    {
        $this->assertTrue($this->isRegex($input, $regex));
    }

    /**
     * @dataProvider providerForNotRegex
     * @param mixed $input
     * @param mixed $regex
     */
    public function testNotRegex($input, $regex)
    {
        $this->assertFalse($this->isRegex($input, $regex));
    }

    public function providerForRegex()
    {
        return [
            ['This is Wei Framework.', '/wei/i'],
        ];
    }

    public function providerForNotRegex()
    {
        return [
            ['This is Wei Framework.', '/that/i'],
            ['Abc', '/abc/'],
        ];
    }
}
