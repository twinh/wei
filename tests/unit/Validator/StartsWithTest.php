<?php

namespace WeiTest\Validator;

class StartsWithTest extends TestCase
{
    /**
     * @dataProvider providerForStartsWith
     */
    public function testStartsWith($input, $findMe, $case = false)
    {
        $this->assertTrue($this->isStartsWith($input, $findMe, $case));
    }

    /**
     * @dataProvider providerForNotStartsWith
     */
    public function testNotStartsWith($input, $findMe, $case = false)
    {
        $this->assertFalse($this->isStartsWith($input, $findMe, $case));
    }

    public function providerForStartsWith()
    {
        return array(
            array('abc', 'a', false),
            array('ABC', 'A', false),
            array('abc', '', false),
            array('abc', array('A', 'B', 'C'), false),
            array('hello word', array('hel', 'hell'), false),
            array('/abc', array('a', 'b', '/')),
            array('#abc', array('#', 'a', '?')),
            array(123, 1),
        );
    }

    public function providerForNotStartsWith()
    {
        return array(
            array('abc', 'b', false),
            array('ABC', 'a', true),
            array('abc', array('A', 'B', 'C'), true),
            array(123, 3),
            array('#abc', array('?', '\\', '/', '$', '^')),
            array('abcd', array('bc', 'cd', 'bcd')),
        );
    }
}
