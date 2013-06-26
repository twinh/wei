<?php

namespace WidgetTest\Validator;

class PostcodeCnTest extends TestCase
{
    /**
     * @dataProvider providerForPostcodeCn
     */
    public function testPostcodeCn($input)
    {
        $this->assertTrue($this->is('postcodeCn', $input));

        $this->assertFalse($this->is('notPostcodeCn', $input));
    }

    /**
     * @dataProvider providerForNotPostcodeCn
     */
    public function testNotPostcodeCn($input)
    {
        $this->assertFalse($this->is('postcodeCn', $input));

        $this->assertTrue($this->is('notPostcodeCn', $input));
    }

    public function providerForPostcodeCn()
    {
        return array(
            array('123456'),
        );
    }

    public function providerForNotPostcodeCn()
    {
        return array(
            array('1234567'),
            array('0234567'),
        );
    }
}
