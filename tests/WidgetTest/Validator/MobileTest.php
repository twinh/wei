<?php

namespace WidgetTest\Validator;

class MobileTest extends TestCase
{
    /**
     * @dataProvider providerForMobile
     */
    public function testMobile($input)
    {
        $this->assertTrue($this->isMobile($input));
    }

    /**
     * @dataProvider providerForNotMobile
     */
    public function testNotMobile($input)
    {
        $this->assertFalse($this->isMobile($input));
    }

    public function providerForMobile()
    {
        return array(
            array('13112345678'),
            array('13612345678'),
            array('13800138000'),
            array('15012345678'),
            array('15812345678'),
            array('18812345678'),
        );
    }

    public function providerForNotMobile()
    {
        return array(
            array('12000000000'),
            array('88888888'),
            array('not digit'),
            array('0754-8888888')
        );
    }
}
