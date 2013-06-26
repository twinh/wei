<?php

namespace WidgetTest\Validator;

class EndsWithTest extends TestCase
{
    /**
     * @dataProvider providerForEndsWith
     */
    public function testEndsWith($input, $findMe, $case = false)
    {
        $this->assertTrue($this->isEndsWith($input, $findMe, $case));
    }

    /**
     * @dataProvider providerForNotEndsWith
     */
    public function testNotEndsWith($input, $findMe, $case = false)
    {
        $this->assertFalse($this->isEndsWith($input, $findMe, $case));
    }

    public function providerForEndsWith()
    {
        return array(
            array('abc', 'c', false),
            array('ABC', 'c', false),
            array('abc', '', false),
            array('abc', array('C', 'B', 'A'), false),
            array('hello word', array('wo', 'word'), true)
        );
    }

    public function providerForNotEndsWith()
    {
        return array(
            array('abc', 'b', false),
            array('ABC', 'c', true),
            array('ABC', array('a', 'b', 'c'), true)
        );
    }
}
