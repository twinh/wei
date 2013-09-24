<?php

namespace WidgetTest\Validator;

class IdCardCnTest extends TestCase
{
    /**
     * @dataProvider providerForIdCardCn
     */
    public function testIdCardCn($input)
    {
        $this->assertTrue($this->isIdCardCn($input));
    }

    /**
     * @dataProvider providerForNotIdCardCn
     */
    public function testNotIdCardCn($input)
    {
        $this->assertFalse($this->isIdCardCn($input));
    }

    public function providerForIdCardCn()
    {
        return array(
            array('632801197908170036'),
            array('34052419800101001X'),
            array('34262219840209049X'),
            array('34262219840209049x'),
            array('310109198002147295'),
            array('342622840209049')
        );
    }

    public function providerForNotIdCardCn()
    {
        return array(
            array('632801197908170037'), // checksum invalid
            array('340524198001010010'), // checksum invalid
            array('342622198402090491'), // checksum invalid
            array('310109198022147295'), // month invalid
            array('34262284020904'), // length invalid
            array('abcdefghijklmnopqr'),
            array('012345-1234567890'),
            array('010-1234567890'),
            array('not digit'),
        );
    }
}
