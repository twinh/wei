<?php

namespace WeiTest;

/**
 * @internal
 */
final class IsPhoneTest extends BaseValidatorTestCase
{
    /**
     * @dataProvider providerForPhone
     * @param mixed $input
     */
    public function testPhoneCn($input)
    {
        $this->assertTrue($this->isPhone($input));
    }

    /**
     * @dataProvider providerForNotPhone
     * @param mixed $input
     */
    public function testNotPhone($input)
    {
        $this->assertFalse($this->isPhone($input));
    }

    public function providerForPhone()
    {
        return [
            ['020-1234567'],
            ['0768-123456789'],
            // PhoneCn number without city code
            ['1234567'],
            ['123456789'],
            ['012345-1234567890'],
            ['010-1234567890'],
            ['123456'],
            ['110'],
            ['+448001111'],
            ['0800 1111'],
            ['09063020288'],
            ['+443335555555'],
            ['022-1234567'],
            ['416-981-0001'],
            ['4001234567'],
            ['1-877-777-1420'],
        ];
    }

    public function providerForNotPhone()
    {
        return [
            ['not digit'],
            ['(010)88886666'],
            ['1-877-777-1420 x117'],
            ['++123234'],
            ['1233456+'],
        ];
    }
}
