<?php

namespace WeiTest\Validator;

/**
 * @internal
 */
final class IdCardTwTest extends TestCase
{
    /**
     * @dataProvider providerForIdCardTw
     * @param mixed $input
     */
    public function testIdCardTw($input)
    {
        $this->assertTrue($this->isIdCardTw($input));
    }

    /**
     * @dataProvider providerForNotIdCardTw
     * @param mixed $input
     */
    public function testNotIdCardTw($input)
    {
        $this->assertFalse($this->isIdCardTw($input));
    }

    public function providerForIdCardTw()
    {
        return [
            ['A122501945'],
            ['B171087264'],
            ['C175935164'],
            ['D121110354'],
            ['E141534575'],
            ['F155768839'],
            ['G223352284'],
            ['H250895728'],
            ['I266502775'],
            ['J299456042'],
            ['K212857520'],
            ['L274095366'],
            ['M296742249'],
            ['N274942497'],
            ['O264561151'],
            ['P267647291'],
            ['Q228992767'],
            ['R252210560'],
            ['S288750107'],
            ['T220907223'],
            ['U241305159'],
            ['V250028951'],
            ['W206430112'],
            ['X282442234'],
            ['Y214481364'],
            ['Z285003269'],
        ];
    }

    public function providerForNotIdCardTw()
    {
        return [
            ['1285003269'], // first char should be A-Z
            ['Z385003269'], // second should be 1 or 2
            ['Z2749052'],   // length
            ['Z285003260'], // checksum invalid
            ['Z285003261'],
            ['Z285003262'],
            ['Z285003263'],
            ['Z285003264'],
            ['Z285003265'],
            ['Z285003266'],
            ['Z285003267'],
            ['Z285003268'],
            ['not digit'],
        ];
    }
}
