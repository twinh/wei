<?php

namespace WeiTest\Validator;

class IdCardTwTest extends TestCase
{
    /**
     * @dataProvider providerForIdCardTw
     */
    public function testIdCardTw($input)
    {
        $this->assertTrue($this->isIdCardTw($input));
    }

    /**
     * @dataProvider providerForNotIdCardTw
     */
    public function testNotIdCardTw($input)
    {
        $this->assertFalse($this->isIdCardTw($input));
    }

    public function providerForIdCardTw()
    {
        return array(
            array('A122501945'),
            array('B171087264'),
            array('C175935164'),
            array('D121110354'),
            array('E141534575'),
            array('F155768839'),
            array('G223352284'),
            array('H250895728'),
            array('I266502775'),
            array('J299456042'),
            array('K212857520'),
            array('L274095366'),
            array('M296742249'),
            array('N274942497'),
            array('O264561151'),
            array('P267647291'),
            array('Q228992767'),
            array('R252210560'),
            array('S288750107'),
            array('T220907223'),
            array('U241305159'),
            array('V250028951'),
            array('W206430112'),
            array('X282442234'),
            array('Y214481364'),
            array('Z285003269'),
        );
    }

    public function providerForNotIdCardTw()
    {
        return array(
            array('1285003269'), // first char should be A-Z
            array('Z385003269'), // second should be 1 or 2
            array('Z2749052'),   // length
            array('Z285003260'), // checksum invalid
            array('Z285003261'),
            array('Z285003262'),
            array('Z285003263'),
            array('Z285003264'),
            array('Z285003265'),
            array('Z285003266'),
            array('Z285003267'),
            array('Z285003268'),
            array('not digit'),
        );
    }
}
