<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIdCardHkTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIdCardHk
     * @param mixed $input
     */
    public function testIdCardHk($input)
    {
        $this->assertTrue($this->isIdCardHk($input));
    }

    /**
     * @dataProvider providerForNotIdCardHk
     * @param mixed $input
     */
    public function testNotIdCardHk($input)
    {
        $this->assertFalse($this->isIdCardHk($input));
    }

    public static function providerForIdCardHk()
    {
        return [
            ['Z437626A'],
            ['a3134191'],
            ['A3399993'],
            ['B6418622'],
            ['C5512873'],
            ['N0177324'],
            ['O6811575'],
            ['R9971776'],
            ['U5012667'],
            ['W4945428'],
            ['X1983259'],
            ['Z2749050'],
        ];
    }

    public static function providerForNotIdCardHk()
    {
        return [
            ['13134191'], // first char should be A-Z
            ['Z2749051'], // checksum invalid
            ['Z2749052'],
            ['Z2749053'],
            ['Z2749054'],
            ['Z2749055'],
            ['Z2749056'],
            ['Z2749057'],
            ['Z2749058'],
            ['Z2749059'],
            ['not digit'],
        ];
    }
}
