<?php

namespace WeiTest\Validator;

class MobileCnTest extends TestCase
{
    /**
     * @dataProvider providerForMobileCn
     */
    public function testMobileCn($input)
    {
        $this->assertTrue($this->isMobileCn($input));
    }

    /**
     * @dataProvider providerForNotMobileCn
     */
    public function testNotMobileCn($input)
    {
        $this->assertFalse($this->isMobileCn($input));
    }

    public function providerForMobileCn()
    {
        return array(
            array('13112345678'),
            array('13612345678'),
            array('13800138000'),
            array('15012345678'),
            array('15812345678'),
            array('18812345678'),
            array('14012345678'),
        );
    }

    public function providerForNotMobileCn()
    {
        return array(
            array('12000000000'),
            array('88888888'),
            array('not digit'),
            array('0754-8888888')
        );
    }
}
