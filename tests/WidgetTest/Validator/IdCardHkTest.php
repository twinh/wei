<?php

namespace WidgetTest\Validator;

class IdCardHkTest extends TestCase
{
    /**
     * @dataProvider providerForIdCardHk
     */
    public function testIdCardHk($input)
    {
        $this->assertTrue($this->is('idCardHk', $input));
        
        $this->assertFalse($this->is('notIdCardHk', $input));
    }

    /**
     * @dataProvider providerForNotIdCardHk
     */
    public function testNotIdCardHk($input)
    {
        $this->assertFalse($this->is('idCardHk', $input));
        
        $this->assertTrue($this->is('notIdCardHk', $input));
    }

    public function providerForIdCardHk()
    {
        return array(
            array('Z437626A'),
            array('a3134191'),
            array('A3399993'),
            array('B6418622'),
            array('C5512873'),
            array('N0177324'),
            array('O6811575'),
            array('R9971776'),
            array('U5012667'),
            array('W4945428'),
            array('X1983259'),
            array('Z2749050')
        );
    }

    public function providerForNotIdCardHk()
    {
        return array(
            array('13134191'), // first char should be A-Z
            array('Z2749051'), // checksum invalid
            array('Z2749052'),
            array('Z2749053'),
            array('Z2749054'),
            array('Z2749055'),
            array('Z2749056'),
            array('Z2749057'),
            array('Z2749058'),
            array('Z2749059'),
            array('not digit'),
        );
    }
}
