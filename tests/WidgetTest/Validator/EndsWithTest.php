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
            array('abc', 'c'),
            array('ABC', 'c'),
            array('abc', ''),
        );
    }

    public function providerForNotEndsWith()
    {
        return array(
            array('abc', 'b'),
            array('ABC', 'c', true),
        );
    }
}
