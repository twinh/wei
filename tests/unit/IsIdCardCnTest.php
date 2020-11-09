<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsIdCardCnTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForIdCardCn
     * @param mixed $input
     */
    public function testIdCardCn($input)
    {
        $this->assertTrue($this->isIdCardCn($input));
    }

    /**
     * @dataProvider providerForNotIdCardCn
     * @param mixed $input
     */
    public function testNotIdCardCn($input)
    {
        $this->assertFalse($this->isIdCardCn($input));
    }

    public function providerForIdCardCn()
    {
        return [
            ['632801197908170036'],
            ['34052419800101001X'],
            ['34262219840209049X'],
            ['34262219840209049x'],
            ['310109198002147295'],
            ['342622840209049'],
        ];
    }

    public function providerForNotIdCardCn()
    {
        return [
            ['632801197908170037'], // checksum invalid
            ['340524198001010010'], // checksum invalid
            ['342622198402090491'], // checksum invalid
            ['310109198022147295'], // month invalid
            ['34262284020904'], // length invalid
            ['abcdefghijklmnopqr'],
            ['012345-1234567890'],
            ['010-1234567890'],
            ['not digit'],
        ];
    }
}
