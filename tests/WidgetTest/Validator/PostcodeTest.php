<?php

namespace WidgetTest\Validator;

class PostcodeTest extends TestCase
{
    /**
     * @dataProvider providerForPostcode
     */
    public function testPostcode($input)
    {
        $this->assertTrue($this->isPostcode($input));
    }

    /**
     * @dataProvider providerForNotPostcode
     */
    public function testNotPostcode($input)
    {
        $this->assertFalse($this->isPostcode($input));
    }

    public function providerForPostcode()
    {
        return array(
            array('123456'),
        );
    }

    public function providerForNotPostcode()
    {
        return array(
            array('1234567'),
            array('0234567'),
        );
    }
}
