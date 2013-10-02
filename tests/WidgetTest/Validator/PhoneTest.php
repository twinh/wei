<?php

namespace WidgetTest\Validator;

class PhoneTest extends TestCase
{
    /**
     * @dataProvider providerForPhone
     */
    public function testPhoneCn($input)
    {
        $this->assertTrue($this->isPhone($input));
    }

    /**
     * @dataProvider providerForNotPhone
     */
    public function testNotPhone($input)
    {
        $this->assertFalse($this->isPhone($input));
    }

    public function providerForPhone()
    {
        return array(
            array('020-1234567'),
            array('0768-123456789'),
            // PhoneCn number without city code
            array('1234567'),
            array('123456789'),
            array('012345-1234567890'),
            array('010-1234567890'),
            array('123456'),
            array('110'),
            array('+448001111'),
            array('0800 1111'),
            array('09063020288'),
            array('+443335555555'),
            array('022-1234567'),
            array('416-981-0001'),
            array('4001234567'),
            array('1-877-777-1420')
        );
    }

    public function providerForNotPhone()
    {
        return array(
            array('not digit'),
            array('(010)88886666'),
            array('1-877-777-1420 x117'),
            array('++123234'),
            array('1233456+')
        );
    }
}
