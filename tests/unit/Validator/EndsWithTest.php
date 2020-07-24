<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class EndsWithTest extends TestCase
{
    /**
     * @dataProvider providerForEndsWith
     * @param mixed $input
     * @param mixed $findMe
     * @param mixed $case
     */
    public function testEndsWith($input, $findMe, $case = false)
    {
        $this->assertTrue($this->isEndsWith($input, $findMe, $case));
    }

    /**
     * @dataProvider providerForNotEndsWith
     * @param mixed $input
     * @param mixed $findMe
     * @param mixed $case
     */
    public function testNotEndsWith($input, $findMe, $case = false)
    {
        $this->assertFalse($this->isEndsWith($input, $findMe, $case));
    }

    public function providerForEndsWith()
    {
        return [
            ['abc', 'c', false],
            ['ABC', 'c', false],
            ['abc', '', false],
            ['abc', ['C', 'B', 'A'], false],
            ['hello word', ['wo', 'word'], true],
            ['#?\\', ['#', '?', '\\']],
            [123, 3],
        ];
    }

    public function providerForNotEndsWith()
    {
        return [
            ['abc', 'b', false],
            ['ABC', 'c', true],
            ['ABC', ['a', 'b', 'c'], true],
            [123, 1],
            ['abcd', ['abc', 'bc']],
        ];
    }
}
