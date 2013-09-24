<?php

namespace WidgetTest\Validator;

class PhoneCnTest extends TestCase
{
    /**
     * @dataProvider providerForPhoneCn
     */
    public function testPhoneCn($input)
    {
        $this->assertTrue($this->isPhoneCn($input));
    }

    /**
     * @dataProvider providerForNotPhoneCn
     */
    public function testNotPhoneCn($input)
    {
        $this->assertFalse($this->isPhoneCn($input));
    }

    public function providerForPhoneCn()
    {
        return array(
            array('020-1234567'),
            array('0768-123456789'),
            // PhoneCn number without city code
            array('1234567'),
            array('123456789'),
        );
    }

    public function providerForNotPhoneCn()
    {
        return array(
            array('012345-1234567890'),
            array('010-1234567890'),
            array('123456'),
            array('not digit'),
        );
    }
}
