<?php

namespace WidgetTest\Validator;

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
            array('abc', 'a'),
            array('ABC', 'A'),
            array('abc', ''),
        );
    }

    public function providerForNotStartsWith()
    {
        return array(
            array('abc', 'b'),
            array('ABC', 'a', true),
        );
    }
}
