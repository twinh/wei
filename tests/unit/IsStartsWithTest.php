<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsStartsWithTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForStartsWith
     * @param mixed $input
     * @param mixed $findMe
     * @param mixed $case
     */
    public function testStartsWith($input, $findMe, $case = false)
    {
        $this->assertTrue($this->isStartsWith($input, $findMe, $case));
    }

    /**
     * @dataProvider providerForNotStartsWith
     * @param mixed $input
     * @param mixed $findMe
     * @param mixed $case
     */
    public function testNotStartsWith($input, $findMe, $case = false)
    {
        $this->assertFalse($this->isStartsWith($input, $findMe, $case));
    }

    public static function providerForStartsWith()
    {
        return [
            ['abc', 'a', false],
            ['ABC', 'A', false],
            ['abc', '', false],
            ['abc', ['A', 'B', 'C'], false],
            ['hello word', ['hel', 'hell'], false],
            ['/abc', ['a', 'b', '/']],
            ['#abc', ['#', 'a', '?']],
            [123, 1],
        ];
    }

    public static function providerForNotStartsWith()
    {
        return [
            ['abc', 'b', false],
            ['ABC', 'a', true],
            ['abc', ['A', 'B', 'C'], true],
            [123, 3],
            ['#abc', ['?', '\\', '/', '$', '^']],
            ['abcd', ['bc', 'cd', 'bcd']],
        ];
    }
}
