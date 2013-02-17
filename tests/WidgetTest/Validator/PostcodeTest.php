<?php

namespace WidgetTest\Validator;

class PostcodeTest extends TestCase
{
    /**
     * @dataProvider providerForPostcode
     */
    public function testPostcode($input)
    {
        $this->assertTrue($this->is('postcode', $input));
        
        $this->assertFalse($this->is('notPostcode', $input));
    }

    /**
     * @dataProvider providerForNotPostcode
     */
    public function testNotPostcode($input)
    {
        $this->assertFalse($this->is('postcode', $input));
        
        $this->assertTrue($this->is('notPostcode', $input));
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
